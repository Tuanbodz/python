<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'thumbnail',
        'summary', 'content', 'user_id', 'is_active'
    ];

    // Thuộc 1 user (người viết)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 1 bài viết có nhiều bình luận
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}