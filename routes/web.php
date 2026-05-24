<?php
use Illuminate\Support\Facades\Route;

// ============================================
// ROUTES NGƯỜI DÙNG (User)
// ============================================

// Trang chủ
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Dashboard redirect (Breeze mặc định)
Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->middleware('auth')->name('dashboard');

// Sản phẩm
Route::get('/san-pham', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/san-pham/{slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');

// Giỏ hàng
Route::get('/gio-hang', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/gio-hang/them', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::patch('/gio-hang/cap-nhat/{id}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::delete('/gio-hang/xoa/{id}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::post('/gio-hang/ap-dung-coupon', [App\Http\Controllers\CartController::class, 'applyCoupon'])->name('cart.coupon');

// Checkout & Đơn hàng (cần đăng nhập)
Route::middleware('auth')->group(function () {
    Route::get('/dat-hang', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/dat-hang', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/dat-hang/thanh-cong/{orderCode}', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/vnpay/return', [App\Http\Controllers\CheckoutController::class, 'vnpayReturn'])->name('vnpay.return');

    Route::get('/don-hang', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/don-hang/{orderCode}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/don-hang/{orderCode}/huy', [App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('/tai-khoan', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/tai-khoan', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// Tin tức
Route::get('/tin-tuc', [App\Http\Controllers\NewsController::class, 'index'])->name('news.index');
Route::get('/tin-tuc/{slug}', [App\Http\Controllers\NewsController::class, 'show'])->name('news.show');
Route::post('/tin-tuc/{id}/binh-luan', [App\Http\Controllers\NewsController::class, 'comment'])->name('news.comment')->middleware('auth');

// ============================================
// ROUTES AI (User)
// ============================================
Route::prefix('ai')->name('ai.')->group(function () {
    // Chatbot
    Route::post('/chat', [App\Http\Controllers\AI\ChatbotController::class, 'chat'])->name('chat');

    // Gợi ý sản phẩm (cần đăng nhập)
    Route::middleware('auth')->group(function () {
        Route::get('/suggestions', [App\Http\Controllers\AI\SuggestionController::class, 'index'])->name('suggestions');
    });
});

// ============================================
// ROUTES ADMIN
// ============================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Danh mục
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);

    // Thương hiệu
    Route::resource('brands', App\Http\Controllers\Admin\BrandController::class);

    // Sản phẩm
    Route::delete('products/image/delete', [App\Http\Controllers\Admin\ProductController::class, 'deleteImage'])->name('products.deleteImage');
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);

    // Đơn hàng
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
    Route::patch('orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Người dùng
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::patch('users/{user}/toggle', [App\Http\Controllers\Admin\UserController::class, 'toggle'])->name('users.toggle');

    // Banner
    Route::resource('banners', App\Http\Controllers\Admin\BannerController::class);

    // Tin tức + Bình luận
    Route::resource('news', App\Http\Controllers\Admin\NewsController::class);
    Route::patch('comments/{comment}/approve', [App\Http\Controllers\Admin\NewsController::class, 'approveComment'])->name('comments.approve');
    Route::delete('comments/{comment}', [App\Http\Controllers\Admin\NewsController::class, 'deleteComment'])->name('comments.delete');

    // Coupon
    Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class);

    // Đánh giá
    Route::get('reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('reviews/{review}/approve', [App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('reviews/{review}', [App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // AI Dashboard
    Route::get('ai-dashboard', [App\Http\Controllers\Admin\AiDashboardController::class, 'index'])->name('ai.dashboard');

    // AI Sentiment
    Route::post('ai/sentiment/batch', [App\Http\Controllers\AI\SentimentController::class, 'analyzeBatch'])->name('ai.admin.sentiment.batch');
    Route::post('ai/sentiment/{review}', [App\Http\Controllers\AI\SentimentController::class, 'analyze'])->name('ai.admin.sentiment');
});

// Auth routes (Breeze)
require __DIR__.'/auth.php';