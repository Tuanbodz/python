@extends('admin.layouts.app')
@section('title', 'Quản lý mã giảm giá')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-ticket-perforated"></i> Mã giảm giá</h6>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Thêm mã
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Mã</th>
                    <th>Loại</th>
                    <th>Giá trị</th>
                    <th>Đơn tối thiểu</th>
                    <th>Đã dùng</th>
                    <th>Hết hạn</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td>
                        <span class="badge bg-dark fs-6">{{ $coupon->code }}</span>
                    </td>
                    <td>
                        @if($coupon->type == 'percent')
                            <span class="badge bg-info">Phần trăm</span>
                        @else
                            <span class="badge bg-warning text-dark">Tiền mặt</span>
                        @endif
                    </td>
                    <td class="fw-bold text-danger">
                        @if($coupon->type == 'percent')
                            {{ $coupon->value }}%
                        @else
                            {{ number_format($coupon->value) }}đ
                        @endif
                    </td>
                    <td>{{ number_format($coupon->min_order) }}đ</td>
                    <td>
                        {{ $coupon->used_count }}
                        @if($coupon->usage_limit)
                            / {{ $coupon->usage_limit }}
                        @else
                            / ∞
                        @endif
                    </td>
                    <td>
                        @if($coupon->expires_at)
                            <span class="{{ $coupon->expires_at->isPast() ? 'text-danger' : 'text-muted' }}">
                                {{ $coupon->expires_at->format('d/m/Y') }}
                            </span>
                        @else
                            <span class="text-muted">Không giới hạn</span>
                        @endif
                    </td>
                    <td>
                        @if($coupon->isValid())
                            <span class="badge bg-success">Hợp lệ</span>
                        @else
                            <span class="badge bg-danger">Hết hạn/Tắt</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.coupons.edit', $coupon) }}"
                           class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Xác nhận xoá mã này?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">Chưa có mã giảm giá</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0">{{ $coupons->links() }}</div>
</div>
@endsection