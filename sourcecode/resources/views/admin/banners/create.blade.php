@extends('admin.layouts.app')
@section('title', 'Thêm Banner')

@section('content')
<div class="card border-0 shadow-sm" style="max-width:700px">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0"><i class="bi bi-plus-circle"></i> Thêm banner mới</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.banners.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">Tiêu đề <span class="text-danger">*</span></label>
                <input type="text" name="title"
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title') }}"
                       placeholder="VD: Khuyến mãi tháng 12">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Ảnh banner <span class="text-danger">*</span></label>
                <input type="file" name="image"
                       class="form-control @error('image') is-invalid @enderror"
                       accept="image/*" onchange="previewImage(this)">
                <div class="form-text">Khuyến nghị kích thước: 1200x400px</div>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <img id="preview" src="#"
                     class="mt-2 rounded w-100 d-none" style="max-height:200px;object-fit:cover">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Link khi click</label>
                <input type="text" name="link" class="form-control"
                       value="{{ old('link') }}"
                       placeholder="VD: /san-pham hoặc để trống">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Thứ tự</label>
                <input type="number" name="sort_order" class="form-control"
                       style="width:120px" value="{{ old('sort_order', 0) }}" min="0">
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
                    <i class="bi bi-check-lg"></i> Lưu banner
                </button>
                <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
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