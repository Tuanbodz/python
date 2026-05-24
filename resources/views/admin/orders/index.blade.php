@extends('admin.layouts.app')
@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0"><i class="bi bi-bag"></i> Danh sách đơn hàng</h6>
    </div>

    {{-- Bộ lọc --}}
    <div class="card-body border-bottom py-2">
        <form method="GET" class="d-flex flex-wrap gap-2">
            <input type="text" name="search"
                   class="form-control form-control-sm"
                   placeholder="Mã đơn, tên, SĐT..."
                   value="{{ request('search') }}" style="max-width:200px">

            <select name="status" class="form-select form-select-sm" style="max-width:160px">
                <option value="">Tất cả trạng thái</option>
                <option value="pending"   {{ request('status')=='pending'   ? 'selected':'' }}>Chờ xác nhận</option>
                <option value="confirmed" {{ request('status')=='confirmed' ? 'selected':'' }}>Đã xác nhận</option>
                <option value="shipping"  {{ request('status')=='shipping'  ? 'selected':'' }}>Đang giao</option>
                <option value="delivered" {{ request('status')=='delivered' ? 'selected':'' }}>Đã giao</option>
                <option value="cancelled" {{ request('status')=='cancelled' ? 'selected':'' }}>Đã huỷ</option>
            </select>

            <select name="payment_method" class="form-select form-select-sm" style="max-width:140px">
                <option value="">Tất cả TT</option>
                <option value="cod"   {{ request('payment_method')=='cod'   ? 'selected':'' }}>COD</option>
                <option value="vnpay" {{ request('payment_method')=='vnpay' ? 'selected':'' }}>VNPay</option>
            </select>

            <button type="submit" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-search"></i> Lọc
            </button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x"></i> Xoá lọc
            </a>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>SĐT</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    @php
                        $badges = [
                            'pending'   => 'warning',
                            'confirmed' => 'info',
                            'shipping'  => 'primary',
                            'delivered' => 'success',
                            'cancelled' => 'danger',
                        ];
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="fw-bold text-decoration-none">
                                {{ $order->order_code }}
                            </a>
                        </td>
                        <td>{{ $order->receiver_name }}</td>
                        <td>{{ $order->receiver_phone }}</td>
                        <td class="fw-bold text-danger">
                            {{ number_format($order->total) }}đ
                        </td>
                        <td>
                            <span class="badge {{ $order->payment_method == 'vnpay' ? 'bg-info' : 'bg-secondary' }}">
                                {{ strtoupper($order->payment_method) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $badges[$order->status] ?? 'secondary' }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="text-muted small">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            Không có đơn hàng nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0">
        {{ $orders->links() }}
    </div>
</div>
@endsection