<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Danh sách đơn hàng của user
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    // Chi tiết đơn hàng
    public function show($orderCode)
    {
        $order = Order::with(['items.product', 'coupon'])
            ->where('order_code', $orderCode)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    // Huỷ đơn hàng
    public function cancel($orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Chỉ huỷ được khi đang ở trạng thái pending
        if ($order->status !== 'pending') {
            return back()->with('error',
                'Không thể huỷ đơn hàng ở trạng thái "' . $order->status_label . '"!');
        }

        $order->update(['status' => 'cancelled']);

        // Hoàn lại tồn kho
        foreach ($order->items as $item) {
            $item->product?->increment('stock', $item->quantity);
        }

        return back()->with('success', 'Đã huỷ đơn hàng thành công!');
    }
}