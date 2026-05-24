@extends('admin.layouts.app')
@section('title', 'Thêm bài viết')

@section('content')
<div class="row g-3">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0"><i class="bi bi-plus-circle"></i> Thêm bài viết mới</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.news.store') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Tiêu đề <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}"
                               placeholder="Tiêu đề bài viết...">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tóm tắt</label>
                        <textarea name="summary" rows="2" class="form-control"
                                  placeholder="Mô tả ngắn hiển thị ở trang danh sách...">{{ old('summary') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Nội dung <span class="text-danger">*</span>
                        </label>
                        <textarea name="content" rows="12"
                                  class="form-control @error('content') is-invalid @enderror"
                                  placeholder="Nội dung bài viết...">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ảnh đại diện</label>
                        <input type="file" name="thumbnail"
                               class="form-control @error('thumbnail') is-invalid @enderror"
                               accept="image/*" onchange="previewImage(this)">
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <img id="preview" src="#"
                             class="mt-2 rounded d-none"
                             style="max-height:200px;max-width:100%">
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active"
                                   class="form-check-input" id="is_active" checked>
                            <label class="form-check-label" for="is_active">
                                Hiển thị
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Đăng bài
                        </button>
                        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
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