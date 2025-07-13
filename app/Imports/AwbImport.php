<?php

namespace App\Imports;

use App\Jobs\ProcessAwbBatchJob;
use App\Models\UploadBatch;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AwbImport implements ToCollection, WithChunkReading
{
    protected $batch;

    public function __construct(UploadBatch $batch)
    {
        $this->batch = $batch;
    }

    public function collection(Collection $rows)
    {
        $rows = $rows->skip(1); // Skip header

        $this->batch->update([
            'total_rows' => $this->batch->total_rows + $rows->count(),
        ]);

        $buffer = [];
        $chunkIndex = 0;

        foreach ($rows as $row) {
            $awb = strtoupper(trim((string) $row[0]));
            if ($awb !== '') {
                $buffer[] = $awb;
            }

            if (count($buffer) >= 100) {
                // Tambahkan delay agar job tidak dijalankan bersamaan
                ProcessAwbBatchJob::dispatch($buffer, $this->batch->id)
                    ->delay(now()->addSeconds($chunkIndex * 3)); // delay antar job
                $buffer = [];
                $chunkIndex++;
            }
        }

        // Sisa buffer akhir
        if (count($buffer) > 0) {
            ProcessAwbBatchJob::dispatch($buffer, $this->batch->id)
                ->delay(now()->addSeconds($chunkIndex * 3));
        }
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
