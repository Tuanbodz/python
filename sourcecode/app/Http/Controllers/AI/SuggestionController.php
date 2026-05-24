<?php
namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\AiLog;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;

class SuggestionController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Lịch sử mua hàng
        $purchasedProducts = Order::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->with('items.product.category')
            ->latest()
            ->take(5)
            ->get()
            ->flatMap(fn($order) => $order->items)
            ->map(fn($item) => $item->product)
            ->filter()
            ->unique('id');

        // Chưa có lịch sử → gợi ý sản phẩm nổi bật
        if ($purchasedProducts->isEmpty()) {
            $suggestions = Product::with(['category', 'brand'])
                ->where('is_active', true)
                ->where('is_featured', true)
                ->inRandomOrder()
                ->take(4)
                ->get();

            return view('products.suggestions', compact('suggestions'));
        }

        // Dùng AI gợi ý
        $purchasedInfo = $purchasedProducts->map(fn($p) => [
            'name'     => $p->name,
            'category' => $p->category->name ?? '',
            'brand'    => $p->brand->name ?? '',
            'price'    => $p->price,
        ])->values()->toArray();

        $purchasedIds = $purchasedProducts->pluck('id')->toArray();

        $availableProducts = Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->whereNotIn('id', $purchasedIds)
            ->take(20)
            ->get();

        $availableInfo = $availableProducts->map(fn($p) => [
            'id'       => $p->id,
            'name'     => $p->name,
            'category' => $p->category->name ?? '',
            'brand'    => $p->brand->name ?? '',
            'price'    => $p->price,
        ])->values()->toArray();

        try {
            $prompt = "Dựa vào lịch sử mua hàng, hãy gợi ý 4 sản phẩm phù hợp nhất.
Chỉ trả về JSON array gồm 4 id, không thêm gì khác. Ví dụ: [1,5,12,8]

Lịch sử mua: " . json_encode($purchasedInfo, JSON_UNESCAPED_UNICODE) . "

Sản phẩm có sẵn: " . json_encode($availableInfo, JSON_UNESCAPED_UNICODE);

            $response     = Gemini::generativeModel('gemini-flash-latest')->generateContent($prompt);
            $content      = preg_replace('/```json|```/', '', $response->text());
            $suggestedIds = json_decode(trim($content), true);

            AiLog::create([
                'user_id'       => $user->id,
                'feature'       => 'suggestion',
                'input_tokens'  => 0,
                'output_tokens' => 0,
                'cost'          => 0,
            ]);

            if (is_array($suggestedIds) && count($suggestedIds) > 0) {
                $suggestions = Product::with(['category', 'brand'])
                    ->whereIn('id', $suggestedIds)
                    ->get()
                    ->sortBy(fn($p) => array_search($p->id, $suggestedIds))
                    ->values();
            } else {
                $suggestions = $availableProducts->take(4);
            }

        } catch (\Exception $e) {
            \Log::error('Suggestion error: ' . $e->getMessage());
            $suggestions = Product::with(['category', 'brand'])
                ->where('is_active', true)
                ->where('is_featured', true)
                ->take(4)
                ->get();
        }

        return view('products.suggestions', compact('suggestions'));
    }
}