<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Đổi redirect mặc định sau login từ /dashboard về /
        \Illuminate\Support\Facades\Route::bind('dashboard', function () {
            return redirect()->route('home');
        });
    }
}