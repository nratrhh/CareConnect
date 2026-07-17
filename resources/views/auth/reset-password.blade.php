<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareConnect — Reset Password</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #00827F;
            --accent: #00827F;
            --accent-dark: #006b68;
            --accent-light: #e6f2f2;
            --danger: #e24b4a;
            --text: #1a1a2e;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --bg: #f0f2f5;
            --card: #fff;
        }

        html, body {
            height: 100%;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .auth-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(46,196,182,0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(30,58,110,0.06) 0%, transparent 50%),
                var(--bg);
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
            background: var(--card);
            border-radius: 16px;
            border: 0.5px solid var(--border);
            box-shadow: 0 4px 24px rgba(0,0,0,0.06), 0 1px 4px rgba(0,0,0,0.04);
            overflow: hidden;
        }

        .auth-header {
            background: var(--primary);
            padding: 24px 32px 20px;
            text-align: center;
        }

        .auth-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 6px;
        }

        .auth-logo-icon {
            width: 32px;
            height: 32px;
            background: #fff;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .auth-logo-icon img { width: 24px; height: 24px; object-fit: contain; }
        .auth-logo-text { font-size: 17px; font-weight: 600; color: #fff; }
        .auth-subtitle { font-size: 13px; color: rgba(255,255,255,0.55); margin-top: 4px; }

        .auth-body { padding: 26px 32px 32px; }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: var(--danger);
            font-size: 13px;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .form-group { margin-bottom: 18px; }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 5px;
        }

        .form-input {
            width: 100%;
            height: 42px;
            padding: 0 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            color: var(--text);
            font-family: inherit;
            transition: border-color 0.15s, box-shadow 0.15s;
            background: var(--card);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(46,196,182,0.12);
        }

        .form-input::placeholder { color: #94a3b8; }
        .form-input.error { border-color: var(--danger); }

        .password-wrapper { position: relative; }
        .password-wrapper .form-input { padding-right: 42px; }

        .password-toggle {
            position: absolute;
            right: 1px;
            top: 1px;
            bottom: 1px;
            width: 40px;
            border: none;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            border-radius: 0 7px 7px 0;
        }

        .password-toggle:hover { color: var(--text-muted); }
        .password-toggle svg { width: 18px; height: 18px; fill: currentColor; }

        .pw-requirements {
            margin-top: 8px;
            padding: 10px 12px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .pw-requirements .pw-title {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .pw-req {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 3px;
        }

        .pw-req:last-child { margin-bottom: 0; }

        .pw-req .req-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #cbd5e1;
            flex-shrink: 0;
            transition: background 0.2s;
        }

        .pw-req.met .req-dot { background: var(--accent); }
        .pw-req.met { color: var(--accent-dark); }

        .btn-primary {
            width: 100%;
            height: 44px;
            border: none;
            border-radius: 8px;
            background: var(--accent);
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            font-family: inherit;
        }

        .btn-primary:hover { background: var(--accent-dark); }
        .btn-primary:active { transform: scale(0.98); }

        .auth-branding {
            text-align: center;
            margin-top: 22px;
            font-size: 11px;
            color: #cbd5e1;
        }
    </style>
</head>
<body>

<div class="auth-shell">
    <div class="auth-card">

        <div class="auth-header">
            <div class="auth-logo">
                <div class="auth-logo-icon">
                    <img src="{{ asset('images/logo.png') }}" alt="CareConnect Logo">
                </div>
                <span class="auth-logo-text">CareConnect</span>
            </div>
            <div class="auth-subtitle">Set your new password</div>
        </div>

        <div class="auth-body">

            @if ($errors->any())
                <div class="alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ url('/reset-password') }}">
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                           value="{{ old('email', $email ?? '') }}" required readonly
                           style="background: #f8fafc; color: var(--text-muted); cursor: not-allowed;">
                </div>

                {{-- New Password --}}
                <div class="form-group">
                    <label class="form-label" for="password">New Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                               placeholder="Enter new password" required autofocus>
                        <button type="button" class="password-toggle" onclick="togglePw('password','eyeIcon1')" aria-label="Toggle password">
                            <svg id="eyeIcon1" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                        </button>
                    </div>

                    <div class="pw-requirements">
                        <div class="pw-title">Password Requirements</div>
                        <div class="pw-req" id="req-length">
                            <span class="req-dot"></span>
                            <span>Minimum 8 characters</span>
                        </div>
                        <div class="pw-req" id="req-upper">
                            <span class="req-dot"></span>
                            <span>At least one uppercase letter</span>
                        </div>
                        <div class="pw-req" id="req-number">
                            <span class="req-dot"></span>
                            <span>At least one number</span>
                        </div>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                               placeholder="Re-enter your password" required>
                        <button type="button" class="password-toggle" onclick="togglePw('password_confirmation','eyeIcon2')" aria-label="Toggle password">
                            <svg id="eyeIcon2" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Reset Password</button>
            </form>

            <div class="auth-branding">© {{ date('Y') }} CareConnect · Ummah Relief Project</div>
        </div>

    </div>
</div>

<script>
    function togglePw(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>';
        }
    }

    const pwInput = document.getElementById('password');
    pwInput.addEventListener('input', function() {
        const val = this.value;
        document.getElementById('req-length').classList.toggle('met', val.length >= 8);
        document.getElementById('req-upper').classList.toggle('met', /[A-Z]/.test(val));
        document.getElementById('req-number').classList.toggle('met', /[0-9]/.test(val));
    });
</script>

</body>
</html>
