<?php
namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Comment;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('user')
            ->where('is_active', true)
            ->latest()
            ->paginate(9);

        return view('news.index', compact('news'));
    }

    public function show($slug)
    {
        $news = News::with([
            'user',
            'comments' => fn($q) => $q->where('is_approved', true)
                                       ->with('user')->latest()
        ])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

        // Tin tức liên quan
        $related = News::where('id', '!=', $news->id)
            ->where('is_active', true)
            ->latest()
            ->take(3)
            ->get();

        return view('news.show', compact('news', 'related'));
    }

    public function comment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ], [
            'content.required' => 'Vui lòng nhập nội dung bình luận',
        ]);

        Comment::create([
            'news_id'     => $id,
            'user_id'     => auth()->id(),
            'content'     => $request->content,
            'is_approved' => false, // Cần admin duyệt
        ]);

        return back()->with('success',
            'Bình luận đã gửi! Đang chờ admin duyệt.');
    }
}