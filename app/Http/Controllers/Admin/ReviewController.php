<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product'])->latest();

        if ($request->status === 'approved') {
            $query->where('is_approved', true);
        } elseif ($request->status === 'pending') {
            $query->where('is_approved', false);
        }

        if ($request->sentiment) {
            $query->where('ai_sentiment', $request->sentiment);
        }

        $reviews = $query->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => !$review->is_approved]);
        $msg = $review->is_approved ? 'Đã duyệt đánh giá!' : 'Đã ẩn đánh giá!';
        return back()->with('success', $msg);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Đã xoá đánh giá!');
    }
}