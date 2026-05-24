<?php
namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\AiLog;
use App\Models\Product;
use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;

class ChatbotController extends Controller
{
    public function chat(Request $request)
{
    $request->validate(['message' => 'required|string|max:500']);
    
    $userMessage = $request->input('message');

    try {
        // Chỉ gửi 1 string đơn giản, KHÔNG dùng array history
        $prompt = "Bạn là trợ lý tư vấn bán đồng hồ. Hãy trả lời ngắn gọn, thân thiện bằng tiếng Việt.\n\nKhách hỏi: " . $userMessage;

        $response = \Gemini\Laravel\Facades\Gemini::generativeModel('gemini-flash-latest')
            ->generateContent($userMessage);

        $reply = $response->text();

        // Lưu log
        \App\Models\AiLog::create([
            'user_id'       => auth()->id(),
            'feature'       => 'chatbot',
            'input_tokens'  => 0,
            'output_tokens' => 0,
            'cost'          => 0,
        ]);

        return response()->json([
            'success' => true,
            'reply'   => $reply,
        ]);

    } catch (\Exception $e) {
        \Log::error('Chatbot error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'reply'   => 'Lỗi: ' . $e->getMessage(),
        ], 500);
    }
}
}