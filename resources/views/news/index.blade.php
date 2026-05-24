@extends('layouts.app')
@section('title', 'Tin tức')

@section('content')
<div class="container my-4">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Tin tức</li>
        </ol>
    </nav>

    <h4 class="fw-bold mb-4">
        <i class="bi bi-newspaper"></i> Tin tức & Khuyến mãi
    </h4>

    @if($news->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-newspaper" style="font-size:60px;color:#dee2e6"></i>
            <h5 class="mt-3 text-muted">Chưa có bài viết nào</h5>
        </div>
    @else
    <div class="row g-4">
        @foreach($news as $item)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 product-card">
                {{-- Thumbnail --}}
                <a href="{{ route('news.show', $item->slug) }}">
                    @if($item->thumbnail)
                        <img src="{{ asset('storage/'.$item->thumbnail) }}"
                             class="card-img-top"
                             style="height:200px;object-fit:cover"
                             alt="{{ $item->title }}">
                    @else
                        <div class="bg-light d-flex align-items-center
                                    justify-content-center"
                             style="height:200px">
                            <i class="bi bi-newspaper text-muted"
                               style="font-size:50px"></i>
                        </div>
                    @endif
                </a>

                <div class="card-body d-flex flex-column">
                    {{-- Meta --}}
                    <div class="d-flex gap-2 mb-2">
                        <small class="text-muted">
                            <i class="bi bi-person"></i>
                            {{ $item->user->name ?? 'Admin' }}
                        </small>
                        <small class="text-muted">·</small>
                        <small class="text-muted">
                            <i class="bi bi-calendar"></i>
                            {{ $item->created_at->format('d/m/Y') }}
                        </small>
                    </div>

                    {{-- Tiêu đề --}}
                    <a href="{{ route('news.show', $item->slug) }}"
                       class="text-decoration-none text-dark">
                        <h6 class="fw-bold mb-2" style="
                            display:-webkit-box;
                            -webkit-line-clamp:2;
                            -webkit-box-orient:vertical;
                            overflow:hidden">
                            {{ $item->title }}
                        </h6>
                    </a>

                    {{-- Tóm tắt --}}
                    @if($item->summary)
                    <p class="text-muted small mb-3" style="
                        display:-webkit-box;
                        -webkit-line-clamp:3;
                        -webkit-box-orient:vertical;
                        overflow:hidden">
                        {{ $item->summary }}
                    </p>
                    @endif

                    <div class="mt-auto">
                        <a href="{{ route('news.show', $item->slug) }}"
                           class="btn btn-outline-primary btn-sm">
                            Đọc thêm <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $news->links() }}
    </div>
    @endif
</div>
@endsection