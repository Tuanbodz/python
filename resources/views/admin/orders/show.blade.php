@extends('admin.layouts.app')
@section('title', 'Chi tiết đơn hàng')

@section('content')
@php
    $badges = [
        'pending'   => 'warning',
        'confirmed' => 'info',
        'shipping'  => 'primary',
        'delivered' => 'success',
        'cancelled' => 'danger',
    ];
@endphp

<div class="row g-3">
    {{-- Cột trái --}}
    <div class="col-md-8">

        {{-- Sản phẩm trong đơn --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0 d-flex justify-content-between">
                <h6 class="mb-0">
                    <i class="bi bi-bag"></i> Đơn hàng #{{ $order->order_code }}
                </h6>
                <span class="badge bg-{{ $badges[$order->status] ?? 'secondary' }} fs-6">
                    {{ $order->status_label }}
                </span>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th class="text-center">Đơn giá</th>
                            <th class="text-center">SL</th>
                            <th class="text-end">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($item->product && $item->product->thumbnail)
                                        <img src="{{ asset('storage/'.$item->product->thumbnail) }}"
                                             width="50" height="50"
                                             class="rounded object-fit-cover">
                                    @endif
                                    <span>{{ $item->product_name }}</span>
                                </div>
                            </td>
                            <td class="text-center">{{ number_format($item->price) }}đ</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end fw-bold">{{ number_format($item->total) }}đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end">Tổng tiền hàng:</td>
                            <td class="text-end">{{ number_format($order->subtotal) }}đ</td>
                        </tr>
                        @if($order->discount > 0)
                        <tr>
                            <td colspan="3" class="text-end text-success">
                                Giảm giá
                                @if($order->coupon)
                                    ({{ $order->coupon->code }})
                                @endif:
                            </td>
                            <td class="text-end text-success">
                                -{{ number_format($order->discount) }}đ
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="3" class="text-end fw-bold fs-5">Tổng thanh toán:</td>
                            <td class="text-end fw-bold fs-5 text-danger">
                                {{ number_format($order->total) }}đ
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Timeline trạng thái --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0"><i class="bi bi-clock-history"></i> Cập nhật trạng thái</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.updateStatus', $order) }}"
                      method="POST" class="d-flex gap-2 align-items-end">
                    @csrf @method('PATCH')
                    <div>
                        <label class="form-label fw-bold mb-1">Trạng thái mới</label>
                        <select name="status" class="form-select" style="min-width: 200px">
                            <option value="pending"   {{ $order->status=='pending'   ? 'selected':'' }}>Chờ xác nhận</option>
                            <option value="confirmed" {{ $order->status=='confirmed' ? 'selected':'' }}>Đã xác nhận</option>
                            <option value="shipping"  {{ $order->status=='shipping'  ? 'selected':'' }}>Đang giao hàng</option>
                            <option value="delivered" {{ $order->status=='delivered' ? 'selected':'' }}>Đã giao hàng</option>
                            <option value="cancelled" {{ $order->status=='cancelled' ? 'selected':'' }}>Đã huỷ</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Cập nhật
                    </button>
                </form>

                {{-- Timeline --}}
                <div class="mt-4 d-flex gap-0">
                    @php
                        $steps = [
                            'pending'   => ['label' => 'Chờ xác nhận', 'icon' => 'clock'],
                            'confirmed' => ['label' => 'Đã xác nhận',  'icon' => 'check-circle'],
                            'shipping'  => ['label' => 'Đang giao',    'icon' => 'truck'],
                            'delivered' => ['label' => 'Đã giao',      'icon' => 'house-check'],
                        ];
                        $statusOrder = ['pending','confirmed','shipping','delivered'];
                        $currentIndex = array_search($order->status, $statusOrder);
                    @endphp

                    @foreach($steps as $key => $step)
                    @php $stepIndex = array_search($key, $statusOrder); @endphp
                    <div class="text-center flex-fill">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1
                            {{ $stepIndex <= $currentIndex && $order->status != 'cancelled'
                               ? 'bg-success text-white'
                               : 'bg-light text-muted' }}"
                             style="width:40px;height:40px">
                            <i class="bi bi-{{ $step['icon'] }}"></i>
                        </div>
                        <small class="{{ $stepIndex <= $currentIndex && $order->status != 'cancelled'
                            ? 'text-success fw-bold'
                            : 'text-muted' }}">
                            {{ $step['label'] }}
                        </small>
                    </div>
                    @if(!$loop->last)
                        <div class="flex-fill d-flex align-items-center" style="margin-top:-20px">
                            <hr class="w-100 {{ $stepIndex < $currentIndex && $order->status != 'cancelled'
                                ? 'border-success border-2'
                                : 'border-light' }}">
                        </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- Cột phải --}}
    <div class="col-md-4">

        {{-- Thông tin khách hàng --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0"><i class="bi bi-person"></i> Thông tin khách hàng</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Tài khoản:</td>
                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Người nhận:</td>
                        <td class="fw-bold">{{ $order->receiver_name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">SĐT:</td>
                        <td>{{ $order->receiver_phone }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Địa chỉ:</td>
                        <td>{{ $order->receiver_address }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tỉnh/TP:</td>
                        <td>{{ $order->receiver_city }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Thông tin thanh toán --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0"><i class="bi bi-credit-card"></i> Thanh toán</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Phương thức:</td>
                        <td>
                            <span class="badge {{ $order->payment_method == 'vnpay' ? 'bg-info' : 'bg-secondary' }}">
                                {{ strtoupper($order->payment_method) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">TT thanh toán:</td>
                        <td>
                            @if($order->payment_status == 'paid')
                                <span class="badge bg-success">Đã thanh toán</span>
                            @elseif($order->payment_status == 'failed')
                                <span class="badge bg-danger">Thất bại</span>
                            @else
                                <span class="badge bg-warning text-dark">Chờ thanh toán</span>
                            @endif
                        </td>
                    </tr>
                    @if($order->vnpay_transaction_id)
                    <tr>
                        <td class="text-muted">Mã GD VNPay:</td>
                        <td class="small">{{ $order->vnpay_transaction_id }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- Ghi chú --}}
        @if($order->note)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0"><i class="bi bi-chat-text"></i> Ghi chú</h6>
            </div>
            <div class="card-body">
                <p class="mb-0 text-muted">{{ $order->note }}</p>
            </div>
        </div>
        @endif

        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary w-100">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
    </div>
</div>
@endsection