<?php
namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\AiLog;
use App\Models\Review;
use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;

class SentimentController extends Controller
{
    public function analyze(Review $review)
    {
        try {
            $prompt = "Phân tích cảm xúc đánh giá sản phẩm sau và trả về JSON (chỉ JSON, không thêm gì khác):
{\"sentiment\":\"positive\" hoặc \"neutral\" hoặc \"negative\",\"summary\":\"tóm tắt 1 câu tiếng Việt\"}

Đánh giá: {$review->comment}
Số sao: {$review->rating}/5";

            $response = Gemini::generativeModel('gemini-flash-latest')->generateContent($prompt);
            $content  = $response->text();

            // Xoá markdown code block nếu có
            $content = preg_replace('/```json|```/', '', $content);
            $data    = json_decode(trim($content), true);

            if ($data && isset($data['sentiment'])) {
                $review->update([
                    'ai_sentiment' => $data['sentiment'],
                    'ai_summary'   => $data['summary'] ?? null,
                ]);

                AiLog::create([
                    'user_id'       => auth()->id(),
                    'feature'       => 'sentiment',
                    'input_tokens'  => str_word_count($review->comment ?? ''),
                    'output_tokens' => str_word_count($content),
                    'cost'          => 0,
                ]);
            }

            return back()->with('success', 'Phân tích cảm xúc thành công!');

        } catch (\Exception $e) {
            \Log::error('Sentiment error: ' . $e->getMessage());
            return back()->with('error', 'Lỗi phân tích: ' . $e->getMessage());
        }
    }

    public function analyzeBatch()
    {
        $reviews = Review::whereNull('ai_sentiment')
            ->whereNotNull('comment')
            ->take(20)
            ->get();

        if ($reviews->isEmpty()) {
            return back()->with('info', 'Không có đánh giá nào cần phân tích!');
        }

        $successCount = 0;

        foreach ($reviews as $review) {
            try {
                $prompt = "Phân tích cảm xúc và trả về JSON (chỉ JSON):
{\"sentiment\":\"positive/neutral/negative\",\"summary\":\"tóm tắt 1 câu tiếng Việt\"}
Đánh giá: {$review->comment}
Sao: {$review->rating}/5";

                $response = Gemini::generativeModel('gemini-flash-latest')->generateContent($prompt);
                $content  = preg_replace('/```json|```/', '', $response->text());
                $data     = json_decode(trim($content), true);

                if ($data && isset($data['sentiment'])) {
                    $review->update([
                        'ai_sentiment' => $data['sentiment'],
                        'ai_summary'   => $data['summary'] ?? null,
                    ]);

                    AiLog::create([
                        'user_id'       => auth()->id(),
                        'feature'       => 'sentiment_batch',
                        'input_tokens'  => str_word_count($review->comment ?? ''),
                        'output_tokens' => str_word_count($content),
                        'cost'          => 0,
                    ]);

                    $successCount++;
                }

                usleep(500000); // 0.5 giây tránh rate limit

            } catch (\Exception $e) {
                \Log::error('Sentiment batch error: ' . $e->getMessage());
                continue;
            }
        }

        return back()->with('success',
            "Đã phân tích {$successCount}/{$reviews->count()} đánh giá!");
    }
}