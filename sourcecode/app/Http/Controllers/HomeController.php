<?php
namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class HomeController extends Controller
{
    public function index()
    {
        // Banner slider
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Sản phẩm nổi bật
        $featuredProducts = Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->take(8)
            ->get();

        // Sản phẩm mới nhất
        $newProducts = Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        // Sản phẩm bán chạy
        $bestSellers = Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->orderBy('sold', 'desc')
            ->take(4)
            ->get();

        // Danh mục + thương hiệu cho hiển thị
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $brands = Brand::where('is_active', true)->take(6)->get();

        return view('home.index', compact(
            'banners', 'featuredProducts', 'newProducts',
            'bestSellers', 'categories', 'brands'
        ));
    }
}