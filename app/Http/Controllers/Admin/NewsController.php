<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with(['user', 'comments'])
            ->withCount('comments')
            ->latest()
            ->paginate(10);

        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ], [
            'title.required'   => 'Vui lòng nhập tiêu đề',
            'content.required' => 'Vui lòng nhập nội dung',
        ]);

        $data = [
            'title'     => $request->title,
            'slug'      => Str::slug($request->title) . '-' . time(),
            'summary'   => $request->summary,
            'content'   => $request->content,
            'user_id'   => auth()->id(),
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('news', 'public');
        }

        News::create($data);

        return redirect()->route('admin.news.index')
            ->with('success', 'Thêm bài viết thành công!');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $data = [
            'title'     => $request->title,
            'slug'      => Str::slug($request->title) . '-' . $news->id,
            'summary'   => $request->summary,
            'content'   => $request->content,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('thumbnail')) {
            if ($news->thumbnail) {
                \Storage::disk('public')->delete($news->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('news', 'public');
        }

        $news->update($data);

        return redirect()->route('admin.news.index')
            ->with('success', 'Cập nhật bài viết thành công!');
    }

    public function destroy(News $news)
    {
        if ($news->thumbnail) {
            \Storage::disk('public')->delete($news->thumbnail);
        }
        $news->delete();
        return back()->with('success', 'Xoá bài viết thành công!');
    }

    // Duyệt bình luận
    public function approveComment(Comment $comment)
    {
        $comment->update(['is_approved' => !$comment->is_approved]);
        $msg = $comment->is_approved ? 'Đã duyệt!' : 'Đã ẩn!';
        return back()->with('success', $msg);
    }

    // Xoá bình luận
    public function deleteComment(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Đã xoá bình luận!');
    }
}