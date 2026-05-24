@extends('layouts.app')
@section('title', $product->name)

@section('content')
<div class="container my-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('products.index', ['category' => $product->category->slug ?? '']) }}">
                    {{ $product->category->name ?? 'Sản phẩm' }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">

        {{-- Ảnh sản phẩm --}}
        <div class="col-md-5">
            {{-- Ảnh chính --}}
            <div class="card border-0 shadow-sm mb-2">
                <img src="{{ asset('storage/'.$product->thumbnail) }}"
                     id="mainImage"
                     class="card-img-top rounded"
                     style="height:400px;object-fit:cover"
                     alt="{{ $product->name }}">
            </div>

            {{-- Ảnh gallery --}}
            @if($product->images->count() > 0)
            <div class="d-flex gap-2 flex-wrap">
                {{-- Ảnh thumbnail chính --}}
                <img src="{{ asset('storage/'.$product->thumbnail) }}"
                     class="rounded border border-primary gallery-thumb"
                     style="width:70px;height:70px;object-fit:cover;cursor:pointer"
                     onclick="changeImage(this.src)">

                @foreach($product->images as $img)
                <img src="{{ asset('storage/'.$img->image) }}"
                     class="rounded border gallery-thumb"
                     style="width:70px;height:70px;object-fit:cover;cursor:pointer"
                     onclick="changeImage(this.src)">
                @endforeach
            </div>
            @endif
        </div>

        {{-- Thông tin sản phẩm --}}
        <div class="col-md-7">
            <small class="text-muted">{{ $product->brand->name ?? '' }}</small>
            <h3 class="fw-bold mt-1">{{ $product->name }}</h3>

            {{-- Rating --}}
            <div class="d-flex align-items-center gap-2 mb-3">
                @php $avgRating = round($product->avg_rating); @endphp
                <div>
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= $avgRating ? '-fill text-warning' : ' text-muted' }}"></i>
                    @endfor
                </div>
                <span class="text-muted small">
                    ({{ $product->reviews->count() }} đánh giá)
                </span>
                <span class="text-muted small">|</span>
                <span class="text-muted small">Đã bán: {{ $product->sold }}</span>
            </div>

            {{-- Giá --}}
            <div class="mb-3">
                @if($product->sale_price)
                    <span class="text-danger fw-bold" style="font-size:28px">
                        {{ number_format($product->sale_price) }}đ
                    </span>
                    <span class="text-muted text-decoration-line-through ms-2 fs-5">
                        {{ number_format($product->price) }}đ
                    </span>
                    @php $discount = round((($product->price - $product->sale_price) / $product->price) * 100); @endphp
                    <span class="badge bg-danger ms-2">-{{ $discount }}%</span>
                @else
                    <span class="text-danger fw-bold" style="font-size:28px">
                        {{ number_format($product->price) }}đ
                    </span>
                @endif
            </div>

            {{-- Mô tả ngắn --}}
            @if($product->description)
            <p class="text-muted mb-3">{{ $product->description }}</p>
            @endif

            {{-- Thông số nhanh --}}
            <div class="row g-2 mb-3">
                @if($product->movement)
                <div class="col-6">
                    <small class="text-muted">Máy:</small>
                    <strong class="ms-1">{{ $product->movement }}</strong>
                </div>
                @endif
                @if($product->case_size)
                <div class="col-6">
                    <small class="text-muted">Kích thước:</small>
                    <strong class="ms-1">{{ $product->case_size }}</strong>
                </div>
                @endif
                @if($product->water_resistance)
                <div class="col-6">
                    <small class="text-muted">Kháng nước:</small>
                    <strong class="ms-1">{{ $product->water_resistance }}</strong>
                </div>
                @endif
                @if($product->strap_material)
                <div class="col-6">
                    <small class="text-muted">Dây:</small>
                    <strong class="ms-1">{{ $product->strap_material }}</strong>
                </div>
                @endif
            </div>

            {{-- Tình trạng --}}
            <div class="mb-3">
                @if($product->stock > 0)
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle"></i>
                        Còn hàng ({{ $product->stock }})
                    </span>
                @else
                    <span class="badge bg-danger">Hết hàng</span>
                @endif
            </div>

            {{-- Form thêm giỏ hàng --}}
            @if($product->stock > 0)
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <label class="fw-bold">Số lượng:</label>
                    <div class="input-group" style="width:130px">
                        <button type="button" class="btn btn-outline-secondary"
                                onclick="changeQty(-1)">−</button>
                        <input type="number" name="quantity" id="qtyInput"
                               class="form-control text-center" value="1"
                               min="1" max="{{ $product->stock }}">
                        <button type="button" class="btn btn-outline-secondary"
                                onclick="changeQty(1)">+</button>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-bag-plus"></i> Thêm vào giỏ
                    </button>
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-bag"></i> Xem giỏ hàng
                    </a>
                </div>
            </form>
            @endif

        </div>
    </div>

    {{-- Tabs chi tiết --}}
    <div class="mt-5">
        <ul class="nav nav-tabs" id="productTabs">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab"
                        data-bs-target="#tabDetail">Mô tả chi tiết</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab"
                        data-bs-target="#tabSpecs">Thông số kỹ thuật</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab"
                        data-bs-target="#tabReviews">
                    Đánh giá ({{ $product->reviews->count() }})
                </button>
            </li>
        </ul>

        <div class="tab-content border border-top-0 rounded-bottom p-4 bg-white">

            {{-- Tab mô tả --}}
            <div class="tab-pane fade show active" id="tabDetail">
                @if($product->content)
                    {!! nl2br(e($product->content)) !!}
                @else
                    <p class="text-muted">Chưa có mô tả chi tiết.</p>
                @endif
            </div>

            {{-- Tab thông số --}}
            <div class="tab-pane fade" id="tabSpecs">
                <table class="table table-bordered">
                    <tbody>
                        @if($product->movement)
                        <tr><td width="200" class="fw-bold bg-light">Máy</td><td>{{ $product->movement }}</td></tr>
                        @endif
                        @if($product->case_size)
                        <tr><td class="fw-bold bg-light">Kích thước mặt</td><td>{{ $product->case_size }}</td></tr>
                        @endif
                        @if($product->case_material)
                        <tr><td class="fw-bold bg-light">Chất liệu vỏ</td><td>{{ $product->case_material }}</td></tr>
                        @endif
                        @if($product->strap_material)
                        <tr><td class="fw-bold bg-light">Chất liệu dây</td><td>{{ $product->strap_material }}</td></tr>
                        @endif
                        @if($product->dial_color)
                        <tr><td class="fw-bold bg-light">Màu mặt số</td><td>{{ $product->dial_color }}</td></tr>
                        @endif
                        @if($product->water_resistance)
                        <tr><td class="fw-bold bg-light">Kháng nước</td><td>{{ $product->water_resistance }}</td></tr>
                        @endif
                        <tr><td class="fw-bold bg-light">Thương hiệu</td><td>{{ $product->brand->name ?? '—' }}</td></tr>
                        <tr><td class="fw-bold bg-light">Danh mục</td><td>{{ $product->category->name ?? '—' }}</td></tr>
                    </tbody>
                </table>
            </div>

            {{-- Tab đánh giá --}}
            <div class="tab-pane fade" id="tabReviews">
                @forelse($product->reviews as $review)
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between">
                        <div class="fw-bold">{{ $review->user->name ?? 'Ẩn danh' }}</div>
                        <small class="text-muted">
                            {{ $review->created_at->format('d/m/Y') }}
                        </small>
                    </div>
                    <div class="my-1">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-muted' }}"
                               style="font-size:13px"></i>
                        @endfor
                    </div>
                    <p class="mb-0">{{ $review->comment }}</p>
                    @if($review->ai_summary)
                        <small class="text-muted fst-italic">
                            <i class="bi bi-robot"></i> AI: {{ $review->ai_summary }}
                        </small>
                    @endif
                </div>
                @empty
                <p class="text-muted text-center py-3">
                    Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá!
                </p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Sản phẩm liên quan --}}
    @if($related->count() > 0)
    <div class="mt-5">
        <h5 class="fw-bold mb-3">Sản phẩm liên quan</h5>
        <div class="row g-3">
            @foreach($related as $product)
                @include('products.partials.card', ['product' => $product])
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
// Đổi ảnh chính
function changeImage(src) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.gallery-thumb').forEach(img => {
        img.classList.remove('border-primary');
        img.classList.add('border');
    });
    event.target.classList.add('border-primary');
}

// Tăng giảm số lượng
function changeQty(val) {
    const input = document.getElementById('qtyInput');
    const max   = parseInt(input.max);
    let newVal  = parseInt(input.value) + val;
    if (newVal < 1) newVal = 1;
    if (newVal > max) newVal = max;
    input.value = newVal;
}
</script>
@endsection