@extends('layouts.app')
@section('title', 'Giỏ hàng')

@section('content')
<div class="container my-4">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Giỏ hàng</li>
        </ol>
    </nav>

    <h4 class="fw-bold mb-4">
        <i class="bi bi-bag"></i> Giỏ hàng
        <span class="text-muted fs-6">({{ count($cart) }} sản phẩm)</span>
    </h4>

    @if(empty($cart))
    {{-- Giỏ hàng trống --}}
    <div class="text-center py-5">
        <i class="bi bi-bag-x" style="font-size:80px;color:#dee2e6"></i>
        <h5 class="mt-3 text-muted">Giỏ hàng đang trống</h5>
        <p class="text-muted">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">
            <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
        </a>
    </div>

    @else
    <div class="row g-4">

        {{-- Bảng giỏ hàng --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center">Đơn giá</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Thành tiền</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $id => $item)
                            <tr>
                                {{-- Ảnh + tên --}}
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ asset('storage/'.$item['thumbnail']) }}"
                                             width="65" height="65"
                                             class="rounded object-fit-cover">
                                        <div>
                                            <div class="fw-bold">{{ $item['name'] }}</div>
                                            <small class="text-muted">
                                                Còn {{ $item['stock'] }} trong kho
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                {{-- Đơn giá --}}
                                <td class="text-center text-danger fw-bold">
                                    {{ number_format($item['price']) }}đ
                                </td>

                                {{-- Số lượng --}}
                                <td class="text-center">
                                    <form action="{{ route('cart.update', $id) }}"
                                          method="POST" class="d-flex justify-content-center">
                                        @csrf @method('PATCH')
                                        <div class="input-group" style="width:120px">
                                            <button type="button"
                                                    class="btn btn-outline-secondary btn-sm"
                                                    onclick="decreaseQty(this)">−</button>
                                            <input type="number" name="quantity"
                                                   class="form-control form-control-sm text-center qty-input"
                                                   value="{{ $item['quantity'] }}"
                                                   min="1" max="{{ $item['stock'] }}"
                                                   onchange="this.form.submit()">
                                            <button type="button"
                                                    class="btn btn-outline-secondary btn-sm"
                                                    onclick="increaseQty(this)">+</button>
                                        </div>
                                    </form>
                                </td>

                                {{-- Thành tiền --}}
                                <td class="text-end fw-bold">
                                    {{ number_format($item['price'] * $item['quantity']) }}đ
                                </td>

                                {{-- Xoá --}}
                                <td>
                                    <form action="{{ route('cart.remove', $id) }}"
                                          method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Xoá sản phẩm này?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Nút tiếp tục mua --}}
            <div class="mt-3">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>

        {{-- Tóm tắt đơn hàng --}}
        <div class="col-md-4">

            {{-- Mã giảm giá --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">
                        <i class="bi bi-ticket-perforated"></i> Mã giảm giá
                    </h6>
                </div>
                <div class="card-body">
                    @if($coupon)
                        <div class="alert alert-success py-2 mb-2">
                            <i class="bi bi-check-circle"></i>
                            Đã áp dụng: <strong>{{ $coupon['code'] }}</strong>
                        </div>
                        <form action="{{ route('cart.coupon') }}" method="POST">
                            @csrf
                            <input type="hidden" name="coupon_code" value="">
                            <button type="button"
                                    onclick="removeCoupon()"
                                    class="btn btn-outline-danger btn-sm w-100">
                                <i class="bi bi-x"></i> Huỷ mã giảm giá
                            </button>
                        </form>
                    @else
                        <form action="{{ route('cart.coupon') }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="coupon_code"
                                       class="form-control"
                                       placeholder="Nhập mã giảm giá"
                                       style="text-transform:uppercase">
                                <button type="submit" class="btn btn-outline-primary">
                                    Áp dụng
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Tổng tiền --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">
                        <i class="bi bi-receipt"></i> Tóm tắt đơn hàng
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Tạm tính:</td>
                            <td class="text-end">{{ number_format($subtotal) }}đ</td>
                        </tr>
                        @if($discount > 0)
                        <tr>
                            <td class="text-success">Giảm giá:</td>
                            <td class="text-end text-success">
                                -{{ number_format($discount) }}đ
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-muted">Phí vận chuyển:</td>
                            <td class="text-end text-success">Miễn phí</td>
                        </tr>
                        <tr class="border-top">
                            <td class="fw-bold fs-5">Tổng cộng:</td>
                            <td class="text-end fw-bold fs-5 text-danger">
                                {{ number_format($total) }}đ
                            </td>
                        </tr>
                    </table>

                    <div class="d-grid mt-3">
                        @auth
                            <a href="{{ route('checkout.index') }}"
                               class="btn btn-primary btn-lg">
                                <i class="bi bi-bag-check"></i> Tiến hành đặt hàng
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="btn btn-primary btn-lg">
                                <i class="bi bi-person"></i> Đăng nhập để đặt hàng
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
function increaseQty(btn) {
    const input = btn.previousElementSibling;
    const max   = parseInt(input.max);
    if (parseInt(input.value) < max) {
        input.value = parseInt(input.value) + 1;
        input.dispatchEvent(new Event('change'));
    }
}

function decreaseQty(btn) {
    const input = btn.nextElementSibling;
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
        input.dispatchEvent(new Event('change'));
    }
}

function removeCoupon() {
    if (confirm('Huỷ mã giảm giá?')) {
        fetch('{{ route("cart.coupon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ coupon_code: '' })
        }).then(() => {
            sessionStorage.removeItem('coupon');
            location.reload();
        });
    }
}
</script>
@endsection