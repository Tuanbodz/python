<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'        => 'required|string|max:50|unique:coupons,code',
            'type'        => 'required|in:percent,fixed',
            'value'       => 'required|numeric|min:1',
            'min_order'   => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at'  => 'nullable|date|after:today',
        ], [
            'code.required'   => 'Vui lòng nhập mã giảm giá',
            'code.unique'     => 'Mã này đã tồn tại',
            'value.required'  => 'Vui lòng nhập giá trị giảm',
            'expires_at.after' => 'Ngày hết hạn phải sau hôm nay',
        ]);

        Coupon::create([
            'code'        => strtoupper($request->code),
            'type'        => $request->type,
            'value'       => $request->value,
            'min_order'   => $request->min_order ?? 0,
            'usage_limit' => $request->usage_limit,
            'expires_at'  => $request->expires_at,
            'is_active'   => $request->has('is_active'),
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Thêm mã giảm giá thành công!');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code'        => 'required|string|max:50|unique:coupons,code,'.$coupon->id,
            'type'        => 'required|in:percent,fixed',
            'value'       => 'required|numeric|min:1',
            'min_order'   => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at'  => 'nullable|date',
        ]);

        $coupon->update([
            'code'        => strtoupper($request->code),
            'type'        => $request->type,
            'value'       => $request->value,
            'min_order'   => $request->min_order ?? 0,
            'usage_limit' => $request->usage_limit,
            'expires_at'  => $request->expires_at,
            'is_active'   => $request->has('is_active'),
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Cập nhật mã giảm giá thành công!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Xoá mã giảm giá thành công!');
    }
}