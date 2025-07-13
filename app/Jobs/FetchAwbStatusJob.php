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

            $tracking->update([
                'status_code'     => $lastCode,
                'status_label'    => $cnote['pod_status'] ?? null,
                'is_completed'    => $delivered,
                'last_checked_at' => now(),
                'delivered_at'    => $cnote['cnote_pod_date'] ?? null,
                'pod_receiver'    => $cnote['cnote_pod_receiver'] ?? null,
            ]);

            $data = [
                'reference_number'     => $response['cnote']['reference_number'] ?? null,
                'origin'               => $response['cnote']['cnote_origin'] ?? null,
                'destination'          => $response['cnote']['cnote_destination'] ?? null,
                'service_code'         => $response['cnote']['cnote_services_code'] ?? null,
                'service_type'         => $response['cnote']['servicetype'] ?? null,
                'cust_no'              => $response['cnote']['cnote_cust_no'] ?? null,
                'cnote_date'           => $this->parseIsoDate($response['cnote']['cnote_date'] ?? null),
                'goods_description'    => $response['cnote']['cnote_goods_descr'] ?? null,
                'amount'               => $response['cnote']['cnote_amount'] ?? null,
                'weight'               => $response['cnote']['cnote_weight'] ?? null,
                'shipper_name'         => $response['detail'][0]['cnote_shipper_name'] ?? null,
                'shipper_address'      => $this->concatAddress(
                                            $response['detail'][0]['cnote_shipper_addr1'] ?? null,
                                            $response['detail'][0]['cnote_shipper_addr2'] ?? null,
                                            $response['detail'][0]['cnote_shipper_addr3'] ?? null),
                'shipper_city'         => $response['detail'][0]['cnote_shipper_city'] ?? null,
                'receiver_name'        => $response['detail'][0]['cnote_receiver_name'] ?? null,
                'receiver_address'     => $this->concatAddress(
                                            $response['detail'][0]['cnote_receiver_addr1'] ?? null,
                                            $response['detail'][0]['cnote_receiver_addr2'] ?? null,
                                            $response['detail'][0]['cnote_receiver_addr3'] ?? null),
                'receiver_city'        => $response['detail'][0]['cnote_receiver_city'] ?? null,
            ];

            Log::info("🔍 DetailInfo Data Before Save", $data);
            $tracking->detailInfo()->updateOrCreate([], $data);

            $tracking->histories()->delete();
            foreach ($response['history'] ?? [] as $h) {
                $tracking->histories()->create([
                    'date'        => $this->parseDate($h['date'] ?? null),
                    'description' => $h['desc'] ?? null, // ubah key-nya ke "description"
                    'code'        => $h['code'] ?? null,
                ]);
            }

            $tracking->photoHistories()->delete();
            foreach ($response['photo_history'] ?? [] as $p) {
                $tracking->photoHistories()->create([
                    'date'   => $this->parseDate($p['date'] ?? null),
                    'photo1' => $p['photo1'] ?? null,
                    'photo2' => $p['photo2'] ?? null,
                    'photo3' => $p['photo3'] ?? null,
                    'photo4' => $p['photo4'] ?? null,
                    'photo5' => $p['photo5'] ?? null,
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Gagal fetch AWB {$tracking->awb_number}", ['error' => $e->getMessage()]);
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

        $response = Http::retry(3, 1000)
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
