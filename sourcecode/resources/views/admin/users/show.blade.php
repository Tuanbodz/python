@extends('admin.layouts.app')
@section('title', 'Chi tiết người dùng')

@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-4">
                @if($user->avatar)
                    <img src="{{ asset('storage/'.$user->avatar) }}"
                         width="100" height="100"
                         class="rounded-circle object-fit-cover mb-3">
                @else
                    <div class="rounded-circle bg-primary d-flex align-items-center
                                justify-content-center text-white fw-bold mx-auto mb-3"
                         style="width:100px;height:100px;font-size:40px">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <h5>{{ $user->name }}</h5>
                <p class="text-muted mb-1">{{ $user->email }}</p>
                <p class="text-muted mb-3">{{ $user->phone ?? 'Chưa có SĐT' }}</p>

                <span class="badge {{ $user->role == 'admin' ? 'bg-danger' : 'bg-primary' }} mb-2">
                    {{ ucfirst($user->role) }}
                </span>
                <br>
                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}">
                    {{ $user->is_active ? 'Hoạt động' : 'Bị khoá' }}
                </span>

                <div class="mt-3">
                    <small class="text-muted">
                        Tham gia: {{ $user->created_at->format('d/m/Y') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">
                    <i class="bi bi-bag"></i>
                    Lịch sử đơn hàng ({{ $orders->total() }})
                </h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
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
                                <a href="{{ route('admin.orders.show', $order) }}">
                                    {{ $order->order_code }}
                                </a>
                            </td>
                            <td class="fw-bold text-danger">
                                {{ number_format($order->total) }}đ
                            </td>
                            <td>
                                <span class="badge bg-{{ $badges[$order->status] ?? 'secondary' }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="text-muted small">
                                {{ $order->created_at->format('d/m/Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-3 text-muted">
                                Chưa có đơn hàng
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-0">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection