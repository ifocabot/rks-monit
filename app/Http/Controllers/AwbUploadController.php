<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\AwbImport;
use App\Models\UploadBatch;
use Maatwebsite\Excel\Facades\Excel;

class AwbUploadController extends Controller
{    
    public function showForm()
    {
        return view('awb.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        $uploadedFile = $request->file('file');

        $batch = UploadBatch::create([
            'user_id' => auth()->id(),
            'file_name' => $uploadedFile->getClientOriginalName() ?? 'upload.xlsx',
            'total_rows' => 0,
            'inserted' => 0,
            'failed' => 0,
            'uploaded_at' => now(),
        ]);

        Excel::import(new AwbImport($batch), $uploadedFile);

        return back()->with('success', 'File sedang diproses.');
    }

}
