<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareConnect — Verify OTP</title>
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

        .auth-description {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: var(--danger);
            font-size: 13px;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .alert-success {
            background: var(--accent-light);
            border: 1px solid #a7f3d0;
            color: #065f46;
            font-size: 13px;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .form-group { margin-bottom: 24px; text-align: center; }

        .otp-input {
            width: 100%;
            height: 56px;
            padding: 0 14px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 12px;
            text-align: center;
            color: var(--text);
            font-family: inherit;
            transition: border-color 0.15s, box-shadow 0.15s;
            background: var(--card);
        }

        .otp-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(46,196,182,0.12);
        }

        .otp-input::placeholder { color: #94a3b8; font-weight: 400; letter-spacing: 12px; }
        .otp-input.error { border-color: var(--danger); }

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

        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 20px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .back-link a {
            color: var(--accent-dark);
            text-decoration: none;
            font-weight: 600;
        }

        .back-link a:hover { text-decoration: underline; }

        .auth-branding {
            text-align: center;
            margin-top: 18px;
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
            <div class="auth-subtitle">Verify OTP Code</div>
        </div>

        <div class="auth-body">

            <p class="auth-description">
                We've sent a 6-digit code to <strong>{{ session('reset_email') }}</strong>. Please enter the code below to reset your password.
            </p>

            @if (session('status'))
                <div class="alert-success">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ url('/verify-otp') }}">
                @csrf

                <div class="form-group">
                    <input type="text" id="otp" name="otp" class="otp-input {{ $errors->has('otp') ? 'error' : '' }}"
                           value="{{ old('otp') }}" placeholder="------" maxlength="6" pattern="\d{6}" required autofocus autocomplete="off">
                </div>

                <button type="submit" class="btn-primary">Verify Code</button>
            </form>

            <div class="back-link">
                <a href="{{ url('/forgot-password') }}">Didn't receive code? Try again</a>
            </div>

            <div class="auth-branding">© {{ date('Y') }} CareConnect · Ummah Relief Project</div>
        </div>

    </div>
</div>

</body>
</html>
