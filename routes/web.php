<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AwbUploadController;
use App\Http\Controllers\DashboardController;
use App\Jobs\FetchAwbStatusJob;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::post('/awb/sync-status', [AwbUploadController::class, 'syncStatus'])->name('awb.syncStatus');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/awb/upload', [AwbUploadController::class, 'showForm'])->name('awb.upload.form');
    Route::post('/awb/upload', [AwbUploadController::class, 'upload'])->name('awb.upload.submit');
});

Route::get('/test-awb', function () {
    $awb = '3589474020006'; // ← ganti dengan AWB yang valid milikmu

    try {
        $job = new FetchAwbStatusJob(
            \App\Models\AwbTracking::where('awb_number', $awb)->value('id')
        );
        $job->handle();

        return "AWB {$awb} fetched successfully.";
    } catch (\Exception $e) {
        Log::error("Test fetch AWB failed", ['error' => $e->getMessage()]);
        return "Failed: " . $e->getMessage();
    }
});

route::get('/test', function () {
    return view ('pages.dashboard.index');
});



require __DIR__.'/auth.php';
