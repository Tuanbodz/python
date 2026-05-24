<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code', 'user_id',
        'receiver_name', 'receiver_phone',
        'receiver_address', 'receiver_city',
        'subtotal', 'discount', 'total',
        'coupon_id', 'payment_method',
        'payment_status', 'status',
        'note', 'vnpay_transaction_id'
    ];

    // Thuộc 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 1 đơn hàng có nhiều sản phẩm
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Thuộc 1 coupon
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    // 1 đơn hàng có nhiều đánh giá
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Lấy tên trạng thái tiếng Việt
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'shipping'  => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã huỷ',
            default     => 'Không xác định'
        };
    }
}