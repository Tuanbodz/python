<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // Danh sách danh mục
    public function index()
    {
        $categories = Category::with('parent')
            ->orderBy('sort_order')
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    // Form thêm mới
    public function create()
    {
        // Chỉ lấy danh mục cấp 1 làm danh mục cha
        $parents = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parents'));
    }

    // Lưu danh mục mới
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'sort_order' => 'nullable|integer',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục',
            'image.image'   => 'File phải là hình ảnh',
            'image.max'     => 'Ảnh không được vượt quá 2MB',
        ]);

        $data = [
            'name'       => $request->name,
            'slug'       => Str::slug($request->name),
            'parent_id'  => $request->parent_id,
            'is_active'  => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ];

        // Upload ảnh nếu có
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Thêm danh mục thành công!');
    }

    // Form sửa
    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id) // Không cho chọn chính nó làm cha
            ->get();

        return view('admin.categories.edit', compact('category', 'parents'));
    }

    // Cập nhật
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'sort_order' => 'nullable|integer',
        ]);

        $data = [
            'name'       => $request->name,
            'slug'       => Str::slug($request->name),
            'parent_id'  => $request->parent_id,
            'is_active'  => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ];

        if ($request->hasFile('image')) {
            // Xoá ảnh cũ nếu có
            if ($category->image) {
                \Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Cập nhật danh mục thành công!');
    }

    // Xoá
    public function destroy(Category $category)
    {
        // Kiểm tra có sản phẩm không
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Không thể xoá! Danh mục đang có sản phẩm.');
        }

        if ($category->image) {
            \Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return back()->with('success', 'Xoá danh mục thành công!');
    }
}