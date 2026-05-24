@extends('admin.layouts.app')
@section('title', 'Thêm sản phẩm')

@section('content')
<form action="{{ route('admin.products.store') }}"
      method="POST" enctype="multipart/form-data">
@csrf

<div class="row g-3">
    {{-- Cột trái --}}
    <div class="col-md-8">

        {{-- Thông tin cơ bản --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Thông tin cơ bản</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Tên sản phẩm <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="VD: Casio MTP-V001L-1B">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Mô tả ngắn</label>
                    <textarea name="description" rows="2"
                              class="form-control"
                              placeholder="Mô tả ngắn hiển thị ở trang danh sách...">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nội dung chi tiết</label>
                    <textarea name="content" rows="5"
                              class="form-control"
                              placeholder="Mô tả chi tiết sản phẩm...">{{ old('content') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Thông số kỹ thuật --}}
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
                            <option value="Quartz" {{ old('movement') == 'Quartz' ? 'selected' : '' }}>Quartz</option>
                            <option value="Automatic" {{ old('movement') == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                            <option value="Eco-Drive" {{ old('movement') == 'Eco-Drive' ? 'selected' : '' }}>Eco-Drive</option>
                            <option value="Kinetic" {{ old('movement') == 'Kinetic' ? 'selected' : '' }}>Kinetic</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Kháng nước</label>
                        <select name="water_resistance" class="form-select">
                            <option value="">— Chọn —</option>
                            <option value="30m">30m</option>
                            <option value="50m">50m</option>
                            <option value="100m">100m</option>
                            <option value="200m">200m</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Đường kính mặt</label>
                        <input type="text" name="case_size"
                               class="form-control" value="{{ old('case_size') }}"
                               placeholder="VD: 40mm">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Màu mặt đồng hồ</label>
                        <input type="text" name="dial_color"
                               class="form-control" value="{{ old('dial_color') }}"
                               placeholder="VD: Đen, Trắng, Xanh...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Chất liệu vỏ</label>
                        <input type="text" name="case_material"
                               class="form-control" value="{{ old('case_material') }}"
                               placeholder="VD: Thép không gỉ">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Chất liệu dây</label>
                        <input type="text" name="strap_material"
                               class="form-control" value="{{ old('strap_material') }}"
                               placeholder="VD: Da, Kim loại, Cao su...">
                    </div>
                </div>
            </div>
        </div>

        {{-- Ảnh gallery --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Ảnh gallery (nhiều ảnh)</h6>
            </div>
            <div class="card-body">
                <input type="file" name="images[]"
                       class="form-control @error('images.*') is-invalid @enderror"
                       accept="image/*" multiple
                       onchange="previewGallery(this)">
                <div class="form-text">Có thể chọn nhiều ảnh cùng lúc</div>
                <div id="gallery-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
            </div>
        </div>

    </div>

    {{-- Cột phải --}}
    <div class="col-md-4">

        {{-- Ảnh đại diện --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Ảnh đại diện <span class="text-danger">*</span></h6>
            </div>
            <div class="card-body">
                <input type="file" name="thumbnail"
                       class="form-control @error('thumbnail') is-invalid @enderror"
                       accept="image/*"
                       onchange="previewThumb(this)">
                @error('thumbnail')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <img id="thumb-preview" src="#"
                     class="mt-2 rounded w-100 d-none" style="max-height: 200px; object-fit: cover">
            </div>
        </div>

        {{-- Danh mục & Thương hiệu --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Phân loại</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Danh mục <span class="text-danger">*</span>
                    </label>
                    <select name="category_id"
                            class="form-select @error('category_id') is-invalid @enderror">
                        <option value="">— Chọn danh mục —</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Thương hiệu <span class="text-danger">*</span>
                    </label>
                    <select name="brand_id"
                            class="form-select @error('brand_id') is-invalid @enderror">
                        <option value="">— Chọn thương hiệu —</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}"
                                {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Giá & Kho --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Giá & Kho hàng</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Giá gốc (đ) <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="price"
                           class="form-control @error('price') is-invalid @enderror"
                           value="{{ old('price') }}"
                           placeholder="VD: 850000" min="0">
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Giá khuyến mãi (đ)</label>
                    <input type="number" name="sale_price"
                           class="form-control @error('sale_price') is-invalid @enderror"
                           value="{{ old('sale_price') }}"
                           placeholder="Để trống nếu không giảm giá" min="0">
                    @error('sale_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Số lượng tồn kho <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="stock"
                           class="form-control @error('stock') is-invalid @enderror"
                           value="{{ old('stock', 0) }}" min="0">
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Tuỳ chọn --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Tuỳ chọn</h6>
            </div>
            <div class="card-body">
                <div class="form-check form-switch mb-2">
                    <input type="checkbox" name="is_active"
                           class="form-check-input" id="is_active" checked>
                    <label class="form-check-label" for="is_active">
                        Hiển thị sản phẩm
                    </label>
                </div>
                <div class="form-check form-switch">
                    <input type="checkbox" name="is_featured"
                           class="form-check-input" id="is_featured">
                    <label class="form-check-label" for="is_featured">
                        <i class="bi bi-star-fill text-warning"></i> Sản phẩm nổi bật
                    </label>
                </div>
            </div>
        </div>

        {{-- Nút lưu --}}
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Lưu sản phẩm
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
// Preview ảnh đại diện
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

// Preview nhiều ảnh gallery
function previewGallery(input) {
    const container = document.getElementById('gallery-preview');
    container.innerHTML = '';
    if (input.files) {
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
}
</script>
@endsection