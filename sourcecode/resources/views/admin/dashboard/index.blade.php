@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Cards thống kê --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea, #764ba2)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-white-50 mb-1">Doanh thu</div>
                    <div class="stat-number">{{ number_format($totalRevenue) }}đ</div>
                </div>
                <i class="bi bi-currency-dollar stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb, #f5576c)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-white-50 mb-1">Đơn hàng</div>
                    <div class="stat-number">{{ $totalOrders }}</div>
                </div>
                <i class="bi bi-bag stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe, #00f2fe)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-white-50 mb-1">Người dùng</div>
                    <div class="stat-number">{{ $totalUsers }}</div>
                </div>
                <i class="bi bi-people stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #43e97b, #38f9d7)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-white-50 mb-1">Sản phẩm</div>
                    <div class="stat-number">{{ $totalProducts }}</div>
                </div>
                <i class="bi bi-watch stat-icon"></i>
            </div>
        </div>
    </div>
</div>

{{-- Biểu đồ --}}
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0"><i class="bi bi-bar-chart"></i> Doanh thu 7 ngày gần nhất</h6>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0"><i class="bi bi-pie-chart"></i> Trạng thái đơn hàng</h6>
            </div>
            <div class="card-body">
                <canvas id="orderChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Đơn hàng mới + Sản phẩm sắp hết --}}
<div class="row g-3">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex justify-content-between">
                <h6 class="mb-0"><i class="bi bi-clock-history"></i> Đơn hàng mới nhất</h6>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}">
                                        {{ $order->order_code }}
                                    </a>
                                </td>
                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                                <td>{{ number_format($order->total) }}đ</td>
                                <td>
                                    @php
                                        $badges = [
                                            'pending'   => 'warning',
                                            'confirmed' => 'info',
                                            'shipping'  => 'primary',
                                            'delivered' => 'success',
                                            'cancelled' => 'danger',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $badges[$order->status] ?? 'secondary' }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Chưa có đơn hàng</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0 text-danger">
                    <i class="bi bi-exclamation-triangle"></i> Sắp hết hàng
                </h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($lowStockProducts as $product)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-truncate" style="max-width: 180px">
                            {{ $product->name }}
                        </span>
                        <span class="badge bg-danger rounded-pill">
                            Còn {{ $product->stock }}
                        </span>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted">
                        Không có sản phẩm sắp hết
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Biểu đồ doanh thu
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($revenueChart->pluck('date')) !!},
        datasets: [{
            label: 'Doanh thu (đ)',
            data: {!! json_encode($revenueChart->pluck('revenue')) !!},
            backgroundColor: 'rgba(102, 126, 234, 0.7)',
            borderColor: 'rgba(102, 126, 234, 1)',
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Biểu đồ trạng thái đơn hàng
const orderCtx = document.getElementById('orderChart').getContext('2d');
new Chart(orderCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($ordersByStatus->pluck('status')) !!},
        datasets: [{
            data: {!! json_encode($ordersByStatus->pluck('total')) !!},
            backgroundColor: ['#ffc107','#0dcaf0','#0d6efd','#198754','#dc3545'],
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>
@endsection