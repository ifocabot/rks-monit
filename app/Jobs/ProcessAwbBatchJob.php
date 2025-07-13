<?php

namespace App\Jobs;

use App\Models\AwbTracking;
use App\Models\UploadBatch;
use App\Models\AwbImportFailure;
use App\Models\AwbImportFailureLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class ProcessAwbBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $awbList;
    protected int $batchId;

    public function __construct(array $awbList, int $batchId)
    {
        $this->awbList = $awbList;
        $this->batchId = $batchId;
    }

    public function handle()
    {
        $batch = UploadBatch::find($this->batchId);

        if (!$batch) {
            Log::error("UploadBatch ID {$this->batchId} tidak ditemukan.");
            return;
        }

        foreach ($this->awbList as $awb) {
            $awb = strtoupper(trim(preg_replace('/[^\x20-\x7E]/', '', (string)$awb)));

            Log::info("Processing AWB", ['awb' => $awb]);

            if (empty($awb)) {
                Log::warning("AWB kosong (skipped)", ['awb' => $awb]);
                AwbImportFailureLog::create([
                    'batch_id' => $batch->id,
                    'awb_number' => $awb,
                    'reason' => 'empty_awb',
                ]);
                $batch->increment('failed');
                continue;
            }

            try {
                $inserted = AwbTracking::firstOrCreate(
                    ['awb_number' => $awb],
                    ['uploaded_by' => $batch->user_id, 'batch_id' => $batch->id]
                );

                if ($inserted->wasRecentlyCreated) {
                    Log::info("AWB berhasil disimpan", ['awb' => $awb]);
                    $batch->increment('inserted');
                } else {
                    Log::warning("AWB duplikat saat firstOrCreate", ['awb' => $awb]);
                    AwbImportFailureLog::create([
                        'batch_id' => $batch->id,
                        'awb_number' => $awb,
                        'reason' => 'duplicate_awb',
                    ]);
                    $batch->increment('failed');
                }
            } catch (\Exception $e) {
                Log::error("Gagal insert AWB", [
                    'awb' => $awb,
                    'error' => $e->getMessage()
                ]);
                AwbImportFailureLog::create([
                    'batch_id' => $batch->id,
                    'awb_number' => $awb,
                    'reason' => substr($e->getMessage(), 0, 190), // prevent 1406 error
                ]);
                $batch->increment('failed');
            }
        }
    }
}