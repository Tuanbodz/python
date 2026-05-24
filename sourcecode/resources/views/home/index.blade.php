@extends('layouts.app')
@section('title', 'Trang chủ - Đồng Hồ Online')

@section('content')

{{-- Hero Banner Slider --}}
<div id="heroSlider" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach($banners as $i => $banner)
            <button type="button" data-bs-target="#heroSlider"
                    data-bs-slide-to="{{ $i }}"
                    class="{{ $i == 0 ? 'active' : '' }}"></button>
        @endforeach
    </div>
    <div class="carousel-inner">
        @forelse($banners as $i => $banner)
        <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
            @if($banner->link)
                <a href="{{ $banner->link }}">
            @endif
            <img src="{{ asset('storage/'.$banner->image) }}"
                 class="d-block w-100"
                 style="height: 480px; object-fit: cover;"
                 alt="{{ $banner->title }}">
            <div class="carousel-caption d-none d-md-block">
                <h3>{{ $banner->title }}</h3>
            </div>
            @if($banner->link)</a>@endif
        </div>
        @empty
        {{-- Fallback nếu chưa có banner --}}
        <div class="carousel-item active">
            <div class="bg-dark d-flex align-items-center justify-content-center text-white"
                 style="height:480px">
                <div class="text-center">
                    <i class="bi bi-clock" style="font-size:80px"></i>
                    <h2 class="mt-3">Đồng Hồ Chính Hãng</h2>
                    <p>Casio · Seiko · Citizen · Fossil</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg mt-2">
                        Mua ngay
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>
    @if($banners->count() > 1)
    <button class="carousel-control-prev" type="button"
            data-bs-target="#heroSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button"
            data-bs-target="#heroSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
    @endif
</div>

{{-- Danh mục nhanh --}}
<div class="container my-5">
    <div class="row g-3 justify-content-center">
        @foreach($categories as $category)
        <div class="col-6 col-md-3">
            <a href="{{ route('products.index', ['category' => $category->slug]) }}"
               class="text-decoration-none">
                <div class="card border-0 shadow-sm text-center py-3 h-100
                            product-card">
                    @if($category->image)
                        <img src="{{ asset('storage/'.$category->image) }}"
                             width="70" height="70"
                             class="rounded-circle mx-auto mb-2 object-fit-cover">
                    @else
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex
                                    align-items-center justify-content-center mx-auto mb-2"
                             style="width:70px;height:70px">
                            <i class="bi bi-watch text-primary" style="font-size:28px"></i>
                        </div>
                    @endif
                    <h6 class="mb-0 text-dark">{{ $category->name }}</h6>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

{{-- Sản phẩm nổi bật --}}
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">⭐ Sản phẩm nổi bật</h4>
            <p class="text-muted mb-0">Những mẫu đồng hồ được yêu thích nhất</p>
        </div>
        <a href="{{ route('products.index', ['featured' => 1]) }}"
           class="btn btn-outline-primary btn-sm">Xem tất cả</a>
    </div>
    <div class="row g-3">
        @foreach($featuredProducts as $product)
        @include('products.partials.card', ['product' => $product])
        @endforeach
    </div>
</div>

{{-- Banner quảng cáo giữa trang --}}
<div class="bg-dark text-white py-5 my-5">
    <div class="container text-center">
        <h3 class="fw-bold">🎁 Ưu đãi đặc biệt tháng này</h3>
        <p class="lead">Giảm đến 30% cho tất cả đồng hồ Casio và Seiko</p>
        <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg">
            Mua ngay <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</div>

{{-- Sản phẩm mới nhất --}}
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">🆕 Sản phẩm mới nhất</h4>
            <p class="text-muted mb-0">Cập nhật mẫu mã mới nhất</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm">
            Xem tất cả
        </a>
    </div>
    <div class="row g-3">
        @foreach($newProducts as $product)
        @include('products.partials.card', ['product' => $product])
        @endforeach
    </div>
</div>

{{-- Thương hiệu --}}
<div class="container my-5">
    <h4 class="fw-bold text-center mb-4">Thương hiệu nổi tiếng</h4>
    <div class="row g-3 justify-content-center align-items-center">
        @foreach($brands as $brand)
        <div class="col-4 col-md-2 text-center">
            <a href="{{ route('products.index', ['brand' => $brand->slug]) }}"
               class="text-decoration-none">
                @if($brand->logo)
                    <img src="{{ asset('storage/'.$brand->logo) }}"
                         style="max-height:50px;max-width:100%;object-fit:contain"
                         alt="{{ $brand->name }}">
                @else
                    <div class="fw-bold text-muted fs-5">{{ $brand->name }}</div>
                @endif
            </a>
        </div>
        @endforeach
    </div>
</div>

{{-- Điểm mạnh --}}
<div class="bg-light py-5 mt-5">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <i class="bi bi-shield-check text-primary" style="font-size:40px"></i>
                <h6 class="mt-2 fw-bold">Hàng chính hãng 100%</h6>
                <p class="text-muted small">Cam kết nguồn gốc rõ ràng</p>
            </div>
            <div class="col-md-3">
                <i class="bi bi-truck text-primary" style="font-size:40px"></i>
                <h6 class="mt-2 fw-bold">Giao hàng toàn quốc</h6>
                <p class="text-muted small">Nhanh chóng và an toàn</p>
            </div>
            <div class="col-md-3">
                <i class="bi bi-arrow-return-left text-primary" style="font-size:40px"></i>
                <h6 class="mt-2 fw-bold">Đổi trả trong 7 ngày</h6>
                <p class="text-muted small">Không hài lòng hoàn tiền</p>
            </div>
            <div class="col-md-3">
                <i class="bi bi-headset text-primary" style="font-size:40px"></i>
                <h6 class="mt-2 fw-bold">Hỗ trợ 24/7</h6>
                <p class="text-muted small">Tư vấn miễn phí</p>
            </div>
        </div>
    </div>
</div>

@endsection