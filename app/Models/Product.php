<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'category_id', 'brand_id',
        'description', 'content', 'price', 'sale_price',
        'thumbnail', 'stock', 'sold',
        'dial_color', 'case_material', 'strap_material',
        'movement', 'water_resistance', 'case_size',
        'is_active', 'is_featured'
    ];

    // Thuộc 1 danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Thuộc 1 thương hiệu
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // 1 sản phẩm có nhiều ảnh
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // 1 sản phẩm có nhiều đánh giá
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Lấy giá hiển thị (nếu có giá sale thì lấy giá sale)
    public function getDisplayPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    // Tính trung bình rating
    public function getAvgRatingAttribute()
    {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?? 0;
    }
}