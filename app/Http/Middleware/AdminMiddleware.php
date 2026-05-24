<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Chưa đăng nhập → chuyển về trang login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Đã đăng nhập nhưng không phải admin → về trang chủ
        if (!auth()->user()->isAdmin()) {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập!');
        }

        return $next($request);
    }
}