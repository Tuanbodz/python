<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Đồng Hồ Online')</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background: #f8f9fa; }

        /* Navbar */
        .navbar-brand {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a2e !important;
        }
        .navbar {
            background: #fff !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .navbar .nav-link {
            color: #333 !important;
            font-weight: 500;
            padding: 8px 14px !important;
        }
        .navbar .nav-link:hover { color: #0d6efd !important; }

        /* Cart badge */
        .cart-btn {
            position: relative;
            display: inline-block;
        }
        .cart-badge {
            position: absolute;
            top: -6px; right: -8px;
            background: #dc3545;
            color: #fff;
            border-radius: 50%;
            width: 18px; height: 18px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Product card */
        .product-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            background: #fff;
        }
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12) !important;
        }
        .product-card img {
            height: 220px;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .product-card:hover img { transform: scale(1.05); }
        .product-card .badge-sale {
            position: absolute;
            top: 10px; left: 10px;
            background: #dc3545;
            color: #fff;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        /* Footer */
        footer {
            background: #1a1a2e;
            color: #adb5bd;
        }
        footer a { color: #adb5bd; text-decoration: none; }
        footer a:hover { color: #fff; }
        footer h6 { color: #fff; }

        /* Breadcrumb */
        .breadcrumb { background: none; padding: 0; }
        .breadcrumb-item a { color: #0d6efd; text-decoration: none; }
    </style>

    @yield('styles')
</head>
{{-- Chatbot Widget --}}
<div id="chatbot-widget" style="position:fixed;bottom:20px;right:20px;z-index:9999">

    {{-- Nút mở chat --}}
    <button id="chat-toggle"
            onclick="toggleChat()"
            class="btn btn-primary rounded-circle shadow-lg"
            style="width:56px;height:56px;font-size:24px">
        <i class="bi bi-chat-dots-fill" id="chat-icon"></i>
    </button>

    {{-- Cửa sổ chat --}}
    <div id="chat-window"
         class="card border-0 shadow-lg d-none"
         style="width:340px;position:absolute;bottom:65px;right:0;
                border-radius:16px;overflow:hidden">

        {{-- Header --}}
        <div class="card-header d-flex align-items-center gap-2 py-2"
             style="background:linear-gradient(135deg,#667eea,#764ba2)">
            <div class="rounded-circle bg-white d-flex align-items-center
                        justify-content-center"
                 style="width:34px;height:34px">
                <i class="bi bi-robot text-primary" style="font-size:18px"></i>
            </div>
            <div>
                <div class="text-white fw-bold small">Trợ lý tư vấn</div>
                <div class="text-white-50" style="font-size:11px">
                    <span class="rounded-circle bg-success d-inline-block me-1"
                          style="width:7px;height:7px"></span>
                    Đang online
                </div>
            </div>
            <button onclick="toggleChat()"
                    class="btn btn-sm ms-auto text-white border-0 p-0"
                    style="background:none;font-size:18px">
                <i class="bi bi-x"></i>
            </button>
        </div>

        {{-- Messages --}}
        <div id="chat-messages"
             class="card-body p-3"
             style="height:320px;overflow-y:auto;background:#f8f9fa">

            {{-- Tin nhắn chào --}}
            <div class="d-flex gap-2 mb-3">
                <div class="rounded-circle bg-primary d-flex align-items-center
                            justify-content-center text-white flex-shrink-0"
                     style="width:30px;height:30px;font-size:14px">
                    <i class="bi bi-robot"></i>
                </div>
                <div class="bg-white rounded p-2 shadow-sm small"
                     style="max-width:240px">
                    Xin chào! 👋 Tôi là trợ lý tư vấn đồng hồ. Bạn cần tôi giúp gì?
                </div>
            </div>
        </div>

        {{-- Input --}}
        <div class="card-footer bg-white border-top p-2">
            <div class="input-group">
                <input type="text"
                       id="chat-input"
                       class="form-control form-control-sm border-0 bg-light"
                       placeholder="Nhập câu hỏi..."
                       onkeypress="if(event.key==='Enter') sendMessage()">
                <button onclick="sendMessage()"
                        class="btn btn-primary btn-sm"
                        id="send-btn">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
            <div class="mt-1 d-flex gap-1 flex-wrap">
                <button onclick="quickAsk('Tư vấn đồng hồ nam dưới 2 triệu')"
                        class="btn btn-outline-secondary"
                        style="font-size:10px;padding:2px 6px;border-radius:10px">
                    Đồng hồ nam &lt;2tr
                </button>
                <button onclick="quickAsk('Sự khác nhau giữa máy Quartz và Automatic')"
                        class="btn btn-outline-secondary"
                        style="font-size:10px;padding:2px 6px;border-radius:10px">
                    Quartz vs Automatic
                </button>
                <button onclick="quickAsk('Chính sách đổi trả như thế nào')"
                        class="btn btn-outline-secondary"
                        style="font-size:10px;padding:2px 6px;border-radius:10px">
                    Đổi trả
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let chatHistory = [];
let isChatOpen = false;

function toggleChat() {
    const window_ = document.getElementById('chat-window');
    const icon    = document.getElementById('chat-icon');
    isChatOpen    = !isChatOpen;

    if (isChatOpen) {
        window_.classList.remove('d-none');
        icon.className = 'bi bi-x-lg';
        document.getElementById('chat-input').focus();
    } else {
        window_.classList.add('d-none');
        icon.className = 'bi bi-chat-dots-fill';
    }
}

function quickAsk(text) {
    document.getElementById('chat-input').value = text;
    sendMessage();
}

function sendMessage() {
    const input   = document.getElementById('chat-input');
    const message = input.value.trim();
    if (!message) return;

    // Hiện tin nhắn user
    appendMessage('user', message);
    chatHistory.push({ role: 'user', content: message });
    input.value = '';

    // Hiện loading
    const loadingId = appendLoading();

    // Gọi API
    fetch('{{ route("ai.chat") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            message: message,
            history: chatHistory.slice(-10)
        })
    })
    .then(res => res.json())
    .then(data => {
        removeLoading(loadingId);
        const reply = data.reply || 'Xin lỗi, có lỗi xảy ra!';
        appendMessage('bot', reply);
        chatHistory.push({ role: 'assistant', content: reply });
    })
    .catch(() => {
        removeLoading(loadingId);
        appendMessage('bot', 'Xin lỗi, không thể kết nối. Vui lòng thử lại!');
    });
}

function appendMessage(role, content) {
    const messages = document.getElementById('chat-messages');
    const isUser   = role === 'user';

    const div = document.createElement('div');
    div.className = `d-flex gap-2 mb-3 ${isUser ? 'flex-row-reverse' : ''}`;
    div.innerHTML = `
        <div class="rounded-circle ${isUser ? 'bg-success' : 'bg-primary'}
                    d-flex align-items-center justify-content-center
                    text-white flex-shrink-0"
             style="width:30px;height:30px;font-size:14px">
            <i class="bi bi-${isUser ? 'person-fill' : 'robot'}"></i>
        </div>
        <div class="${isUser ? 'bg-primary text-white' : 'bg-white'}
                    rounded p-2 shadow-sm small"
             style="max-width:240px;line-height:1.5">
            ${content.replace(/\n/g, '<br>')}
        </div>`;

    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
}

function appendLoading() {
    const messages = document.getElementById('chat-messages');
    const id       = 'loading-' + Date.now();
    const div      = document.createElement('div');
    div.id         = id;
    div.className  = 'd-flex gap-2 mb-3';
    div.innerHTML  = `
        <div class="rounded-circle bg-primary d-flex align-items-center
                    justify-content-center text-white flex-shrink-0"
             style="width:30px;height:30px;font-size:14px">
            <i class="bi bi-robot"></i>
        </div>
        <div class="bg-white rounded p-2 shadow-sm small">
            <span class="spinner-border spinner-border-sm text-primary"></span>
            Đang trả lời...
        </div>`;
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
    return id;
}

function removeLoading(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}
</script>
<body>

{{-- Topbar --}}
<div class="bg-dark text-white py-1 small">
    <div class="container d-flex justify-content-between">
        <span><i class="bi bi-telephone"></i> 0901 234 567 &nbsp;|&nbsp;
              <i class="bi bi-envelope"></i> info@dongho.com</span>
        <span>
            @auth
                Xin chào, <strong>{{ auth()->user()->name }}</strong>
                @if(auth()->user()->isAdmin())
                    &nbsp;|&nbsp;
                    <a href="{{ route('admin.dashboard') }}" class="text-warning">
                        <i class="bi bi-speedometer2"></i> Admin
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="text-white">Đăng nhập</a>
                &nbsp;|&nbsp;
                <a href="{{ route('register') }}" class="text-white">Đăng ký</a>
            @endauth
        </span>
    </div>
</div>

{{-- Navbar --}}
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="bi bi-clock text-primary"></i> Đồng Hồ
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            {{-- Menu chính --}}
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'text-primary fw-bold' : '' }}"
                       href="{{ route('home') }}">Trang chủ</a>
                </li>
                {{-- Thêm vào trong dropdown user --}}
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#"
                       data-bs-toggle="dropdown">Sản phẩm</a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('products.index') }}">
                                Tất cả sản phẩm
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        @foreach(\App\Models\Category::whereNull('parent_id')->where('is_active',true)->get() as $cat)
                        <li>
                            <a class="dropdown-item" href="{{ route('products.index', ['category' => $cat->slug]) }}">
                                {{ $cat->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('news.*') ? 'text-primary fw-bold' : '' }}"
                       href="{{ route('news.index') }}">Tin tức</a>
                </li>
                @auth
                <li>
                    <a class="dropdown-item" href="{{ route('ai.suggestions') }}">
                        <i class="bi bi-robot text-primary"></i> Gợi ý cho tôi
                    </a>
                </li>
                @endauth
            </ul>
            

            {{-- Tìm kiếm --}}
            <form action="{{ route('products.index') }}" method="GET"
                  class="d-flex me-3" style="max-width: 280px">
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Tìm đồng hồ..."
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            {{-- Giỏ hàng + User --}}
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('cart.index') }}" class="btn btn-outline-primary btn-sm cart-btn">
                    <i class="bi bi-bag"></i> Giỏ hàng
                    @php $cartCount = count(session('cart', [])); @endphp
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>

                @auth
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/'.auth()->user()->avatar) }}"
                                 width="24" height="24"
                                 class="rounded-circle object-fit-cover">
                        @else
                            <i class="bi bi-person-circle"></i>
                        @endif
                        {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.index') }}">
                                <i class="bi bi-person"></i> Tài khoản
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('orders.index') }}">
                                <i class="bi bi-bag"></i> Đơn hàng của tôi
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Thông báo --}}
<div class="container mt-2">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

