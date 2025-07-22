<?php

namespace App\Http\Controllers;

use App\Models\AwbTracking;
use App\Models\DeliveryStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AwbController extends Controller
{
    public function index(Request $request)
    {
        $status_code = DeliveryStatus::orderBy('code')->get();

        $query = AwbTracking::query()
            ->with(['user', 'batch', 'detailInfo', 'histories']);

        // Handle search/filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('awb_number', 'like', "%{$search}%")
                  ->orWhere('status_label', 'like', "%{$search}%")
                  ->orWhereHas('detailInfo', function($q) use ($search) {
                      $q->where('origin', 'like', "%{$search}%")
                        ->orWhere('destination', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('batch', function($q) use ($search) {
                      $q->where('batch_id', 'like', "%{$search}%");
                  });
            });
        }

        // Handle status code filters
        if ($request->has('status_codes')) {
            $statusCodes = (array) $request->status_codes;
            $query->whereIn('status_code', $statusCodes);
        }

        if ($request->has('completed')) {
            $query->where('is_completed', $request->completed === '1');
        }

        // Handle column sorting
        $sortColumn = $request->get('sort', 'last_checked_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortColumn, $sortDirection);

        // Get paginated results with relationships
        $awb = $query->paginate($request->get('per_page', 10));

        // Pass all parameters to view for maintaining state
        $awb->appends($request->all());

        return view('pages.awb.index', compact('awb','status_code'));
    }
}