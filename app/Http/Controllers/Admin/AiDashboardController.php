<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiLog;
use Illuminate\Support\Facades\DB;

class AiDashboardController extends Controller
{
    public function index()
    {
        // Tổng chi phí
        $totalCost = AiLog::sum('cost');

        // Tổng token
        $totalTokens = AiLog::sum('input_tokens')
                     + AiLog::sum('output_tokens');

        // Thống kê theo feature
        $byFeature = AiLog::select(
                'feature',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(input_tokens + output_tokens) as total_tokens'),
                DB::raw('SUM(cost) as total_cost')
            )
            ->groupBy('feature')
            ->get();

        // Chi phí 7 ngày gần nhất
        $costChart = AiLog::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(cost) as cost'),
                DB::raw('COUNT(*) as calls')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Log gần nhất
        $recentLogs = AiLog::with('user')
            ->latest()
            ->take(20)
            ->get();

        return view('admin.ai.dashboard',
            compact('totalCost', 'totalTokens',
                    'byFeature', 'costChart', 'recentLogs'));
    }
}