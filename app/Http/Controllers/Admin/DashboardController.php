<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê tổng quan
        $totalRevenue = Order::where('status', 'delivered')->sum('total');
        $totalOrders  = Order::count();
        $totalUsers   = User::where('role', 'user')->count();
        $totalProducts = Product::count();

        // Đơn hàng mới nhất (10 cái)
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Sản phẩm sắp hết hàng (stock < 5)
        $lowStockProducts = Product::where('stock', '<', 5)
            ->where('is_active', true)
            ->get();

        // Doanh thu 7 ngày gần nhất (cho biểu đồ)
        $revenueChart = Order::where('status', 'delivered')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Đơn hàng theo trạng thái (cho biểu đồ tròn)
        $ordersByStatus = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        return view('admin.dashboard.index', compact(
            'totalRevenue', 'totalOrders', 'totalUsers', 'totalProducts',
            'recentOrders', 'lowStockProducts', 'revenueChart', 'ordersByStatus'
        ));
    }
}