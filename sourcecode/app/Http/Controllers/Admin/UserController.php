<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('orders')->latest();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
                  ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $orders = $user->orders()->latest()->paginate(10);
        return view('admin.users.show', compact('user', 'orders'));
    }

    // Khoá / Mở khoá tài khoản
    public function toggle(User $user)
    {
        // Không cho khoá chính tài khoản admin đang đăng nhập
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Không thể khoá tài khoản đang đăng nhập!');
        }

        $user->update(['is_active' => !$user->is_active]);

        $msg = $user->is_active ? 'Đã mở khoá tài khoản!' : 'Đã khoá tài khoản!';

        return back()->with('success', $msg);
    }

    // Đổi role admin/user
    public function update(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Không thể thay đổi quyền của chính mình!');
        }

        $request->validate([
            'role' => 'required|in:admin,user',
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Cập nhật quyền thành công!');
    }

    public function create() { return back(); }
    public function store()  { return back(); }
    public function edit(User $user)
    {
        return view('admin.users.show', compact('user'));
    }
    public function destroy(User $user) { return back(); }
}