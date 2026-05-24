@extends('admin.layouts.app')
@section('title', 'Thêm thương hiệu')

@section('content')
<div class="card border-0 shadow-sm" style="max-width: 700px">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0"><i class="bi bi-plus-circle"></i> Thêm thương hiệu mới</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.brands.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">
                    Tên thương hiệu <span class="text-danger">*</span>
                </label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       placeholder="VD: Casio, Seiko...">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Logo thương hiệu</label>
                <input type="file" name="logo"
                       class="form-control @error('logo') is-invalid @enderror"
                       accept="image/*"
                       onchange="previewImage(this)">
                @error('logo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <img id="preview" src="#"
                     class="mt-2 rounded d-none" style="max-height: 120px">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mô tả</label>
                <textarea name="description" rows="3"
                          class="form-control"
                          placeholder="Mô tả ngắn về thương hiệu...">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <div class="form-check form-switch">
                    <input type="checkbox" name="is_active"
                           class="form-check-input" id="is_active" checked>
                    <label class="form-check-label" for="is_active">Hiển thị</label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Lưu thương hiệu
                </button>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
