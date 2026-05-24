@extends('admin.layouts.app')
@section('title', 'Sửa mã giảm giá')

@section('content')
<div class="card border-0 shadow-sm" style="max-width:700px">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0"><i class="bi bi-pencil"></i> Sửa mã: {{ $coupon->code }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
            @csrf @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Mã giảm giá <span class="text-danger">*</span></label>
                    <input type="text" name="code"
                           class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code', $coupon->code) }}"
                           style="text-transform:uppercase">
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Loại giảm</label>
                    <select name="type" class="form-select">
                        <option value="percent" {{ $coupon->type=='percent' ? 'selected':'' }}>Phần trăm (%)</option>
                        <option value="fixed"   {{ $coupon->type=='fixed'   ? 'selected':'' }}>Tiền mặt (đ)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Giá trị giảm <span class="text-danger">*</span></label>
                    <input type="number" name="value"
                           class="form-control @error('value') is-invalid @enderror"
                           value="{{ old('value', $coupon->value) }}" min="1">
                    @error('value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Đơn hàng tối thiểu (đ)</label>
                    <input type="number" name="min_order" class="form-control"
                           value="{{ old('min_order', $coupon->min_order) }}" min="0">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Giới hạn lượt dùng</label>
                    <input type="number" name="usage_limit" class="form-control"
                           value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1"
                           placeholder="Để trống = không giới hạn">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Ngày hết hạn</label>
                    <input type="date" name="expires_at" class="form-control"
                           value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}">
                </div>

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="is_active"
                               class="form-check-input" id="is_active"
                               {{ $coupon->is_active ? 'checked':'' }}>
                        <label class="form-check-label" for="is_active">Kích hoạt</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Cập nhật
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </form>
    </div>
</div>
@endsection