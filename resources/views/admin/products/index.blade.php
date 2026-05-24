@extends('admin.layouts.app')
@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-watch"></i> Danh sách sản phẩm</h6>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Thêm sản phẩm
        </a>
    </div>

    {{-- Thanh tìm kiếm --}}
    <div class="card-body border-bottom py-2">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm"
                   placeholder="Tìm tên sản phẩm..."
                   value="{{ request('search') }}" style="max-width: 250px">
            <select name="category_id" class="form-select form-select-sm" style="max-width: 180px">
                <option value="">Tất cả danh mục</option>
                @foreach(\App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}"
                        {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-search"></i> Lọc
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x"></i> Xoá lọc
            </a>
        </form>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Thương hiệu</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Nổi bật</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="{{ asset('storage/'.$product->thumbnail) }}"
                             width="55" height="55"
                             class="rounded object-fit-cover">
                    </td>
                    <td>
                        <div class="fw-bold">{{ $product->name }}</div>
                        <small class="text-muted">Đã bán: {{ $product->sold }}</small>
                    </td>
                    <td>{{ $product->category->name ?? '—' }}</td>
                    <td>{{ $product->brand->name ?? '—' }}</td>
                    <td>
                        @if($product->sale_price)
                            <div class="text-danger fw-bold">
                                {{ number_format($product->sale_price) }}đ
                            </div>
                            <small class="text-muted text-decoration-line-through">
                                {{ number_format($product->price) }}đ
                            </small>
                        @else
                            <div class="fw-bold">{{ number_format($product->price) }}đ</div>
                        @endif
                    </td>
                    <td>
                        @if($product->stock <= 5)
                            <span class="badge bg-danger">{{ $product->stock }}</span>
                        @elseif($product->stock <= 20)
                            <span class="badge bg-warning text-dark">{{ $product->stock }}</span>
                        @else
                            <span class="badge bg-success">{{ $product->stock }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($product->is_featured)
                            <i class="bi bi-star-fill text-warning"></i>
                        @else
                            <i class="bi bi-star text-muted"></i>
                        @endif
                    </td>
                    <td>
                        @if($product->is_active)
                            <span class="badge bg-success">Hiện</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Xác nhận xoá sản phẩm này?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-4 text-muted">
                        Chưa có sản phẩm nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0">
        {{ $products->links() }}
    </div>
</div>
@endsection