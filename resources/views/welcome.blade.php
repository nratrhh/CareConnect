<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CareConnect — Empowering Communities</title>
    <meta name="description" content="CareConnect is a professional Ummah management system for fundraising campaigns, volunteer coordination, and community impact.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #00A896;      /* Bright Teal */
            --primary-dark: #028090; /* Darker Teal */
            --primary-light: #E8F7F6;
            --dark: #1E293B;
            --text: #475569;
            --muted: #94A3B8;
            --border: #E2E8F0;
            --white: #FFFFFF;
            --bg-light: #F0FCFB; /* Very light mint background */
            --gold: #FBBF24;
        }
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text); background: var(--white); overflow-x: hidden; }
        a { text-decoration: none; color: inherit; }
        img { max-width: 100%; display: block; }

        /* ── Navbar ── */
        .navbar { position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; padding: 20px 0; transition: all 0.4s; background: transparent; border: none; }
        .navbar.scrolled { padding: 12px 0; background: #0B132B; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); }
        .nav-inner { max-width: 1200px; margin: 0 auto; padding: 0 32px; display: flex; align-items: center; justify-content: space-between; }
        .nav-logo { display: flex; align-items: center; gap: 12px; }
        .nav-logo img { width: 40px; height: 40px; object-fit: contain; }
        .nav-logo-text { display: flex; flex-direction: column; }
        .nav-logo-text .brand { font-size: 20px; font-weight: 800; color: var(--white); line-height: 1; transition: color 0.4s; }
        .nav-logo-text .sub { font-size: 10px; font-weight: 700; color: var(--primary); letter-spacing: 0.5px; text-transform: uppercase; margin-top: 4px; }
        
        .nav-links { display: flex; align-items: center; gap: 32px; }
        .nav-links a { font-size: 14px; font-weight: 600; color: rgba(255, 255, 255, 0.9); transition: color 0.3s; text-shadow: 0 1px 4px rgba(0,0,0,0.4); }
        .nav-links a:hover { color: var(--primary); }
        
        .nav-actions { display: flex; align-items: center; gap: 16px; }
        .btn-nav-outline { padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; border: 1px solid rgba(255,255,255,0.4); color: var(--white); transition: all 0.3s; }
        .btn-nav-outline:hover { background: rgba(255,255,255,0.1); border-color: var(--white); }
        
        .btn-nav-fill { padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; background: var(--primary); color: var(--white); transition: all 0.3s; border: 1px solid var(--primary); }
        .btn-nav-fill:hover { background: var(--primary-dark); border-color: var(--primary-dark); }

        /* ── Hero ── */
        .hero { min-height: 100vh; position: relative; display: flex; align-items: center; background: #002E2C; padding-top: 80px; }
        .hero-bg { position: absolute; inset: 0; z-index: 1; }
        .hero-bg img { width: 100%; height: 100%; object-fit: cover; opacity: 1.0; }
        .hero-overlay { position: absolute; inset: 0; background: linear-gradient(to right, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.2) 40%, transparent 100%), linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, transparent 20%); z-index: 2; }
        .hero-inner { position: relative; z-index: 3; max-width: 1200px; margin: 0 auto; padding: 60px 32px; width: 100%; }
        
        .hero-badge { display: inline-flex; align-items: center; gap: 8px; padding: 6px 16px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 999px; font-size: 13px; font-weight: 500; color: var(--primary-light); margin-bottom: 24px; }
        .hero-badge svg { width: 14px; height: 14px; fill: var(--primary); }
        
        .hero-content { max-width: 700px; animation: fadeUp 0.8s ease-out; }
        .hero h1 { font-size: clamp(40px, 5vw, 64px); font-weight: 800; color: #fff; line-height: 1.1; margin-bottom: 24px; letter-spacing: -1px; text-shadow: 0 2px 10px rgba(0,0,0,0.3); }
        .hero h1 span { color: var(--primary); }
        .hero p { font-size: 18px; color: rgba(255,255,255,0.95); line-height: 1.6; margin-bottom: 40px; font-weight: 400; text-shadow: 0 1px 5px rgba(0,0,0,0.3); text-align: justify; }
        
        .hero-btns { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 60px; }
        .btn-hero { padding: 14px 28px; border-radius: 8px; font-size: 15px; font-weight: 600; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; border: 1px solid transparent; }
        .btn-hero-primary { background: var(--primary); color: var(--white); }
        .btn-hero-primary:hover { background: var(--primary-dark); transform: translateY(-2px); }
        .btn-hero-outline { background: rgba(255,255,255,0.1); color: #fff; border-color: rgba(255,255,255,0.3); backdrop-filter: blur(4px); }
        .btn-hero-outline:hover { background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.5); transform: translateY(-2px); }
        
        .hero-stats { display: flex; gap: 60px; animation: fadeUp 0.8s ease-out 0.2s both; }
        .stat-item h3 { font-size: 36px; font-weight: 800; color: #fff; line-height: 1; margin-bottom: 8px; display: flex; align-items: center; }
        .stat-item h3 span { color: var(--gold); }
        .stat-item p { font-size: 14px; color: rgba(255,255,255,0.7); margin: 0; font-weight: 500; }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        /* ── Sections ── */
        .section { padding: 40px 0; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 32px; }
        
        .section-header { text-align: center; margin-bottom: 32px; }
        .section-tag { display: inline-flex; align-items: center; gap: 16px; font-size: 12px; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 16px; }
        .section-tag::before, .section-tag::after { content: ''; width: 30px; height: 1px; background: var(--primary); }
        .section-title { font-size: 36px; font-weight: 800; color: var(--dark); line-height: 1.2; letter-spacing: -0.5px; }
        .section-title span { color: var(--primary); }
        .section-desc { font-size: 16px; color: var(--text); max-width: 600px; margin: 16px auto 0; line-height: 1.6; }

        /* ── About Us ── */
        .about-section { background: var(--bg-light); }
        .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
        .about-img { border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
        .about-img img { width: 100%; height: auto; }
        
        .about-content .section-tag { justify-content: flex-start; margin-bottom: 12px; }
        .about-content .section-tag::before { display: none; }
        .about-content .section-title { font-size: 40px; margin-bottom: 24px; text-align: left; }
        .about-content p { font-size: 16px; color: var(--text); line-height: 1.7; margin-bottom: 20px; text-align: justify; }
        
        .about-features-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 32px; }
        .about-feature-item { background: var(--white); padding: 16px 20px; border-radius: 8px; display: flex; align-items: center; gap: 12px; font-size: 14px; font-weight: 600; color: var(--dark); transition: transform 0.3s; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .about-feature-item:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.05); }
        .about-feature-item svg { width: 20px; height: 20px; color: var(--primary); }

        /* ── Event Types ── */
        .events-section { background: var(--bg-light); }

        /* ── Features ── */
        .features-section { background: var(--white); }
        .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .feature-card { background: var(--white); padding: 24px 24px; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: all 0.3s; position: relative; overflow: hidden; border: 1px solid transparent; }
        .feature-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: var(--primary); transform: scaleX(0); transform-origin: left; transition: transform 0.4s ease; }
        .feature-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,168,150,0.1); border-color: var(--primary-light); }
        .feature-card:hover::before { transform: scaleX(1); }
        .feature-card:hover .feature-icon { background: var(--primary); color: var(--white); transform: scale(1.1); }
        .feature-icon { width: 44px; height: 44px; background: var(--primary-light); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--primary); margin-bottom: 16px; transition: all 0.3s; }
        .feature-card h3 { font-size: 16px; font-weight: 700; color: var(--dark); margin-bottom: 8px; }
        .feature-card p { font-size: 14px; color: var(--text); line-height: 1.6; text-align: justify; }

        /* ── Footer ── */
        .footer { background: #0B132B; color: #94A3B8; padding: 40px 0 20px; font-size: 13px; }
        .footer-grid { display: grid; grid-template-columns: 2.5fr 1.5fr 1.5fr 2fr; gap: 24px; margin-bottom: 32px; }
        .footer-brand .nav-logo-text .brand { color: #fff; }
        .footer-brand p { line-height: 1.6; margin: 12px 0; max-width: 320px; }
        .social-links { display: flex; gap: 12px; }
        .social-btn { width: 32px; height: 32px; background: rgba(255,255,255,0.05); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #fff; transition: all 0.3s; }
        .social-btn:hover { background: var(--primary); transform: translateY(-3px); }
        .footer-col h4 { font-size: 14px; font-weight: 700; color: #fff; margin-bottom: 12px; }
        .footer-col a { display: block; margin-bottom: 8px; transition: color 0.3s; color: #94A3B8; }
        .footer-col a:hover { color: var(--primary); }
        .contact-item { display: flex; gap: 12px; margin-bottom: 8px; align-items: flex-start; }
        .contact-item svg { width: 16px; height: 16px; color: var(--primary); flex-shrink: 0; margin-top: 2px; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; display: flex; justify-content: space-between; align-items: center; font-size: 12px; }
        .footer-bottom-links a { color: var(--primary); margin-left: 16px; text-decoration: none; }

        /* ── Scroll to Top ── */
        .scroll-to-top { position: fixed; bottom: 32px; right: 32px; width: 48px; height: 48px; background: var(--primary); color: white; border: none; border-radius: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0, 168, 150, 0.4); opacity: 0; visibility: hidden; transform: translateY(20px); transition: all 0.3s; z-index: 999; }
        .scroll-to-top.show { opacity: 1; visibility: visible; transform: translateY(0); }
        .scroll-to-top:hover { background: var(--primary-dark); transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0, 168, 150, 0.5); }

        /* ── Active Events Section ── */
        .active-events-section { background: var(--bg-light); }
        
        .slider-wrapper { position: relative; width: 100%; }
        .slider-track { 
            display: flex; 
            gap: 32px; 
            overflow-x: auto; /* Enable scroll for mobile swipe */
            scroll-behavior: smooth; 
            padding: 4px 0 20px 0; 
            margin: 0; 
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE/Edge */
        }
        .slider-track::-webkit-scrollbar { 
            display: none; /* Chrome/Safari/Webkit */
        }

        .slider-track .ae-card { min-width: calc((100% - 64px) / 3); width: calc((100% - 64px) / 3); flex-shrink: 0; }
        .slider-btn { position: absolute; top: 50%; transform: translateY(-50%); width: 50px; height: 50px; border-radius: 50%; background: var(--white); border: 1px solid var(--border); box-shadow: 0 4px 15px rgba(0,0,0,0.1); cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 20; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); color: var(--dark); }
        .slider-btn:hover { background: var(--white); color: var(--primary); border-color: var(--primary); transform: translateY(-50%) scale(1.1); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        .slider-btn.prev { left: -25px; }
        .slider-btn.next { right: -25px; }
        .slider-btn:disabled { opacity: 0; pointer-events: none; }

        /* Slider Dots */
        .slider-dots {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #cbd5e1;
            cursor: pointer;
            transition: all 0.3s;
        }
        .dot.active {
            background: var(--primary);
            width: 24px;
            border-radius: 5px;
        }

        .ae-card { background: var(--white); border-radius: 16px; border: 1px solid var(--border); overflow: hidden; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column; }
        .ae-card:hover { transform: translateY(-6px); box-shadow: 0 16px 32px rgba(0,0,0,0.08); }

        .ae-card-img { height: 200px; position: relative; overflow: hidden; }
        .ae-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
        .ae-card:hover .ae-card-img img { transform: scale(1.05); }

        .ae-type-badge { position: absolute; top: 16px; left: 16px; padding: 6px 14px; border-radius: 999px; font-size: 12px; font-weight: 700; color: #fff; display: inline-flex; align-items: center; gap: 6px; letter-spacing: 0.5px; }
        .ae-badge-fundraising { background: #F97316; }
        .ae-badge-volunteer { background: var(--primary); }

        .ae-card-body { padding: 24px; flex: 1; display: flex; flex-direction: column; }

        .ae-card-meta { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; font-size: 13px; color: var(--muted); }
        .ae-card-meta span { display: flex; align-items: center; gap: 5px; }

        .ae-card-title { font-size: 18px; font-weight: 700; color: var(--dark); margin-bottom: 8px; line-height: 1.4; min-height: 52px; display: flex; align-items: center; }
        .ae-card-desc { font-size: 14px; color: var(--text); line-height: 1.6; margin-bottom: 24px; flex-grow: 1; }

        .ae-progress { margin-top: auto; margin-bottom: 16px; }
        .ae-progress-bar { height: 6px; background: #f1f5f9; border-radius: 999px; overflow: hidden; }
        .ae-progress-fill { height: 100%; border-radius: 999px; transition: width 0.6s ease; }
        .ae-fill-fundraising { background: #F97316; }
        .ae-fill-volunteer { background: var(--primary); }

        .ae-card-footer { display: flex; align-items: flex-end; justify-content: space-between; }
        .ae-stat h4 { font-size: 13px; font-weight: 700; color: var(--dark); }
        .ae-stat p { font-size: 11px; color: var(--muted); margin-top: 2px; }

        .ae-btn-details { padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; background: #F59E0B; color: #fff; text-decoration: none; transition: all 0.2s; border: none; cursor: pointer; }
        .ae-btn-details:hover { background: #D97706; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3); }

        .ae-empty { text-align: center; padding: 60px 20px; grid-column: span 3; }
        .ae-empty-icon { width: 64px; height: 64px; background: var(--primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: var(--primary); }
        .ae-empty h3 { font-size: 18px; font-weight: 700; color: var(--dark); margin-bottom: 8px; }
        .ae-empty p { font-size: 14px; color: var(--text); }

        /* ── Responsive ── */
        @media(max-width: 1024px) {
            .hero-stats { gap: 30px; }
            .about-grid { grid-template-columns: 1fr; gap: 40px; }
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .slider-track .ae-card { min-width: calc((100% - 32px) / 2); width: calc((100% - 32px) / 2); }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media(max-width: 768px) {
            .nav-links { display: none; }
            .hero-content { text-align: center; margin: 0 auto; }
            .hero h1 { font-size: 36px; }
            .hero-badge { margin: 0 auto 24px; }
            .hero-btns { justify-content: center; }
            .hero-stats { justify-content: center; flex-wrap: wrap; }
            .features-grid { grid-template-columns: 1fr; }
            .slider-track .ae-card { min-width: 100%; width: 100%; }
            .slider-btn { display: none !important; }
            .slider-track { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .ae-empty { grid-column: span 1; }
            .footer-grid { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; gap: 16px; text-align: center; }
        }

        /* ── Scroll animations ── */
        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1); }
        .reveal.visible { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body>

<!-- ════ NAVBAR ════ -->
<nav class="navbar" id="navbar">
    <div class="nav-inner">
        <a href="/" class="nav-logo">
            <img src="{{ asset('images/logo.png') }}" alt="CareConnect">
            <div class="nav-logo-text">
                <span class="brand">CareConnect</span>
                <span class="sub">Ummah Relief Project</span>
            </div>
        </a>
        <div class="nav-links">
            @auth
                <a href="{{ url('/dashboard') }}">Dashboard</a>
            @endauth
            <a href="#about">About</a>
            <a href="#how-it-works">How It Works</a>
            <a href="#active-events">Events</a>
        </div>
        <div class="nav-actions">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-nav-fill">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-nav-outline">Log In</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-nav-fill">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</nav>

<!-- ════ HERO ════ -->
<section class="hero">
    <div class="hero-bg"><img src="{{ asset('images/hero-landing.png') }}" alt="Ummah Relief Project"></div>
    <div class="hero-overlay"></div>
    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-badge">
                <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                Making a Difference Together
            </div>
            <h1>Empower Change with <span>CareConnect</span></h1>
            <p>CareConnect supports Ummah Relief Project in connecting donors and volunteers to meaningful causes. Our secure and user-friendly platform makes it easy to donate, volunteer, and stay updated on every campaign and event.</p>
            
            <div class="hero-btns">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        Get Started
                    </a>
                @endif
                <a href="#about" class="btn-hero btn-hero-outline">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8"></polygon></svg>
                    Learn More
                </a>
            </div>
        </div>
        
        <div class="hero-stats">
            <div class="stat-item">
                <h3>50<span>+</span></h3>
                <p>Volunteers Active</p>
            </div>
            <div class="stat-item">
                <h3>12<span>+</span></h3>
                <p>Events Organized</p>
            </div>
            <div class="stat-item">
                <h3>RM 25K<span>+</span></h3>
                <p>Funds Raised</p>
            </div>
        </div>
    </div>
</section>

<!-- ════ ABOUT US ════ -->
<section class="section about-section" id="about">
    <div class="container">
        <div class="about-grid">
            <div class="about-img reveal">
                <img src="{{ asset('images/about-community.png') }}" alt="Community Impact">
            </div>
            <div class="about-content reveal">
                <div class="section-tag">About Us</div>
                <h2 class="section-title">Connecting Hearts, <span>Changing Lives</span></h2>
                
                <p>Ummah Relief Project (URP) is a registered non-profit organisation in Malaysia, established in 2021. We are dedicated to delivering humanitarian aid and support to communities in need, primarily across the East Coast of Malaysia.</p>
                <p>CareConnect is our digital platform built to make giving easier. Whether you choose to donate through a Fundraising Campaign or give your time through a Volunteer Event — everything is managed in one secure, real-time platform.</p>
                
                <div class="about-features-grid">
                    <div class="about-feature-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        Fundraising Campaigns
                    </div>
                    <div class="about-feature-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        Volunteer Events
                    </div>
                    <div class="about-feature-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        Secure Donations
                    </div>
                    <div class="about-feature-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        Real-time Notifications
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ════ FEATURES ════ -->
<section class="section features-section" id="how-it-works">
    <div class="container">
        <div class="section-header reveal">
            <div class="section-tag">Platform Features</div>
            <h2 class="section-title">Everything You Need to Make an Impact</h2>
            <p class="section-desc">CareConnect provides powerful tools to manage events, volunteers, and donations — all in one place.</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card reveal">
                <div class="feature-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
                <h3>Unified Event Management</h3>
                <p>Create and manage both fundraising and volunteer events under one system. Organize dates, locations, and event details effortlessly.</p>
            </div>
            
            <div class="feature-card reveal">
                <div class="feature-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
                <h3>Donation Collection</h3>
                <p>Accept donations through fundraising events. Donors can contribute directly and track how their money is being used in real-time.</p>
            </div>
            
            <div class="feature-card reveal">
                <div class="feature-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <h3>Volunteer Registration</h3>
                <p>Allow volunteers to sign up for events, manage their schedules, and coordinate with team members seamlessly.</p>
            </div>
            
            <div class="feature-card reveal">
                <div class="feature-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                </div>
                <h3>Real-time Notifications</h3>
                <p>Stay updated with instant notifications about event updates, new campaigns, donation confirmations, and important announcements.</p>
            </div>
            
            <div class="feature-card reveal">
                <div class="feature-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                </div>
                <h3>Progress Tracking</h3>
                <p>Monitor fundraising goals and volunteer participation with detailed analytics and transparent reporting dashboards.</p>
            </div>
            
            <div class="feature-card reveal">
                <div class="feature-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
                <h3>Secure & Transparent</h3>
                <p>Built with security in mind, ensuring all transactions and personal data are protected while maintaining full transparency.</p>
            </div>
        </div>
    </div>
</section>

<!-- ════ ACTIVE EVENTS ════ -->
<section class="section active-events-section" id="active-events">
    <div class="container">
        <div class="section-header reveal">
            <div class="section-tag">Active Events</div>
            <h2 class="section-title">Join Our Volunteer & Fundraising Efforts</h2>
            <p class="section-desc">Explore our ongoing fundraising campaigns and volunteer opportunities. Register now to make a difference.</p>
        </div>

        @if($activeEvents->count() > 0)
        <div class="slider-wrapper reveal">
            <button class="slider-btn prev" id="sliderPrev" aria-label="Previous">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            <div class="slider-track" id="sliderTrack">
                @foreach($activeEvents as $event)
                <div class="ae-card" style="transition-delay: {{ $loop->index * 0.1 }}s;">
                <div class="ae-card-img">
                    <img src="{{ $event->eventImg ? asset('storage/'.$event->eventImg) : 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=600&auto=format&fit=crop' }}" alt="{{ $event->title }}">
                    @if($event->fundraisingCampaign)
                        <div class="ae-type-badge ae-badge-fundraising">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                            Fundraising
                        </div>
                    @else
                        <div class="ae-type-badge ae-badge-volunteer">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                            Volunteer
                        </div>
                    @endif
                </div>

                <div class="ae-card-body">
                    <div class="ae-card-meta">
                        <span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            @if($event->volunteerEvent)
                                {{ $event->volunteerEvent->eventDate->format('d M, Y') }}
                            @else
                                End: {{ $event->fundraisingCampaign->end_date->format('d M, Y') }}
                            @endif
                        </span>
                        <span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                            @if($event->fundraisingCampaign)
                                {{ $event->fundraisingCampaign->donations->count() }} Donors
                            @else
                                {{ $event->volunteerEvent->applications->count() }} Volunteers
                            @endif
                        </span>
                    </div>

                    <h3 class="ae-card-title">{{ $event->title }}</h3>
                    <p class="ae-card-desc">{{ $event->eventShortDesc ?? $event->eventLongDesc }}</p>

                    <div class="ae-progress">
                        <div class="ae-progress-bar">
                            @php
                                $pct = 0;
                                if($event->fundraisingCampaign) {
                                    $pct = $event->fundraisingCampaign->target_amount > 0 ? ($event->fundraisingCampaign->collected_amount / $event->fundraisingCampaign->target_amount) * 100 : 0;
                                } elseif($event->volunteerEvent && $event->volunteerEvent->capacity > 0) {
                                    $pct = ($event->volunteerEvent->applications->count() / $event->volunteerEvent->capacity) * 100;
                                }
                            @endphp
                            <div class="ae-progress-fill {{ $event->fundraisingCampaign ? 'ae-fill-fundraising' : 'ae-fill-volunteer' }}" style="width: {{ min(100, $pct) }}%;"></div>
                        </div>
                    </div>

                    <div class="ae-card-footer">
                        <div class="ae-stat">
                            @if($event->fundraisingCampaign)
                                <h4>RM {{ number_format($event->fundraisingCampaign->collected_amount, 0) }} raised</h4>
                                <p>Goal: RM {{ number_format($event->fundraisingCampaign->target_amount, 0) }}</p>
                            @else
                                <h4>{{ $event->volunteerEvent->applications->count() }} / {{ $event->volunteerEvent->capacity }} volunteers</h4>
                                <p>Slots left: {{ max(0, $event->volunteerEvent->capacity - $event->volunteerEvent->applications->count()) }}</p>
                            @endif
                        </div>
                        <a href="{{ route('login') }}" class="ae-btn-details">View Details</a>
                    </div>
                </div>
                </div>
                @endforeach
            </div>
            <button class="slider-btn next" id="sliderNext" aria-label="Next">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
        </div>
        <div class="slider-dots" id="sliderDots"></div>
        @else
        <div class="ae-empty reveal">
            <div class="ae-empty-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
            <h3>No Active Events</h3>
            <p>There are no active events at the moment. Check back soon for new campaigns and activities!</p>
        </div>
        @endif
    </div>
</section>

<!-- ════ FOOTER ════ -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="nav-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="CareConnect">
                    <div class="nav-logo-text">
                        <span class="brand">CareConnect</span>
                        <span class="sub" style="color:var(--primary);">Ummah Relief Project</span>
                    </div>
                </div>
                <p>CareConnect is a charity event and fundraising management system developed for Ummah Relief Project (URP). Connecting hearts, changing lives.</p>
                <div class="social-links">
                    <a href="https://www.facebook.com/ummahterengganu" target="_blank" class="social-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a>
                    <a href="https://www.instagram.com/ummahreliefproject_official?fbclid=IwY2xjawRwOi9leHRuA2FlbQIxMABicmlkETFSN05SQzRYRFlINmVGQkVoc3J0YwZhcHBfaWQQMjIyMDM5MTc4ODIwMDg5MgABHvXAyJgsng7Yn_mJ5g2gB4Nkmxk5ykLLdshXxjbg4ve1GaxWi-7jH7t-rcGp_aem_jH3sd3kPkUtIk0pb-firQA" target="_blank" class="social-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a>
                    <a href="https://www.tiktok.com/@ummahreliefproject?fbclid=IwY2xjawRwOnZleHRuA2FlbQIxMABicmlkETFSN05SQzRYRFlINmVGQkVoc3J0YwZhcHBfaWQQMjIyMDM5MTc4ODIwMDg5MgABHsSGHTfUttmVye7quk1EcuR5AuO2zsb9-flS5aJ9GSEG8wMhJqCW66mKgwdC_aem_vMsSI94dBg659DHfBWtc3A" target="_blank" class="social-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19.589 6.686a4.793 4.793 0 0 1-3.77-4.245V2h-3.445v13.672a2.896 2.896 0 0 1-5.201 1.743l-.002-.001.002.001a2.895 2.895 0 0 1 3.183-4.51v-3.5a6.329 6.329 0 0 0-5.394 10.692 6.33 6.33 0 0 0 10.857-4.424V8.687a8.182 8.182 0 0 0 4.773 1.526V6.79a4.831 4.831 0 0 1-1.003-.104z"></path></svg></a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Quick Links</h4>
                @auth
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                @endauth
                <a href="#about">About</a>
                <a href="#how-it-works">How It Works</a>
                <a href="#active-events">Events</a>
            </div>
            <div class="footer-col">
                <h4>Get Involved</h4>
                <a href="{{ route('login') }}">Fundraising Campaigns</a>
                <a href="{{ route('login') }}">Volunteer Events</a>
                <a href="{{ route('login') }}">Donate</a>
                <a href="{{ route('login') }}">Apply Volunteer</a>
            </div>
            <div class="footer-col">
                <h4>Contact Us</h4>
                <div class="contact-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <span>ummahreliefproject@gmail.com</span>
                </div>
                <div class="contact-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    <span>+60 17-948 3752</span>
                </div>
                <div class="contact-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    <span>319 Taman Sri Java, Wakaf Tembesu, 21300 Kuala Terengganu, Terengganu</span>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© 2026 CareConnect — Ummah Relief Project. All rights reserved.</span>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a> • <a href="#">Terms of Use</a>
            </div>
        </div>
    </div>
</footer>

<!-- Scroll to Top Button -->
<button id="scrollToTopBtn" class="scroll-to-top" aria-label="Scroll to top" onclick="scrollToTop()">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
</button>

<script>
// Navbar scroll effect
window.addEventListener('scroll', () => {
    document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
});

// Scroll reveal
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if(e.isIntersecting) {
            e.target.classList.add('visible');
            observer.unobserve(e.target);
        }
    });
}, { threshold: 0.1 });

document.addEventListener('DOMContentLoaded', function() {
    // Reveal Animations
    const reveals = document.querySelectorAll('.reveal');
    function reveal() {
        for (let i = 0; i < reveals.length; i++) {
            const windowHeight = window.innerHeight;
            const elementTop = reveals[i].getBoundingClientRect().top;
            const elementVisible = 100;
            if (elementTop < windowHeight - elementVisible) {
                reveals[i].classList.add('visible');
            }
        }
    }
    window.addEventListener('scroll', reveal);
    reveal();

    // Scroll to Top
    const scrollBtn = document.getElementById('scrollToTopBtn');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            scrollBtn.classList.add('show');
        } else {
            scrollBtn.classList.remove('show');
        }
    });

    // Slider Logic
    const track = document.getElementById('sliderTrack');
    const prevBtn = document.getElementById('sliderPrev');
    const nextBtn = document.getElementById('sliderNext');
    if (track && prevBtn && nextBtn) {
        function getCardWidth() {
            const card = track.querySelector('.ae-card');
            if (!card) return 0;
            return card.offsetWidth + 32; // card width + gap
        }

        function updateButtons() {
            if (track.scrollWidth > track.offsetWidth + 10) {
                prevBtn.style.display = 'flex';
                nextBtn.style.display = 'flex';
            } else {
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
            }
            
            prevBtn.disabled = track.scrollLeft <= 5;
            nextBtn.disabled = track.scrollLeft + track.offsetWidth >= track.scrollWidth - 5;

            // Update Dots
            const dots = document.querySelectorAll('.dot');
            const index = Math.round(track.scrollLeft / getCardWidth());
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
        }

        // Initialize Dots
        const cardCount = track.querySelectorAll('.ae-card').length;
        const dotsContainer = document.getElementById('sliderDots');
        if (dotsContainer) {
            for (let i = 0; i < cardCount; i++) {
                const dot = document.createElement('div');
                dot.className = 'dot' + (i === 0 ? ' active' : '');
                dot.onclick = () => {
                    track.scrollTo({ left: i * getCardWidth(), behavior: 'smooth' });
                };
                dotsContainer.appendChild(dot);
            }
        }

        prevBtn.addEventListener('click', () => {
            track.scrollBy({ left: -getCardWidth(), behavior: 'smooth' });
        });

        nextBtn.addEventListener('click', () => {
            track.scrollBy({ left: getCardWidth(), behavior: 'smooth' });
        });

        track.addEventListener('scroll', updateButtons);
        window.addEventListener('resize', updateButtons);
        setTimeout(updateButtons, 500);
    }
});
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
</body>
</html>
