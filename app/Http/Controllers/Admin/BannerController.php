<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:3048',
            'link'  => 'nullable|string|max:255',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề banner',
            'image.required' => 'Vui lòng chọn ảnh banner',
        ]);

        Banner::create([
            'title'      => $request->title,
            'image'      => $request->file('image')->store('banners', 'public'),
            'link'       => $request->link,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->has('is_active'),
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Thêm banner thành công!');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
        ]);

        $data = [
            'title'      => $request->title,
            'link'       => $request->link,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            \Storage::disk('public')->delete($banner->image);
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Cập nhật banner thành công!');
    }

    public function destroy(Banner $banner)
    {
        \Storage::disk('public')->delete($banner->image);
        $banner->delete();
        return back()->with('success', 'Xoá banner thành công!');
    }
}