<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id', 'user_id',
        'content', 'is_approved'
    ];

    // Thuộc 1 bài viết
    public function news()
    {
        return $this->belongsTo(News::class);
    }

    // Thuộc 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}