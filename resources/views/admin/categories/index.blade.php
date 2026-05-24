@extends('admin.layouts.app')
@section('title', 'Quản lý danh mục')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-grid"></i> Danh sách danh mục</h6>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Thêm danh mục
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Ảnh</th>
                    <th>Tên danh mục</th>
                    <th>Danh mục cha</th>
                    <th>Thứ tự</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($category->image)
                            <img src="{{ asset('storage/'.$category->image) }}"
                                 width="50" height="50"
                                 class="rounded object-fit-cover">
                        @else
                            <div class="bg-light rounded d-flex align-items-center
                                        justify-content-center" style="width:50px;height:50px">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->parent->name ?? '— Cấp 1 —' }}</td>
                    <td>{{ $category->sort_order }}</td>
                    <td>
                        @if($category->is_active)
                            <span class="badge bg-success">Hiện</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Xác nhận xoá danh mục này?')">
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
                        Chưa có danh mục nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0">
        {{ $categories->links() }}
    </div>
</div>
@endsection