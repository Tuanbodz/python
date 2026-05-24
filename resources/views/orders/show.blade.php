@extends('layouts.app')
@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container my-4">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Đơn hàng</a></li>
            <li class="breadcrumb-item active">{{ $order->order_code }}</li>
        </ol>
    </nav>

    @php
        $badges = [
            'pending'   => 'warning',
            'confirmed' => 'info',
            'shipping'  => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
        ];
    @endphp

    <div class="row g-4">
        <div class="col-md-8">

            {{-- Header đơn hàng --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1">{{ $order->order_code }}</h5>
                        <small class="text-muted">
                            Đặt lúc: {{ $order->created_at->format('H:i d/m/Y') }}
                        </small>
                    </div>
                    <span class="badge bg-{{ $badges[$order->status] ?? 'secondary' }} fs-6">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>

            {{-- Timeline --}}
            @if($order->status !== 'cancelled')
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    @php
                        $steps = ['pending','confirmed','shipping','delivered'];
                        $currentIndex = array_search($order->status, $steps);
                    @endphp
                    <div class="d-flex">
                        @foreach(['Chờ xác nhận','Đã xác nhận','Đang giao','Đã giao'] as $i => $label)
                        <div class="text-center flex-fill">
                            <div class="rounded-circle d-flex align-items-center
                                        justify-content-center mx-auto mb-1
                                        {{ $i <= $currentIndex ? 'bg-success text-white' : 'bg-light text-muted' }}"
                                 style="width:36px;height:36px">
                                <i class="bi bi-{{ ['clock','check','truck','house-check'][$i] }}"
                                   style="font-size:16px"></i>
                            </div>
                            <small class="{{ $i <= $currentIndex ? 'text-success fw-bold' : 'text-muted' }}">
                                {{ $label }}
                            </small>
                        </div>
                        @if($i < 3)
                        <div class="flex-fill d-flex align-items-center pb-4">
                            <hr class="w-100 {{ $i < $currentIndex ? 'border-success border-2' : '' }}">
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Sản phẩm --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">Sản phẩm đã đặt</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0 align-middle">
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($item->product)
                                            <img src="{{ asset('storage/'.$item->product->thumbnail) }}"
                                                 width="55" height="55"
                                                 class="rounded object-fit-cover">
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $item->product_name }}</div>
                                            <small class="text-muted">
                                                x{{ $item->quantity }} ×
                                                {{ number_format($item->price) }}đ
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end fw-bold">
                                    {{ number_format($item->total) }}đ
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td>Tạm tính</td>
                                <td class="text-end">{{ number_format($order->subtotal) }}đ</td>
                            </tr>
                            @if($order->discount > 0)
                            <tr>
                                <td class="text-success">Giảm giá</td>
                                <td class="text-end text-success">
                                    -{{ number_format($order->discount) }}đ
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td class="fw-bold fs-5">Tổng cộng</td>
                                <td class="text-end fw-bold fs-5 text-danger">
                                    {{ number_format($order->total) }}đ
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>

        {{-- Cột phải --}}
        <div class="col-md-4">

            {{-- Địa chỉ giao hàng --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0"><i class="bi bi-geo-alt"></i> Địa chỉ giao hàng</h6>
                </div>
                <div class="card-body small">
                    <p class="fw-bold mb-1">{{ $order->receiver_name }}</p>
                    <p class="mb-1">📞 {{ $order->receiver_phone }}</p>
                    <p class="mb-0 text-muted">
                        📍 {{ $order->receiver_address }}, {{ $order->receiver_city }}
                    </p>
                </div>
            </div>

            {{-- Thanh toán --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0"><i class="bi bi-credit-card"></i> Thanh toán</h6>
                </div>
                <div class="card-body small">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Phương thức:</span>
                        <span class="badge bg-secondary">
                            {{ strtoupper($order->payment_method) }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Trạng thái:</span>
                        @if($order->payment_status === 'paid')
                            <span class="badge bg-success">Đã thanh toán</span>
                        @else
                            <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Nút hành động --}}
            <div class="d-grid gap-2">
                @if($order->status === 'pending')
                <form action="{{ route('orders.cancel', $order->order_code) }}"
                      method="POST"
                      onsubmit="return confirm('Xác nhận huỷ đơn?')">
                    @csrf @method('PATCH')
                    <button class="btn btn-outline-danger w-100">
                        <i class="bi bi-x-circle"></i> Huỷ đơn hàng
                    </button>
                </form>
                @endif
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Về danh sách đơn hàng
                </a>
            </div>
        </div>
    </div>
</div>
@endsection