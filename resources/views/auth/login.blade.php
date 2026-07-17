<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareConnect — Log In</title>
    <meta name="description" content="Sign in to CareConnect — Professional community management and volunteer coordination platform.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #00827F;
            --primary-dark: #006b68;
            --primary-light: #E6F2F2;
            --text: #1a1a2e;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --bg: #f8fafc;
            --white: #ffffff;
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            /* Hero side */
            --hero-bg: #060F1D;
            --hero-accent: #00C9A7;
            --hero-accent-light: #00F5E0;
            --hero-accent-glow: rgba(0,201,167,0.35);
            --hero-card: rgba(255,255,255,0.04);
            --hero-border: rgba(255,255,255,0.08);
            --hero-text: #FFFFFF;
            --hero-text-soft: rgba(255,255,255,0.65);
            --hero-text-muted: rgba(255,255,255,0.4);
        }

        body {
            height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--white);
            overflow: hidden;
            color: var(--text);
        }

        .auth-container {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        /* ═══════════════════════════════════════
           LEFT — HERO PANEL (Immersive Design)
        ═══════════════════════════════════════ */
        .hero-panel {
            flex: 60;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            background: var(--hero-bg);
        }

        /* Layered gradient background */
        .hero-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 50% at 50% 40%, rgba(0,201,167,0.12) 0%, transparent 60%),
                radial-gradient(ellipse 90% 60% at 20% 80%, rgba(0,100,200,0.06) 0%, transparent 50%),
                radial-gradient(ellipse 60% 80% at 80% 20%, rgba(0,201,167,0.08) 0%, transparent 50%),
                linear-gradient(180deg, #040B16 0%, #081525 40%, #060F1D 100%);
            z-index: 0;
        }

        /* Particle canvas */
        #particleCanvas {
            position: absolute;
            inset: 0;
            z-index: 1;
            opacity: 0.4;
        }

        /* ── Rotating Rings ── */
        .rings-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 520px;
            height: 520px;
            z-index: 1;
        }

        .ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .ring-1 {
            width: 520px;
            height: 520px;
            border-color: rgba(0,201,167,0.06);
            animation: spinSlow 20s linear infinite;
        }
        .ring-2 {
            width: 420px;
            height: 420px;
            border-color: rgba(0,201,167,0.1);
            border-style: dashed;
            animation: spinSlow 15s linear infinite reverse;
        }
        .ring-3 {
            width: 320px;
            height: 320px;
            border-color: rgba(0,201,167,0.08);
            animation: spinSlow 12s linear infinite;
        }
        .ring-4 {
            width: 200px;
            height: 200px;
            border-color: rgba(0,201,167,0.15);
            animation: spinSlow 8s linear infinite reverse;
        }

        /* Ring dots - orbiting elements */
        .ring-dot {
            position: absolute;
            width: 6px;
            height: 6px;
            background: var(--hero-accent);
            border-radius: 50%;
            box-shadow: 0 0 12px var(--hero-accent-glow), 0 0 24px rgba(0,201,167,0.15);
        }
        .ring-1 .ring-dot { top: -3px; left: 50%; }
        .ring-2 .ring-dot { bottom: -3px; right: 20%; }
        .ring-3 .ring-dot { top: 50%; left: -3px; }

        @keyframes spinSlow {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* ── Central Emblem ── */
        .central-emblem {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .emblem-glow {
            position: absolute;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0,201,167,0.2) 0%, transparent 70%);
            animation: pulseGlow 3s ease-in-out infinite;
        }

        .emblem-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(0,201,167,0.15), rgba(0,201,167,0.05));
            border: 1.5px solid rgba(0,201,167,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(20px);
            position: relative;
            z-index: 1;
        }

        .emblem-icon svg {
            width: 36px;
            height: 36px;
            fill: var(--hero-accent);
            filter: drop-shadow(0 0 8px rgba(0,201,167,0.4));
        }

        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0.6; }
        }

        /* ── Hero Text Content (TOP) ── */
        .hero-content {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 5;
            padding: 40px 48px 80px;
            background: linear-gradient(180deg, rgba(6,15,29,0.95) 0%, rgba(6,15,29,0.6) 60%, transparent 100%);
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(0,201,167,0.1);
            border: 1px solid rgba(0,201,167,0.25);
            border-radius: 100px;
            padding: 7px 16px;
            font-size: 11px;
            font-weight: 700;
            color: var(--hero-accent);
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 20px;
            animation: fadeSlideDown 0.6s ease both;
        }
        .badge-dot {
            width: 7px; height: 7px;
            background: var(--hero-accent);
            border-radius: 50%;
            animation: pulseDot 1.8s ease infinite;
        }
        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.5); }
        }

        .hero-title {
            font-size: clamp(36px, 4.5vw, 64px);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -1.5px;
            color: var(--hero-text);
            margin-bottom: 14px;
            animation: fadeSlideDown 0.7s ease 0.1s both;
        }
        .hero-title .accent {
            display: block;
            color: var(--hero-accent-light);
            text-shadow: 
                0 0 8px rgba(0, 245, 224, 0.6),
                0 0 16px rgba(0, 245, 224, 0.4),
                0 0 32px rgba(0, 201, 167, 0.3);
            animation: pulseNeon 3s ease-in-out infinite;
        }
        @keyframes pulseNeon {
            0%, 100% {
                text-shadow: 
                    0 0 8px rgba(0, 245, 224, 0.6),
                    0 0 16px rgba(0, 245, 224, 0.4),
                    0 0 32px rgba(0, 201, 167, 0.3);
            }
            50% {
                text-shadow: 
                    0 0 12px rgba(0, 245, 224, 0.8),
                    0 0 24px rgba(0, 245, 224, 0.6),
                    0 0 48px rgba(0, 201, 167, 0.5);
            }
        }

        .hero-description {
            font-size: 16px;
            color: var(--hero-text-soft);
            line-height: 1.65;
            max-width: 500px;
            text-align: justify;
            animation: fadeSlideDown 0.7s ease 0.2s both;
        }

        @keyframes fadeSlideDown {
            from { opacity: 0; transform: translateY(-16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Bottom Stats Bar ── */
        .hero-bottom-stats {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 5;
            padding: 0 36px 32px;
            background: linear-gradient(0deg, rgba(6,15,29,0.9) 0%, rgba(6,15,29,0.5) 60%, transparent 100%);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .stat-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 14px;
            padding: 18px 16px;
            text-align: center;
            backdrop-filter: blur(12px);
            transition: border-color 0.3s, transform 0.3s, background 0.3s;
            cursor: default;
            position: relative;
            overflow: hidden;
            animation: fadeSlideUp 0.6s ease both;
        }
        .stat-card:nth-child(1) { animation-delay: 0.3s; }
        .stat-card:nth-child(2) { animation-delay: 0.45s; }
        .stat-card:nth-child(3) { animation-delay: 0.6s; }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--hero-accent), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .stat-card:hover {
            border-color: rgba(0,201,167,0.25);
            transform: translateY(-3px);
            background: rgba(255,255,255,0.06);
        }
        .stat-card:hover::before { opacity: 1; }

        .stat-icon {
            width: 32px; height: 32px;
            background: rgba(0,201,167,0.1);
            border: 1px solid rgba(0,201,167,0.15);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 10px;
        }
        .stat-icon svg { width: 16px; height: 16px; fill: var(--hero-accent); }

        .stat-value {
            font-size: 14px;
            font-weight: 800;
            color: var(--hero-text);
            display: block;
            margin-bottom: 4px;
        }
        .stat-label {
            font-size: 10px;
            font-weight: 600;
            color: var(--hero-text-muted);
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Scanning Light Effect ── */
        .scan-line {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent 0%, rgba(0,201,167,0.04) 40%, rgba(0,201,167,0.08) 50%, rgba(0,201,167,0.04) 60%, transparent 100%);
            z-index: 1;
            animation: scanMove 6s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes scanMove {
            0% { left: -100%; }
            50% { left: 100%; }
            100% { left: 100%; }
        }

        /* ── Floating Orbs (subtle) ── */
        .float-orb {
            position: absolute;
            border-radius: 50%;
            z-index: 1;
            pointer-events: none;
        }
        .float-orb-1 {
            width: 6px; height: 6px;
            background: var(--hero-accent);
            top: 25%; left: 15%;
            opacity: 0.4;
            animation: floatOrb 8s ease-in-out infinite;
            box-shadow: 0 0 12px rgba(0,201,167,0.3);
        }
        .float-orb-2 {
            width: 4px; height: 4px;
            background: var(--hero-accent-light);
            top: 35%; right: 12%;
            opacity: 0.3;
            animation: floatOrb 10s ease-in-out infinite reverse;
            box-shadow: 0 0 8px rgba(0,245,224,0.3);
        }
        .float-orb-3 {
            width: 5px; height: 5px;
            background: #818cf8;
            bottom: 30%; left: 20%;
            opacity: 0.25;
            animation: floatOrb 9s ease-in-out infinite;
            animation-delay: -3s;
            box-shadow: 0 0 10px rgba(129,140,248,0.3);
        }
        .float-orb-4 {
            width: 3px; height: 3px;
            background: #fbbf24;
            bottom: 40%; right: 18%;
            opacity: 0.3;
            animation: floatOrb 7s ease-in-out infinite reverse;
            animation-delay: -2s;
            box-shadow: 0 0 8px rgba(251,191,36,0.3);
        }
        .float-orb-5 {
            width: 4px; height: 4px;
            background: var(--hero-accent);
            top: 55%; left: 70%;
            opacity: 0.2;
            animation: floatOrb 11s ease-in-out infinite;
            animation-delay: -5s;
            box-shadow: 0 0 10px rgba(0,201,167,0.2);
        }

        @keyframes floatOrb {
            0%, 100% { transform: translate(0, 0); }
            25% { transform: translate(12px, -18px); }
            50% { transform: translate(-8px, -30px); }
            75% { transform: translate(15px, -12px); }
        }

        /* ═══════════════════════════════════════
           RIGHT — FORM PANEL (Original Design)
        ═══════════════════════════════════════ */
        .auth-form-section {
            flex: 40;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
            z-index: 10;
        }

        .auth-form-wrapper {
            width: 100%;
            max-width: 420px;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .auth-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
        }

        .auth-logo-icon {
            width: 54px;
            height: 54px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-logo-icon img { width: 100%; height: 100%; object-fit: contain; }
        .auth-logo span { font-size: 24px; font-weight: 800; color: var(--primary); letter-spacing: -0.5px; }

        .auth-header h2 { font-size: 36px; font-weight: 800; color: var(--text); margin-bottom: 12px; letter-spacing: -1px; }
        .auth-header p { color: var(--text-muted); font-size: 16px; margin-bottom: 32px; }

        /* Role Tabs */
        .role-selector {
            display: flex;
            background: #f1f5f9;
            padding: 6px;
            border-radius: 14px;
            margin-bottom: 28px;
        }

        .role-tab {
            flex: 1;
            padding: 12px;
            border: none;
            background: transparent;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-muted);
            cursor: pointer;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .role-tab.active {
            background: #fff;
            color: var(--primary);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .role-tab svg { width: 18px; height: 18px; fill: currentColor; }

        /* Form Inputs */
        .form-group { margin-bottom: 24px; }
        .form-label { display: block; font-size: 14px; font-weight: 700; color: var(--text); margin-bottom: 10px; }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            color: var(--text-muted);
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            height: 54px;
            padding: 0 16px 0 48px;
            border: 2px solid #f1f5f9;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.2s;
            background: #f8fafc;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(0,130,127,0.1);
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            display: flex;
            padding: 4px;
        }

        .btn-submit {
            width: 100%;
            height: 56px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(0,130,127,0.25);
            margin-top: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0,130,127,0.35);
        }

        .btn-submit:active { transform: translateY(0); }

        .auth-footer {
            margin-top: 40px;
            text-align: center;
            font-size: 15px;
            color: var(--text-muted);
        }

        .auth-footer a { color: var(--primary); text-decoration: none; font-weight: 700; }
        .auth-footer a:hover { text-decoration: underline; }

        /* Alerts */
        .alert {
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 28px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .alert-error { background: #fff1f2; color: #be123c; border: 1.5px solid #fecdd3; }
        .alert-success { background: #f0fdf4; color: #15803d; border: 1.5px solid #bbf7d0; }

        /* Keyframe animations */
        @keyframes fadeSlideDown {
            from { opacity: 0; transform: translateY(-16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive */
        @media (max-width: 1100px) {
            .hero-panel { display: none; }
            .auth-form-section { flex: 1; padding: 40px 24px; background: #f8fafc; }
            .auth-form-wrapper { background: #fff; padding: 40px; border-radius: 24px; box-shadow: var(--shadow); }
        }
    </style>
</head>
<body>

<div class="auth-container">

    <!-- ══════════ LEFT: HERO (Immersive) ══════════ -->
    <div class="hero-panel">
        <!-- Particle Background -->
        <canvas id="particleCanvas"></canvas>

        <!-- Scanning Light -->
        <div class="scan-line"></div>

        <!-- Floating Orbs -->
        <div class="float-orb float-orb-1"></div>
        <div class="float-orb float-orb-2"></div>
        <div class="float-orb float-orb-3"></div>
        <div class="float-orb float-orb-4"></div>
        <div class="float-orb float-orb-5"></div>

        <!-- Rotating Rings -->
        <div class="rings-container">
            <div class="ring ring-1"><span class="ring-dot"></span></div>
            <div class="ring ring-2"><span class="ring-dot"></span></div>
            <div class="ring ring-3"><span class="ring-dot"></span></div>
            <div class="ring ring-4"></div>
        </div>

        <!-- Central Emblem -->
        <div class="central-emblem">
            <div class="emblem-glow"></div>
            <div class="emblem-icon">
                <svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            </div>
        </div>

        <!-- Hero Text Content (TOP) -->
        <div class="hero-content">
            <div class="hero-badge">
                <span class="badge-dot"></span>
                Live Platform · Malaysia
            </div>

            <h1 class="hero-title">
                Empowering<br>
                <span class="accent">Communities,</span>
                Changing Lives.
            </h1>

            <p class="hero-description">
                CareConnect is more than a platform — it's a global movement for good. Join thousands making a real impact today.
            </p>
        </div>

        <!-- Bottom Features -->
        <div class="hero-bottom-stats">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                    </div>
                    <span class="stat-value">Official Events</span>
                    <span class="stat-label">Managed by URP</span>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                    </div>
                    <span class="stat-value">Secure Platform</span>
                    <span class="stat-label">Safe Donations</span>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                    </div>
                    <span class="stat-value">Live Tracking</span>
                    <span class="stat-label">Real-time Impact</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════ RIGHT: FORM (Original Design) ══════════ -->
    <div class="auth-form-section">
        <div class="auth-form-wrapper">
            <div class="auth-logo">
                <div class="auth-logo-icon">
                    <img src="{{ asset('images/logo.png') }}" alt="CareConnect">
                </div>
                <span>CareConnect</span>
            </div>

            <div class="auth-header">
                <h2>Welcome Back</h2>
                <p>Please log in to continue your impact journey.</p>
            </div>

            {{-- Alerts --}}
            @if (session('status'))
                <div class="alert alert-success">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <input type="hidden" name="role" id="roleInput" value="{{ old('role', 'admin') }}">

                <div class="role-selector">
                    <button type="button" class="role-tab {{ old('role', 'admin') === 'admin' ? 'active' : '' }}" data-role="admin">
                        <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
                        Admin
                    </button>
                    <button type="button" class="role-tab {{ old('role') === 'participant' ? 'active' : '' }}" data-role="participant">
                        <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        Participant
                    </button>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <input type="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="name@email.com" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <label class="form-label" style="margin-bottom: 0;">Password</label>
                        <a href="{{ url('/forgot-password') }}" style="font-size: 13px; color: var(--primary); text-decoration: none; font-weight: 700;">Forgot Password?</a>
                    </div>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Sign In to Account</button>
            </form>

            <div class="auth-footer">
                New to CareConnect? <a href="{{ url('/register') }}">Create an Account</a>
            </div>

            <p style="margin-top: 60px; font-size: 12px; color: #94a3b8; text-align: center; font-weight: 500;">
                CareConnect · Professional Ummah Management System<br>
                © {{ date('Y') }} All Rights Reserved
            </p>
        </div>
    </div>
</div>

<script>
    // Role tab switching
    document.querySelectorAll('.role-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById('roleInput').value = tab.dataset.role;
        });
    });

    // Password visibility toggle
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
        }
    }

    // Particle canvas
    (function() {
        const canvas = document.getElementById('particleCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let W, H, particles = [], mouse = { x: -9999, y: -9999 };

        function resize() {
            W = canvas.width  = canvas.offsetWidth;
            H = canvas.height = canvas.offsetHeight;
        }
        resize();
        window.addEventListener('resize', resize);

        canvas.parentElement.addEventListener('mousemove', e => {
            const r = canvas.getBoundingClientRect();
            mouse.x = e.clientX - r.left;
            mouse.y = e.clientY - r.top;
        });
        canvas.parentElement.addEventListener('mouseleave', () => {
            mouse.x = -9999; mouse.y = -9999;
        });

        const COLORS = ['rgba(0,201,167,', 'rgba(0,243,208,', 'rgba(255,255,255,'];

        for (let i = 0; i < 70; i++) {
            particles.push({
                x: Math.random() * 1200,
                y: Math.random() * 900,
                vx: (Math.random() - 0.5) * 0.35,
                vy: (Math.random() - 0.5) * 0.35,
                r: Math.random() * 1.6 + 0.4,
                a: Math.random() * 0.4 + 0.15,
                color: COLORS[Math.floor(Math.random() * COLORS.length)]
            });
        }

        function draw() {
            ctx.clearRect(0, 0, W, H);

            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx*dx + dy*dy);
                    if (dist < 110) {
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(0,201,167,${0.07 * (1 - dist/110)})`;
                        ctx.lineWidth = 0.5;
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
            }

            particles.forEach(p => {
                const dx = p.x - mouse.x, dy = p.y - mouse.y;
                const d = Math.sqrt(dx*dx + dy*dy);
                if (d < 100) {
                    const force = (100 - d) / 100 * 0.6;
                    p.vx += dx / d * force;
                    p.vy += dy / d * force;
                }

                p.vx *= 0.99; p.vy *= 0.99;
                p.x += p.vx; p.y += p.vy;
                if (p.x < 0) p.x = W;
                if (p.x > W) p.x = 0;
                if (p.y < 0) p.y = H;
                if (p.y > H) p.y = 0;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = p.color + p.a + ')';
                ctx.fill();
            });

            requestAnimationFrame(draw);
        }
        draw();
    })();


</script>

</body>
</html>