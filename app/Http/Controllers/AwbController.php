<?php

namespace App\Http\Controllers;

use App\Models\AwbTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AwbController extends Controller
{
    public function index(Request $request)
    {
        $query = AwbTracking::query();

        // Filter by status_code
        if ($request->filled('code')) {
            $query->where('status_code', $request->code);
        }

        // Filter by dashboard category (join ke delivery_statuses)
        if ($request->filled('dashboard_category')) {
            $query->whereHas('deliveryStatus', function ($q) use ($request) {
                $q->where('dashboard_category', $request->dashboard_category);
            });
        }

        // Eager load relasi deliveryStatus
        $awbs = $query->with('deliveryStatus')
            ->orderByDesc('created_at')
            ->paginate(50);

        // Ambil list kategori unik dari tabel referensi
        $availableCategories = DB::table('delivery_statuses')
            ->distinct()
            ->pluck('dashboard_category');

        return view('pages.awb.index', compact('awbs', 'availableCategories'));
    }
}