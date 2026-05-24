<div class="col-6 col-md-3">
    <div class="card product-card shadow-sm h-100 position-relative">
        {{-- Badge giảm giá --}}
        @if($product->sale_price)
            @php
                $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
            @endphp
            <span class="badge-sale">-{{ $discount }}%</span>
        @endif

        {{-- Ảnh sản phẩm --}}
        <a href="{{ route('products.show', $product->slug) }}">
            <img src="{{ asset('storage/'.$product->thumbnail) }}"
                 class="card-img-top"
                 alt="{{ $product->name }}">
        </a>

        <div class="card-body d-flex flex-column p-3">
            {{-- Thương hiệu --}}
            <small class="text-muted mb-1">{{ $product->brand->name ?? '' }}</small>

            {{-- Tên sản phẩm --}}
            <a href="{{ route('products.show', $product->slug) }}"
               class="text-decoration-none text-dark">
                <h6 class="card-title mb-2" style="
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;">
                    {{ $product->name }}
                </h6>
            </a>

            {{-- Rating --}}
            <div class="mb-2">
                @php $rating = round($product->avg_rating); @endphp
                @for($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $i <= $rating ? '-fill text-warning' : ' text-muted' }}"
                       style="font-size:12px"></i>
                @endfor
                <small class="text-muted">({{ $product->reviews->count() }})</small>
            </div>

            {{-- Giá --}}
            <div class="mt-auto">
                @if($product->sale_price)
                    <div class="text-danger fw-bold fs-6">
                        {{ number_format($product->sale_price) }}đ
                    </div>
                    <small class="text-muted text-decoration-line-through">
                        {{ number_format($product->price) }}đ
                    </small>
                @else
                    <div class="text-danger fw-bold fs-6">
                        {{ number_format($product->price) }}đ
                    </div>
                @endif
            </div>

            {{-- Nút thêm giỏ hàng --}}
            <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit"
                        class="btn btn-primary btn-sm w-100
                               {{ $product->stock == 0 ? 'disabled' : '' }}">
                    @if($product->stock == 0)
                        <i class="bi bi-x-circle"></i> Hết hàng
                    @else
                        <i class="bi bi-bag-plus"></i> Thêm vào giỏ
                    @endif
                </button>
            </form>
        </div>
    </div>
</div>