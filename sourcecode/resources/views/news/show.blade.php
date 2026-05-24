@extends('layouts.app')
@section('title', $news->title)

@section('content')
<div class="container my-4">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('news.index') }}">Tin tức</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($news->title, 40) }}</li>
        </ol>
    </nav>

    <div class="row g-4">

        {{-- Nội dung bài viết --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">

                    {{-- Tiêu đề --}}
                    <h3 class="fw-bold mb-2">{{ $news->title }}</h3>

                    {{-- Meta --}}
                    <div class="d-flex gap-3 mb-3 text-muted small">
                        <span>
                            <i class="bi bi-person"></i>
                            {{ $news->user->name ?? 'Admin' }}
                        </span>
                        <span>
                            <i class="bi bi-calendar"></i>
                            {{ $news->created_at->format('d/m/Y H:i') }}
                        </span>
                        <span>
                            <i class="bi bi-chat"></i>
                            {{ $news->comments->count() }} bình luận
                        </span>
                    </div>

                    {{-- Thumbnail --}}
                    @if($news->thumbnail)
                        <img src="{{ asset('storage/'.$news->thumbnail) }}"
                             class="img-fluid rounded mb-4 w-100"
                             style="max-height:400px;object-fit:cover"
                             alt="{{ $news->title }}">
                    @endif

                    {{-- Nội dung --}}
                    <div class="news-content" style="line-height:1.8">
                        {!! nl2br(e($news->content)) !!}
                    </div>

                </div>
            </div>

            {{-- Bình luận --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">
                        <i class="bi bi-chat"></i>
                        Bình luận ({{ $news->comments->count() }})
                    </h6>
                </div>
                <div class="card-body">

                    {{-- Danh sách bình luận --}}
                    @forelse($news->comments as $comment)
                    <div class="d-flex gap-3 mb-4">
                        <div class="rounded-circle bg-primary d-flex align-items-center
                                    justify-content-center text-white fw-bold flex-shrink-0"
                             style="width:42px;height:42px">
                            {{ strtoupper(substr($comment->user->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">{{ $comment->user->name ?? 'Ẩn danh' }}</span>
                                <small class="text-muted">
                                    {{ $comment->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <p class="mb-0 mt-1">{{ $comment->content }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center py-2">
                        Chưa có bình luận nào. Hãy là người đầu tiên!
                    </p>
                    @endforelse

                    <hr>

                    {{-- Form bình luận --}}
                    @auth
                    <h6 class="fw-bold mb-3">Viết bình luận</h6>
                    <form action="{{ route('news.comment', $news->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea name="content" rows="3"
                                      class="form-control @error('content') is-invalid @enderror"
                                      placeholder="Nhập bình luận của bạn...">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Gửi bình luận
                        </button>
                    </form>
                    @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <a href="{{ route('login') }}">Đăng nhập</a> để bình luận.
                    </div>
                    @endauth

                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-md-4">

            {{-- Tin tức liên quan --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">
                        <i class="bi bi-newspaper"></i> Bài viết liên quan
                    </h6>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($related as $item)
                    <a href="{{ route('news.show', $item->slug) }}"
                       class="list-group-item list-group-item-action border-0 py-3">
                        <div class="d-flex gap-3">
                            @if($item->thumbnail)
                                <img src="{{ asset('storage/'.$item->thumbnail) }}"
                                     width="70" height="55"
                                     class="rounded object-fit-cover flex-shrink-0">
                            @endif
                            <div>
                                <div class="small fw-bold" style="
                                    display:-webkit-box;
                                    -webkit-line-clamp:2;
                                    -webkit-box-orient:vertical;
                                    overflow:hidden">
                                    {{ $item->title }}
                                </div>
                                <small class="text-muted">
                                    {{ $item->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="list-group-item border-0 text-muted text-center py-3">
                        Không có bài viết liên quan
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection