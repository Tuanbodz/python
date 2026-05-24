@extends('admin.layouts.app')
@section('title', 'Quản lý đánh giá')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-star"></i> Danh sách đánh giá</h6>
    </div>

    <div class="card-body border-bottom py-2">
        <form method="GET" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm" style="max-width:150px">
                <option value="">Tất cả</option>
                <option value="pending"  {{ request('status')=='pending'  ? 'selected':'' }}>Chờ duyệt</option>
                <option value="approved" {{ request('status')=='approved' ? 'selected':'' }}>Đã duyệt</option>
            </select>
            <select name="sentiment" class="form-select form-select-sm" style="max-width:160px">
                <option value="">Tất cả cảm xúc</option>
                <option value="positive" {{ request('sentiment')=='positive' ? 'selected':'' }}>Tích cực</option>
                <option value="neutral"  {{ request('sentiment')=='neutral'  ? 'selected':'' }}>Trung tính</option>
                <option value="negative" {{ request('sentiment')=='negative' ? 'selected':'' }}>Tiêu cực</option>
            </select>
            <button type="submit" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-search"></i> Lọc
            </button>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x"></i> Xoá lọc
            </a>
        </form>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Sản phẩm</th>
                    <th>Khách hàng</th>
                    <th>Rating</th>
                    <th>Nội dung</th>
                    <th>Cảm xúc AI</th>
                    <th>Trạng thái</th>
                    <th>Ngày</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td class="small">{{ $review->product->name ?? '—' }}</td>
                    <td class="small">{{ $review->user->name ?? '—' }}</td>
                    <td>
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-muted' }}"
                               style="font-size:12px"></i>
                        @endfor
                    </td>
                    <td style="max-width:200px">
                        <span class="text-truncate d-block">{{ $review->comment }}</span>
                        @if($review->ai_summary)
                            <small class="text-muted fst-italic">
                                AI: {{ Str::limit($review->ai_summary, 60) }}
                            </small>
                        @endif
                    </td>
                    <td>
                        @if($review->ai_sentiment == 'positive')
                            <span class="badge bg-success">Tích cực</span>
                        @elseif($review->ai_sentiment == 'negative')
                            <span class="badge bg-danger">Tiêu cực</span>
                        @elseif($review->ai_sentiment == 'neutral')
                            <span class="badge bg-secondary">Trung tính</span>
                        @else
                            <span class="text-muted small">Chưa phân tích</span>
                        @endif
                    </td>
                    <td>
                        @if($review->is_approved)
                            <span class="badge bg-success">Đã duyệt</span>
                        @else
                            <span class="badge bg-warning text-dark">Chờ duyệt</span>
                        @endif
                    </td>
                    <td class="text-muted small">
                        {{ $review->created_at->format('d/m/Y') }}
                    </td>
                    {{-- Thêm vào phần td thao tác của mỗi review --}}
                    @if(!$review->ai_sentiment)
                    <form action="{{ route('ai.admin.sentiment', $review) }}"
                        method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-outline-info"
                                title="Phân tích AI">
                            <i class="bi bi-robot"></i>
                        </button>
                    </form>
                    @endif
                    <td>
                        <form action="{{ route('admin.reviews.approve', $review) }}"
                              method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm {{ $review->is_approved ? 'btn-warning' : 'btn-success' }}">
                                <i class="bi bi-{{ $review->is_approved ? 'eye-slash' : 'check-lg' }}"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.reviews.destroy', $review) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Xác nhận xoá đánh giá này?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        Chưa có đánh giá nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0">{{ $reviews->links() }}</div>
</div>
@endsection