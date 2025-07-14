<?php

namespace App\Jobs;

use App\Models\AwbTracking;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchAwbStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $trackingId;

    public function __construct(int $trackingId)
    {
        $this->trackingId = $trackingId;
    }

    public function handle()
    {
        $tracking = AwbTracking::find($this->trackingId);

        if (!$tracking) {
            Log::warning("Tracking ID {$this->trackingId} tidak ditemukan.");
            return;
        }

        try {
            $response = $this->fetchFromApi($tracking->awb_number);
            $cnote = $response['cnote'] ?? [];
            $detail = $response['detail'][0] ?? [];

            $lastCode = $this->getLastHistoryCode($response);
            $delivered = ($lastCode === 'D01' || ($cnote['pod_status'] ?? '') === 'DELIVERED');

            // Update main tracking info
            $tracking->update([
                'status_code'     => $lastCode,
                'status_label'    => $cnote['pod_status'] ?? null,
                'is_completed'    => $delivered,
                'last_checked_at' => now(),
                'delivered_at'    => $cnote['cnote_pod_date'] ?? null,
                'pod_receiver'    => $cnote['cnote_pod_receiver'] ?? null,
            ]);

            // Update or Create Detail Info
            $detailData = [
                'reference_number'     => $cnote['reference_number'] ?? null,
                'origin'               => $cnote['cnote_origin'] ?? null,
                'destination'          => $cnote['cnote_destination'] ?? null,
                'service_code'         => $cnote['cnote_services_code'] ?? null,
                'service_type'         => $cnote['servicetype'] ?? null,
                'cust_no'              => $cnote['cnote_cust_no'] ?? null,
                'cnote_date'           => $this->parseIsoDate($cnote['cnote_date'] ?? null),
                'goods_description'    => $cnote['cnote_goods_descr'] ?? null,
                'amount'               => $cnote['cnote_amount'] ?? null,
                'weight'               => $cnote['cnote_weight'] ?? null,
                'shipper_name'         => $detail['cnote_shipper_name'] ?? null,
                'shipper_address'      => $this->concatAddress(
                                            $detail['cnote_shipper_addr1'] ?? null,
                                            $detail['cnote_shipper_addr2'] ?? null,
                                            $detail['cnote_shipper_addr3'] ?? null),
                'shipper_city'         => $detail['cnote_shipper_city'] ?? null,
                'receiver_name'        => $detail['cnote_receiver_name'] ?? null,
                'receiver_address'     => $this->concatAddress(
                                            $detail['cnote_receiver_addr1'] ?? null,
                                            $detail['cnote_receiver_addr2'] ?? null,
                                            $detail['cnote_receiver_addr3'] ?? null),
                'receiver_city'        => $detail['cnote_receiver_city'] ?? null,
            ];

            Log::info("🔍 DetailInfo Data Before Save", $detailData);
            $tracking->detailInfo()->updateOrCreate([], $detailData);

            // 🔁 Sync Histories
            $tracking->histories()->delete();
            $historyRecords = collect($response['history'] ?? [])->map(function ($h) {
                return [
                    'awb_tracking_id' => $this->trackingId,
                    'date'        => $this->parseDate($h['date'] ?? null),
                    'description' => $h['desc'] ?? null,
                    'code'        => $h['code'] ?? null,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            })->toArray();

            if (!empty($historyRecords)) {
                \App\Models\AwbHistory::insert($historyRecords);
            }

            // 🔁 Sync Photo Histories
            $tracking->photoHistories()->delete();
            $photoRecords = collect($response['photo_history'] ?? [])->map(function ($p) {
                return [
                    'awb_tracking_id' => $this->trackingId,
                    'date'   => $this->parseDate($p['date'] ?? null),
                    'photo1' => $p['photo1'] ?? null,
                    'photo2' => $p['photo2'] ?? null,
                    'photo3' => $p['photo3'] ?? null,
                    'photo4' => $p['photo4'] ?? null,
                    'photo5' => $p['photo5'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            if (!empty($photoRecords)) {
                \App\Models\AwbPhotoHistory::insert($photoRecords);
            }

        } catch (\Throwable $e) {
            Log::error("❌ Gagal fetch AWB {$tracking->awb_number}", [
                'tracking_id' => $this->trackingId,
                'error' => $e->getMessage(),
            ]);
        }
    }


    protected function fetchFromApi(string $awb): array
    {
        $username = config('services.jne.username');
        $apiKey = config('services.jne.api_key');
        $url = "https://apiv2.jne.co.id:10205/tracing/api/list/v1/cnote/{$awb}";

        $awb = strtoupper(trim(preg_replace('/[^\x20-\x7E]/', '', $awb)));

        Log::info("🔎 Fetching AWB", [
            'awb' => $awb,
            'awb_length' => strlen($awb),
            'username' => $username,
        ]);

        if (empty($awb) || strlen($awb) < 6 || strlen($awb) > 25 || !ctype_alnum($awb)) {
            throw new \Exception("AWB invalid format: '{$awb}'");
        }

        $response = Http::retry(1, 200)
            ->withHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])
            ->asForm()
            ->post($url, [
                'username' => $username,
                'api_key' => $apiKey,
            ]);

        if (!$response->successful()) {
            $status = $response->status();
            Log::error("❌ API Error {$status} for AWB {$awb}", ['body' => $response->body()]);
            throw new \Exception("API error: {$status}");
        }

        return $response->json();
    }

    protected function getLastHistoryCode(array $response): ?string
    {
        return collect($response['history'] ?? [])->last()['code'] ?? null;
    }

    protected function parseDate(?string $input): ?string
    {
        if (!$input) return null;

        try {
            return \Carbon\Carbon::createFromFormat('d-m-Y H:i', $input)->toDateTimeString();
        } catch (\Exception $e) {
            Log::warning('Invalid date format for AWB history', ['raw' => $input]);
            return null;
        }
    }

    protected function concatAddress(?string ...$parts): ?string
    {
        return collect($parts)->filter()->implode(' ');
    }

    protected function parseIsoDate(?string $input): ?string
    {
        if (!$input) return null;
        try {
            return \Carbon\Carbon::parse($input)->toDateTimeString();
        } catch (\Exception $e) {
            Log::warning('Invalid ISO datetime', ['raw' => $input]);
            return null;
        }
    }
}
