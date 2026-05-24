<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập — Dương Lie</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --gold:       #C9A84C;
            --gold-light: #E8C97A;
            --gold-dark:  #8B6914;
            --ink:        #0C0C0E;
            --border:     rgba(201,168,76,.22);
            --muted:      rgba(255,255,255,.42);
        }

        html, body { height: 100%; font-family: 'Montserrat', sans-serif; background: var(--ink); color: #fff; overflow-x: hidden; }

        /* Ambient glow */
        .bg-ambient {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background:
                radial-gradient(ellipse 70% 60% at 15% 50%,  rgba(201,168,76,.09) 0%, transparent 60%),
                radial-gradient(ellipse 50% 70% at 85% 20%,  rgba(201,168,76,.06) 0%, transparent 55%),
                radial-gradient(ellipse 40% 40% at 70% 85%,  rgba(139,105,20,.08) 0%, transparent 50%);
        }

        /* Layout */
        .auth-wrapper { position: relative; z-index: 1; min-height: 100vh; display: grid; grid-template-columns: 1fr 1fr; }

        /* ── Brand Panel ── */
        .brand-panel {
            position: relative; display: flex; flex-direction: column;
            justify-content: center; align-items: center;
            padding: 60px 48px; overflow: hidden;
            border-right: 1px solid var(--border);
        }
        .brand-panel::before {
            content: ''; position: absolute; inset: 0; pointer-events: none;
            background: linear-gradient(140deg, rgba(201,168,76,.07) 0%, transparent 55%);
        }

        /* Rings */
        .ring { position: absolute; border-radius: 50%; border: 1px solid rgba(201,168,76,.10); top: 50%; left: 50%; transform: translate(-50%,-50%); }
        .ring-1 { width: 560px; height: 560px; animation: spin 38s linear infinite; }
        .ring-2 { width: 430px; height: 430px; animation: spin 25s linear infinite reverse; border-style: dashed; border-color: rgba(201,168,76,.06); }
        .ring-3 { width: 310px; height: 310px; animation: spin 17s linear infinite; border-color: rgba(201,168,76,.08); }
        @keyframes spin { to { transform: translate(-50%,-50%) rotate(360deg); } }
        .ring-1::after {
            content: ''; position: absolute; top: -4px; left: 50%; transform: translateX(-50%);
            width: 8px; height: 8px; border-radius: 50%; background: var(--gold);
            box-shadow: 0 0 14px 4px rgba(201,168,76,.55);
        }

        /* Brand content */
        .brand-content { position: relative; z-index: 2; text-align: center; animation: fadeUp .9s ease both; }

        .brand-icon {
            width: 88px; height: 88px; border-radius: 50%;
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 26px;
            background: rgba(201,168,76,.05);
            animation: glowPulse 4s ease-in-out infinite;
        }
        .brand-icon i { font-size: 34px; background: linear-gradient(135deg, var(--gold-dark), var(--gold-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        @keyframes glowPulse {
            0%,100% { box-shadow: 0 0 28px rgba(201,168,76,.14); }
            50%      { box-shadow: 0 0 52px rgba(201,168,76,.32); }
        }

        .brand-name { font-family: 'Cormorant Garamond', serif; font-size: 50px; font-weight: 300; letter-spacing: 10px; line-height: 1; }
        .brand-name strong { color: var(--gold); font-weight: 500; }
        .brand-tag { font-size: 9.5px; letter-spacing: 6px; text-transform: uppercase; color: var(--gold); opacity: .75; margin-top: 10px; }

        .brand-sep { display: flex; align-items: center; gap: 14px; width: 200px; margin: 28px auto; }
        .brand-sep::before, .brand-sep::after { content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, transparent, var(--gold), transparent); }
        .diamond { width: 6px; height: 6px; background: var(--gold); transform: rotate(45deg); box-shadow: 0 0 8px var(--gold); }

        .brand-quote { font-family: 'Cormorant Garamond', serif; font-size: 15.5px; font-style: italic; color: var(--muted); line-height: 1.85; }

        .feature-list { list-style: none; padding: 0; margin: 28px 0 0; display: flex; flex-direction: column; gap: 13px; text-align: left; }
        .feature-list li { display: flex; align-items: center; gap: 12px; font-size: 12px; color: var(--muted); animation: fadeUp .8s ease both; }
        .feature-list li:nth-child(1) { animation-delay: .4s; }
        .feature-list li:nth-child(2) { animation-delay: .6s; }
        .feature-list li:nth-child(3) { animation-delay: .8s; }
        .feature-list li .feat-icon { width: 30px; height: 30px; border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; color: var(--gold); font-size: 12px; flex-shrink: 0; }

        /* ── Form Panel ── */
        .form-panel { display: flex; flex-direction: column; justify-content: center; padding: 60px 72px; animation: fadeUp .9s ease .15s both; }

        .form-eyebrow { font-size: 10px; letter-spacing: 5px; text-transform: uppercase; color: var(--gold); margin-bottom: 10px; }
        .form-heading { font-family: 'Cormorant Garamond', serif; font-size: 46px; font-weight: 300; line-height: 1.08; margin-bottom: 8px; }
        .form-sub { font-size: 12.5px; color: var(--muted); letter-spacing: .4px; margin-bottom: 38px; }

        /* Fields */
        .auth-field { margin-bottom: 22px; }
        .auth-label { display: block; font-size: 9.5px; letter-spacing: 3.5px; text-transform: uppercase; color: var(--gold); font-weight: 500; margin-bottom: 8px; }
        .auth-input-wrap { position: relative; }

        .auth-input {
            width: 100%; background: rgba(255,255,255,.03); border: 1px solid var(--border);
            color: #fff; font-family: 'Montserrat', sans-serif; font-size: 13.5px;
            padding: 15px 44px 15px 18px; outline: none; border-radius: 0;
            transition: border-color .3s, background .3s, box-shadow .3s; letter-spacing: .4px;
            -webkit-appearance: none;
        }
        .auth-input::placeholder { color: rgba(255,255,255,.18); }
        .auth-input:focus { background: rgba(201,168,76,.05); border-color: var(--gold); box-shadow: 0 0 0 2px rgba(201,168,76,.18); color: #fff; }
        .auth-input:-webkit-autofill { -webkit-box-shadow: 0 0 0 1000px #1a1a1a inset !important; -webkit-text-fill-color: #fff !important; }

        .input-icon { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--gold); opacity: .4; font-size: 13px; transition: opacity .3s; pointer-events: none; }
        .auth-input:focus ~ .input-icon { opacity: 1; }
        .input-icon.toggle-pw { pointer-events: all; cursor: pointer; }

        .input-bar { position: absolute; bottom: 0; left: 0; height: 2px; width: 0; background: linear-gradient(90deg, var(--gold), var(--gold-light)); transition: width .4s; box-shadow: 0 0 8px rgba(201,168,76,.5); }
        .auth-input:focus ~ .input-bar { width: 100%; }

        .field-error { font-size: 11px; color: #ff5577; margin-top: 5px; }

        /* Checkbox */
        .auth-check { display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none; }
        .auth-check input[type=checkbox] { display: none; }
        .check-box { width: 16px; height: 16px; border: 1px solid var(--border); flex-shrink: 0; display: flex; align-items: center; justify-content: center; transition: all .25s; }
        .auth-check input:checked ~ .check-box { background: var(--gold); border-color: var(--gold); }
        .auth-check input:checked ~ .check-box::after { content: ''; width: 8px; height: 5px; border-left: 2px solid #000; border-bottom: 2px solid #000; transform: rotate(-45deg) translateY(-1px); display: block; }
        .check-label { font-size: 12px; color: var(--muted); }

        .forgot-link { font-size: 12px; color: var(--gold); text-decoration: none; opacity: .8; transition: opacity .2s; }
        .forgot-link:hover { opacity: 1; color: var(--gold-light); }

        /* Button */
        .btn-auth {
            width: 100%; padding: 17px; border: none; border-radius: 0;
            background: linear-gradient(135deg, var(--gold-dark) 0%, var(--gold) 50%, var(--gold-light) 100%);
            color: #000; font-family: 'Montserrat', sans-serif; font-size: 10.5px; font-weight: 700;
            letter-spacing: 5px; text-transform: uppercase; cursor: pointer; position: relative; overflow: hidden;
            transition: all .3s; box-shadow: 0 8px 28px rgba(201,168,76,.2);
        }
        .btn-auth::before { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, var(--gold-light), var(--gold)); opacity: 0; transition: opacity .3s; }
        .btn-auth:hover::before { opacity: 1; }
        .btn-auth:hover { box-shadow: 0 14px 40px rgba(201,168,76,.4); transform: translateY(-2px); }
        .btn-auth:active { transform: translateY(0); }
        .btn-auth span { position: relative; z-index: 1; }

        /* OR */
        .or-row { display: flex; align-items: center; gap: 14px; color: var(--muted); font-size: 11px; letter-spacing: 3px; }
        .or-row::before, .or-row::after { content: ''; flex: 1; height: 1px; background: var(--border); }

        /* Social */
        .btn-social {
            display: flex; align-items: center; justify-content: center; gap: 9px;
            padding: 13px 16px; background: rgba(255,255,255,.03); border: 1px solid var(--border);
            color: rgba(255,255,255,.7); font-family: 'Montserrat', sans-serif;
            font-size: 12px; text-decoration: none; transition: all .3s; border-radius: 0;
        }
        .btn-social:hover { background: rgba(201,168,76,.08); border-color: rgba(201,168,76,.5); color: var(--gold-light); transform: translateY(-1px); }

        .form-foot { text-align: center; font-size: 13px; color: var(--muted); }
        .form-foot a { color: var(--gold); text-decoration: none; font-weight: 500; }
        .form-foot a:hover { color: var(--gold-light); }

        /* Alerts */
        .auth-alert { padding: 13px 18px; border-left: 3px solid; font-size: 12.5px; margin-bottom: 20px; }
        .auth-alert-success { background: rgba(201,168,76,.08); border-color: var(--gold); color: var(--gold-light); }
        .auth-alert-error   { background: rgba(220,0,80,.08); border-color: #e05; color: #ff6b9d; }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: translateY(0); } }

        @media (max-width: 992px) { .auth-wrapper { grid-template-columns: 1fr; } .brand-panel { display: none; } .form-panel { padding: 48px 32px; } }
        @media (max-width: 480px) { .form-panel { padding: 36px 20px; } .form-heading { font-size: 36px; } }
    </style>
</head>
<body>

<div class="bg-ambient"></div>

<div class="auth-wrapper">

    {{-- ══ BRAND PANEL ══ --}}
    <div class="brand-panel">
        <div class="ring ring-1"></div>
        <div class="ring ring-2"></div>
        <div class="ring ring-3"></div>

        <div class="brand-content">
            <div class="brand-icon">
                <i class="fas fa-clock"></i>
            </div>

            <div class="brand-name">Dương <strong>Liêm</strong></div>
            <div class="brand-tag">Luxury Timepieces</div>

            <div class="brand-sep"><div class="diamond"></div></div>

            <p class="brand-quote">
                "Mỗi chiếc đồng hồ là một<br>kiệt tác vượt thời gian"
            </p>

            <ul class="feature-list">
                <li>
                    <div class="feat-icon"><i class="fas fa-gem"></i></div>
                    Bộ sưu tập đồng hồ xa xỉ chính hãng
                </li>
                <li>
                    <div class="feat-icon"><i class="fas fa-shield-halved"></i></div>
                    Bảo hành chính hãng toàn quốc
                </li>
                <li>
                    <div class="feat-icon"><i class="fas fa-headset"></i></div>
                    Tư vấn chuyên gia 24/7
                </li>
            </ul>
        </div>
    </div>

    {{-- ══ FORM PANEL ══ --}}
    <div class="form-panel">

        <div class="form-eyebrow">Chào mừng trở lại</div>
        <h1 class="form-heading">Đăng<br>Nhập</h1>
        <p class="form-sub">Trải nghiệm bộ sưu tập đồng hồ dành riêng cho bạn</p>

        @if (session('status'))
            <div class="auth-alert auth-alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="auth-field">
                <label class="auth-label" for="email">Địa Chỉ Email</label>
                <div class="auth-input-wrap">
                    <input id="email" type="email" name="email" class="auth-input"
                           placeholder="your@email.com" value="{{ old('email') }}"
                           required autocomplete="username">
                    <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    <div class="input-bar"></div>
                </div>
                @error('email')
                    <div class="field-error"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="auth-field">
                <label class="auth-label" for="password">Mật Khẩu</label>
                <div class="auth-input-wrap">
                    <input id="password" type="password" name="password" class="auth-input"
                           placeholder="••••••••••" required autocomplete="current-password">
                    <span class="input-icon toggle-pw" onclick="togglePw('password',this)">
                        <i class="fas fa-eye"></i>
                    </span>
                    <div class="input-bar"></div>
                </div>
                @error('password')
                    <div class="field-error"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- Remember + Forgot --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <label class="auth-check">
                    <input type="checkbox" name="remember">
                    <div class="check-box"></div>
                    <span class="check-label ms-1">Ghi nhớ đăng nhập</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Quên mật khẩu?</a>
                @endif
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-auth mb-3">
                <span>Đăng Nhập</span>
            </button>

            <div class="or-row my-3">HOẶC</div>

            {{-- Social --}}
            <div class="row g-2 mb-4">
                <div class="col-6">
                    <a href="{{ config('services.google.client_id') ? route('auth.google') : '#' }}" class="btn-social w-100">
                        <svg width="15" height="15" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        Google
                    </a>
                </div>
                <div class="col-6">
                    <a href="#" class="btn-social w-100">
                        <i class="fab fa-facebook" style="color:#1877F2;font-size:15px"></i>
                        Facebook
                    </a>
                </div>
            </div>

            @if (Route::has('register'))
                <div class="form-foot">
                    Chưa có tài khoản?
                    <a href="{{ route('register') }}">Đăng ký ngay →</a>
                </div>
            @endif
        </form>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePw(id, el) {
    const input = document.getElementById(id);
    const icon  = el.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
</script>
</body>
</html>