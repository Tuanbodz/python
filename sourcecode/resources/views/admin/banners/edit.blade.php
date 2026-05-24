@extends('admin.layouts.app')
@section('title', 'Sửa Banner')

@section('content')
<div class="card border-0 shadow-sm" style="max-width:700px">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0"><i class="bi bi-pencil"></i> Sửa banner: {{ $banner->title }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.banners.update', $banner) }}"
              method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">Tiêu đề <span class="text-danger">*</span></label>
                <input type="text" name="title"
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title', $banner->title) }}">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Ảnh banner</label>
                <img src="{{ asset('storage/'.$banner->image) }}"
                     class="rounded w-100 mb-2" style="max-height:150px;object-fit:cover">
                <input type="file" name="image" class="form-control"
                       accept="image/*" onchange="previewImage(this)">
                <div class="form-text">Để trống nếu không đổi ảnh</div>
                <img id="preview" src="#"
                     class="mt-2 rounded w-100 d-none" style="max-height:200px;object-fit:cover">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Link khi click</label>
                <input type="text" name="link" class="form-control"
                       value="{{ old('link', $banner->link) }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Thứ tự</label>
                <input type="number" name="sort_order" class="form-control"
                       style="width:120px"
                       value="{{ old('sort_order', $banner->sort_order) }}" min="0">
            </div>

            <div class="mb-3">
                <div class="form-check form-switch">
                    <input type="checkbox" name="is_active"
                           class="form-check-input" id="is_active"
                           {{ $banner->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Hiển thị</label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Cập nhật
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