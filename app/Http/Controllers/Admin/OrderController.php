<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])->latest();

        // Lọc theo trạng thái
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Lọc theo phương thức thanh toán
        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        // Tìm theo mã đơn hoặc tên khách
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('order_code', 'like', '%'.$request->search.'%')
                  ->orWhere('receiver_name', 'like', '%'.$request->search.'%')
                  ->orWhere('receiver_phone', 'like', '%'.$request->search.'%');
            });
        }

        $orders = $query->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'coupon']);
        return view('admin.orders.show', compact('order'));
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,shipping,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        // Nếu đã giao thành công → cập nhật số lượng đã bán
        if ($request->status === 'delivered') {
            foreach ($order->items as $item) {
                $item->product->increment('sold', $item->quantity);
            }
        }

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    // Không cho xoá đơn hàng — chỉ huỷ
    public function destroy(Order $order)
    {
        return back()->with('error', 'Không thể xoá đơn hàng. Hãy chuyển sang trạng thái Huỷ.');
    }
}