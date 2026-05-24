<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký — Dương Liêm</title>
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

        html, body { min-height: 100%; font-family: 'Montserrat', sans-serif; background: var(--ink); color: #fff; overflow-x: hidden; }

        /* Ambient */
        .bg-ambient {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background:
                radial-gradient(ellipse 70% 60% at 85% 50%,  rgba(201,168,76,.09) 0%, transparent 60%),
                radial-gradient(ellipse 50% 60% at 15% 25%,  rgba(201,168,76,.06) 0%, transparent 50%),
                radial-gradient(ellipse 40% 40% at 30% 85%,  rgba(139,105,20,.07) 0%, transparent 50%);
        }

        /* Layout */
        .auth-wrapper { position: relative; z-index: 1; min-height: 100vh; display: grid; grid-template-columns: 1fr 1fr; }

        /* ── Form panel (LEFT) ── */
        .form-panel { display: flex; flex-direction: column; justify-content: center; padding: 50px 72px; animation: fadeUp .9s ease both; }

        .form-eyebrow { font-size: 10px; letter-spacing: 5px; text-transform: uppercase; color: var(--gold); margin-bottom: 10px; }
        .form-heading { font-family: 'Cormorant Garamond', serif; font-size: 42px; font-weight: 300; line-height: 1.08; margin-bottom: 8px; }
        .form-sub { font-size: 12.5px; color: var(--muted); letter-spacing: .4px; margin-bottom: 32px; }

        /* Fields */
        .auth-field { margin-bottom: 20px; }
        .auth-label { display: block; font-size: 9.5px; letter-spacing: 3.5px; text-transform: uppercase; color: var(--gold); font-weight: 500; margin-bottom: 8px; }
        .auth-input-wrap { position: relative; }

        .auth-input {
            width: 100%; background: rgba(255,255,255,.03); border: 1px solid var(--border);
            color: #fff; font-family: 'Montserrat', sans-serif; font-size: 13.5px;
            padding: 14px 44px 14px 18px; outline: none; border-radius: 0;
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

        /* Password strength */
        .strength-bar { display: flex; gap: 4px; margin-top: 8px; }
        .strength-seg { flex: 1; height: 3px; background: rgba(255,255,255,.1); transition: background .3s; border-radius: 2px; }

        /* Checkbox */
        .auth-check { display: flex; align-items: flex-start; gap: 10px; cursor: pointer; user-select: none; }
        .auth-check input[type=checkbox] { display: none; }
        .check-box { width: 17px; height: 17px; border: 1px solid var(--border); flex-shrink: 0; display: flex; align-items: center; justify-content: center; transition: all .25s; margin-top: 1px; }
        .auth-check input:checked ~ .check-box { background: var(--gold); border-color: var(--gold); }
        .auth-check input:checked ~ .check-box::after { content: ''; width: 8px; height: 5px; border-left: 2px solid #000; border-bottom: 2px solid #000; transform: rotate(-45deg) translateY(-1px); display: block; }
        .check-label { font-size: 12px; color: var(--muted); line-height: 1.6; }
        .check-label a { color: var(--gold); text-decoration: none; }
        .check-label a:hover { color: var(--gold-light); }

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

        .form-foot { text-align: center; font-size: 13px; color: var(--muted); margin-top: 24px; }
        .form-foot a { color: var(--gold); text-decoration: none; font-weight: 500; }
        .form-foot a:hover { color: var(--gold-light); }

        /* ── Brand panel (RIGHT) ── */
        .brand-panel {
            position: relative; display: flex; flex-direction: column;
            justify-content: center; align-items: center;
            padding: 60px 48px; overflow: hidden;
            border-left: 1px solid var(--border);
        }
        .brand-panel::before {
            content: ''; position: absolute; inset: 0; pointer-events: none;
            background: linear-gradient(220deg, rgba(201,168,76,.08) 0%, transparent 55%);
        }

        /* Rings */
        .ring { position: absolute; border-radius: 50%; border: 1px solid rgba(201,168,76,.10); top: 50%; left: 50%; transform: translate(-50%,-50%); }
        .ring-1 { width: 540px; height: 540px; animation: spin 36s linear infinite; }
        .ring-2 { width: 410px; height: 410px; animation: spin 24s linear infinite reverse; border-style: dashed; border-color: rgba(201,168,76,.06); }
        .ring-3 { width: 290px; height: 290px; animation: spin 16s linear infinite; border-color: rgba(201,168,76,.08); }
        @keyframes spin { to { transform: translate(-50%,-50%) rotate(360deg); } }
        .ring-1::after {
            content: ''; position: absolute; top: -4px; left: 50%; transform: translateX(-50%);
            width: 8px; height: 8px; border-radius: 50%; background: var(--gold);
            box-shadow: 0 0 14px 4px rgba(201,168,76,.55);
        }

        .brand-content { position: relative; z-index: 2; text-align: center; animation: fadeUp .9s ease .2s both; }

        .brand-icon {
            width: 88px; height: 88px; border-radius: 50%; border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center; margin: 0 auto 26px;
            background: rgba(201,168,76,.05); animation: glowPulse 4s ease-in-out infinite;
        }
        .brand-icon i { font-size: 34px; background: linear-gradient(135deg, var(--gold-dark), var(--gold-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        @keyframes glowPulse {
            0%,100% { box-shadow: 0 0 28px rgba(201,168,76,.14); }
            50%      { box-shadow: 0 0 52px rgba(201,168,76,.32); }
        }

        .brand-name { font-family: 'Cormorant Garamond', serif; font-size: 48px; font-weight: 300; letter-spacing: 10px; line-height: 1; }
        .brand-name strong { color: var(--gold); font-weight: 500; }
        .brand-tag { font-size: 9.5px; letter-spacing: 6px; text-transform: uppercase; color: var(--gold); opacity: .75; margin-top: 10px; }

        .brand-sep { display: flex; align-items: center; gap: 14px; width: 200px; margin: 28px auto; }
        .brand-sep::before, .brand-sep::after { content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, transparent, var(--gold), transparent); }
        .diamond { width: 6px; height: 6px; background: var(--gold); transform: rotate(45deg); box-shadow: 0 0 8px var(--gold); }

        /* Perk cards */
        .perk-list { display: flex; flex-direction: column; gap: 14px; text-align: left; }

        .perk-card {
            display: flex; align-items: flex-start; gap: 14px;
            padding: 14px 16px;
            background: rgba(255,255,255,.02);
            border: 1px solid var(--border);
            transition: background .3s, border-color .3s;
            animation: fadeUp .8s ease both;
        }
        .perk-card:nth-child(1) { animation-delay: .4s; }
        .perk-card:nth-child(2) { animation-delay: .55s; }
        .perk-card:nth-child(3) { animation-delay: .7s; }
        .perk-card:hover { background: rgba(201,168,76,.06); border-color: rgba(201,168,76,.4); }

        .perk-icon { width: 34px; height: 34px; border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; color: var(--gold); font-size: 13px; flex-shrink: 0; }

        .perk-text strong { display: block; font-size: 12px; font-weight: 600; letter-spacing: .8px; color: #fff; margin-bottom: 3px; }
        .perk-text span   { font-size: 11px; color: var(--muted); }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: translateY(0); } }

        @media (max-width: 992px) { .auth-wrapper { grid-template-columns: 1fr; } .brand-panel { display: none; } .form-panel { padding: 48px 32px; } }
        @media (max-width: 480px) { .form-panel { padding: 36px 20px; } .form-heading { font-size: 34px; } }
    </style>
</head>
<body>

<div class="bg-ambient"></div>

<div class="auth-wrapper">

    {{-- ══ FORM PANEL (LEFT) ══ --}}
    <div class="form-panel">

        <div class="form-eyebrow">Tham gia cộng đồng</div>
        <h1 class="form-heading">Tạo<br>Tài Khoản</h1>
        <p class="form-sub">Đăng ký để khám phá bộ sưu tập đồng hồ luxury độc quyền</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Họ + Tên --}}
            <div class="row g-3 mb-1">
                <div class="col-6">
                    <div class="auth-field mb-0">
                        <label class="auth-label" for="last_name">Họ</label>
                        <div class="auth-input-wrap">
                            <input id="last_name" type="text" name="last_name" class="auth-input"
                                   placeholder="Nguyễn" value="{{ old('last_name') }}">
                            <span class="input-icon"><i class="fas fa-user"></i></span>
                            <div class="input-bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="auth-field mb-0">
                        <label class="auth-label" for="first_name">Tên</label>
                        <div class="auth-input-wrap">
                            <input id="first_name" type="text" name="first_name" class="auth-input"
                                   placeholder="Văn A" value="{{ old('first_name') }}">
                            <span class="input-icon"><i class="fas fa-user"></i></span>
                            <div class="input-bar"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hidden name field for Breeze --}}
            <input type="hidden" name="name" id="name">

            {{-- Email --}}
            <div class="auth-field mt-3">
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

            {{-- Phone --}}
            <div class="auth-field">
                <label class="auth-label" for="phone">Số Điện Thoại <span style="opacity:.5;font-size:9px">(Tùy chọn)</span></label>
                <div class="auth-input-wrap">
                    <input id="phone" type="tel" name="phone" class="auth-input"
                           placeholder="0912 345 678" value="{{ old('phone') }}">
                    <span class="input-icon"><i class="fas fa-phone"></i></span>
                    <div class="input-bar"></div>
                </div>
            </div>

            {{-- Password --}}
            <div class="auth-field">
                <label class="auth-label" for="password">Mật Khẩu</label>
                <div class="auth-input-wrap">
                    <input id="password" type="password" name="password" class="auth-input"
                           placeholder="Tối thiểu 8 ký tự" required autocomplete="new-password"
                           oninput="checkStrength(this.value)">
                    <span class="input-icon toggle-pw" onclick="togglePw('password',this)">
                        <i class="fas fa-eye"></i>
                    </span>
                    <div class="input-bar"></div>
                </div>
                <div class="strength-bar">
                    <div class="strength-seg" id="s1"></div>
                    <div class="strength-seg" id="s2"></div>
                    <div class="strength-seg" id="s3"></div>
                    <div class="strength-seg" id="s4"></div>
                </div>
                @error('password')
                    <div class="field-error"><i class="fas fa-circle-exclamation me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="auth-field">
                <label class="auth-label" for="password_confirmation">Xác Nhận Mật Khẩu</label>
                <div class="auth-input-wrap">
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           class="auth-input" placeholder="Nhập lại mật khẩu"
                           required autocomplete="new-password">
                    <span class="input-icon toggle-pw" onclick="togglePw('password_confirmation',this)">
                        <i class="fas fa-eye"></i>
                    </span>
                    <div class="input-bar"></div>
                </div>
            </div>

            {{-- Terms --}}
            <div class="mb-4">
                <label class="auth-check">
                    <input type="checkbox" name="terms" required>
                    <div class="check-box"></div>
                    <span class="check-label ms-1">
                        Tôi đồng ý với <a href="#">Điều khoản dịch vụ</a>
                        và <a href="#">Chính sách bảo mật</a> của Dương Liêm
                    </span>
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-auth">
                <span>Tạo Tài Khoản</span>
            </button>

            <div class="form-foot">
                Đã có tài khoản?
                <a href="{{ route('login') }}">Đăng nhập ngay →</a>
            </div>
        </form>
    </div>

    {{-- ══ BRAND PANEL (RIGHT) ══ --}}
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

            <div class="perk-list">
                <div class="perk-card">
                    <div class="perk-icon"><i class="fas fa-crown"></i></div>
                    <div class="perk-text">
                        <strong>Đặc Quyền Thành Viên</strong>
                        <span>Ưu đãi và giá thành viên độc quyền hàng tháng</span>
                    </div>
                </div>
                <div class="perk-card">
                    <div class="perk-icon"><i class="fas fa-box-open"></i></div>
                    <div class="perk-text">
                        <strong>Theo Dõi Đơn Hàng</strong>
                        <span>Quản lý lịch sử mua hàng dễ dàng</span>
                    </div>
                </div>
                <div class="perk-card">
                    <div class="perk-icon"><i class="fas fa-bell"></i></div>
                    <div class="perk-text">
                        <strong>Thông Báo Ưu Đãi</strong>
                        <span>Nhận ngay thông tin khuyến mãi sớm nhất</span>
                    </div>
                </div>
            </div>
        </div>
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

function checkStrength(val) {
    const segs   = ['s1','s2','s3','s4'].map(id => document.getElementById(id));
    const colors = ['#e05', '#e07020', '#C9A84C', '#4CAF50'];
    segs.forEach(s => s.style.background = 'rgba(255,255,255,.1)');
    let score = 0;
    if (val.length >= 8)            score++;
    if (/[A-Z]/.test(val))          score++;
    if (/[0-9]/.test(val))          score++;
    if (/[^A-Za-z0-9]/.test(val))   score++;
    for (let i = 0; i < score; i++) segs[i].style.background = colors[score - 1];
}

// Ghép họ + tên → field name trước khi submit
document.querySelector('form').addEventListener('submit', function () {
    const last  = document.getElementById('last_name').value.trim();
    const first = document.getElementById('first_name').value.trim();
    document.getElementById('name').value = [last, first].filter(Boolean).join(' ');
});
</script>
</body>
</html>