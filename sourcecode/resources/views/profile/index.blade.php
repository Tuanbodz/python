@extends('layouts.app')
@section('title', 'Tài khoản của tôi')

@section('content')
<div class="container my-4">

    <h4 class="fw-bold mb-4">
        <i class="bi bi-person-circle"></i> Tài khoản của tôi
    </h4>

    <div class="row g-4">

        {{-- Cột trái: Avatar + Menu --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3 mb-3">
                {{-- Avatar --}}
                @if($user->avatar)
                    <img src="{{ asset('storage/'.$user->avatar) }}"
                         width="90" height="90"
                         class="rounded-circle object-fit-cover mx-auto mb-2">
                @else
                    <div class="rounded-circle bg-primary d-flex align-items-center
                                justify-content-center text-white fw-bold mx-auto mb-2"
                         style="width:90px;height:90px;font-size:36px">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <h6 class="mb-0">{{ $user->name }}</h6>
                <small class="text-muted">{{ $user->email }}</small>
            </div>

            {{-- Menu --}}
            <div class="list-group border-0 shadow-sm">
                <a href="{{ route('profile.index') }}"
                   class="list-group-item list-group-item-action active border-0">
                    <i class="bi bi-person me-2"></i> Thông tin cá nhân
                </a>
                <a href="{{ route('orders.index') }}"
                   class="list-group-item list-group-item-action border-0">
                    <i class="bi bi-bag me-2"></i> Đơn hàng của tôi
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="list-group-item list-group-item-action
                                   border-0 text-danger w-100 text-start">
                        <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                    </button>
                </form>
            </div>
        </div>

        {{-- Cột phải: Form cập nhật --}}
        <div class="col-md-9">

            {{-- Form thông tin --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">
                        <i class="bi bi-pencil"></i> Cập nhật thông tin
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf @method('PATCH')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Họ tên <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control"
                                       value="{{ $user->email }}" disabled>
                                <div class="form-text">Email không thể thay đổi</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Số điện thoại</label>
                                <input type="text" name="phone"
                                       class="form-control"
                                       value="{{ old('phone', $user->phone) }}"
                                       placeholder="VD: 0901234567">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ảnh đại diện</label>
                                @if($user->avatar)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/'.$user->avatar) }}"
                                             width="50" height="50"
                                             class="rounded-circle object-fit-cover">
                                    </div>
                                @endif
                                <input type="file" name="avatar"
                                       class="form-control @error('avatar') is-invalid @enderror"
                                       accept="image/*"
                                       onchange="previewAvatar(this)">
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <img id="avatar-preview" src="#"
                                     class="mt-2 rounded-circle d-none"
                                     style="width:60px;height:60px;object-fit:cover">
                            </div>
                        </div>

                        {{-- Đổi mật khẩu --}}
                        <hr class="my-3">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-lock"></i> Đổi mật khẩu
                            <small class="text-muted fw-normal">(để trống nếu không đổi)</small>
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Mật khẩu mới</label>
                                <input type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Tối thiểu 6 ký tự">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Xác nhận mật khẩu mới</label>
                                <input type="password" name="password_confirmation"
                                       class="form-control"
                                       placeholder="Nhập lại mật khẩu mới">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="bi bi-check-lg"></i> Lưu thay đổi
                        </button>
                    </form>
                </div>
            </div>

            {{-- Đơn hàng gần đây --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between">
                    <h6 class="mb-0">
                        <i class="bi bi-clock-history"></i> Đơn hàng gần đây
                    </h6>
                    <a href="{{ route('orders.index') }}"
                       class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            @php
                                $badges = [
                                    'pending'   => 'warning',
                                    'confirmed' => 'info',
                                    'shipping'  => 'primary',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger',
                                ];
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('orders.show', $order->order_code) }}"
                                       class="text-decoration-none fw-bold">
                                        {{ $order->order_code }}
                                    </a>
                                </td>
                                <td class="text-muted small">
                                    {{ $order->created_at->format('d/m/Y') }}
                                </td>
                                <td class="fw-bold text-danger">
                                    {{ number_format($order->total) }}đ
                                </td>
                                <td>
                                    <span class="badge bg-{{ $badges[$order->status] ?? 'secondary' }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">
                                    Chưa có đơn hàng nào
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function previewAvatar(input) {
    const preview = document.getElementById('avatar-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection