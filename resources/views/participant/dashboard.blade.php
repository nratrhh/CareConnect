@extends('layouts.participant')

@section('title', 'Home')

@push('styles')
<style>
    .hero {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 24px;
        padding: 40px 48px;
        color: var(--white);
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }

    .hero::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 600px;
    }

    .hero-tag {
        display: inline-block;
        padding: 6px 14px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(4px);
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .hero h1 {
        font-size: 42px;
        font-weight: 800;
        margin-bottom: 8px;
        line-height: 1.1;
    }

    .hero h1 span {
        color: #FBBF24;
    }

    .hero p {
        font-size: 18px;
        opacity: 0.9;
        margin-bottom: 24px;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .section-title {
        font-size: 24px;
        font-weight: 700;
    }

    /* â”€â”€â”€ Event Card Base â”€â”€â”€ */
    .event-card {
        background: var(--white);
        border-radius: 24px;
        border: 1px solid var(--border);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    .event-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
    }
    .event-visual { height: 140px; position: relative; overflow: hidden; }
    .event-visual img { width: 100%; height: 100%; object-fit: cover; }
    .overlay-fundraising { background: transparent; position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; }
    .overlay-volunteer { background: transparent; position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; }
    .overlay-icon { font-size: 80px; opacity: 0.15; color: var(--dark); }
    .type-label { position: absolute; top: 12px; left: 12px; z-index: 10; padding: 4px 10px; border-radius: 999px; font-size: 10px; font-weight: 700; display: flex; align-items: center; gap: 4px; color: var(--white); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .label-fundraising { background: #F97316; }
    .label-volunteer   { background: #00827F; }
    .event-content { padding: 12px; flex: 1; display: flex; flex-direction: column; }
    .card-meta { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; font-size: 11px; color: var(--secondary); }
    .card-meta span { display: flex; align-items: center; gap: 4px; }
    .card-location { 
        font-size: 11px; 
        color: var(--secondary); 
        margin-bottom: 8px; 
        display: flex; 
        align-items: flex-start; 
        gap: 6px; 
        min-height: 32px; /* Height for ~2 lines of text */
        line-height: 1.4;
    }
    .card-location svg { margin-top: 2px; flex-shrink: 0; }
    .card-title { font-size: 15px; font-weight: 800; margin-bottom: 6px; line-height: 1.3; color: var(--dark); min-height: 38px; display: flex; align-items: center; }
    .card-desc { font-size: 12px; color: var(--secondary); margin-bottom: 12px; line-height: 1.5; flex-grow: 1; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; }
    .progress-container { margin-bottom: 16px; margin-top: auto; }
    .progress-bar-bg { height: 6px; background: #f1f5f9; border-radius: 999px; overflow: hidden; }
    .progress-bar-fill { height: 100%; border-radius: 999px; }
    .fill-fundraising { background: #F97316; }
    .fill-volunteer   { background: #00827F; }
    .card-footer-stats { display: flex; align-items: flex-end; justify-content: space-between; margin-top: auto; }
    .stat-text h4 { font-size: 14px; font-weight: 700; color: var(--dark); }
    .stat-text p { font-size: 12px; color: var(--secondary); margin-top: 2px; }

    /* ——— Slider (3 Cards Like Original) ——— */
    .slider-wrapper { position: relative; width: 100%; min-width: 0; }
    .slider-track { 
        display: flex; 
        gap: 16px; 
        overflow: hidden; 
        scroll-behavior: smooth; 
        padding: 4px 0 20px 0; /* Space for hover shadow */
        margin: 0;
    }
    .slider-track .event-card { 
        min-width: calc((100% - 32px) / 3); 
        width: calc((100% - 32px) / 3);
        flex-shrink: 0; 
    }
    .slider-btn { 
        position: absolute; 
        top: calc(50% - 10px); 
        transform: translateY(-50%); 
        width: 44px; 
        height: 44px; 
        border-radius: 50%; 
        background: var(--white); 
        border: 1px solid var(--border); 
        box-shadow: 0 4px 12px rgba(0,0,0,0.12); 
        cursor: pointer; 
        display: none; /* Hidden by default, shown via JS if needed */
        align-items: center; 
        justify-content: center; 
        z-index: 20; 
        transition: all 0.2s; 
        color: var(--dark); 
    }
    .slider-btn:hover { background: var(--primary); color: var(--white); border-color: var(--primary); }
    .slider-btn.prev { left: 8px; }
    .slider-btn.next { right: 8px; }
    .slider-btn:disabled { opacity: 0; cursor: not-allowed; pointer-events: none; }

    /* â”€â”€â”€ Quick Actions â”€â”€â”€ */
    .quick-actions { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-top: 32px; margin-bottom: 48px; }
    .action-card { padding: 32px; display: flex; align-items: center; gap: 24px; cursor: pointer; }
    .action-icon { width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .action-info h3 { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
    .action-info p { font-size: 14px; color: var(--secondary); }

    @media(max-width: 1024px) {
        .slider-track .event-card { min-width: calc((100% - 32px) / 2); width: calc((100% - 32px) / 2); }
    }
    @media(max-width: 768px) {
        .slider-track .event-card { min-width: 100%; width: 100%; }
        .slider-btn { display: none !important; }
        .slider-track { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .quick-actions { grid-template-columns: 1fr; }
    }

    /* â”€â”€â”€ Timeline List (Full Width) â”€â”€â”€ */
    .timeline-list-container {
        background: var(--white);
        border-radius: 24px;
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .timeline-row {
        display: flex;
        align-items: center;
        padding: 24px;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s;
        gap: 24px;
    }

    .timeline-row:last-child {
        border-bottom: none;
    }

    .timeline-row:hover {
        background: #f8fafc;
    }

    /* Date Box */
    .tl-date-box {
        width: 70px;
        height: 90px;
        background: var(--white);
        border: 2px solid #f1f5f9;
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }

    .tl-day-num {
        font-size: 24px;
        font-weight: 800;
        color: var(--dark);
        line-height: 1;
    }

    .tl-month-name {
        font-size: 12px;
        font-weight: 700;
        color: var(--secondary);
        text-transform: uppercase;
        margin-top: 2px;
    }

    .tl-day-name {
        font-size: 11px;
        font-weight: 600;
        color: var(--primary);
        background: var(--primary-light);
        padding: 2px 8px;
        border-radius: 6px;
        margin-top: 6px;
    }

    /* Content Area */
    .tl-main-info {
        flex: 1;
    }

    .tl-event-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 6px;
        cursor: pointer;
        transition: color 0.2s;
    }

    .tl-event-title:hover {
        color: var(--primary);
    }

    .tl-meta-row {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 12px;
    }

    .tl-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: var(--secondary);
    }

    .tl-badges {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Right Side Stats */
    .tl-right-stats {
        display: flex;
        align-items: center;
        gap: 24px;
        text-align: right;
    }

    .tl-countdown {
        font-size: 14px;
        font-weight: 600;
        color: var(--secondary);
        background: #f1f5f9;
        padding: 6px 12px;
        border-radius: 999px;
        white-space: nowrap;
    }

    .tl-expand-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 1px solid var(--border);
        background: var(--white);
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .tl-expand-btn:hover {
        background: var(--primary);
        color: var(--white);
        border-color: var(--primary);
    }

    /* Details Expand Section */
    .tl-details {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: #fafafa;
    }

    .timeline-row-wrapper.active .tl-details {
        max-height: 500px;
    }

    .timeline-row-wrapper.active .tl-expand-btn {
        transform: rotate(180deg);
        background: var(--primary);
        color: var(--white);
    }

    .tl-details-inner {
        padding: 0 24px 24px 118px; /* Align with content */
    }

    .tl-details-main {
        display: flex;
        gap: 32px;
        align-items: flex-end;
    }

    .tl-details-text {
        flex: 1;
    }

    .tl-info-line {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 8px;
    }

    .tl-short-desc {
        font-size: 13px;
        color: var(--secondary);
        line-height: 1.6;
        margin-bottom: 20px;
        font-style: italic;
    }

    /* Progress Section */
    .tl-progress-section {
        background: var(--white);
        padding: 16px;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
    }

    .tl-progress-labels {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 8px;
    }

    .tl-progress-bar-bg {
        height: 8px;
        background: #f1f5f9;
        border-radius: 999px;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .tl-progress-bar-fill {
        height: 100%;
        border-radius: 999px;
        transition: width 1s ease-out;
    }

    .fill-v { background: #6D28D9; }
    .fill-f { background: #F97316; }

    .tl-progress-footer {
        font-size: 11px;
        font-weight: 700;
        text-align: right;
    }

    /* Action Buttons */
    .tl-details-action {
        flex-shrink: 0;
    }

    .btn-primary-full, .btn-fund-full, .btn-applied-full {
        padding: 12px 28px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary-full { background: var(--dark); color: var(--white); }
    .btn-primary-full:hover { background: #000; transform: translateX(5px); }

    .btn-fund-full { background: var(--white); color: var(--dark); border: 1px solid var(--border); }
    .btn-fund-full:hover { background: #f8fafc; border-color: var(--dark); transform: translateX(5px); }

    .btn-applied-full { background: #DCFCE7; color: #16A34A; border: 1px solid #BBF7D0; cursor: default; }

    .badge-applied { background: #DCFCE7; color: #16A34A; border: 1px solid #BBF7D0; }

    @media (max-width: 768px) {
        .tl-details-inner { padding: 0 20px 20px 20px; }
        .tl-details-main { flex-direction: column; align-items: stretch; }
    }

    .empty-state-list {
        padding: 48px;
        text-align: center;
        background: var(--white);
        border: 1px dashed var(--border);
        border-radius: 24px;
        color: var(--secondary);
    }

    @media (max-width: 768px) {
        .timeline-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        .tl-right-stats {
            width: 100%;
            justify-content: space-between;
        }
    }

    /* ─── New Quick Actions ─── */
    .qa-cards-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 32px;
    }
    .qa-card {
        border-radius: 12px;
        padding: 24px;
        display: flex;
        align-items: center;
        text-align: left;
        gap: 20px;
    }
    .qa-card-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    .qa-card-blue { background: #E5F0FF; }
    .qa-card-green { background: #E6F7F0; }
    .qa-icon-wrapper { flex-shrink: 0; padding: 16px; background: rgba(255,255,255,0.6); border-radius: 16px; }
    .qa-icon-wrapper svg { width: 32px; height: 32px; }
    .qa-card-blue .qa-icon-wrapper svg { color: #0056D2; }
    .qa-card-green .qa-icon-wrapper svg { color: #008253; }
    .qa-title { font-size: 18px; font-weight: 700; color: var(--dark); margin-bottom: 6px; }
    .qa-desc { font-size: 14px; color: var(--secondary); margin-bottom: 16px; line-height: 1.5; }
    .qa-btn {
        display: inline-flex; align-items: center; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px;
        border: none; cursor: pointer; transition: all 0.2s; text-decoration: none;
    }
    .qa-btn:hover { opacity: 0.9; transform: translateY(-2px); }
    .qa-btn-blue { background: #0056D2; color: #fff; }
    .qa-btn-green { background: #008253; color: #fff; }

    /* ─── Closest Event ─── */
    .closest-event-card {
        display: flex; background: var(--white); border: 1px solid var(--border);
        border-radius: 12px; padding: 24px; gap: 20px; margin-bottom: 16px;
        align-items: center;
    }
    .closest-event-img {
        width: 180px; height: 135px; border-radius: 8px; object-fit: cover; flex-shrink: 0;
    }
    .closest-event-info { display: flex; flex-direction: column; justify-content: center; flex: 1; min-width: 0; }
    .closest-event-meta {
        font-size: 12px; font-weight: 600; color: var(--secondary); margin-bottom: 8px;
        display: flex; align-items: center; gap: 8px;
    }
    .closest-event-category { background: #E5F0FF; color: #0056D2; padding: 5px 12px; border-radius: 999px; font-weight: 700; }
    .closest-event-title { font-size: 18px; font-weight: 700; color: var(--dark); margin-bottom: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .closest-event-desc { font-size: 13px; color: var(--secondary); margin-bottom: 14px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; }
    .closest-event-actions { display: flex; gap: 12px; justify-content: flex-end; }
    .btn-view-details-yellow {
        background: #F59E0B; /* Original Orange-Yellow */
        color: var(--white); /* White text */
        padding: 8px 16px; 
        border-radius: 8px; 
        font-weight: 700; 
        font-size: 13px; 
        border: none; 
        cursor: pointer; 
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 6px rgba(245, 158, 11, 0.2);
        transition: all 0.2s;
    }
    .btn-view-details-yellow:hover { background: #D97706; transform: translateY(-2px); color: var(--white); }
    @media (max-width: 768px) {
        .closest-event-card { flex-direction: column; }
        .closest-event-img { width: 100%; height: 200px; }
        .qa-cards-container { grid-template-columns: 1fr; }
    }

    /* ─── Recommended for You ─── */
    .recommended-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .rec-card { background: var(--white); border: 1px solid var(--border); border-radius: 12px; padding: 24px; display: flex; flex-direction: column; }
    .rec-meta { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; font-size: 12px; font-weight: 600; color: var(--secondary); }
    .rec-category { padding: 4px 10px; border-radius: 999px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; }
    .rec-category-edu { background: #E6F7F0; color: #008253; }
    .rec-category-env { background: #E5F0FF; color: #0056D2; }
    .rec-category-other { background: #FFF3CD; color: #856404; }
    .rec-title { font-size: 18px; font-weight: 700; color: var(--dark); margin-bottom: 12px; line-height: 1.3; }
    .rec-desc { font-size: 14px; color: var(--secondary); margin-bottom: 24px; line-height: 1.5; flex: 1; }
    .btn-view-role { width: 100%; background: #fff; color: #0056D2; border: 1px solid #0056D2; padding: 10px; border-radius: 8px; font-weight: 600; font-size: 14px; text-align: center; transition: all 0.2s; cursor: pointer; text-decoration: none; display: block; }
    .btn-view-role:hover { background: #0056D2; color: #fff; }
    @media (max-width: 768px) { .recommended-grid { grid-template-columns: 1fr; } }

    /* ─── Dashboard Grid ─── */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 2.6fr 1fr;
        gap: 32px;
        margin-top: 48px;
    }
    .main-column { min-width: 0; }
    .side-column { min-width: 0; }
    
    @media (max-width: 800px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ─── Recent Activities ─── */
    .activities-container {
        background: var(--white);
        border-radius: 24px;
        border: 1px solid var(--border);
        padding: 24px;
    }

    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .activity-item {
        display: flex;
        gap: 16px;
        align-items: flex-start;
    }

    .activity-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
        min-width: 0;
    }

    .activity-title {
        font-size: 15px;
        color: var(--dark);
        margin-bottom: 4px;
        line-height: 1.4;
    }

    .activity-subtitle {
        font-size: 13px;
        color: var(--secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 6px;
    }

    .activity-date {
        font-size: 12px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .empty-state-side {
        text-align: center;
        padding: 40px 20px;
        color: var(--secondary);
        font-size: 14px;
    }
</style>
@endpush

@section('content')

<div class="hero">
    <div class="hero-content">
        <div class="hero-tag">Welcome Back</div>
        <h1>{{ auth()->guard('participant')->user()->name }},<br><span>let's make a difference.</span></h1>
        <p>Your small act of kindness can create a big impact.</p>
    </div>
</div>

<div class="qa-cards-container">
    <!-- Blue Card -->
    <div class="qa-card qa-card-blue">
        <div class="qa-icon-wrapper">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </div>
        <div class="qa-card-content">
            <h3 class="qa-title">Find New Opportunities</h3>
            <p class="qa-desc">Browse through local volunteering projects that match your skills.</p>
            <a href="{{ route('participant.events.index', ['type' => 'volunteer']) }}" class="qa-btn qa-btn-blue">Explore Now</a>
        </div>
    </div>
    <!-- Green Card -->
    <div class="qa-card qa-card-green">
        <div class="qa-icon-wrapper">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
        </div>
        <div class="qa-card-content">
            <h3 class="qa-title">Support a Cause</h3>
            <p class="qa-desc">Your donations directly fund essential community services.</p>
            <a href="{{ route('participant.events.index', ['type' => 'fundraising']) }}" class="qa-btn qa-btn-green">Donate Now</a>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Left Column: Main Content -->
    <div class="main-column">
        <!-- ═══ CLOSEST EVENT (Active Event) ═══ -->
        @if(isset($activeEvents) && $activeEvents->count() > 0 || isset($upcomingEvents) && $upcomingEvents->count() > 0)
            <div style="background: var(--white); border-radius: 24px; border: 1px solid var(--border); overflow: hidden; margin-bottom: 32px;">
                <div style="padding: 16px 24px 12px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">Active Event</h2>
                    <a href="{{ route('participant.events.index') }}" style="font-size: 14px; color: var(--primary); font-weight: 600; text-decoration: none;">View All</a>
                </div>

            @php
                // Get the single closest event
                $closestEvent = $activeEvents->first() ?? $upcomingEvents->first();
                $dateStr = '';
                $location = '';
                $category = $closestEvent->volunteerEvent ? 'Volunteer' : 'Fundraising';
                if ($closestEvent->volunteerEvent) {
                    $dateStr = \Carbon\Carbon::parse($closestEvent->volunteerEvent->eventDate)->format('M d, Y') . ' at ' . (\Carbon\Carbon::parse($closestEvent->volunteerEvent->start_time)->format('g:i A'));
                    $location = $closestEvent->volunteerEvent->location;
                } else {
                    $dateStr = 'Ends ' . \Carbon\Carbon::parse($closestEvent->fundraisingCampaign->end_date)->format('M d, Y');
                    $location = 'Online Campaign';
                }
            @endphp
            <div class="closest-event-card" style="border: none; border-radius: 0; margin-bottom: 0;">
                <img src="{{ $closestEvent->eventImg ? asset('storage/'.$closestEvent->eventImg) : 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop' }}" alt="{{ $closestEvent->title }}" class="closest-event-img" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop';">
                <div class="closest-event-info">
                    <div class="closest-event-meta">
                        <span class="closest-event-category">{{ $category }}</span>
                        <span>• {{ $location }}</span>
                        <span>• {{ $dateStr }}</span>
                    </div>
                    <h3 class="closest-event-title">{{ $closestEvent->title }}</h3>
                    <p class="closest-event-desc">{{ Str::limit($closestEvent->eventShortDesc ?? $closestEvent->eventLongDesc, 250) }}</p>
                    <div class="closest-event-actions">
                        <a href="{{ route('participant.events.show', $closestEvent->event_id) }}" class="btn-view-details-yellow">
                            View Details
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            </div> <!-- End Active Event Container -->

            <!-- ═══ RECOMMENDED FOR YOU (Slider) ═══ -->
            <div style="background: var(--white); border-radius: 24px; border: 1px solid var(--border); overflow: hidden; margin-bottom: 32px;">
                <div style="padding: 16px 24px 12px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">Recommended for You</h2>
                    <a href="{{ route('participant.events.index') }}" style="font-size: 14px; color: var(--primary); font-weight: 600; text-decoration: none;">View All Events</a>
                </div>

            @php
                $sliderEvents = collect();
                if (isset($activeEvents)) {
                    $sliderEvents = $activeEvents->where('event_id', '!=', $closestEvent->event_id ?? null);
                }
            @endphp

            @if($sliderEvents->count() > 0)
            <div class="slider-wrapper" style="padding: 24px 24px 8px 24px;">
                <button class="slider-btn prev" id="sliderPrev" aria-label="Previous">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <div class="slider-track" id="sliderTrack">
                    @foreach($sliderEvents as $event)
                    @include('participant._event_card', ['event' => $event])
                    @endforeach
                </div>
                <button class="slider-btn next" id="sliderNext" aria-label="Next">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
            @else
            <div class="empty-state-list" style="border: none; border-radius: 0;">
                No recommended events at the moment.
            </div>
            @endif
            </div> <!-- End Recommended for You Container -->

        @else
            <div style="background: var(--white); border-radius: 24px; border: 1px solid var(--border); overflow: hidden; margin-bottom: 32px;">
                <div style="padding: 16px 24px 12px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">Active Event</h2>
                    <a href="{{ route('participant.events.index') }}" style="font-size: 14px; color: var(--primary); font-weight: 600; text-decoration: none;">View All</a>
                </div>
                <div class="empty-state-list" style="border: none; border-radius: 0;">
                    No events scheduled at the moment.
                </div>
            </div>
        @endif

        <!-- ═══ UPCOMING EVENTS (Timeline List) ═══ -->
        <div style="display: flex; flex-direction: column; background: var(--white); border-radius: 24px; border: 1px solid var(--border); overflow: hidden;">
            <div style="padding: 16px 24px 12px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: baseline; gap: 12px;">
                    <h2 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">Upcoming Events</h2>
                    <span style="color: #94a3b8; font-size: 14px;">in the next 30+ days</span>
                </div>
                <a href="{{ route('participant.events.index', ['type' => 'upcoming']) }}" style="font-size: 14px; color: var(--primary); font-weight: 600; text-decoration: none;">View All</a>
            </div>

        @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
        <div class="timeline-list-container" style="border: none; border-radius: 0;">
            @foreach($upcomingEvents as $event)
                @php
                    $targetDate = $event->volunteerEvent ? $event->volunteerEvent->eventDate : $event->fundraisingCampaign->start_date;
                    $daysLeft = ceil(now()->diffInDays($targetDate, false));
                    
                    // Volunteer Logic
                    $isApplied = false;
                    $slotsFilled = 0;
                    $capacity = 0;
                    if($event->volunteerEvent) {
                        $isApplied = $event->volunteerEvent->applications()
                            ->where('participant_id', auth()->guard('participant')->id())
                            ->whereNotIn('status', ['cancelled'])
                            ->exists();
                        $capacity = $event->volunteerEvent->capacity;
                        $slotsFilled = $event->volunteerEvent->applications()->whereIn('status', ['pending', 'approved'])->count();
                    }

                    // Fundraising Logic
                    $collected = 0;
                    $target = 0;
                    $percentage = 0;
                    if($event->fundraisingCampaign) {
                        $collected = $event->fundraisingCampaign->collected_amount ?? 0;
                        $target = $event->fundraisingCampaign->target_amount ?? 0;
                        $percentage = $target > 0 ? min(100, round(($collected / $target) * 100)) : 0;
                    }
                @endphp
                <div class="timeline-row-wrapper">
                    <div class="timeline-row" onclick="toggleTimelineDetails(this)">
                        <!-- Left: Date Box -->
                        <div class="tl-date-box">
                            <div class="tl-day-num">{{ \Carbon\Carbon::parse($targetDate)->format('d') }}</div>
                            <div class="tl-month-name">{{ strtoupper(\Carbon\Carbon::parse($targetDate)->format('M')) }}</div>
                            <div class="tl-day-name">{{ \Carbon\Carbon::parse($targetDate)->format('D') }}</div>
                        </div>

                        <!-- Middle: Content -->
                        <div class="tl-main-info">
                            <div class="tl-title-row">
                                <h3 class="tl-event-title">{{ $event->title }}</h3>
                            </div>
                            <div class="tl-meta-row">
                                @if($event->volunteerEvent)
                                    <span class="tl-meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                        {{ $event->volunteerEvent->location }}
                                    </span>
                                @else
                                    <span class="tl-meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1 4-10z"></path></svg>
                                        Online Campaign
                                    </span>
                                @endif
                            </div>
                            <div class="tl-badges">
                                <span class="badge-sm {{ $event->volunteerEvent ? 'badge-volunteer' : 'badge-fundraising' }}">
                                    {{ $event->volunteerEvent ? 'Volunteer' : 'Fundraising' }}
                                </span>
                            </div>
                        </div>

                        <!-- Right: Stats -->
                        <div class="tl-right-stats">
                            <div class="tl-countdown">
                                {{ $daysLeft }} days left
                            </div>
                            <button class="tl-expand-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Expandable Details Section -->
                    <div class="tl-details">
                        <div class="tl-details-inner">
                            <div class="tl-details-main">
                                <div class="tl-details-text">
                                    <div class="tl-info-line">
                                        @if($event->volunteerEvent)
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                            <span>
                                                @if($event->volunteerEvent->start_time)
                                                    {{ \Carbon\Carbon::parse($event->volunteerEvent->start_time)->format('h:i A') }} 
                                                    @if($event->volunteerEvent->end_time)
                                                        - {{ \Carbon\Carbon::parse($event->volunteerEvent->end_time)->format('h:i A') }}
                                                    @endif
                                                @else
                                                    Time not specified
                                                @endif
                                            </span>
                                        @else
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                            <span>Campaign Ends: {{ \Carbon\Carbon::parse($event->fundraisingCampaign->end_date)->format('d M Y') }}</span>
                                        @endif
                                    </div>
                                    <p class="card-desc">{{ $event->eventShortDesc ?? $event->eventLongDesc }}</p>
                                    
                                    <div class="tl-progress-section">
                                        <div class="tl-progress-labels">
                                            <span>{{ $event->volunteerEvent ? 'Slots Filled' : 'Funds Collected' }}</span>
                                            <span>{{ $event->volunteerEvent ? "$slotsFilled / $capacity" : "RM " . number_format($collected, 0) . " / RM " . number_format($target, 0) }}</span>
                                        </div>
                                        <div class="tl-progress-bar-bg">
                                            <div class="tl-progress-bar-fill {{ $event->volunteerEvent ? 'fill-v' : 'fill-f' }}" 
                                                 style="width: {{ $event->volunteerEvent ? ($capacity > 0 ? ($slotsFilled/$capacity)*100 : 0) : $percentage }}%"></div>
                                        </div>
                                        <div class="tl-progress-footer">
                                            @if($event->volunteerEvent)
                                                <span class="slots-left text-danger">{{ max(0, $capacity - $slotsFilled) }} slots left</span>
                                            @else
                                                <span class="perc-reached">{{ $percentage }}% of goal reached</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="tl-details-action">
                                    <button class="btn btn-fund-full" onclick="window.location='{{ route('participant.events.show', $event->event_id) }}'">
                                        View Details &rarr;
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <div class="empty-state-list" style="border: none; border-radius: 0;">
            No upcoming events scheduled for the next 30 days.
        </div>
        @endif
        </div> <!-- End Upcoming Events Container -->
    </div>
    
    <!-- Right Column: Recent Activities -->
    <div class="side-column">
        <div class="activities-container" style="padding: 0; display: flex; flex-direction: column; background: var(--white); border-radius: 24px; border: 1px solid var(--border); overflow: hidden;">
            <div style="padding: 16px 24px 12px 24px; border-bottom: 1px solid #f1f5f9;">
                <h2 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">Recent Activity</h2>
            </div>
            
            @if(isset($recentActivities) && $recentActivities->count() > 0)
                <div class="activity-list" style="padding: 8px 24px 8px 24px; display: flex; flex-direction: column; gap: 0;">
                    @foreach($recentActivities as $activity)
                    <div class="activity-item" style="padding: 16px 0; {{ !$loop->last ? 'border-bottom: 1px solid #f1f5f9;' : '' }} display: flex; gap: 16px; align-items: flex-start; margin: 0;">
                        <div class="activity-icon" style="color: {{ $activity->color }}; background: {{ $activity->bg }}; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            {!! $activity->icon !!}
                        </div>
                        <div class="activity-content" style="display: flex; flex-direction: column; justify-content: center; flex: 1; min-width: 0;">
                            <h4 class="activity-title" style="font-size: 14px; font-weight: 700; margin-bottom: 4px; color: var(--dark); line-height: 1.4;">{!! $activity->title !!}</h4>
                            <p class="activity-subtitle" style="font-size: 12px; margin-bottom: 0; color: #64748b; white-space: normal;">
                                {{ $activity->subtitle }} &bull; {{ \Carbon\Carbon::parse($activity->date)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state-side" style="padding: 40px 24px; text-align: center; color: var(--secondary); font-size: 14px;">
                    <p>No recent activities yet.</p>
                </div>
            @endif

            <div style="margin-top: auto; border-top: 1px solid #e0e7ff;">
                <a href="{{ route('participant.activities.index') }}" style="display: block; background: #eef2ff; color: #4338ca; font-weight: 600; font-size: 14px; text-align: center; padding: 16px; text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#e0e7ff'" onmouseout="this.style.background='#eef2ff'">
                    View activities
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('sliderTrack');
    const prevBtn = document.getElementById('sliderPrev');
    const nextBtn = document.getElementById('sliderNext');
    if (!track || !prevBtn || !nextBtn) return;

    function getCardWidth() {
        const card = track.querySelector('.event-card');
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
});

// â”€â”€ Timeline Toggle â”€â”€
window.toggleTimelineDetails = function(rowElement) {
    const wrapper = rowElement.closest('.timeline-row-wrapper');
    const isActive = wrapper.classList.contains('active');
    
    // Close others
    document.querySelectorAll('.timeline-row-wrapper').forEach(w => w.classList.remove('active'));
    
    // Toggle current
    if (!isActive) {
        wrapper.classList.add('active');
    }
};
</script>
@endpush
