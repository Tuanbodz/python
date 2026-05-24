<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value', 'min_order',
        'usage_limit', 'used_count',
        'expires_at', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'date',
        ];
    }

    // Kiểm tra coupon còn hợp lệ không
    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        return true;
    }
}