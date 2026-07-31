<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Group orders by Year and Month
        $monthsData = Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as cases'),
                DB::raw('SUM(price) as total_price')
            )
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        return view('admin.reports.index', compact('monthsData'));
    }

    public function fetchOrders(Request $request)
    {
        $year = $request->query('year');
        $month = $request->query('month');
        $limit = $request->query('limit', 10); // default limit 10

        if (!$year || !$month) {
            return response()->json(['error' => 'Year and month are required.'], 400);
        }

        $orders = Order::with('client')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderByDesc('created_at')
            ->paginate($limit);

        // Append query parameters to pagination links
        $orders->appends($request->query());

        return view('admin.reports._orders', compact('orders', 'year', 'month', 'limit'))->render();
    }
}
