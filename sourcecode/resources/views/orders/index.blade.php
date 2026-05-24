@extends('layouts.app')
@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container my-4">

    <h4 class="fw-bold mb-4">
        <i class="bi bi-bag"></i> Đơn hàng của tôi
    </h4>

    @if($orders->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-bag-x" style="font-size:70px;color:#dee2e6"></i>
        <h5 class="mt-3 text-muted">Bạn chưa có đơn hàng nào</h5>
        <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">
            Mua sắm ngay
        </a>
    </div>
    @else

    <div class="row g-3">
        @foreach($orders as $order)
        @php
            $badges = [
                'pending'   => 'warning',
                'confirmed' => 'info',
                'shipping'  => 'primary',
                'delivered' => 'success',
                'cancelled' => 'danger',
            ];
        @endphp
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex
                            justify-content-between align-items-center">
                    <div>
                        <span class="fw-bold">{{ $order->order_code }}</span>
                        <span class="text-muted small ms-2">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <span class="badge bg-{{ $badges[$order->status] ?? 'secondary' }}">
                        {{ $order->status_label }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            {{ $order->items->count() }} sản phẩm
                            · {{ strtoupper($order->payment_method) }}
                        </div>
                        <div class="fw-bold text-danger fs-5">
                            {{ number_format($order->total) }}đ
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 d-flex gap-2 justify-content-end">
                    <a href="{{ route('orders.show', $order->order_code) }}"
                       class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i> Chi tiết
                    </a>
                    @if($order->status === 'pending')
                    <form action="{{ route('orders.cancel', $order->order_code) }}"
                          method="POST"
                          onsubmit="return confirm('Xác nhận huỷ đơn hàng này?')">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-x-circle"></i> Huỷ đơn
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection