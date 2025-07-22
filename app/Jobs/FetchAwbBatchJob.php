<?php

namespace App\Jobs;

use App\Models\AwbTracking;
use App\Models\AwbDetailInfo;
use App\Models\AwbHistory;
use App\Models\AwbPhotoHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchAwbBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $trackingIds;

    public function __construct(array $trackingIds)
    {
        $this->trackingIds = $trackingIds;
    }

    public function handle()
    {
        $trackings = AwbTracking::whereIn('id', $this->trackingIds)->get(['id', 'awb_number']);
        $now = now();

        // Pool HTTP requests (paralel)
        $responses = Http::pool(fn ($pool) => collect($trackings)->mapWithKeys(function ($t) use ($pool) {
            $awb = strtoupper(trim(preg_replace('/[^\x20-\x7E]/', '', $t->awb_number)));
            $url = "https://apiv2.jne.co.id:10205/tracing/api/list/v1/cnote/{$awb}";
            return [$t->id => $pool
                ->as($t->id)
                ->withHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])
                ->asForm()
                ->retry(1, 200)
                ->post($url, [
                    'username' => config('services.jne.username'),
                    'api_key'  => config('services.jne.api_key'),
                ])];
        })->all());

        $details = [];
        $trackUpdates = [];
        $histories = [];
        $photoHistories = [];

        foreach ($trackings as $t) {
            $resp = $responses[$t->id] ?? null;

            if (!$resp || !$resp->successful()) continue;

            $json = $resp->json();
            $cnote  = $json['cnote'] ?? [];
            $detail = $json['detail'][0] ?? [];
            $lastCode = collect($json['history'] ?? [])->last()['code'] ?? null;
            $delivered = $lastCode === 'D01' || ($cnote['pod_status'] ?? '') === 'DELIVERED';

            // Prepare update tracking
            $trackUpdates[] = [
                'id'              => $t->id,
                'status_code'     => $lastCode,
                'status_label'    => $cnote['pod_status'] ?? null,
                'is_completed'    => $delivered,
                'last_checked_at' => $now,
                'delivered_at'    => $cnote['cnote_pod_date'] ?? null,
                'pod_receiver'    => $cnote['cnote_pod_receiver'] ?? null,
            ];

            // Prepare detail
            $details[] = [
                'awb_tracking_id'     => $t->id,
                'reference_number'    => $cnote['reference_number'] ?? null,
                'origin'              => $cnote['cnote_origin'] ?? null,
                'destination'         => $cnote['cnote_destination'] ?? null,
                'service_code'        => $cnote['cnote_services_code'] ?? null,
                'service_type'        => $cnote['servicetype'] ?? null,
                'cust_no'             => $cnote['cnote_cust_no'] ?? null,
                'cnote_date'          => $this->parseDate($cnote['cnote_date'] ?? null),
                'goods_description'   => $cnote['cnote_goods_descr'] ?? null,
                'amount'              => $cnote['cnote_amount'] ?? null,
                'weight'              => $cnote['cnote_weight'] ?? null,
                'shipper_name'        => $detail['cnote_shipper_name'] ?? null,
                'shipper_address'     => trim(($detail['cnote_shipper_addr1'] ?? '') . ' ' . ($detail['cnote_shipper_addr2'] ?? '') . ' ' . ($detail['cnote_shipper_addr3'] ?? '')),
                'shipper_city'        => $detail['cnote_shipper_city'] ?? null,
                'receiver_name'       => $detail['cnote_receiver_name'] ?? null,
                'receiver_address'    => trim(($detail['cnote_receiver_addr1'] ?? '') . ' ' . ($detail['cnote_receiver_addr2'] ?? '') . ' ' . ($detail['cnote_receiver_addr3'] ?? '')),
                'receiver_city'       => $detail['cnote_receiver_city'] ?? null,
                'updated_at'          => $now,
            ];

            foreach ($json['history'] ?? [] as $h) {
                $histories[] = [
                    'awb_tracking_id' => $t->id,
                    'date'        => $this->parseDate($h['date'] ?? null, 'd-m-Y H:i'),
                    'description' => $h['desc'] ?? null,
                    'code'        => $h['code'] ?? null,
                    'updated_at'  => $now,
                ];
            }

            foreach ($json['photo_history'] ?? [] as $p) {
                $photoHistories[] = [
                    'awb_tracking_id' => $t->id,
                    'date'     => $this->parseDate($p['date'] ?? null, 'd-m-Y H:i'),
                    'photo1'   => $p['photo1'] ?? null,
                    'photo2'   => $p['photo2'] ?? null,
                    'photo3'   => $p['photo3'] ?? null,
                    'photo4'   => $p['photo4'] ?? null,
                    'photo5'   => $p['photo5'] ?? null,
                    'updated_at' => $now,
                ];
            }
        }

        // Simpan dalam DB
        DB::transaction(function () use ($trackUpdates, $details, $histories, $photoHistories) {
            AwbTracking::upsert($trackUpdates, ['id'], ['status_code', 'status_label', 'is_completed', 'last_checked_at', 'delivered_at', 'pod_receiver']);
            AwbDetailInfo::upsert($details, ['awb_tracking_id']);
            AwbHistory::upsert($histories, ['awb_tracking_id', 'code', 'date']);
            AwbPhotoHistory::upsert($photoHistories, ['awb_tracking_id', 'date']);
        });
    }

    protected function parseDate(?string $input, string $format = null): ?string
    {
        if (!$input) return null;
        try {
            return $format
                ? \Carbon\Carbon::createFromFormat($format, $input)->toDateTimeString()
                : \Carbon\Carbon::parse($input)->toDateTimeString();
        } catch (\Exception) {
            return null;
        }
    }
}
