@extends('admin.layouts.app')
@section('title', 'Sửa sản phẩm')

@section('content')
<form action="{{ route('admin.products.update', $product) }}"
      method="POST" enctype="multipart/form-data">
@csrf @method('PUT')

<div class="row g-3">
    {{-- Cột trái --}}
    <div class="col-md-8">

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Thông tin cơ bản</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $product->name) }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Mô tả ngắn</label>
                    <textarea name="description" rows="2" class="form-control">{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Nội dung chi tiết</label>
                    <textarea name="content" rows="5" class="form-control">{{ old('content', $product->content) }}</textarea>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Thông số kỹ thuật</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Máy (Movement)</label>
                        <select name="movement" class="form-select">
                            <option value="">— Chọn —</option>
                            @foreach(['Quartz','Automatic','Eco-Drive','Kinetic'] as $m)
                                <option value="{{ $m }}"
                                    {{ $product->movement == $m ? 'selected' : '' }}>
                                    {{ $m }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Kháng nước</label>
                        <select name="water_resistance" class="form-select">
                            <option value="">— Chọn —</option>
                            @foreach(['30m','50m','100m','200m'] as $w)
                                <option value="{{ $w }}"
                                    {{ $product->water_resistance == $w ? 'selected' : '' }}>
                                    {{ $w }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Đường kính mặt</label>
                        <input type="text" name="case_size" class="form-control"
                               value="{{ old('case_size', $product->case_size) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Màu mặt đồng hồ</label>
                        <input type="text" name="dial_color" class="form-control"
                               value="{{ old('dial_color', $product->dial_color) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Chất liệu vỏ</label>
                        <input type="text" name="case_material" class="form-control"
                               value="{{ old('case_material', $product->case_material) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Chất liệu dây</label>
                        <input type="text" name="strap_material" class="form-control"
                               value="{{ old('strap_material', $product->strap_material) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Ảnh gallery hiện tại --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Ảnh gallery</h6>
            </div>
            <div class="card-body">
                {{-- Hiện ảnh cũ --}}
                @if($images->count() > 0)
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach($images as $img)
                        <div class="position-relative" id="img-{{ $img->id }}">
                            <img src="{{ asset('storage/'.$img->image) }}"
                                 class="rounded"
                                 style="width:80px;height:80px;object-fit:cover">
                            <button type="button"
                                    class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                    style="padding: 0 4px; font-size: 10px"
                                    onclick="deleteGalleryImage({{ $img->id }})">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                @endif

                {{-- Upload thêm ảnh mới --}}
                <label class="form-label fw-bold">Thêm ảnh mới</label>
                <input type="file" name="images[]" class="form-control"
                       accept="image/*" multiple
                       onchange="previewGallery(this)">
                <div id="gallery-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
            </div>
        </div>

    </div>

    {{-- Cột phải --}}
    <div class="col-md-4">

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Ảnh đại diện</h6>
            </div>
            <div class="card-body">
                <img src="{{ asset('storage/'.$product->thumbnail) }}"
                     class="rounded w-100 mb-2" style="max-height:200px;object-fit:cover">
                <input type="file" name="thumbnail" class="form-control"
                       accept="image/*" onchange="previewThumb(this)">
                <div class="form-text">Để trống nếu không đổi ảnh</div>
                <img id="thumb-preview" src="#"
                     class="mt-2 rounded w-100 d-none" style="max-height:200px;object-fit:cover">
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Phân loại</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Thương hiệu <span class="text-danger">*</span></label>
                    <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}"
                                {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Giá & Kho hàng</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Giá gốc (đ) <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control"
                           value="{{ old('price', $product->price) }}" min="0">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Giá khuyến mãi (đ)</label>
                    <input type="number" name="sale_price" class="form-control"
                           value="{{ old('sale_price', $product->sale_price) }}" min="0">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Tồn kho <span class="text-danger">*</span></label>
                    <input type="number" name="stock" class="form-control"
                           value="{{ old('stock', $product->stock) }}" min="0">
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Tuỳ chọn</h6>
            </div>
            <div class="card-body">
                <div class="form-check form-switch mb-2">
                    <input type="checkbox" name="is_active" class="form-check-input"
                           id="is_active" {{ $product->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Hiển thị</label>
                </div>
                <div class="form-check form-switch">
                    <input type="checkbox" name="is_featured" class="form-check-input"
                           id="is_featured" {{ $product->is_featured ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_featured">
                        <i class="bi bi-star-fill text-warning"></i> Sản phẩm nổi bật
                    </label>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Cập nhật sản phẩm
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>

    </div>
</div>
</form>
@endsection

@section('scripts')
<script>
function previewThumb(input) {
    const preview = document.getElementById('thumb-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewGallery(input) {
    const container = document.getElementById('gallery-preview');
    container.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'rounded';
            img.style = 'width:80px;height:80px;object-fit:cover';
            container.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}

// Xoá ảnh gallery bằng AJAX
function deleteGalleryImage(imageId) {
    if (!confirm('Xác nhận xoá ảnh này?')) return;

    fetch('{{ route("admin.products.deleteImage") }}', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ image_id: imageId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('img-' + imageId).remove();
        }
    });
}
</script>
@endsection