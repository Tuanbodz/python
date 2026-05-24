@extends('admin.layouts.app')
@section('title', 'Quản lý Banner')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-image"></i> Danh sách Banner</h6>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Thêm banner
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Ảnh banner</th>
                    <th>Tiêu đề</th>
                    <th>Link</th>
                    <th>Thứ tự</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="{{ asset('storage/'.$banner->image) }}"
                             style="width:120px;height:60px;object-fit:cover"
                             class="rounded">
                    </td>
                    <td class="fw-bold">{{ $banner->title }}</td>
                    <td class="text-muted small">{{ $banner->link ?? '—' }}</td>
                    <td>{{ $banner->sort_order }}</td>
                    <td>
                        @if($banner->is_active)
                            <span class="badge bg-success">Hiện</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.banners.edit', $banner) }}"
                           class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.banners.destroy', $banner) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Xác nhận xoá banner?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Chưa có banner</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0">{{ $banners->links() }}</div>
</div>
@endsection