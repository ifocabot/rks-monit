<?php

namespace App\Jobs;

use App\Models\{
    AwbTracking,
    AwbHistory,
    AwbPhotoHistory,
    AwbDetailInfo
};
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\{
    Http,
    Log,
    DB
};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Client\Pool;
use Throwable;
use Carbon\Carbon;

class FetchUndeliveredAwbStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $chunkSize;
    protected bool $useBatch;
    protected bool $forceAll;

    public function __construct(int $chunkSize = 50, bool $useBatch = true, bool $forceAll = false)
    {
        $this->chunkSize = $chunkSize;
        $this->useBatch = $useBatch;
        $this->forceAll = $forceAll;
    }

    public function handle()
    {
        $query = AwbTracking::query();

        if (!$this->forceAll) {
            $query->where('is_completed', 0);
        }

        $query->chunkById($this->chunkSize, function ($trackings) {
            if ($this->useBatch && $trackings->count() > 1) {
                $this->processBatch($trackings);
            } else {
                foreach ($trackings as $tracking) {
                    $this->processSingle($tracking->id);
                }
            }
        });
    }

    protected function processBatch($trackings): void
    {
        $trackings = $trackings->keyBy('id');

        // Concurrent API requests with pool
        $responses = Http::pool(function (Pool $pool) use ($trackings) {
            $requests = [];
            $username = config('services.jne.username');
            $apiKey = config('services.jne.api_key');
            
            foreach ($trackings as $tracking) {
                $awb = $this->normalizeAwbNumber($tracking->awb_number);
                $url = "https://apiv2.jne.co.id:10205/tracing/api/list/v1/cnote/{$awb}";
                
                $requests[] = $pool->as($tracking->id)
                    ->retry(2, 100)
                    ->timeout(15)
                    ->withHeaders([
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Accept' => 'application/json'
                    ])
                    ->asForm()
                    ->post($url, [
                        'username' => $username,
                        'api_key' => $apiKey,
                    ]);
            }
            
            return $requests;
        });

        // Process all responses in transaction
        DB::transaction(function () use ($trackings, $responses) {
            foreach ($trackings as $tracking) {
                $response = $responses[$tracking->id] ?? null;
                
                if (!$response) {
                    Log::error("No response for AWB {$tracking->awb_number}");
                    continue;
                }

                if (!$response->successful()) {
                    Log::error("Failed API response for AWB {$tracking->awb_number}", [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    continue;
                }

                try {
                    $this->processTracking($tracking, $response->json());
                } catch (Throwable $e) {
                    Log::error("Failed to process AWB {$tracking->awb_number}", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
        });
    }

    protected function processSingle(int $trackingId): void
    {
        $query = AwbTracking::query();

        if (!$this->forceAll) {
            $query->where('is_completed', 0);
        }

        $tracking = $query->find($trackingId);

        if (!$tracking) {
            Log::warning("Tracking ID {$trackingId} not found or already delivered");
            return;
        }

        DB::transaction(function () use ($tracking) {
            try {
                $response = $this->fetchFromApi($tracking->awb_number);
                $this->processTracking($tracking, $response);
            } catch (Throwable $e) {
                Log::error("Failed to process AWB {$tracking->awb_number}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        });
    }

    protected function processTracking(AwbTracking $tracking, array $response): void
    {
        $cnote = $response['cnote'] ?? [];
        $detail = $response['detail'][0] ?? [];
        $history = $response['history'] ?? [];
        $photoHistory = $response['photo_history'] ?? [];

        $lastCode = $this->getLastHistoryCode($history);
        $delivered = ($lastCode === 'D01' || ($cnote['pod_status'] ?? '') === 'DELIVERED');

        // Update main tracking
        $updateData = [
            'status_code' => $lastCode,
            'status_label' => $cnote['pod_status'] ?? null,
            'is_completed' => $delivered,
            'last_checked_at' => now(),
        ];

        if ($delivered) {
            $updateData['delivered_at'] = $this->parseIsoDate($cnote['cnote_pod_date'] ?? null);
            $updateData['pod_receiver'] = $cnote['cnote_pod_receiver'] ?? null;
        }

        $tracking->update($updateData);

        // Skip further processing if delivered and not forced
        if ($delivered && !$this->forceAll) {
            return;
        }

        // Process detail info
        $detailData = [
            'reference_number' => $cnote['reference_number'] ?? null,
            'origin' => $cnote['cnote_origin'] ?? null,
            'destination' => $cnote['cnote_destination'] ?? null,
            'service_code' => $cnote['cnote_services_code'] ?? null,
            'service_type' => $cnote['servicetype'] ?? null,
            'cust_no' => $cnote['cnote_cust_no'] ?? null,
            'cnote_date' => $this->parseIsoDate($cnote['cnote_date'] ?? null),
            'goods_description' => $cnote['cnote_goods_descr'] ?? null,
            'amount' => $cnote['cnote_amount'] ?? null,
            'weight' => $cnote['cnote_weight'] ?? null,
            'shipper_name' => $detail['cnote_shipper_name'] ?? null,
            'shipper_address' => $this->concatAddress(
                $detail['cnote_shipper_addr1'] ?? null,
                $detail['cnote_shipper_addr2'] ?? null,
                $detail['cnote_shipper_addr3'] ?? null
            ),
            'shipper_city' => $detail['cnote_shipper_city'] ?? null,
            'receiver_name' => $detail['cnote_receiver_name'] ?? null,
            'receiver_address' => $this->concatAddress(
                $detail['cnote_receiver_addr1'] ?? null,
                $detail['cnote_receiver_addr2'] ?? null,
                $detail['cnote_receiver_addr3'] ?? null
            ),
            'receiver_city' => $detail['cnote_receiver_city'] ?? null,
        ];

        $tracking->detailInfo()->updateOrCreate([], $detailData);

        // Process histories
        $this->processHistories($tracking, $history);

        // Process photo histories
        $this->processPhotoHistories($tracking, $photoHistory);
    }

    protected function processHistories(AwbTracking $tracking, array $histories): void
    {
        $tracking->histories()->delete();

        if (empty($histories)) {
            return;
        }

        $now = now();
        $records = array_map(function ($h) use ($tracking, $now) {
            return [
                'awb_tracking_id' => $tracking->id,
                'date' => $this->parseDate($h['date'] ?? null),
                'description' => $h['desc'] ?? null,
                'code' => $h['code'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $histories);

        AwbHistory::insert($records);
    }

    protected function processPhotoHistories(AwbTracking $tracking, array $photoHistories): void
    {
        $tracking->photoHistories()->delete();

        if (empty($photoHistories)) {
            return;
        }

        $now = now();
        $records = array_map(function ($p) use ($tracking, $now) {
            return [
                'awb_tracking_id' => $tracking->id,
                'date' => $this->parseDate($p['date'] ?? null),
                'photo1' => $p['photo1'] ?? null,
                'photo2' => $p['photo2'] ?? null,
                'photo3' => $p['photo3'] ?? null,
                'photo4' => $p['photo4'] ?? null,
                'photo5' => $p['photo5'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $photoHistories);

        AwbPhotoHistory::insert($records);
    }

    protected function fetchFromApi(string $awb): array
    {
        $awb = $this->normalizeAwbNumber($awb);
        $username = config('services.jne.username');
        $apiKey = config('services.jne.api_key');
        $url = "https://apiv2.jne.co.id:10205/tracing/api/list/v1/cnote/{$awb}";

        $response = Http::retry(2, 100)
            ->timeout(15)
            ->withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json'
            ])
            ->asForm()
            ->post($url, [
                'username' => $username,
                'api_key' => $apiKey,
            ]);

        if (!$response->successful()) {
            throw new \Exception("API request failed with status: {$response->status()}");
        }

        return $response->json();
    }

    protected function normalizeAwbNumber(string $awb): string
    {
        $awb = strtoupper(trim(preg_replace('/[^\x20-\x7E]/', '', $awb)));

        if (empty($awb)) {
            throw new \InvalidArgumentException('AWB number cannot be empty');
        }

        if (strlen($awb) < 6 || strlen($awb) > 25 || !ctype_alnum($awb)) {
            throw new \InvalidArgumentException("Invalid AWB format: '{$awb}'");
        }

        return $awb;
    }

    protected function getLastHistoryCode(array $history): ?string
    {
        return empty($history) ? null : end($history)['code'] ?? null;
    }

    protected function parseDate(?string $input): ?string
    {
        if (!$input) {
            return null;
        }

        try {
            return Carbon::createFromFormat('d-m-Y H:i', $input)->toDateTimeString();
        } catch (Throwable $e) {
            Log::warning("Invalid history date format: {$input}");
            return null;
        }
    }

    protected function concatAddress(?string ...$parts): ?string
    {
        return implode(' ', array_filter($parts));
    }

    protected function parseIsoDate(?string $input): ?string
    {
        if (!$input) {
            return null;
        }

        try {
            return Carbon::parse($input)->toDateTimeString();
        } catch (Throwable $e) {
            Log::warning("Invalid ISO date format: {$input}");
            return null;
        }
    }
}
