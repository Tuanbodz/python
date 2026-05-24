<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'user_id', 'order_id',
        'rating', 'comment',
        'ai_sentiment', 'ai_summary', 'is_approved'
    ];

    // Thuộc 1 sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Thuộc 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Thuộc 1 đơn hàng
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}