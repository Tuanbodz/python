<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'reviews'])
            ->where('is_active', true);

        // Lọc theo danh mục
        if ($request->category) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $childIds = $category->children->pluck('id')->toArray();
                $childIds[] = $category->id;
                $query->whereIn('category_id', $childIds);
            }
        }

        // Lọc theo thương hiệu
        if ($request->brand) {
            $brand = Brand::where('slug', $request->brand)->first();
            if ($brand) $query->where('brand_id', $brand->id);
        }

        // Lọc theo khoảng giá
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Lọc sản phẩm nổi bật
        if ($request->featured) {
            $query->where('is_featured', true);
        }

        // Tìm kiếm
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        // Sắp xếp
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'best_seller':
                $query->orderBy('sold', 'desc');
                break;
            default:
                $query->latest();
        }

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::whereNull('parent_id')->where('is_active', true)->get();
        $brands     = Brand::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function show($slug)
    {
        $product = Product::with([
            'category', 'brand',
            'images' => fn($q) => $q->orderBy('sort_order'),
            'reviews' => fn($q) => $q->where('is_approved', true)
                                     ->with('user')->latest()
        ])->where('slug', $slug)->firstOrFail();

        // Sản phẩm liên quan
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }
}