@extends('admin.layouts.app')
@section('title', 'Thêm mã giảm giá')

@section('content')
<div class="card border-0 shadow-sm" style="max-width:700px">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0"><i class="bi bi-plus-circle"></i> Thêm mã giảm giá mới</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Mã giảm giá <span class="text-danger">*</span></label>
                    <input type="text" name="code"
                           class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code') }}"
                           placeholder="VD: SALE20"
                           style="text-transform:uppercase">
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Loại giảm <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" id="typeSelect"
                            onchange="updateValueLabel()">
                        <option value="percent" {{ old('type')=='percent' ? 'selected':'' }}>Phần trăm (%)</option>
                        <option value="fixed"   {{ old('type')=='fixed'   ? 'selected':'' }}>Tiền mặt (đ)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold" id="valueLabel">
                        Giá trị giảm (%) <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="value"
                           class="form-control @error('value') is-invalid @enderror"
                           value="{{ old('value') }}" min="1"
                           placeholder="VD: 10">
                    @error('value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Đơn hàng tối thiểu (đ)</label>
                    <input type="number" name="min_order" class="form-control"
                           value="{{ old('min_order', 0) }}" min="0"
                           placeholder="0 = không giới hạn">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Giới hạn lượt dùng</label>
                    <input type="number" name="usage_limit" class="form-control"
                           value="{{ old('usage_limit') }}" min="1"
                           placeholder="Để trống = không giới hạn">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Ngày hết hạn</label>
                    <input type="date" name="expires_at" class="form-control"
                           value="{{ old('expires_at') }}">
                    <div class="form-text">Để trống = không hết hạn</div>
                </div>

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="is_active"
                               class="form-check-input" id="is_active" checked>
                        <label class="form-check-label" for="is_active">Kích hoạt</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Lưu mã
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function updateValueLabel() {
    const type = document.getElementById('typeSelect').value;
    const label = document.getElementById('valueLabel');
    label.innerHTML = type === 'percent'
        ? 'Giá trị giảm (%) <span class="text-danger">*</span>'
        : 'Giá trị giảm (đ) <span class="text-danger">*</span>';
}
</script>
@endsection