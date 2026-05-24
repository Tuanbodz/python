@extends('admin.layouts.app')
@section('title', 'Quản lý thương hiệu')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-award"></i> Danh sách thương hiệu</h6>
        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Thêm thương hiệu
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Logo</th>
                    <th>Tên thương hiệu</th>
                    <th>Mô tả</th>
                    <th>Số sản phẩm</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($brand->logo)
                            <img src="{{ asset('storage/'.$brand->logo) }}"
                                 width="50" height="50"
                                 class="rounded object-fit-contain">
                        @else
                            <div class="bg-light rounded d-flex align-items-center
                                        justify-content-center" style="width:50px;height:50px">
                                <i class="bi bi-award text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td class="fw-bold">{{ $brand->name }}</td>
                    <td class="text-muted">
                        {{ Str::limit($brand->description, 50) ?? '—' }}
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $brand->products_count }} sản phẩm</span>
                    </td>
                    <td>
                        @if($brand->is_active)
                            <span class="badge bg-success">Hiện</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.brands.edit', $brand) }}"
                           class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.brands.destroy', $brand) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Xác nhận xoá thương hiệu này?')">
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
                        Chưa có thương hiệu nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0">
        {{ $brands->links() }}
    </div>
</div>
@endsection