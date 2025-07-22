<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->input('range');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        $baseQuery = DB::table('awb_trackings');
    
        if ($range === 'today') {
            $baseQuery->whereDate('awb_trackings.created_at', Carbon::today());
        } elseif ($range === '7days') {
            $baseQuery->where('awb_trackings.created_at', '>=', now()->subDays(7));
        } elseif ($range === 'thismonth') {
            $baseQuery->whereMonth('awb_trackings.created_at', now()->month)->whereYear('awb_trackings.created_at', now()->year);
        } elseif ($range === 'custom' && $startDate && $endDate) {
            try {
                $start = Carbon::parse($startDate)->startOfDay();
                $end = Carbon::parse($endDate)->endOfDay();
                $baseQuery->whereBetween('awb_trackings.created_at', [$start, $end]);
            } catch (\Exception $e) {}
        }
    
        $total_all = (clone $baseQuery)->count();
        $completed = (clone $baseQuery)->where('awb_trackings.is_completed', 1)->count();
        $incomplete = (clone $baseQuery)->where('awb_trackings.is_completed', 0)->count();
    
        $summary = (clone $baseQuery)
            ->join('delivery_statuses as d', 'awb_trackings.status_code', '=', 'd.code')
            ->select(
                'd.dashboard_category',
                DB::raw('GROUP_CONCAT(DISTINCT d.code) as status_codes'),
                DB::raw('COUNT(*) as total'),
                DB::raw('ROUND(COUNT(*) * 100.0 / ' . ($total_all ?: 1) . ', 2) as percentage')
            )
            ->groupBy('d.dashboard_category')
            ->orderByDesc('total')
            ->get();
    
        $batches = DB::table('upload_batches')
            ->leftJoin('users', 'users.id', '=', 'upload_batches.user_id')
            ->select('upload_batches.*', 'users.name as uploader')
            ->orderByDesc('uploaded_at')->limit(5)->get();
    
        // Grafik data per hari (7 hari terakhir)
        $chartDates = collect();
        $chartSuccess = collect();
        $chartFailed = collect();
    
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('d M');
    
            $chartDates->push($label);
            $chartSuccess->push(DB::table('awb_trackings')->whereDate('created_at', $date)->where('status_code', 'DELIVERED')->count());
            $chartFailed->push(DB::table('awb_trackings')->whereDate('created_at', $date)->where('is_completed', 0)->count());
        }
    
        return view('pages.dashboard.index', compact(
            'summary', 'batches', 'total_all', 'completed', 'incomplete',
            'chartDates', 'chartSuccess', 'chartFailed'
        ));
    }
    
    
}
