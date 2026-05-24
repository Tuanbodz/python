@extends('layouts.app')
@section('title', 'Danh sách sản phẩm')

@section('content')
<div class="container my-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Sản phẩm</li>
        </ol>
    </nav>

    <div class="row g-4">

        {{-- Sidebar lọc --}}
        <div class="col-md-3">
            <form method="GET" id="filterForm">
                {{-- Giữ lại search nếu có --}}
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                {{-- Danh mục --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0"><i class="bi bi-grid"></i> Danh mục</h6>
                    </div>
                    <div class="card-body pt-0">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('products.index') }}"
                               class="list-group-item list-group-item-action border-0
                                      {{ !request('category') ? 'text-primary fw-bold' : '' }}">
                                Tất cả
                            </a>
                            @foreach($categories as $cat)
                            <a href="{{ route('products.index', array_merge(request()->except('category','page'), ['category' => $cat->slug])) }}"
                               class="list-group-item list-group-item-action border-0
                                      {{ request('category') == $cat->slug ? 'text-primary fw-bold' : '' }}">
                                {{ $cat->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Thương hiệu --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0"><i class="bi bi-award"></i> Thương hiệu</h6>
                    </div>
                    <div class="card-body">
                        @foreach($brands as $brand)
                        <div class="form-check">
                            <input type="radio" name="brand"
                                   class="form-check-input"
                                   id="brand_{{ $brand->id }}"
                                   value="{{ $brand->slug }}"
                                   onchange="document.getElementById('filterForm').submit()"
                                   {{ request('brand') == $brand->slug ? 'checked' : '' }}>
                            <label class="form-check-label" for="brand_{{ $brand->id }}">
                                {{ $brand->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Khoảng giá --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0"><i class="bi bi-currency-dollar"></i> Khoảng giá</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="small text-muted">Từ (đ)</label>
                            <input type="number" name="min_price" class="form-control form-control-sm"
                                   value="{{ request('min_price') }}" placeholder="0">
                        </div>
                        <div class="mb-2">
                            <label class="small text-muted">Đến (đ)</label>
                            <input type="number" name="max_price" class="form-control form-control-sm"
                                   value="{{ request('max_price') }}" placeholder="100,000,000">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            Áp dụng
                        </button>
                    </div>
                </div>

                {{-- Xoá lọc --}}
                @if(request()->hasAny(['category','brand','min_price','max_price','search']))
                <a href="{{ route('products.index') }}"
                   class="btn btn-outline-secondary btn-sm w-100">
                    <i class="bi bi-x"></i> Xoá bộ lọc
                </a>
                @endif
            </form>
        </div>

        {{-- Danh sách sản phẩm --}}
        <div class="col-md-9">

            {{-- Thanh công cụ --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="text-muted mb-0">
                    Tìm thấy <strong>{{ $products->total() }}</strong> sản phẩm
                    @if(request('search'))
                        cho "<strong>{{ request('search') }}</strong>"
                    @endif
                </p>
                <select name="sort" form="filterForm"
                        class="form-select form-select-sm" style="width:180px"
                        onchange="document.getElementById('filterForm').submit()">
                    <option value=""        {{ !request('sort') ? 'selected':'' }}>Mới nhất</option>
                    <option value="price_asc"  {{ request('sort')=='price_asc'  ? 'selected':'' }}>Giá tăng dần</option>
                    <option value="price_desc" {{ request('sort')=='price_desc' ? 'selected':'' }}>Giá giảm dần</option>
                    <option value="best_seller"{{ request('sort')=='best_seller'? 'selected':'' }}>Bán chạy nhất</option>
                </select>
            </div>

            {{-- Grid sản phẩm --}}
            <div class="row g-3">
                @forelse($products as $product)
                    @include('products.partials.card', ['product' => $product])
                @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search" style="font-size:60px;color:#dee2e6"></i>
                    <h5 class="mt-3 text-muted">Không tìm thấy sản phẩm</h5>
                    <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">
                        Xem tất cả sản phẩm
                    </a>
                </div>
                @endforelse
            </div>

            {{-- Phân trang --}}
            <div class="mt-4 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>

    </div>
</div>
@endsection