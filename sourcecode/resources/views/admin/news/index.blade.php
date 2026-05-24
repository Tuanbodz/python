@extends('admin.layouts.app')
@section('title', 'Quản lý tin tức')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-newspaper"></i> Danh sách bài viết</h6>
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Thêm bài viết
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Thumbnail</th>
                    <th>Tiêu đề</th>
                    <th>Tác giả</th>
                    <th>Bình luận</th>
                    <th>Trạng thái</th>
                    <th>Ngày đăng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news as $item)
                <tr>
                    <td>
                        @if($item->thumbnail)
                            <img src="{{ asset('storage/'.$item->thumbnail) }}"
                                 style="width:80px;height:50px;object-fit:cover"
                                 class="rounded">
                        @else
                            <div class="bg-light rounded d-flex align-items-center
                                        justify-content-center"
                                 style="width:80px;height:50px">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold">{{ Str::limit($item->title, 50) }}</div>
                        @if($item->summary)
                            <small class="text-muted">{{ Str::limit($item->summary, 60) }}</small>
                        @endif
                    </td>
                    <td class="small">{{ $item->user->name ?? '—' }}</td>
                    <td>
                        <span class="badge bg-info">{{ $item->comments_count }}</span>
                    </td>
                    <td>
                        @if($item->is_active)
                            <span class="badge bg-success">Hiện</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td class="text-muted small">
                        {{ $item->created_at->format('d/m/Y') }}
                    </td>
                    <td>
                        <a href="{{ route('news.show', $item->slug) }}"
                           target="_blank" class="btn btn-sm btn-info text-white">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.news.edit', $item) }}"
                           class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.news.destroy', $item) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Xác nhận xoá bài viết?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        Chưa có bài viết nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0">{{ $news->links() }}</div>
</div>
@endsection