<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\FetchUndeliveredAwbStatusJob;

class FetchAwbStatusCommand extends Command
{
    /**
     * Nama dan signature dari console command
     *
     * @var string
     */
    protected $signature = 'awb:fetch 
                            {--chunk=50 : Jumlah AWB yang diproses per batch} 
                            {--single : Jalankan dalam mode single (tanpa HTTP pool)} 
                            {--all : Proses semua AWB termasuk yang sudah delivered}';

    /**
     * Deskripsi console command
     *
     * @var string
     */
    protected $description = 'Fetch status AWB yang belum delivered dari API JNE';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $chunkSize = (int)$this->option('chunk');
        $useBatch = !$this->option('single');
        $forceAll = $this->option('all');

        $this->info("Memulai proses fetch AWB status...");
        $this->line("Konfigurasi:");
        $this->line("- Chunk size: {$chunkSize}");
        $this->line("- Mode: " . ($useBatch ? 'Batch (HTTP Pool)' : 'Single'));
        $this->line("- Scope: " . ($forceAll ? 'Semua AWB' : 'Hanya yang belum delivered'));

        $startTime = microtime(true);

        try {
            FetchUndeliveredAwbStatusJob::dispatch($chunkSize, $useBatch, $forceAll);
            
            $executionTime = round(microtime(true) - $startTime, 2);
            $this->info("\nProses selesai dalam {$executionTime} detik");
            return Command::SUCCESS;
            
        } catch (\Throwable $e) {
            $this->error("\nError: " . $e->getMessage());
            $this->error("File: " . $e->getFile() . ":" . $e->getLine());
            return Command::FAILURE;
        }
    }
}
