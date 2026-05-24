<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Xem giỏ hàng
    public function index()
    {
        $cart    = session('cart', []);
        $coupon  = session('coupon');
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discount = 0;
        if ($coupon) {
            if ($coupon['type'] === 'percent') {
                $discount = $subtotal * $coupon['value'] / 100;
            } else {
                $discount = $coupon['value'];
            }
        }

        $total = $subtotal - $discount;

        return view('cart.index', compact('cart', 'coupon', 'subtotal', 'discount', 'total'));
    }

    // Thêm vào giỏ
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Số lượng trong kho không đủ!');
        }

        $cart = session('cart', []);
        $id   = $product->id;

        if (isset($cart[$id])) {
            // Đã có → cộng thêm số lượng
            $newQty = $cart[$id]['quantity'] + $request->quantity;

            if ($newQty > $product->stock) {
                return back()->with('error', 'Vượt quá số lượng tồn kho!');
            }

            $cart[$id]['quantity'] = $newQty;
        } else {
            // Chưa có → thêm mới
            $cart[$id] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => $product->sale_price ?? $product->price,
                'thumbnail'  => $product->thumbnail,
                'stock'      => $product->stock,
                'quantity'   => $request->quantity,
            ];
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Đã thêm "' . $product->name . '" vào giỏ hàng!');
    }

    // Cập nhật số lượng
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session('cart', []);

        if (isset($cart[$id])) {
            if ($request->quantity > $cart[$id]['stock']) {
                return back()->with('error', 'Vượt quá số lượng tồn kho!');
            }
            $cart[$id]['quantity'] = $request->quantity;
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Đã cập nhật giỏ hàng!');
    }

    // Xoá sản phẩm khỏi giỏ
    public function remove($id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Đã xoá sản phẩm khỏi giỏ hàng!');
    }

    // Áp dụng mã giảm giá
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Giỏ hàng đang trống!');
        }

        // Tính subtotal
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $coupon = Coupon::where('code', strtoupper($request->coupon_code))
            ->first();

        if (!$coupon) {
            return back()->with('error', 'Mã giảm giá không tồn tại!');
        }

        if (!$coupon->isValid()) {
            return back()->with('error', 'Mã giảm giá đã hết hạn hoặc không còn hiệu lực!');
        }

        if ($subtotal < $coupon->min_order) {
            return back()->with('error',
                'Đơn hàng tối thiểu ' . number_format($coupon->min_order) . 'đ để dùng mã này!');
        }

        // Lưu coupon vào session
        session(['coupon' => [
            'id'    => $coupon->id,
            'code'  => $coupon->code,
            'type'  => $coupon->type,
            'value' => $coupon->value,
        ]]);

        return back()->with('success', 'Áp dụng mã giảm giá thành công!');
    }
}