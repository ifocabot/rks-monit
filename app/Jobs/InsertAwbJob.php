<?php

namespace App\Jobs;

use App\Models\AwbTracking;
use App\Models\UploadBatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InsertAwbJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $awbNumber;
    protected $batch;

    public function __construct(string $awbNumber, UploadBatch $batch)
    {
        $this->awbNumber = $awbNumber;
        $this->batch = $batch;
    }

    public function handle()
    {
        // Validasi format
        if (!preg_match('/^\d{10,}$/', $this->awbNumber)) {
            $this->batch->increment('failed');
            return;
        }

        // Cek duplikat
        if (AwbTracking::where('awb_number', $this->awbNumber)->exists()) {
            $this->batch->increment('failed');
            return;
        }

        // Simpan AWB
        AwbTracking::create([
            'awb_number' => $this->awbNumber,
            'uploaded_by' => $this->batch->user_id,
            'batch_id' => $this->batch->id,
        ]);

        $this->batch->increment('inserted');
    }
}
