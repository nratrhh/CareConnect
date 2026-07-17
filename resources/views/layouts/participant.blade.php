<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CareConnect — @yield('title', 'Participant')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #00827F;
            --primary-dark: #006b68;
            --primary-light: #E6F2F2;
            --secondary: #64748B;
            --dark: #1a1a2e;
            --light: #F0FCFB; /* Light mint background from landing page */
            --white: #FFFFFF;
            --border: #E2E8F0;
            --success: #0F6E56;
            --warning: #BA7517;
            --danger: #e24b4a;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            overflow-x: hidden;
            width: 100%;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ─── Header ─── */
        header {
            background: #0B132B;
            border-bottom: none;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            flex: 1;
        }

        .logo-text {
            font-size: 22px;
            font-weight: 700;
            color: var(--white);
            letter-spacing: -0.5px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
            flex: 2;
            justify-content: center;
            height: 100%;
        }

        .nav-link {
            text-decoration: none;
            color: rgba(255, 255, 255, 0.9);
            font-size: 15px;
            font-weight: 600;
            transition: all 0.2s;
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 4px;
            position: relative;
            border-bottom: 2px solid transparent;
        }

        .nav-link:hover {
            color: #00A896;
        }

        .nav-link.active {
            color: #00A896;
            border-bottom-color: #00A896;
        }

        /* Mobile Menu Toggle */
        .mobile-toggle {
            display: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: var(--white);
            cursor: pointer;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        @media (max-width: 992px) {
            .mobile-toggle { display: flex; }
            .nav-links {
                display: none; /* Hide desktop nav */
            }
            .nav-container { height: 70px; }
            .logo-text { font-size: 18px; }
            .logo img { height: 35px; }
        }

        /* Mobile Navigation Overlay */
        .mobile-nav {
            position: fixed;
            top: 0;
            left: -100%;
            width: 280px;
            height: 100vh;
            background: var(--white);
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 10px 0 30px rgba(0,0,0,0.1);
            padding: 30px 24px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .mobile-nav.active {
            left: 0;
        }

        .mobile-nav-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }

        .mobile-nav-overlay.active {
            display: block;
        }

        .mobile-nav-link {
            text-decoration: none;
            color: var(--secondary);
            font-size: 16px;
            font-weight: 600;
            padding: 12px 16px;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .mobile-nav-link.active {
            background: var(--primary-light);
            color: var(--primary);
        }

        .mobile-nav-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .mobile-nav-close {
            background: none;
            border: none;
            font-size: 24px;
            color: var(--secondary);
            cursor: pointer;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 24px;
            flex: 1;
            justify-content: flex-end;
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            cursor: pointer;
            color: var(--white);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .icon-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: var(--white);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .notif-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 9px;
            height: 9px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid #0B132B;
        }

        .profile-dropdown {
            position: relative;
        }

        .profile-btn {
            background: transparent !important;
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 50%;
        }
        
        .profile-btn:focus, .profile-btn:active {
            outline: none !important;
            box-shadow: none !important;
            border: none !important;
        }

        .profile-avatar {
            width: 42px;
            min-width: 42px;
            height: 42px;
            min-height: 42px;
            border-radius: 50%;
            border: 2px solid var(--primary) !important;
            padding: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: white;
            box-shadow: none !important;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-placeholder {
            width: 100%;
            height: 100%;
            background: #f1f5f9;
            color: #335c67;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255,255,255,0.2);
            background: transparent;
            cursor: pointer;
            color: rgba(255,255,255,0.8);
            transition: all 0.2s;
            position: relative;
        }

        .icon-btn:hover {
            border-color: rgba(255,255,255,0.4);
            color: var(--white);
            background: rgba(255,255,255,0.1);
        }

        .notif-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: var(--danger);
            border-radius: 50%;
            border: 2px solid var(--white);
        }

        .profile-dropdown {
            position: relative;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 4px 4px 12px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.2);
            background: transparent;
            cursor: pointer;
            transition: all 0.2s;
        }

        .profile-btn:hover {
            border-color: rgba(255,255,255,0.4);
            background: rgba(255,255,255,0.1);
        }

        .profile-avatar {
            width: 38px;
            height: 38px;
            background: var(--white);
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
        }

        .profile-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--white);
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            width: 220px;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            padding: 8px;
            display: none;
            z-index: 1000;
        }

        .dropdown-menu.show {
            display: block;
            animation: slideDown 0.2s ease-out;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-header {
            padding: 12px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 8px;
        }

        .dd-user-name { font-weight: 700; font-size: 14px; }
        .dd-user-role { font-size: 11px; color: var(--secondary); margin-top: 2px; }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--dark);
            font-size: 14px;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: var(--light);
        }

        .dropdown-item.logout {
            color: var(--danger);
        }

        .dropdown-item.logout:hover {
            background: #FEF2F2;
        }

        /* ─── Main Content ─── */
        main {
            flex: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 24px;
            width: 100%;
        }

        /* ─── Components ─── */
        .card {
            background: var(--white);
            border-radius: 16px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .badge {
            display: inline-flex;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            text-decoration: none;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .btn-yellow {
            background: #F59E0B;
            color: var(--white);
        }

        .btn-yellow:hover {
            background: #D97706;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-outline {
            background: var(--white);
            border: 1px solid var(--border);
            color: var(--secondary);
        }

        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }

        /* Alerts */
        .alert {
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
        .alert-error { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }

        /* ─── Toast Notification ─── */
        .toast-notification {
            position: fixed;
            top: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(-20px);
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            padding: 16px 20px;
            gap: 16px;
            z-index: 9999;
            min-width: 320px;
            max-width: 450px;
            opacity: 0;
            pointer-events: none;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
        }

        .toast-notification.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        .toast-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .toast-success .toast-icon { background: #E6F2F2; color: #00827F; }
        .toast-error .toast-icon { background: #FCEBEB; color: #A32D2D; }

        .toast-content { flex: 1; }
        .toast-title { font-weight: 700; font-size: 15px; color: #1a1a2e; margin-bottom: 2px; }
        .toast-message { font-size: 13px; color: #64748b; line-height: 1.4; }

        .toast-close {
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 20px;
            cursor: pointer;
            padding: 4px;
            margin: -4px;
            border-radius: 6px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toast-close:hover { background: #f1f5f9; color: #1a1a2e; }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            background: #00827F;
            width: 100%;
            transform-origin: left;
            animation: toast-progress 3s linear forwards;
        }

        .toast-error .toast-progress { background: #A32D2D; }

        @keyframes toast-progress {
            0% { transform: scaleX(1); }
            100% { transform: scaleX(0); }
        }

        @stack('styles')
    </style>
</head>
<body>

    <header>
        <div class="nav-container">
            <div style="display: flex; align-items: center;">
                <button class="mobile-toggle" onclick="toggleMobileNav()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
                <a href="{{ route('participant.dashboard') }}" class="logo" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
                    <img src="{{ asset('images/logo.png') }}" alt="CareConnect Logo" style="height: 45px; width: auto; object-fit: contain;">
                    <span class="logo-text">CareConnect</span>
                </a>
            </div>

            <nav class="nav-links">
                <a href="{{ route('participant.dashboard') }}" class="nav-link {{ request()->routeIs('participant.dashboard') ? 'active' : '' }}">Home</a>
                <a href="{{ route('participant.events.index') }}" class="nav-link {{ request()->routeIs('participant.events.*') ? 'active' : '' }}">Events</a>
                <a href="{{ route('participant.activities.index') }}" class="nav-link {{ request()->routeIs('participant.activities.*') ? 'active' : '' }}">My Activities</a>
            </nav>
            
            {{-- Mobile Nav Side Panel --}}
            <div class="mobile-nav-overlay" id="mobileNavOverlay" onclick="toggleMobileNav()"></div>
            <div class="mobile-nav" id="mobileNav">
                <div class="mobile-nav-header">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 30px;">
                        <span style="font-weight: 700; color: var(--primary);">CareConnect</span>
                    </div>
                    <button class="mobile-nav-close" onclick="toggleMobileNav()">&times;</button>
                </div>
                <a href="{{ route('participant.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('participant.dashboard') ? 'active' : '' }}">Home</a>
                <a href="{{ route('participant.events.index') }}" class="mobile-nav-link {{ request()->routeIs('participant.events.*') ? 'active' : '' }}">Events</a>
                <a href="{{ route('participant.activities.index') }}" class="mobile-nav-link {{ request()->routeIs('participant.activities.*') ? 'active' : '' }}">My Activities</a>
                <hr style="border: none; border-top: 1px solid var(--border); margin: 10px 0;">
                <a href="{{ route('participant.profile') }}" class="mobile-nav-link">My Profile</a>
                <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                    @csrf
                    <button type="submit" class="mobile-nav-link" style="width: 100%; border: none; background: none; text-align: left; cursor: pointer;">Log Out</button>
                </form>
            </div>

            <div class="nav-right">
                {{-- Notification Bell --}}
                <div style="position:relative;" id="pNotifWrapper">
                    <button class="icon-btn" onclick="togglePNotifDropdown()" type="button" id="pNotifBellBtn" style="background:#f8fafc;border:1px solid #e2e8f0;color:#335c67;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="#335c67" style="display:block;"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
                        <div class="notif-badge" id="pNotifDot" style="display:none;"></div>
                        <span id="pNotifCount" style="display:none;position:absolute;top:2px;right:2px;min-width:18px;height:18px;background:#ef4444;color:#fff;font-size:10px;font-weight:700;border-radius:99px;align-items:center;justify-content:center;border:2px solid #fff;padding:0 4px;line-height:1;"></span>
                    </button>

                    {{-- Notification Dropdown --}}
                    <div id="pNotifDropdown" style="display:none;position:absolute;top:52px;right:0;width:380px;max-height:480px;background:#fff;border:1px solid #e2e8f0;border-radius:16px;box-shadow:0 20px 40px rgba(0,0,0,0.12);z-index:1000;overflow:hidden;">
                        <div style="padding:16px 20px 12px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
                            <div style="font-size:16px;font-weight:700;color:#1a1a2e;">Notifications</div>
                            <button onclick="markAllReadParticipant()" id="pMarkAllReadBtn" style="background:none;border:none;font-size:12px;color:#00827F;cursor:pointer;font-weight:600;display:none;">Mark all read</button>
                        </div>
                        <div id="pNotifList" style="overflow-y:auto;max-height:380px;padding:8px;">
                            <div style="text-align:center;padding:40px 20px;color:#94a3b8;font-size:13px;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" style="margin-bottom:8px;"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
                                <div>No notifications yet</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-dropdown">
                    <button class="profile-btn" onclick="toggleDropdown()">
                        <div class="profile-avatar">
                            @if(Auth::guard('participant')->user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::guard('participant')->user()->profile_picture) }}" alt="Profile">
                            @else
                                <div class="profile-placeholder">
                                    {{ strtoupper(substr(Auth::guard('participant')->user()->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                    </button>
                    <div class="dropdown-menu" id="userDropdown">
                        <div class="dropdown-header">
                            <div class="dd-user-name">{{ Auth::guard('participant')->user()->name }}</div>
                            <div class="dd-user-role">Participant</div>
                        </div>
                        <a href="{{ route('participant.profile') }}" class="dropdown-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            My Profile
                        </a>
                        <div style="height: 1px; background: var(--border); margin: 4px 0;"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item logout" style="width: 100%; border: none; background: transparent; cursor: pointer; text-align: left; font-family: inherit;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        {{-- Global Toast Notification --}}
        @if(session('success'))
            <div class="toast-notification toast-success" id="globalToast">
                <div class="toast-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <div class="toast-content">
                    <div class="toast-title">Success</div>
                    <div class="toast-message">{{ session('success') }}</div>
                </div>
                <button class="toast-close" onclick="closeToast('globalToast')">&times;</button>
                <div class="toast-progress"></div>
            </div>
        @endif

        @if(session('error'))
            <div class="toast-notification toast-error" id="globalToastError">
                <div class="toast-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                </div>
                <div class="toast-content">
                    <div class="toast-title">Error</div>
                    <div class="toast-message">{{ session('error') }}</div>
                </div>
                <button class="toast-close" onclick="closeToast('globalToastError')">&times;</button>
                <div class="toast-progress"></div>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        // Toast logic
        function closeToast(id) {
            const toast = document.getElementById(id);
            if(toast) {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 400);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast-notification');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.classList.add('show');
                }, 100);

                setTimeout(() => {
                    closeToast(toast.id);
                }, 3000);
            });
        });
        function toggleDropdown() {
            document.getElementById('userDropdown').classList.toggle('show');
            document.getElementById('pNotifDropdown').style.display = 'none';
        }

        function togglePNotifDropdown() {
            var dd = document.getElementById('pNotifDropdown');
            dd.style.display = dd.style.display === 'none' ? 'block' : 'none';
            document.getElementById('userDropdown').classList.remove('show');
            if (dd.style.display === 'block') fetchParticipantNotifications();
        }

        window.onclick = function(event) {
            if (!event.target.closest('.profile-dropdown') && !event.target.closest('#pNotifWrapper')) {
                var dropdowns = document.getElementsByClassName("dropdown-menu");
                for (var i = 0; i < dropdowns.length; i++) {
                    if (dropdowns[i].classList.contains('show')) {
                        dropdowns[i].classList.remove('show');
                    }
                }
                document.getElementById('pNotifDropdown').style.display = 'none';
            }
        }

        // ─── Notification System ───
        const P_NOTIF_ICONS = {
            'application_approved': '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0F6E56" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
            'application_declined': '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#e24b4a" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
            'new_event': '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#00827F" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
            'donation_success': '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#e24b79" stroke-width="2" stroke-linecap="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
            'certificate_available': '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#BA7517" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>',
        };

        function getPNotifIcon(type) {
            return P_NOTIF_ICONS[type] || '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>';
        }

        function pTimeAgo(dateStr) {
            var d = new Date(dateStr);
            var now = new Date();
            var diff = Math.floor((now - d) / 1000);
            if (diff < 60) return 'Just now';
            if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
            if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
            if (diff < 604800) return Math.floor(diff / 86400) + 'd ago';
            return d.toLocaleDateString('en-MY', { day: 'numeric', month: 'short' });
        }

        function renderPNotifications(data) {
            var list = document.getElementById('pNotifList');
            var badge = document.getElementById('pNotifCount');
            var dot = document.getElementById('pNotifDot');
            var markAllBtn = document.getElementById('pMarkAllReadBtn');

            if (data.unread_count > 0) {
                badge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
                badge.style.display = 'flex';
                dot.style.display = 'block';
                markAllBtn.style.display = 'block';
            } else {
                badge.style.display = 'none';
                dot.style.display = 'none';
                markAllBtn.style.display = 'none';
            }

            if (!data.notifications || data.notifications.length === 0) {
                list.innerHTML = '<div style="text-align:center;padding:40px 20px;color:#94a3b8;font-size:13px;"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" style="margin-bottom:8px;"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg><div>No notifications yet</div></div>';
                return;
            }

            var html = '';
            data.notifications.forEach(function(n) {
                var isUnread = !n.read_at;
                var bgColor = isUnread ? '#f0faf9' : '#fff';
                var borderLeft = isUnread ? '3px solid #00827F' : '3px solid transparent';

                html += '<div onclick="pClickNotif(' + n.notification_id + ', \'' + (n.action_url || '') + '\')" style="display:flex;gap:12px;padding:12px 14px;border-radius:10px;cursor:pointer;transition:background 0.15s;background:' + bgColor + ';border-left:' + borderLeft + ';margin-bottom:4px;" onmouseover="this.style.background=\'#f1f5f9\'" onmouseout="this.style.background=\'' + bgColor + '\'">';
                html += '<div style="flex-shrink:0;width:36px;height:36px;border-radius:10px;background:#f8fafc;display:flex;align-items:center;justify-content:center;border:1px solid #e2e8f0;">' + getPNotifIcon(n.type) + '</div>';
                html += '<div style="flex:1;min-width:0;">';
                html += '<div style="font-size:13px;font-weight:' + (isUnread ? '600' : '500') + ';color:#1a1a2e;line-height:1.3;">' + n.title + '</div>';
                html += '<div style="font-size:12px;color:#64748b;margin-top:2px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">' + n.message + '</div>';
                html += '<div style="font-size:11px;color:#94a3b8;margin-top:4px;">' + pTimeAgo(n.created_at) + '</div>';
                html += '</div>';
                if (isUnread) html += '<div style="flex-shrink:0;width:8px;height:8px;background:#00827F;border-radius:50%;margin-top:4px;"></div>';
                html += '</div>';
            });
            list.innerHTML = html;
        }

        function fetchParticipantNotifications() {
            fetch('{{ route("participant.notifications.index") }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) { renderPNotifications(data); })
            .catch(function(err) { console.error('Notification fetch error:', err); });
        }

        function pClickNotif(id, url) {
            fetch(`{{ url('/participant/notifications') }}/${id}/read`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }).then(function() {
                if (url) window.location.href = url;
                else fetchParticipantNotifications();
            });
        }

        function markAllReadParticipant() {
            fetch('{{ route("participant.notifications.readAll") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }).then(function() { fetchParticipantNotifications(); });
        }

        // Fetch on page load & auto-refresh every 30s
        document.addEventListener('DOMContentLoaded', function() {
            fetchParticipantNotifications();
            setInterval(fetchParticipantNotifications, 30000);
        });

        function toggleMobileNav() {
            document.getElementById('mobileNav').classList.toggle('active');
            document.getElementById('mobileNavOverlay').classList.toggle('active');
            document.body.style.overflow = document.getElementById('mobileNav').classList.contains('active') ? 'hidden' : '';
        }
    </script>
    @stack('scripts')
</body>
</html>
