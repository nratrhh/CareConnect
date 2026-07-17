@extends('layouts.participant')

@section('title', $event->title)

@push('styles')
<style>
    /* ─── Page Layout ─── */
    .event-details-container {
        padding-bottom: 80px;
    }

    .main-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* ─── Hero Header ─── */
    .event-hero {
        border-radius: 16px;
        color: white;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
        background: white;
    }

    .event-hero-img {
        width: 100%;
        height: 420px;
        object-fit: cover;
        object-position: center top;
        display: block;
    }

    .hero-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 40px;
        background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.75) 100%);
        z-index: 2;
    }




    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 24px;
        background: #f0fdfa; /* Light mint */
        border-radius: 999px;
        font-size: 14px;
        font-weight: 700;
        color: #0d9488; /* Teal */
        position: absolute;
        top: 16px;
        left: 16px;
        z-index: 5;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .hero-badge.fundraising {
        background: #fff7ed;
        color: #f97316;
    }

    .hero-bottom-content {
        margin-top: 40px;
    }

    .back-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: #64748b;
        font-weight: 700;
        font-size: 15px;
        margin-bottom: 24px;
        transition: color 0.2s;
    }

    .back-action:hover {
        color: #1e293b;
    }



    .hero-title {
        font-size: 40px;
        font-weight: 700;
        margin-bottom: 16px;
        line-height: 1.2;
    }

    .hero-meta {
        display: flex;
        gap: 24px;
        font-size: 15px;
        opacity: 0.9;
    }

    .hero-meta span {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ─── Grid Content ─── */
    .event-main-grid {
        display: grid;
        grid-template-columns: 1.8fr 1fr;
        gap: 48px;
        align-items: start;
    }

    @media (max-width: 992px) {
        .event-main-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ─── Typography & Sections ─── */
    .section-label {
        font-size: 14px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
        display: block;
    }

    .event-description {
        font-size: 16px;
        line-height: 1.8;
        color: #334155;
        margin-bottom: 40px;
        text-align: justify;
    }

    .location-box {
        background: white;
        border-radius: 16px;
        padding: 24px;
        display: flex;
        gap: 20px;
        align-items: center;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    .location-icon {
        width: 48px;
        height: 48px;
        background: #f0fdfa; /* Light mint/teal */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0d9488;
        flex-shrink: 0;
    }

    .location-info h4 {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .location-info p {
        font-size: 14px;
        color: #64748b;
    }

    .view-map-link {
        display: inline-block;
        margin-top: 8px;
        color: #0d9488;
        font-size: 14px;
        font-weight: 700;
        text-decoration: underline;
        transition: all 0.2s;
    }

    .view-map-link:hover {
        color: #0b7a6f;
        opacity: 0.8;
    }

    /* ─── Sidebar Cards ─── */
    .sidebar-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        margin-bottom: 32px;
    }

    .card-title {
        font-size: 14px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 20px;
    }

    /* Progress Box */
    .progress-box {
        background: #f0fdfa; /* Light mint */
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
    }

    .fund-box {
        background: #f8fafc;
    }

    .progress-label {
        font-size: 14px;
        font-weight: 600;
        color: #0d9488;
        margin-bottom: 8px;
    }

    .progress-stats {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 12px;
    }

    .progress-bar-bg {
        height: 8px;
        background: #e2e8f0;
        border-radius: 999px;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .progress-bar-fill {
        height: 100%;
        background: #0d9488;
        border-radius: 999px;
    }

    .fund-progress-fill {
        background: #10b981;
    }

    .fund-amount {
        font-size: 32px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .fund-subtext {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 16px;
    }

    .fund-split {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
    }

    /* Action Button */
    .btn-action {
        display: block;
        width: 100%;
        padding: 16px;
        border-radius: 12px;
        text-align: center;
        font-weight: 700;
        font-size: 16px;
        text-decoration: none;
        transition: all 0.2s;
        background: #0d9488;
        color: white;
        border: none;
        margin-bottom: 32px;
        box-shadow: 0 4px 14px rgba(13, 148, 136, 0.2);
    }

    .btn-action:hover {
        background: #0b7a6f;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(13, 148, 136, 0.3);
    }

    /* Details Table */
    .details-table {
        width: 100%;
    }

    .details-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
    }

    .details-row:last-child {
        border-bottom: none;
    }

    .details-label {
        color: #64748b;
        font-weight: 600;
    }

    .details-value {
        color: #1e293b;
        font-weight: 700;
    }

    .status-active {
        color: #10b981;
    }

    /* Organizer */
    .organizer-info {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 24px;
    }

    .organizer-avatar {
        width: 40px;
        height: 40px;
        background: #1e293b;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
    }

    .organizer-text h5 {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 2px;
    }

    .organizer-text p {
        font-size: 12px;
        color: #64748b;
    }

    /* ─── What's Provided ─── */
    .benefits-section {
        margin-top: 40px;
    }

    .benefits-grid-display {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 32px;
    }

    @media (max-width: 768px) {
        .benefits-grid-display {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .benefit-card {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        transition: all 0.2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .benefit-card:hover {
        border-color: #0d9488;
        box-shadow: 0 4px 12px rgba(13, 148, 136, 0.1);
        transform: translateY(-2px);
    }

    .benefit-card-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        background: #f0fdfa;
        flex-shrink: 0;
    }

    .benefit-card-text {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
    }
</style>
@endpush

@section('content')

<div class="event-details-container">
    <div class="main-wrapper" style="margin-top: 0;">
        <!-- Back Button ABOVE the image -->
        <a href="{{ route('participant.events.index') }}" class="back-action">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="15 18 9 12 15 6"></polyline></svg>
            Back to Events
        </a>

        <!-- Header Hero (now inside main-wrapper for container width) -->
        @php
            $heroImage = $event->eventImg ? asset('storage/'.$event->eventImg) : 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop';
            
            // Logic for "Upcoming" (View Only)
            $targetDate = $event->volunteerEvent ? $event->volunteerEvent->eventDate : $event->fundraisingCampaign?->start_date;
            $isUpcoming = $targetDate ? $targetDate->copy()->startOfDay()->gt(now()->startOfDay()->addDays(30)) : false;
        @endphp
        <div class="event-hero">
            <div class="hero-badge {{ $event->fundraisingCampaign ? 'fundraising' : '' }}">
                @if($event->fundraisingCampaign)
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    Fundraising Event
                @else
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Volunteer Event
                @endif
            </div>
            <img src="{{ $heroImage }}" alt="{{ $event->title }}" class="event-hero-img">
            <div class="hero-overlay">
                <div class="hero-bottom-content">
                    <h1 class="hero-title">{{ $event->title }}</h1>
                    <div class="hero-meta">
                        <span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            @if($event->volunteerEvent)
                                {{ $event->volunteerEvent->eventDate->format('M Y') }}
                            @else
                                {{ $event->fundraisingCampaign->end_date->format('M Y') }}
                            @endif
                        </span>
                        <span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            {{ $event->volunteerEvent?->location ?? 'Various' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="event-main-grid">
        <!-- Left Column -->
        <div class="main-content">
            <span class="section-label">About this event</span>
            <div class="event-description">
                {{ $event->eventLongDesc }}
            </div>

            @if($event->volunteerEvent && $event->volunteerEvent->benefits && count($event->volunteerEvent->benefits) > 0)
                <div class="benefits-section">
                    <span class="section-label">What's Provided</span>
                    <div class="benefits-grid-display">
                        @php
                            $benefitInfo = [
                                'food' => ['icon' => '🍽️', 'label' => 'Food & Beverages'],
                                'clothing' => ['icon' => '👕', 'label' => 'T-Shirt / Attire'],
                                'transport' => ['icon' => '🚌', 'label' => 'Transportation'],
                                'certificate' => ['icon' => '📜', 'label' => 'E-Certificate'],
                                'equipment' => ['icon' => '🎒', 'label' => 'Equipment / Kit'],
                            ];
                        @endphp
                        @foreach($event->volunteerEvent->benefits as $benefit)
                            @if(isset($benefitInfo[$benefit]))
                                <div class="benefit-card">
                                    <div class="benefit-card-icon">{{ $benefitInfo[$benefit]['icon'] }}</div>
                                    <div class="benefit-card-text">{{ $benefitInfo[$benefit]['label'] }}</div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            @if($event->volunteerEvent)
                <span class="section-label">Location</span>
                <div class="location-box">
                    <div class="location-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    </div>
                    <div class="location-info">
                        <h4>{{ $event->volunteerEvent->location }}</h4>
                        <p>{{ $event->volunteerEvent->location_details ?? 'Full address details not provided.' }}</p>
                        @if($event->volunteerEvent->location)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($event->volunteerEvent->location . ' ' . ($event->volunteerEvent->location_details ?? '')) }}" 
                               target="_blank" 
                               class="view-map-link">
                                View Map
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="sidebar">
            @if($event->volunteerEvent)
                <!-- Volunteer Section -->
                <span class="section-label">Volunteer Slots</span>
                <div class="sidebar-card">
                    <div class="progress-box">
                        <div class="progress-label">Volunteers Needed</div>
                        <div class="progress-stats">
                            {{ $event->volunteerEvent->applications->where('status', 'approved')->count() }} of {{ $event->volunteerEvent->capacity }} slots filled
                        </div>
                        <div class="progress-bar-bg">
                            @php
                                $vPct = ($event->volunteerEvent->applications->where('status', 'approved')->count() / max(1, $event->volunteerEvent->capacity)) * 100;
                            @endphp
                            <div class="progress-bar-fill" style="width: {{ min(100, $vPct) }}%"></div>
                        </div>
                    </div>

                    @if($isUpcoming)
                        <button class="btn-action" style="background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; cursor: default; box-shadow: none;" disabled>
                            Registration Opens Soon
                        </button>
                    @elseif($hasApplied)
                        <button class="btn-action" style="background: #64748b; cursor: not-allowed; box-shadow: none;" disabled>
                            Application Submitted
                        </button>
                    @elseif($isFull)
                        <button class="btn-action" style="background: #ef4444; cursor: not-allowed; box-shadow: none;" disabled>
                            Event Full
                        </button>
                    @else
                        <a href="{{ route('participant.events.apply', $event->event_id) }}" class="btn-action">
                            Apply as Volunteer
                        </a>
                    @endif

                    <span class="card-title">Event Details</span>
                    <div class="details-table">
                        <div class="details-row">
                            <span class="details-label">Date</span>
                            <span class="details-value">{{ $event->volunteerEvent->eventDate->format('d M Y') }}</span>
                        </div>
                        <div class="details-row">
                            <span class="details-label">Time</span>
                            <span class="details-value">8:00 AM</span>
                        </div>
                        <div class="details-row">
                            <span class="details-label">Status</span>
                            <span class="details-value status-active">Active</span>
                        </div>
                        <div class="details-row">
                            <span class="details-label">Volunteers</span>
                            <span class="details-value">{{ $event->volunteerEvent->applications->where('status', 'approved')->count() }}/{{ $event->volunteerEvent->capacity }}</span>
                        </div>
                    </div>

                    <div class="organizer-info">
                        <div class="organizer-avatar">UR</div>
                        <div class="organizer-text">
                            <h5>Ummah Relief Project</h5>
                            <p>Event Organizer</p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Fundraising Section -->
                <span class="section-label">Raising Goal</span>
                <div class="sidebar-card">
                    <div class="fund-amount">RM {{ number_format($event->fundraisingCampaign->target_amount, 0) }}</div>
                    
                    @php
                        $raised = $event->fundraisingCampaign->collected_amount ?? 0;
                        $target = $event->fundraisingCampaign->target_amount ?? 1;
                        $pct = round(($raised / $target) * 100);
                        $remaining = max(0, $target - $raised);
                    @endphp

                    <div class="fund-subtext">RM {{ number_format($raised, 0) }} raised · {{ $pct }}% complete</div>

                    <div class="progress-box fund-box">
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill fund-progress-fill" style="width: {{ min(100, $pct) }}%"></div>
                        </div>
                        <div class="fund-split">
                            <span>RM {{ number_format($raised, 0) }} raised</span>
                            <span>RM {{ number_format($remaining, 0) }} remaining</span>
                        </div>
                    </div>

                    @if($isUpcoming)
                        <button class="btn-action" style="background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; cursor: default; box-shadow: none;" disabled>
                            Campaign Starts Soon
                        </button>
                    @else
                        <a href="{{ route('participant.events.donate', $event->event_id) }}" class="btn-action">
                            Donate Now
                        </a>
                    @endif

                    <span class="card-title">Campaign Details</span>
                    <div class="details-table">
                        <div class="details-row">
                            <span class="details-label">End Date</span>
                            <span class="details-value">{{ $event->fundraisingCampaign->end_date->format('d M Y') }}</span>
                        </div>
                        <div class="details-row">
                            <span class="details-label">Donors</span>
                            <span class="details-value">{{ $event->fundraisingCampaign->donations->count() }} people</span>
                        </div>
                        <div class="details-row">
                            <span class="details-label">Status</span>
                            <span class="details-value status-active">Active</span>
                        </div>
                    </div>

                    <div class="organizer-info">
                        <div class="organizer-avatar">UR</div>
                        <div class="organizer-text">
                            <h5>Ummah Relief Project</h5>
                            <p>Campaign Organizer</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
