<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'role', 'avatar', 'phone', 'is_active'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // 1 user có nhiều đơn hàng
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // 1 user có nhiều đánh giá
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // 1 user có nhiều bình luận
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Kiểm tra có phải admin không
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}