@extends('layouts.app')
@section('title', 'Gợi ý sản phẩm cho bạn')

@section('content')
<div class="container my-4">
    <div class="text-center mb-4">
        <h4 class="fw-bold">
            <i class="bi bi-robot text-primary"></i>
            Gợi ý dành riêng cho bạn
        </h4>
        <p class="text-muted">
            AI đã phân tích sở thích của bạn và gợi ý những sản phẩm phù hợp nhất
        </p>
    </div>

    <div class="row g-3 justify-content-center">
        @forelse($suggestions as $product)
            @include('products.partials.card', ['product' => $product])
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-lightbulb" style="font-size:60px;color:#dee2e6"></i>
                <h5 class="mt-3 text-muted">Chưa có gợi ý</h5>
                <p class="text-muted">Hãy mua sắm để AI có thể gợi ý sản phẩm phù hợp!</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    Khám phá sản phẩm
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection