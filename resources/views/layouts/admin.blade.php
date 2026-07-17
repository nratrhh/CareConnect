<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CareConnect Admin — @yield('title', 'Dashboard')</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sb: #00827F;
            --sb-dark: #006b68;
            --accent: #00827F;
            --accent-light: #E6F2F2;
            --accent-dark: #006b68;
        }

        html, body {
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            color: #1a1a2e;
        }

        /* ─── Shell ─── */
        .admin-shell {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* ─── Sidebar ─── */
        .sidebar {
            width: 260px;
            background: var(--sb);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            transition: width 0.25s ease;
            overflow: hidden;
        }

        .sidebar.collapsed { width: 62px; }
        .sidebar.mobile-show { width: 260px !important; left: 0 !important; }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -260px;
                height: 100vh;
                z-index: 2000;
                box-shadow: 10px 0 30px rgba(0,0,0,0.1);
                transition: left 0.3s ease;
            }
            .sidebar.collapsed { width: 260px; left: -260px; }
        }

        .sidebar-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1999;
            display: none;
        }
        .sidebar-overlay.active { display: block; }

        /* Logo */
        .logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 16px;
            border-bottom: 0.5px solid rgba(255,255,255,0.08);
            min-height: 68px;
            flex-shrink: 0;
        }

        .logo-img {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .logo-img img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .logo-text { overflow: hidden; white-space: nowrap; }
        .logo-name { font-size: 16px; font-weight: 700; color: #fff; }
        .logo-sub  { font-size: 11px; color: rgba(255,255,255,0.55); margin-top: 2px; }

        /* Nav */
        .nav { flex: 1; padding: 14px 10px; overflow: hidden; }

        .nav-section {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.3);
            padding: 10px 12px 6px;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar.collapsed .nav-section { opacity: 0; }
        .sidebar.collapsed .nav-item,
        .sidebar.collapsed .footer-item { padding: 12px 10px; }
        .sidebar.collapsed .logo-area { padding-left: 8.5px; padding-right: 8.5px; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 8px;
            cursor: pointer;
            color: rgba(255,255,255,0.6);
            font-size: 15.5px;
            white-space: nowrap;
            overflow: hidden;
            margin-bottom: 3px;
            transition: background 0.15s, color 0.15s;
            text-decoration: none;
        }

        .nav-item:hover { background: rgba(255,255,255,0.08); color: #fff; }

        .nav-item.active,
        .footer-item.active {
            background: #ffffff;
            color: var(--sb);
            font-weight: 600;
        }

        .nav-item svg {
            width: 22px;
            height: 22px;
            fill: currentColor;
            flex-shrink: 0;
        }

        .nav-label { overflow: hidden; }

        /* Footer */
        .sidebar-footer {
            padding: 12px 10px;
            border-top: 0.5px solid rgba(255,255,255,0.08);
            flex-shrink: 0;
            overflow: hidden;
        }

        .footer-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 8px;
            cursor: pointer;
            color: rgba(255,255,255,0.6);
            font-size: 15.5px;
            white-space: nowrap;
            overflow: hidden;
            margin-bottom: 3px;
            transition: background 0.15s, color 0.15s;
            text-decoration: none;
        }

        .footer-item:hover { background: rgba(255,255,255,0.08); color: #fff; }

        .footer-item.logout:hover {
            background: rgba(220,53,69,0.18);
            color: #ff6b6b;
        }

        .footer-item svg {
            width: 22px;
            height: 22px;
            fill: currentColor;
            flex-shrink: 0;
        }

        /* ─── Main area ─── */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: #f0f2f5;
        }

        /* Topbar */
        .topbar {
            height: 64px;
            background: #fff;
            border-bottom: 0.5px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .topbar { padding: 0 16px; }
            .page-breadcrumb { display: none; }
        }

        .topbar-left { display: flex; align-items: center; gap: 16px; }

        .toggle-btn {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            border: 0.5px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: transparent;
            transition: background 0.15s;
        }

        .toggle-btn:hover { background: #f0f2f5; }
        .toggle-btn svg { width: 20px; height: 20px; fill: #64748b; }

        .page-breadcrumb { font-size: 15px; color: #64748b; }
        .page-breadcrumb span { color: var(--sb); font-weight: 500; }

        .topbar-right { display: flex; align-items: center; gap: 10px; position: relative; }

        .notif-btn {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            border: 0.5px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: transparent;
            position: relative;
        }

        .notif-btn:hover { background: #f0f2f5; }
        .notif-btn svg { width: 20px; height: 20px; fill: #64748b; }

        .notif-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent);
            border: 1.5px solid #fff;
        }

        .avatar-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--sb);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            cursor: pointer;
            border: 2px solid var(--accent);
        }

        /* Dropdown */
        .dropdown {
            position: absolute;
            top: 44px;
            right: 0;
            background: #fff;
            border: 0.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 6px;
            min-width: 180px;
            z-index: 999;
            display: none;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }

        .dropdown.show { display: block; }

        .dd-header {
            padding: 10px 12px 8px;
            border-bottom: 0.5px solid #e2e8f0;
            margin-bottom: 4px;
        }

        .dd-name { font-size: 13px; font-weight: 600; color: #1a1a2e; }
        .dd-role { font-size: 11px; color: #64748b; margin-top: 2px; }

        .dd-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 10px;
            border-radius: 7px;
            font-size: 13px;
            color: #1a1a2e;
            cursor: pointer;
            text-decoration: none;
        }

        .dd-item:hover { background: #f0f2f5; }
        .dd-item svg { width: 15px; height: 15px; fill: #64748b; flex-shrink: 0; }

        .dd-divider { height: 0.5px; background: #e2e8f0; margin: 4px 0; }

        .dd-logout { color: #e24b4a; }
        .dd-logout svg { fill: #e24b4a; }

        /* ─── Page content ─── */
        .page-content {
            flex: 1;
            overflow-y: auto;
            padding: 32px 36px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        @media (max-width: 768px) {
            .page-content { padding: 20px 16px; }
        }

        /* ─── Reusable card ─── */
        .card {
            background: #fff;
            border: 0.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px 22px;
        }

        .card-title {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 16px;
        }

        /* ─── Badges ─── */
        .badge {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 999px;
            font-weight: 500;
            white-space: nowrap;
        }

        .badge-open     { background: #e0f7f5; color: #0F6E56; }
        .badge-full     { background: #FAEEDA; color: #633806; }
        .badge-closing  { background: #FCEBEB; color: #A32D2D; }
        .badge-pending  { background: #FAEEDA; color: #633806; }
        .badge-approved { background: #e0f7f5; color: #0F6E56; }
        .badge-rejected { background: #FCEBEB; color: #A32D2D; }

        /* ─── Scrollbar ─── */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
        /* ─── DataTables Custom Theme Overrides ─── */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 6px 12px;
            margin-left: 8px;
            outline: none;
            transition: border-color 0.2s;
            font-family: inherit;
        }
        .dataTables_wrapper .dataTables_filter input:focus { border-color: #00827F; }
        
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 4px 8px;
            outline: none;
            font-family: inherit;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 6px !important;
            border: 1px solid transparent !important;
            padding: 5px 12px !important;
            margin: 0 2px !important;
            font-size: 13px !important;
            color: #64748b !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f1f5f9 !important;
            border: 1px solid #e2e8f0 !important;
            color: #1e293b !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #00827F !important;
            color: #fff !important;
            border: 1px solid #00827F !important;
        }
        
        table.dataTable.no-footer {
            border-bottom: 1px solid #e2e8f0 !important;
        }
        table.dataTable thead th {
            border-bottom: 1px solid #e2e8f0 !important;
            color: #64748b;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        /* Table Row Hover Effect */
        table.dataTable tbody tr {
            transition: background-color 0.15s ease;
        }
        table.dataTable tbody tr:hover {
            background-color: #f1f5f9 !important; /* Light gray/blue hover */
        }

        .dataTables_info {
            font-size: 13px;
            color: #64748b !important;
        }

        /* ─── Toast Notification ─── */
        .toast-container {
            position: fixed;
            top: 24px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: center;
        }

        .toast-msg {
            min-width: 320px;
            max-width: 400px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            padding: 16px 20px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            transform: translateY(-150%);
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            border-left: 5px solid var(--accent);
            position: relative;
            overflow: hidden;
        }

        .toast-msg.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast-msg.hide {
            transform: translateY(-150%);
            opacity: 0;
        }

        .toast-icon {
            flex-shrink: 0;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 2px;
        }

        .toast-success .toast-icon {
            background: #e0f7f5;
            color: #0F6E56;
        }

        .toast-error {
            border-left-color: #ef4444;
        }

        .toast-error .toast-icon {
            background: #fee2e2;
            color: #ef4444;
        }

        .toast-content {
            flex: 1;
        }

        .toast-title {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 4px;
        }

        .toast-message {
            font-size: 13px;
            color: #64748b;
            line-height: 1.4;
        }

        .toast-close {
            background: none;
            border: none;
            color: #cbd5e1;
            cursor: pointer;
            transition: color 0.2s;
            padding: 4px;
            margin-top: -2px;
            margin-right: -4px;
        }

        .toast-close:hover {
            color: #64748b;
        }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            background: var(--accent);
            width: 100%;
            transform-origin: left;
            animation: toast-progress 3s linear forwards;
        }

        .toast-error .toast-progress {
            background: #ef4444;
        }

        @keyframes toast-progress {
            100% { transform: scaleX(0); }
        }
    </style>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    @stack('styles')
</head>
<body>

<div class="admin-shell">
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- ═══ SIDEBAR ═══ --}}
    <div class="sidebar" id="adminSidebar">

        {{-- Logo --}}
        <div class="logo-area">
            <div class="logo-img">
                <img src="{{ asset('images/logo.png') }}" alt="Ummah Relief Project Logo">
            </div>
            <div class="logo-text" id="logoText">
                <div class="logo-name">CareConnect</div>
                <div class="logo-sub">Ummah Relief Project</div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="nav">
            <div class="nav-section">Menu</div>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                <span class="nav-label">Dashboard</span>
            </a>

            <a href="{{ route('admin.events.index') }}"
               class="nav-item {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
                <span class="nav-label">Events</span>
            </a>

            <a href="{{ route('admin.applications.index') }}"
               class="nav-item {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" style="fill:none;" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span class="nav-label">Volunteer Applications</span>
            </a>

            <a href="{{ route('admin.fundraising.index') }}"
               class="nav-item {{ request()->routeIs('admin.fundraising.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" style="fill:none;" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M15.4 9.1a2.82 2.82 0 0 0-.87-2.15c-1.43-1.42-3.81-1.33-5.14.2l-4.1 4.1C3.12 13.43 2 16.35 2 19.5v.5c0 1.1.9 2 2 2h13.2a2 2 0 0 0 1.96-1.59l1.41-7a2 2 0 0 0-1.96-2.41H15.4Z"/></svg>
                <span class="nav-label">Fundraising</span>
            </a>

            <a href="{{ route('admin.reports.index') }}"
               class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" style="fill:none;" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="8" y1="18" x2="8" y2="15"/><line x1="16" y1="18" x2="16" y2="14"/></svg>
                <span class="nav-label">Reports</span>
            </a>
        </nav>

        {{-- Footer --}}
        <div class="sidebar-footer">
            <a href="{{ route('admin.profile') }}" class="footer-item {{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                <span class="nav-label">My Profile</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="footer-item logout" style="width:100%;border:none;cursor:pointer;background:transparent;">
                    <svg viewBox="0 0 24 24"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
                    <span class="nav-label">Logout</span>
                </button>
            </form>
        </div>
    </div>

    {{-- ═══ MAIN ═══ --}}
    <div class="main">

        {{-- Topbar --}}
        <div class="topbar">
            <div class="topbar-left">
                <button class="toggle-btn" onclick="toggleSidebar()" type="button">
                    <svg viewBox="0 0 24 24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>
                </button>
                <div class="page-breadcrumb">
                    Admin &rsaquo; <span>@yield('title', 'Dashboard')</span>
                </div>
            </div>

            <div class="topbar-right">
                {{-- Notification Bell --}}
                <div style="position: relative;" id="notifWrapper">
                    <button class="notif-btn" onclick="toggleNotifDropdown()" type="button" id="notifBellBtn">
                        <svg viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
                        <div class="notif-dot" id="adminNotifDot" style="display:none;"></div>
                        <span id="adminNotifCount" style="display:none;position:absolute;top:2px;right:2px;min-width:18px;height:18px;background:#ef4444;color:#fff;font-size:10px;font-weight:700;border-radius:99px;display:none;align-items:center;justify-content:center;border:2px solid #fff;padding:0 4px;line-height:1;"></span>
                    </button>

                    {{-- Notification Dropdown --}}
                    <div id="adminNotifDropdown" style="display:none;position:absolute;top:48px;right:0;width:380px;max-height:480px;background:#fff;border:1px solid #e2e8f0;border-radius:16px;box-shadow:0 20px 40px rgba(0,0,0,0.12);z-index:1000;overflow:hidden;">
                        <div style="padding:16px 20px 12px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
                            <div style="font-size:16px;font-weight:700;color:#1a1a2e;">Notifications</div>
                            <button onclick="markAllReadAdmin()" id="markAllReadBtn" style="background:none;border:none;font-size:12px;color:var(--accent);cursor:pointer;font-weight:600;display:none;">Mark all read</button>
                        </div>
                        <div id="adminNotifList" style="overflow-y:auto;max-height:380px;padding:8px;">
                            <div style="text-align:center;padding:40px 20px;color:#94a3b8;font-size:13px;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" style="margin-bottom:8px;"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
                                <div>No notifications yet</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="avatar-btn" onclick="toggleDropdown()">
                    {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                </div>

                <div class="dropdown" id="adminDropdown">
                    <div class="dd-header">
                        <div class="dd-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                        <div class="dd-role">Administrator</div>
                    </div>
                    <a href="{{ route('admin.profile') }}" class="dd-item">
                        <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        My Profile
                    </a>
                    <div class="dd-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dd-item dd-logout" style="width:100%;border:none;background:transparent;cursor:pointer;">
                            <svg viewBox="0 0 24 24"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Page Content --}}
        <div class="page-content">
            @yield('content')
        </div>

    </div>
</div>

{{-- ═══ GLOBAL TOAST NOTIFICATIONS ═══ --}}
<div class="toast-container" id="globalToastContainer">
    @if(session('success'))
        <div class="toast-msg toast-success" id="toastSuccess">
            <div class="toast-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <div class="toast-content">
                <div class="toast-title">Success</div>
                <div class="toast-message">{{ session('success') }}</div>
            </div>
            <button class="toast-close" onclick="closeToast('toastSuccess')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
            <div class="toast-progress"></div>
        </div>
    @endif

    @if(session('error'))
        <div class="toast-msg toast-error" id="toastError">
            <div class="toast-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
            </div>
            <div class="toast-content">
                <div class="toast-title">Error</div>
                <div class="toast-message">{{ session('error') }}</div>
            </div>
            <button class="toast-close" onclick="closeToast('toastError')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
            <div class="toast-progress"></div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toasts = document.querySelectorAll('.toast-msg');
        toasts.forEach(toast => {
            // Slight delay for entry animation to look smoother
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);

            // Auto dismiss after 3 seconds
            setTimeout(() => {
                closeToast(toast.id);
            }, 3000);
        });
    });

    function closeToast(id) {
        const toast = document.getElementById(id);
        if (toast) {
            toast.classList.remove('show');
            toast.classList.add('hide');
            setTimeout(() => toast.remove(), 500); // Remove from DOM after animation
        }
    }

    function toggleSidebar() {
        if (window.innerWidth <= 768) {
            document.getElementById('adminSidebar').classList.toggle('mobile-show');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        } else {
            document.getElementById('adminSidebar').classList.toggle('collapsed');
        }
    }

    function toggleDropdown() {
        document.getElementById('adminDropdown').classList.toggle('show');
        // Close notification dropdown when profile dropdown opens
        document.getElementById('adminNotifDropdown').style.display = 'none';
    }

    function toggleNotifDropdown() {
        var dd = document.getElementById('adminNotifDropdown');
        dd.style.display = dd.style.display === 'none' ? 'block' : 'none';
        // Close profile dropdown
        document.getElementById('adminDropdown').classList.remove('show');
        // Fetch latest
        if (dd.style.display === 'block') fetchAdminNotifications();
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.topbar-right')) {
            document.getElementById('adminDropdown').classList.remove('show');
            document.getElementById('adminNotifDropdown').style.display = 'none';
        }
    });

    // ─── Notification System ───
    const NOTIF_ICONS = {
        'new_application': '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#00827F" stroke-width="2" stroke-linecap="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>',
        'application_cancelled': '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#e24b4a" stroke-width="2" stroke-linecap="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="18" y1="8" x2="23" y2="13"/><line x1="23" y1="8" x2="18" y2="13"/></svg>',
        'new_donation': '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0F6E56" stroke-width="2" stroke-linecap="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
    };

    function getNotifIcon(type) {
        return NOTIF_ICONS[type] || '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>';
    }

    function timeAgo(dateStr) {
        var d = new Date(dateStr);
        var now = new Date();
        var diff = Math.floor((now - d) / 1000);
        if (diff < 60) return 'Just now';
        if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
        if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
        if (diff < 604800) return Math.floor(diff / 86400) + 'd ago';
        return d.toLocaleDateString('en-MY', { day: 'numeric', month: 'short' });
    }

    function renderAdminNotifications(data) {
        var list = document.getElementById('adminNotifList');
        var badge = document.getElementById('adminNotifCount');
        var dot = document.getElementById('adminNotifDot');
        var markAllBtn = document.getElementById('markAllReadBtn');

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
            var borderLeft = isUnread ? '3px solid var(--accent)' : '3px solid transparent';

            html += '<div class="notif-item" onclick="clickNotif(' + n.notification_id + ', \'' + (n.action_url || '') + '\')" style="display:flex;gap:12px;padding:12px 14px;border-radius:10px;cursor:pointer;transition:background 0.15s;background:' + bgColor + ';border-left:' + borderLeft + ';margin-bottom:4px;" onmouseover="this.style.background=\'#f1f5f9\'" onmouseout="this.style.background=\'' + bgColor + '\'">';
            html += '<div style="flex-shrink:0;width:36px;height:36px;border-radius:10px;background:#f8fafc;display:flex;align-items:center;justify-content:center;border:1px solid #e2e8f0;">' + getNotifIcon(n.type) + '</div>';
            html += '<div style="flex:1;min-width:0;">';
            html += '<div style="font-size:13px;font-weight:' + (isUnread ? '600' : '500') + ';color:#1a1a2e;line-height:1.3;">' + n.title + '</div>';
            html += '<div style="font-size:12px;color:#64748b;margin-top:2px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">' + n.message + '</div>';
            html += '<div style="font-size:11px;color:#94a3b8;margin-top:4px;">' + timeAgo(n.created_at) + '</div>';
            html += '</div>';
            if (isUnread) html += '<div style="flex-shrink:0;width:8px;height:8px;background:var(--accent);border-radius:50%;margin-top:4px;"></div>';
            html += '</div>';
        });
        list.innerHTML = html;
    }

    function fetchAdminNotifications() {
        fetch('{{ route("admin.notifications.index") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) { renderAdminNotifications(data); })
        .catch(function(err) { console.error('Notification fetch error:', err); });
    }

    function clickNotif(id, url) {
        fetch(`{{ url('/admin/notifications') }}/${id}/read`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        }).then(function() {
            if (url) window.location.href = url;
            else fetchAdminNotifications();
        });
    }

    function markAllReadAdmin() {
        fetch('{{ route("admin.notifications.readAll") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        }).then(function() { fetchAdminNotifications(); });
    }

    // Fetch on page load & auto-refresh every 30s
    document.addEventListener('DOMContentLoaded', function() {
        fetchAdminNotifications();
        setInterval(fetchAdminNotifications, 30000);
    });
</script>

<!-- jQuery & DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize all tables with class 'data-table'
        $('.data-table').DataTable({
            "pageLength": 10,
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries"
            }
        });
    });
</script>

@stack('scripts')

</body>
</html>