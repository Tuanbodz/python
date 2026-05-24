@extends('layouts.app')
@section('title', 'Đặt hàng')

@section('content')
<div class="container my-4">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Giỏ hàng</a></li>
            <li class="breadcrumb-item active">Đặt hàng</li>
        </ol>
    </nav>

    <h4 class="fw-bold mb-4">
        <i class="bi bi-bag-check"></i> Thanh toán
    </h4>

    <form action="{{ route('checkout.store') }}" method="POST">
    @csrf

    <div class="row g-4">

        {{-- Cột trái: Thông tin giao hàng --}}
        <div class="col-md-7">

            {{-- Thông tin người nhận --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">
                        <i class="bi bi-geo-alt"></i> Thông tin giao hàng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                Họ tên người nhận <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="receiver_name"
                                   class="form-control @error('receiver_name') is-invalid @enderror"
                                   value="{{ old('receiver_name', $user->name) }}">
                            @error('receiver_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                Số điện thoại <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="receiver_phone"
                                   class="form-control @error('receiver_phone') is-invalid @enderror"
                                   value="{{ old('receiver_phone', $user->phone) }}"
                                   placeholder="VD: 0901234567">
                            @error('receiver_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-bold">
                                Địa chỉ <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="receiver_address"
                                   class="form-control @error('receiver_address') is-invalid @enderror"
                                   value="{{ old('receiver_address') }}"
                                   placeholder="Số nhà, tên đường, phường/xã">
                            @error('receiver_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                Tỉnh/Thành phố <span class="text-danger">*</span>
                            </label>
                            <select name="receiver_city"
                                    class="form-select @error('receiver_city') is-invalid @enderror">
                                <option value="">— Chọn —</option>
                                @foreach(['Hà Nội','TP. Hồ Chí Minh','Đà Nẵng','Hải Phòng',
                                          'Cần Thơ','An Giang','Bà Rịa - Vũng Tàu','Bắc Giang',
                                          'Bắc Kạn','Bạc Liêu','Bắc Ninh','Bến Tre','Bình Định',
                                          'Bình Dương','Bình Phước','Bình Thuận','Cà Mau',
                                          'Cao Bằng','Đắk Lắk','Đắk Nông','Điện Biên','Đồng Nai',
                                          'Đồng Tháp','Gia Lai','Hà Giang','Hà Nam','Hà Tĩnh',
                                          'Hải Dương','Hậu Giang','Hòa Bình','Hưng Yên',
                                          'Khánh Hòa','Kiên Giang','Kon Tum','Lai Châu',
                                          'Lâm Đồng','Lạng Sơn','Lào Cai','Long An','Nam Định',
                                          'Nghệ An','Ninh Bình','Ninh Thuận','Phú Thọ','Phú Yên',
                                          'Quảng Bình','Quảng Nam','Quảng Ngãi','Quảng Ninh',
                                          'Quảng Trị','Sóc Trăng','Sơn La','Tây Ninh','Thái Bình',
                                          'Thái Nguyên','Thanh Hóa','Thừa Thiên Huế','Tiền Giang',
                                          'Trà Vinh','Tuyên Quang','Vĩnh Long','Vĩnh Phúc',
                                          'Yên Bái'] as $city)
                                    <option value="{{ $city }}"
                                        {{ old('receiver_city') == $city ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                            @error('receiver_city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <textarea name="note" rows="2" class="form-control"
                                      placeholder="Ghi chú thêm (không bắt buộc)...">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Phương thức thanh toán --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">
                        <i class="bi bi-credit-card"></i> Phương thức thanh toán
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        {{-- COD --}}
                        <div class="col-md-6">
                            <label class="d-block cursor-pointer">
                                <input type="radio" name="payment_method"
                                       value="cod" class="d-none payment-radio"
                                       id="cod" checked>
                                <div class="card border payment-option p-3 text-center"
                                     id="cod-card" style="border-color:#0d6efd!important">
                                    <i class="bi bi-cash-coin text-success"
                                       style="font-size:32px"></i>
                                    <div class="fw-bold mt-1">COD</div>
                                    <small class="text-muted">Thanh toán khi nhận hàng</small>
                                </div>
                            </label>
                        </div>

                        {{-- VNPay --}}
                        <div class="col-md-6">
                            <label class="d-block cursor-pointer">
                                <input type="radio" name="payment_method"
                                       value="vnpay" class="d-none payment-radio"
                                       id="vnpay">
                                <div class="card border payment-option p-3 text-center"
                                     id="vnpay-card">
                                    <i class="bi bi-bank text-info"
                                       style="font-size:32px"></i>
                                    <div class="fw-bold mt-1">VNPay</div>
                                    <small class="text-muted">Thanh toán online / QR</small>
                                </div>
                            </label>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- Cột phải: Tóm tắt đơn hàng --}}
        <div class="col-md-5">
            <div class="card border-0 shadow-sm sticky-top" style="top:80px">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">
                        <i class="bi bi-receipt"></i>
                        Đơn hàng ({{ count($cart) }} sản phẩm)
                    </h6>
                </div>
                <div class="card-body p-0">

                    {{-- Danh sách sản phẩm --}}
                    <div class="p-3 border-bottom" style="max-height:300px;overflow-y:auto">
                        @foreach($cart as $item)
                        <div class="d-flex gap-3 mb-3">
                            <img src="{{ asset('storage/'.$item['thumbnail']) }}"
                                 width="55" height="55"
                                 class="rounded object-fit-cover flex-shrink-0">
                            <div class="flex-grow-1">
                                <div class="small fw-bold">{{ $item['name'] }}</div>
                                <div class="small text-muted">
                                    {{ number_format($item['price']) }}đ × {{ $item['quantity'] }}
                                </div>
                            </div>
                            <div class="small fw-bold text-danger">
                                {{ number_format($item['price'] * $item['quantity']) }}đ
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Mã giảm giá --}}
                    @if($coupon)
                    <div class="px-3 pt-3">
                        <div class="alert alert-success py-2 mb-0">
                            <i class="bi bi-ticket-perforated"></i>
                            Mã: <strong>{{ $coupon['code'] }}</strong>
                        </div>
                    </div>
                    @endif

                    {{-- Tổng tiền --}}
                    <div class="p-3">
                        <table class="table table-borderless mb-0 small">
                            <tr>
                                <td class="text-muted ps-0">Tạm tính:</td>
                                <td class="text-end pe-0">{{ number_format($subtotal) }}đ</td>
                            </tr>
                            @if($discount > 0)
                            <tr>
                                <td class="text-success ps-0">Giảm giá:</td>
                                <td class="text-end pe-0 text-success">
                                    -{{ number_format($discount) }}đ
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td class="text-muted ps-0">Vận chuyển:</td>
                                <td class="text-end pe-0 text-success">Miễn phí</td>
                            </tr>
                            <tr class="border-top">
                                <td class="fw-bold fs-5 ps-0">Tổng cộng:</td>
                                <td class="text-end pe-0 fw-bold fs-5 text-danger">
                                    {{ number_format($total) }}đ
                                </td>
                            </tr>
                        </table>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-bag-check"></i> Đặt hàng ngay
                            </button>
                        </div>

                        <div class="text-center mt-2">
                            <small class="text-muted">
                                <i class="bi bi-shield-check text-success"></i>
                                Thanh toán an toàn & bảo mật
                            </small>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
// Highlight phương thức thanh toán được chọn
document.querySelectorAll('.payment-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.payment-option').forEach(card => {
            card.style.borderColor = '#dee2e6';
            card.style.background  = '#fff';
        });
        if (this.checked) {
            const card = document.getElementById(this.value + '-card');
            card.style.borderColor = '#0d6efd';
            card.style.background  = '#f0f7ff';
        }
    });
});
</script>
@endsection