{{-- Nội dung trang --}}
@yield('content')

{{-- Footer --}}
<footer class="mt-5 py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h6><i class="bi bi-clock"></i> Đồng Hồ Online</h6>
                <p class="small">Chuyên cung cấp đồng hồ chính hãng các thương hiệu
                   Casio, Seiko, Citizen, Fossil...</p>
            </div>
            <div class="col-md-2">
                <h6>Sản phẩm</h6>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('products.index', ['category' => 'dong-ho-nam']) }}">Đồng hồ nam</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'dong-ho-nu']) }}">Đồng hồ nữ</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'dong-ho-treo-tuong']) }}">Đồng hồ treo tường</a></li>
                </ul>
            </div>
            <div class="col-md-2">
                <h6>Hỗ trợ</h6>
                <ul class="list-unstyled small">
                    <li><a href="#">Chính sách đổi trả</a></li>
                    <li><a href="#">Hướng dẫn mua hàng</a></li>
                    <li><a href="#">Liên hệ</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6>Liên hệ</h6>
                <ul class="list-unstyled small">
                    <li><i class="bi bi-geo-alt"></i> 123 Đường ABC, TP.HCM</li>
                    <li><i class="bi bi-telephone"></i> 0901 234 567</li>
                    <li><i class="bi bi-envelope"></i> info@dongho.com</li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary mt-4">
        <p class="text-center small mb-0">
            © 2024 Đồng Hồ Online. All rights reserved.
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>