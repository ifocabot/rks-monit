<?php

namespace App\Console\Commands;

use App\Jobs\FetchAwbStatusJob;
use App\Models\AwbTracking;
use Illuminate\Console\Command;

class DispatchFetchAwbStatus extends Command
{
    protected $signature = 'awb:fetch-status 
        {--limit= : Jumlah maksimum AWB yang akan di-fetch (opsional)} 
        {--rate=1 : Jumlah job per detik (opsional)} 
        {--sync : Jalankan secara sinkron (testing saja)}';

    protected $description = 'Dispatch jobs to fetch AWB status for incomplete AWBs';

    public function handle()
    {
        // Ubah bagian ini
        $limitOption = $this->option('limit');
        $rate = max((int) $this->option('rate'), 1);
        $sync = $this->option('sync');

        // Interpretasi limit
        $limit = is_null($limitOption) ? null : (int) $limitOption;

        $this->info("🔄 Memuat " . ($limit ? "maksimal {$limit}" : "SEMUA") . " AWB, dengan {$rate} job per detik. Sync mode: " . ($sync ? 'YA' : 'TIDAK'));

        // Query AWB
        $query = AwbTracking::where('is_completed', false)->orderBy('id');

        if ($limit) {
            $query->limit($limit);
        }

        $awbs = $query->pluck('id');

        if ($awbs->isEmpty()) {
            $this->warn("⚠️ Tidak ada AWB yang ditemukan.");
            return;
        }

        $this->info("📦 Menjadwalkan {$awbs->count()} job...");
        $dispatched = 0;

        foreach ($awbs as $i => $id) {
            $delaySeconds = floor($i / $rate);

            if ($sync) {
                $this->line("🔎 Fetching ID: {$id} (Sync)");
                (new FetchAwbStatusJob($id))->handle();
            } else {
                FetchAwbStatusJob::dispatch($id)->delay(now()->addSeconds($delaySeconds));
            }

            $dispatched++;
        }

        $this->info("✅ {$dispatched} job FetchAwbStatusJob berhasil dijadwalkan.");
    }

}
