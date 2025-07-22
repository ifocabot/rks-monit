<?php

namespace App\Console\Commands;

use App\Jobs\FetchAwbBatchJob;
use App\Models\AwbTracking;
use Illuminate\Console\Command;

class DispatchFetchAwbBatchStatus extends Command
{
    protected $signature = 'awb:fetch-batch-status
        {--limit= : Maksimal jumlah AWB yang diproses (opsional)}
        {--chunk=20 : Jumlah AWB per batch job (default: 20)}
        {--sync : Jalankan job langsung tanpa queue (untuk testing)}';

    protected $description = 'Dispatch FetchAwbBatchJob secara efisien dalam batch (parallel HTTP + upsert)';

    public function handle()
    {
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        $chunkSize = max((int) $this->option('chunk'), 1);
        $sync = $this->option('sync');

        $query = AwbTracking::where('is_completed', false)->orderBy('last_checked_at');

        if ($limit) {
            $query->limit($limit);
        }

        $ids = $query->pluck('id');

        if ($ids->isEmpty()) {
            $this->warn("⚠️ Tidak ada AWB yang ditemukan untuk diproses.");
            return;
        }

        $this->info("📦 Menjadwalkan " . $ids->count() . " AWB dalam batch {$chunkSize}...");
        $bar = $this->output->createProgressBar($ids->count());
        $bar->start();

        $ids->chunk($chunkSize)->each(function ($chunk) use ($sync, $bar) {
            if ($sync) {
                (new FetchAwbBatchJob($chunk->all()))->handle();
            } else {
                FetchAwbBatchJob::dispatch($chunk->all());
            }

            $bar->advance($chunk->count());
        });

        $bar->finish();
        $this->newLine(2);
        $this->info("✅ Selesai menjadwalkan semua batch job.");
    }
}
