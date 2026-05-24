@extends('admin.layouts.app')
@section('title', 'AI Dashboard')

@section('content')

{{-- Thống kê tổng --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card"
             style="background:linear-gradient(135deg,#667eea,#764ba2)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-white-50 mb-1">Tổng chi phí</div>
                    <div class="stat-number">${{ number_format($totalCost, 4) }}</div>
                </div>
                <i class="bi bi-currency-dollar stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card"
             style="background:linear-gradient(135deg,#f093fb,#f5576c)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-white-50 mb-1">Tổng tokens</div>
                    <div class="stat-number">{{ number_format($totalTokens) }}</div>
                </div>
                <i class="bi bi-cpu stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card"
             style="background:linear-gradient(135deg,#4facfe,#00f2fe)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-white-50 mb-1">Tổng lượt gọi</div>
                    <div class="stat-number">{{ number_format($recentLogs->count()) }}</div>
                </div>
                <i class="bi bi-robot stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card"
             style="background:linear-gradient(135deg,#43e97b,#38f9d7)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-white-50 mb-1">Features</div>
                    <div class="stat-number">{{ $byFeature->count() }}</div>
                </div>
                <i class="bi bi-grid stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">

    {{-- Biểu đồ chi phí --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">
                    <i class="bi bi-bar-chart"></i> Chi phí AI 7 ngày gần nhất
                </h6>
            </div>
            <div class="card-body">
                <canvas id="costChart" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- Thống kê theo feature --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">
                    <i class="bi bi-pie-chart"></i> Phân bổ theo tính năng
                </h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tính năng</th>
                            <th>Lượt</th>
                            <th>Chi phí</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($byFeature as $feature)
                        <tr>
                            <td>
                                @php
                                    $icons = [
                                        'chatbot'         => 'chat-dots',
                                        'suggestion'      => 'lightbulb',
                                        'sentiment'       => 'emoji-smile',
                                        'sentiment_batch' => 'collection',
                                    ];
                                @endphp
                                <i class="bi bi-{{ $icons[$feature->feature] ?? 'robot' }}"></i>
                                {{ $feature->feature }}
                            </td>
                            <td>{{ $feature->count }}</td>
                            <td class="text-danger">
                                ${{ number_format($feature->total_cost, 4) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Actions --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">
                    <i class="bi bi-lightning"></i> Hành động nhanh
                </h6>
            </div>
            <div class="card-body d-flex gap-3 flex-wrap">
                {{-- Phân tích hàng loạt --}}
                <form action="{{ route('admin.ai.admin.sentiment.batch') }}"
                      method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary"
                            onclick="return confirm('Phân tích tất cả đánh giá chưa có sentiment?')">
                        <i class="bi bi-robot"></i>
                        Phân tích sentiment hàng loạt
                    </button>
                </form>

                <a href="{{ route('admin.reviews.index') }}"
                   class="btn btn-outline-primary">
                    <i class="bi bi-star"></i> Quản lý đánh giá
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Log gần nhất --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0">
            <i class="bi bi-clock-history"></i> Log AI gần nhất
        </h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 small">
            <thead class="table-light">
                <tr>
                    <th>Thời gian</th>
                    <th>Người dùng</th>
                    <th>Tính năng</th>
                    <th>Input tokens</th>
                    <th>Output tokens</th>
                    <th>Chi phí</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLogs as $log)
                <tr>
                    <td class="text-muted">
                        {{ $log->created_at->format('d/m H:i') }}
                    </td>
                    <td>{{ $log->user->name ?? 'Khách' }}</td>
                    <td>
                        <span class="badge bg-info">{{ $log->feature }}</span>
                    </td>
                    <td>{{ number_format($log->input_tokens) }}</td>
                    <td>{{ number_format($log->output_tokens) }}</td>
                    <td class="text-danger">
                        ${{ number_format($log->cost, 6) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        Chưa có log nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
const costCtx = document.getElementById('costChart').getContext('2d');
new Chart(costCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($costChart->pluck('date')) !!},
        datasets: [{
            label: 'Chi phí ($)',
            data:  {!! json_encode($costChart->pluck('cost')) !!},
            borderColor: 'rgba(102, 126, 234, 1)',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endsection