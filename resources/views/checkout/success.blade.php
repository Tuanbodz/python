@extends('layouts.app')
@section('title', 'Đặt hàng thành công')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-7">

            {{-- Thông báo thành công --}}
            <div class="card border-0 shadow-sm text-center mb-4">
                <div class="card-body py-5">
                    <div class="rounded-circle bg-success bg-opacity-10 d-flex
                                align-items-center justify-content-center mx-auto mb-3"
                         style="width:80px;height:80px">
                        <i class="bi bi-check-circle-fill text-success"
                           style="font-size:45px"></i>
                    </div>
                    <h4 class="fw-bold text-success">Đặt hàng thành công!</h4>
                    <p class="text-muted mb-1">Mã đơn hàng của bạn:</p>
                    <h5 class="fw-bold text-primary">{{ $order->order_code }}</h5>
                    <p class="text-muted mt-2 mb-0">
                        Chúng tôi sẽ xác nhận và giao hàng trong thời gian sớm nhất.
                        Cảm ơn bạn đã tin tưởng mua sắm!
                    </p>
                </div>
            </div>

            {{-- Chi tiết đơn --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0"><i class="bi bi-list-ul"></i> Chi tiết đơn hàng</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0 align-middle">
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        @if($item->product)
                                            <img src="{{ asset('storage/'.$item->product->thumbnail) }}"
                                                 width="45" height="45"
                                                 class="rounded object-fit-cover">
                                        @endif
                                        <div>
                                            <div class="small fw-bold">{{ $item->product_name }}</div>
                                            <small class="text-muted">x{{ $item->quantity }}</small>
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
                                <td class="fw-bold">Tổng thanh toán</td>
                                <td class="text-end fw-bold text-danger fs-5">
                                    {{ number_format($order->total) }}đ
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Thông tin giao hàng --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0"><i class="bi bi-geo-alt"></i> Thông tin giao hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2 small">
                        <div class="col-4 text-muted">Người nhận:</div>
                        <div class="col-8 fw-bold">{{ $order->receiver_name }}</div>
                        <div class="col-4 text-muted">SĐT:</div>
                        <div class="col-8">{{ $order->receiver_phone }}</div>
                        <div class="col-4 text-muted">Địa chỉ:</div>
                        <div class="col-8">
                            {{ $order->receiver_address }}, {{ $order->receiver_city }}
                        </div>
                        <div class="col-4 text-muted">Thanh toán:</div>
                        <div class="col-8">
                            <span class="badge bg-secondary">
                                {{ strtoupper($order->payment_method) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nút điều hướng --}}
            <div class="d-flex gap-2 justify-content-center">
                <a href="{{ route('orders.show', $order->order_code) }}"
                   class="btn btn-primary">
                    <i class="bi bi-eye"></i> Xem đơn hàng
                </a>
                <a href="{{ route('products.index') }}"
                   class="btn btn-outline-secondary">
                    <i class="bi bi-bag"></i> Tiếp tục mua sắm
                </a>
            </div>

        </div>
    </div>
</div>
@endsection