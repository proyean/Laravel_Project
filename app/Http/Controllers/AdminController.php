<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Basic counts
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();
        $totalOrders = Order::count();

        // Sales data for chart (last 6 months)
        $salesData = $this->getSalesData();
        
        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories', 
            'totalUsers',
            'totalOrders',
            'salesData'
        ));
    }

    private function getSalesData()
    {
        // Get sales for last 6 months
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        
        $sales = Order::select(
            DB::raw('DATE_FORMAT(created_at, "%b") as month'),
            DB::raw('MONTH(created_at) as month_num'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(total_amount) as total'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', $sixMonthsAgo)
        ->whereIn('status', ['delivered', 'shipped', 'processing']) // Only count actual sales
        ->groupBy('year', 'month_num', 'month')
        ->orderBy('year')
        ->orderBy('month_num')
        ->get();

        // Prepare data for chart
        $labels = [];
        $data = [];
        $counts = [];
        
        // Generate last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthLabel = $date->format('M');
            $monthNum = $date->format('n');
            $year = $date->format('Y');
            
            $labels[] = $monthLabel;
            
            // Find sales for this month
            $sale = $sales->firstWhere(function ($item) use ($monthNum, $year) {
                return $item->month_num == $monthNum && $item->year == $year;
            });
            
            $data[] = $sale ? $sale->total : 0;
            $counts[] = $sale ? $sale->count : 0;
        }

        return [
            'labels' => $labels,
            'sales' => $data,
            'counts' => $counts,
            'total_sales' => array_sum($data),
            'total_orders' => array_sum($counts)
        ];
    }
}