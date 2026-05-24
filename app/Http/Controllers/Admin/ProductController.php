<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand'])
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $brands     = Brand::where('is_active', true)->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id'    => 'required|exists:brands,id',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0|lt:price',
            'stock'       => 'required|integer|min:0',
            'thumbnail'   => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
            'images.*'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ], [
            'name.required'        => 'Vui lòng nhập tên sản phẩm',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'brand_id.required'    => 'Vui lòng chọn thương hiệu',
            'price.required'       => 'Vui lòng nhập giá sản phẩm',
            'sale_price.lt'        => 'Giá khuyến mãi phải nhỏ hơn giá gốc',
            'thumbnail.required'   => 'Vui lòng chọn ảnh đại diện',
        ]);

        // Upload ảnh đại diện
        $thumbnailPath = $request->file('thumbnail')
            ->store('products', 'public');

        $product = Product::create([
            'name'             => $request->name,
            'slug'             => Str::slug($request->name),
            'category_id'      => $request->category_id,
            'brand_id'         => $request->brand_id,
            'description'      => $request->description,
            'content'          => $request->content,
            'price'            => $request->price,
            'sale_price'       => $request->sale_price,
            'thumbnail'        => $thumbnailPath,
            'stock'            => $request->stock,
            'dial_color'       => $request->dial_color,
            'case_material'    => $request->case_material,
            'strap_material'   => $request->strap_material,
            'movement'         => $request->movement,
            'water_resistance' => $request->water_resistance,
            'case_size'        => $request->case_size,
            'is_active'        => $request->has('is_active'),
            'is_featured'      => $request->has('is_featured'),
        ]);

        // Upload nhiều ảnh gallery
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $brands     = Brand::where('is_active', true)->get();
        $images     = $product->images()->orderBy('sort_order')->get();

        return view('admin.products.edit',
            compact('product', 'categories', 'brands', 'images'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id'    => 'required|exists:brands,id',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0|lt:price',
            'stock'       => 'required|integer|min:0',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'images.*'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $data = [
            'name'             => $request->name,
            'slug'             => Str::slug($request->name),
            'category_id'      => $request->category_id,
            'brand_id'         => $request->brand_id,
            'description'      => $request->description,
            'content'          => $request->content,
            'price'            => $request->price,
            'sale_price'       => $request->sale_price,
            'stock'            => $request->stock,
            'dial_color'       => $request->dial_color,
            'case_material'    => $request->case_material,
            'strap_material'   => $request->strap_material,
            'movement'         => $request->movement,
            'water_resistance' => $request->water_resistance,
            'case_size'        => $request->case_size,
            'is_active'        => $request->has('is_active'),
            'is_featured'      => $request->has('is_featured'),
        ];

        // Đổi ảnh đại diện nếu có upload mới
        if ($request->hasFile('thumbnail')) {
            \Storage::disk('public')->delete($product->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('products', 'public');
        }

        $product->update($data);

        // Thêm ảnh gallery mới
        if ($request->hasFile('images')) {
            $lastOrder = $product->images()->max('sort_order') ?? 0;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => $path,
                    'sort_order' => $lastOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product)
    {
        // Xoá ảnh đại diện
        \Storage::disk('public')->delete($product->thumbnail);

        // Xoá tất cả ảnh gallery
        foreach ($product->images as $image) {
            \Storage::disk('public')->delete($image->image);
        }

        $product->delete();

        return back()->with('success', 'Xoá sản phẩm thành công!');
    }

    // Xoá 1 ảnh gallery (gọi bằng AJAX)
    public function deleteImage(Request $request)
    {
        $image = ProductImage::findOrFail($request->image_id);
        \Storage::disk('public')->delete($image->image);
        $image->delete();

        return response()->json(['success' => true]);
    }
}