<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    // Trang checkout
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng đang trống!');
        }

        $coupon   = session('coupon');
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discount = 0;
        if ($coupon) {
            $discount = $coupon['type'] === 'percent'
                ? $subtotal * $coupon['value'] / 100
                : $coupon['value'];
        }

        $total = $subtotal - $discount;
        $user  = auth()->user();

        return view('checkout.index',
            compact('cart', 'coupon', 'subtotal', 'discount', 'total', 'user'));
    }

    // Xử lý đặt hàng
    public function store(Request $request)
    {
        $request->validate([
            'receiver_name'    => 'required|string|max:255',
            'receiver_phone'   => 'required|string|max:20',
            'receiver_address' => 'required|string|max:500',
            'receiver_city'    => 'required|string|max:100',
            'payment_method'   => 'required|in:cod,vnpay',
        ], [
            'receiver_name.required'    => 'Vui lòng nhập họ tên người nhận',
            'receiver_phone.required'   => 'Vui lòng nhập số điện thoại',
            'receiver_address.required' => 'Vui lòng nhập địa chỉ giao hàng',
            'receiver_city.required'    => 'Vui lòng chọn tỉnh/thành phố',
            'payment_method.required'   => 'Vui lòng chọn phương thức thanh toán',
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng đang trống!');
        }

        // Tính lại tiền
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $coupon   = session('coupon');
        $discount = 0;
        $couponId = null;

        if ($coupon) {
            $couponModel = Coupon::find($coupon['id']);
            if ($couponModel && $couponModel->isValid()) {
                $discount = $coupon['type'] === 'percent'
                    ? $subtotal * $coupon['value'] / 100
                    : $coupon['value'];
                $couponId = $coupon['id'];
                // Tăng số lần đã dùng
                $couponModel->increment('used_count');
            }
        }

        $total = $subtotal - $discount;

        // Tạo mã đơn hàng
        $orderCode = 'DH' . date('Ymd') . strtoupper(Str::random(4));

        // Tạo đơn hàng
        $order = Order::create([
            'order_code'       => $orderCode,
            'user_id'          => auth()->id(),
            'receiver_name'    => $request->receiver_name,
            'receiver_phone'   => $request->receiver_phone,
            'receiver_address' => $request->receiver_address,
            'receiver_city'    => $request->receiver_city,
            'subtotal'         => $subtotal,
            'discount'         => $discount,
            'total'            => $total,
            'coupon_id'        => $couponId,
            'payment_method'   => $request->payment_method,
            'payment_status'   => 'pending',
            'status'           => 'pending',
            'note'             => $request->note,
        ]);

        // Tạo order items + trừ tồn kho
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $item['product_id'],
                'product_name' => $item['name'],
                'price'        => $item['price'],
                'quantity'     => $item['quantity'],
                'total'        => $item['price'] * $item['quantity'],
            ]);

            // Trừ tồn kho
            Product::where('id', $item['product_id'])
                ->decrement('stock', $item['quantity']);
        }

        // Xoá giỏ hàng
        session()->forget(['cart', 'coupon']);

        // Nếu chọn VNPay → chuyển sang trang thanh toán
        if ($request->payment_method === 'vnpay') {
            return $this->redirectToVnpay($order);
        }

        return redirect()->route('checkout.success', $order->order_code)
            ->with('success', 'Đặt hàng thành công!');
    }

    // Trang thành công
    public function success($orderCode)
    {
        $order = Order::with(['items.product', 'user'])
            ->where('order_code', $orderCode)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }

    // Chuyển hướng VNPay
    private function redirectToVnpay(Order $order)
    {
        // TODO: Tích hợp VNPay ở giai đoạn sau
        // Tạm thời redirect về trang thành công
        return redirect()->route('checkout.success', $order->order_code)
            ->with('success', 'Đặt hàng thành công! (VNPay sẽ tích hợp sau)');
    }

    // VNPay callback
    public function vnpayReturn(Request $request)
    {
        // TODO: Xử lý callback VNPay
        return redirect()->route('home');
    }
}