@extends('admin.layouts.app')
@section('title', 'Sửa danh mục')

@section('content')
<div class="card border-0 shadow-sm" style="max-width: 700px">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0"><i class="bi bi-pencil"></i> Sửa danh mục: {{ $category->name }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.categories.update', $category) }}"
              method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $category->name) }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Danh mục cha</label>
                <select name="parent_id" class="form-select">
                    <option value="">— Không có (Danh mục cấp 1) —</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}"
                            {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Ảnh danh mục</label>
                {{-- Hiện ảnh cũ --}}
                @if($category->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$category->image) }}"
                             class="rounded" style="max-height: 150px">
                        <div class="text-muted small mt-1">Ảnh hiện tại</div>
                    </div>
                @endif
                <input type="file" name="image"
                       class="form-control @error('image') is-invalid @enderror"
                       accept="image/*"
                       onchange="previewImage(this)">
                <div class="form-text">Để trống nếu không muốn đổi ảnh</div>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <img id="preview" src="#" class="mt-2 rounded d-none"
                     style="max-height: 150px">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Thứ tự hiển thị</label>
                <input type="number" name="sort_order"
                       class="form-control" style="width: 120px"
                       value="{{ old('sort_order', $category->sort_order) }}" min="0">
            </div>

            <div class="mb-3">
                <div class="form-check form-switch">
                    <input type="checkbox" name="is_active"
                           class="form-check-input" id="is_active"
                           {{ $category->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Hiển thị</label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Cập nhật
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
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