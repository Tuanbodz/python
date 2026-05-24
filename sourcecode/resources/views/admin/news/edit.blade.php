@extends('admin.layouts.app')
@section('title', 'Sửa bài viết')

@section('content')
<div class="row g-3">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">
                    <i class="bi bi-pencil"></i> Sửa bài viết
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.news.update', $news) }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Tiêu đề <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $news->title) }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tóm tắt</label>
                        <textarea name="summary" rows="2" class="form-control">{{ old('summary', $news->summary) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Nội dung <span class="text-danger">*</span>
                        </label>
                        <textarea name="content" rows="12"
                                  class="form-control @error('content') is-invalid @enderror">{{ old('content', $news->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ảnh đại diện</label>
                        @if($news->thumbnail)
                            <div class="mb-2">
                                <img src="{{ asset('storage/'.$news->thumbnail) }}"
                                     class="rounded" style="max-height:150px">
                                <div class="text-muted small mt-1">Ảnh hiện tại</div>
                            </div>
                        @endif
                        <input type="file" name="thumbnail" class="form-control"
                               accept="image/*" onchange="previewImage(this)">
                        <div class="form-text">Để trống nếu không đổi ảnh</div>
                        <img id="preview" src="#"
                             class="mt-2 rounded d-none"
                             style="max-height:150px;max-width:100%">
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active"
                                   class="form-check-input" id="is_active"
                                   {{ $news->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Hiển thị
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Cập nhật
                        </button>
                        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Quản lý bình luận --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">
                    <i class="bi bi-chat"></i> Bình luận
                    ({{ $news->comments->count() }})
                </h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Người dùng</th>
                            <th>Nội dung</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($news->comments as $comment)
                        <tr>
                            <td class="small fw-bold">
                                {{ $comment->user->name ?? '—' }}
                            </td>
                            <td class="small">{{ Str::limit($comment->content, 80) }}</td>
                            <td>
                                @if($comment->is_approved)
                                    <span class="badge bg-success">Đã duyệt</span>
                                @else
                                    <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.comments.approve', $comment) }}"
                                      method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm
                                        {{ $comment->is_approved ? 'btn-warning' : 'btn-success' }}">
                                        <i class="bi bi-{{ $comment->is_approved ? 'eye-slash' : 'check' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.comments.delete', $comment) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Xoá bình luận?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-3 text-muted">
                                Chưa có bình luận
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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