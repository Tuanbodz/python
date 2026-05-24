@extends('admin.layouts.app')
@section('title', 'Quản lý người dùng')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0"><i class="bi bi-people"></i> Danh sách người dùng</h6>
    </div>

    <div class="card-body border-bottom py-2">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm"
                   placeholder="Tên, email, SĐT..."
                   value="{{ request('search') }}" style="max-width:220px">
            <select name="role" class="form-select form-select-sm" style="max-width:130px">
                <option value="">Tất cả role</option>
                <option value="user"  {{ request('role')=='user'  ? 'selected':'' }}>User</option>
                <option value="admin" {{ request('role')=='admin' ? 'selected':'' }}>Admin</option>
            </select>
            <button type="submit" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-search"></i> Lọc
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x"></i> Xoá lọc
            </a>
        </form>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Avatar</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Đơn hàng</th>
                    <th>Role</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($user->avatar)
                            <img src="{{ asset('storage/'.$user->avatar) }}"
                                 width="40" height="40"
                                 class="rounded-circle object-fit-cover">
                        @else
                            <div class="rounded-circle bg-primary d-flex align-items-center
                                        justify-content-center text-white fw-bold"
                                 style="width:40px;height:40px">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </td>
                    <td class="fw-bold">{{ $user->name }}</td>
                    <td class="text-muted">{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '—' }}</td>
                    <td>
                        <span class="badge bg-info">{{ $user->orders_count }}</span>
                    </td>
                    <td>
                        <form action="{{ route('admin.users.update', $user) }}"
                              method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <select name="role" class="form-select form-select-sm d-inline-block"
                                    style="width:90px"
                                    onchange="this.form.submit()">
                                <option value="user"  {{ $user->role=='user'  ? 'selected':'' }}>User</option>
                                <option value="admin" {{ $user->role=='admin' ? 'selected':'' }}>Admin</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-danger">Bị khoá</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="btn btn-sm btn-info text-white">
                            <i class="bi bi-eye"></i>
                        </a>
                        <form action="{{ route('admin.users.toggle', $user) }}"
                              method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="btn btn-sm {{ $user->is_active ? 'btn-warning' : 'btn-success' }}"
                                    onclick="return confirm('{{ $user->is_active ? 'Khoá' : 'Mở khoá' }} tài khoản này?')">
                                <i class="bi bi-{{ $user->is_active ? 'lock' : 'unlock' }}"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        Không tìm thấy người dùng nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0">
        {{ $users->links() }}
    </div>
</div>
@endsection