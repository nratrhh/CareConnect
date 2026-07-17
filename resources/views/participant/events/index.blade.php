@extends('layouts.participant')

@section('title', 'Events')

@push('styles')
<style>
    .page-header {
        margin-bottom: 24px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #1a1a2e;
    }

    .page-subtitle {
        color: #64748b;
        font-size: 18px;
        max-width: 800px;
        line-height: 1.7;
    }

    /* Filter & Search Bar */
    .controls-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 45px;
        margin-bottom: 40px;
        flex-wrap: wrap;
        gap: 24px;
    }

    .search-form {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        max-width: 500px;
    }

    .search-container {
        position: relative;
        flex: 1;
    }

    .search-input {
        width: 100%;
        padding: 10px 16px 10px 44px;
        border-radius: 999px;
        border: 1px solid #e2e8f0;
        font-size: 14px;
        transition: all 0.2s;
        background: #ffffff;
    }

    .search-input:focus {
        outline: none;
        border-color: #00827F;
        box-shadow: 0 0 0 3px rgba(0, 130, 127, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .btn-search {
        padding: 10px 24px;
        border-radius: 999px;
        background: #00827F;
        color: white;
        border: none;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: opacity 0.2s;
    }

    .btn-search:hover {
        opacity: 0.9;
    }

    .filter-section {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .filter-label {
        font-size: 14px;
        font-weight: 600;
        color: #475569;
        white-space: nowrap;
    }

    .filter-pills {
        display: flex;
        gap: 10px;
    }

    .filter-pill {
        padding: 8px 20px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 600;
        color: #475569;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .filter-pill:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .filter-pill.active {
        background: #00827F;
        color: #ffffff;
        border-color: #00827F;
    }

    /* Event Grid */
    .event-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 32px;
    }

    .event-card {
        background: var(--white);
        border-radius: 24px;
        border: 1px solid var(--border);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .event-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .event-visual {
        height: 220px;
        position: relative;
        overflow: hidden;
    }

    .event-visual img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .overlay-fundraising {
        background: transparent;
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .overlay-volunteer {
        background: transparent;
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .overlay-icon {
        font-size: 70px;
        opacity: 0.15;
        color: var(--dark);
    }

    .type-label {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 10;
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--white);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .label-fundraising { background: #F97316; }
    .label-volunteer   { background: #00827F; }

    .upcoming-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 10;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 800;
        background: rgba(30, 41, 59, 0.8);
        color: white;
        backdrop-filter: blur(4px);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .event-content {
        padding: 24px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .card-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 13px;
        color: var(--secondary);
    }

    .card-meta span {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .card-location {
        font-size: 13px;
        color: var(--secondary);
        margin-bottom: 12px;
        display: flex;
        align-items: flex-start;
        gap: 6px;
        min-height: 38px; /* Height for ~2 lines of text */
        line-height: 1.4;
    }

    .card-location svg {
        margin-top: 2px;
        flex-shrink: 0;
    }

    .card-title {
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 10px;
        line-height: 1.4;
        color: var(--dark);
        min-height: 52px;
        display: flex;
        align-items: center;
    }

    .card-desc {
        font-size: 14px;
        color: var(--secondary);
        margin-bottom: 24px;
        line-height: 1.6;
        flex-grow: 1;
    }

    .progress-container {
        margin-bottom: 16px;
        margin-top: auto;
    }

    .progress-bar-bg {
        height: 6px;
        background: #f1f5f9;
        border-radius: 999px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 999px;
    }

    .fill-fundraising { background: #F97316; }
    .fill-volunteer   { background: #00827F; }

    .card-footer-stats {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
    }

    .stat-text h4 {
        font-size: 13px;
        font-weight: 700;
        color: var(--dark);
    }

    .stat-text p {
        font-size: 11px;
        color: var(--secondary);
        margin-top: 2px;
    }

    .pagination-container {
        margin-top: 60px;
    }

    /* ─── Responsive ─── */
    @media (max-width: 1024px) {
        .event-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
    }

    @media (max-width: 768px) {
        .controls-container {
            flex-direction: column;
            align-items: stretch;
            gap: 20px;
        }
        .search-form {
            max-width: 100%;
        }
        .filter-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        .filter-pills {
            flex-wrap: wrap;
        }
        .event-grid {
            grid-template-columns: 1fr;
        }
        .page-title {
            font-size: 24px;
        }
        .page-subtitle {
            font-size: 15px;
        }
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <h1 class="page-title">Explore Events</h1>
    <p class="page-subtitle">Discover opportunities to make a real difference in your local community through volunteering and fundraising.</p>
</div>

<div class="controls-container">
    <form action="{{ route('participant.events.index') }}" method="GET" class="search-form">
        @if($currentType)
            <input type="hidden" name="type" value="{{ $currentType }}">
        @endif
        <div class="search-container">
            <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" name="search" class="search-input" placeholder="Search events..." value="{{ $search }}">
        </div>
        <button type="submit" class="btn-search">Search</button>
    </form>

    <div class="filter-section">
        <span class="filter-label">Filter by:</span>
        <div class="filter-pills">
            <a href="{{ route('participant.events.index', ['search' => $search]) }}" 
               class="filter-pill {{ !$currentType ? 'active' : '' }}">All Events</a>

            <a href="{{ route('participant.events.index', ['type' => 'active', 'search' => $search]) }}" 
               class="filter-pill {{ $currentType === 'active' ? 'active' : '' }}">Active Events</a>

            <a href="{{ route('participant.events.index', ['type' => 'upcoming', 'search' => $search]) }}" 
               class="filter-pill {{ $currentType === 'upcoming' ? 'active' : '' }}">Upcoming Events</a>
            
            <a href="{{ route('participant.events.index', ['type' => 'fundraising', 'search' => $search]) }}" 
               class="filter-pill {{ $currentType === 'fundraising' ? 'active' : '' }}">Fundraising</a>
            
            <a href="{{ route('participant.events.index', ['type' => 'volunteer', 'search' => $search]) }}" 
               class="filter-pill {{ $currentType === 'volunteer' ? 'active' : '' }}">Volunteer</a>
        </div>
    </div>
</div>

<div class="event-grid">
    @forelse($events as $event)
    <div class="event-card">
        <div class="event-visual">
            <img src="{{ $event->eventImg ? asset('storage/'.$event->eventImg) : 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop' }}" alt="{{ $event->title }}">
            @if($event->fundraisingCampaign)
                <div class="type-label label-fundraising">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    Fundraising
                </div>
            @else
                <div class="type-label label-volunteer">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                    Volunteer
                </div>
            @endif

            @php
                $isUpcoming = false;
                if($event->volunteerEvent) {
                    $isUpcoming = \Carbon\Carbon::parse($event->volunteerEvent->eventDate)->gt(now()->addDays(30));
                } elseif($event->fundraisingCampaign) {
                    $isUpcoming = \Carbon\Carbon::parse($event->fundraisingCampaign->start_date)->gt(now()->addDays(30));
                }
            @endphp

            @if($isUpcoming)
                <div class="upcoming-badge">
                    Upcoming
                </div>
            @endif
        </div>

        <div class="event-content">
            <div class="card-meta">
                <span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    @if($event->volunteerEvent)
                        {{ \Carbon\Carbon::parse($event->volunteerEvent->eventDate)->format('M d, Y') }}
                    @else
                        End: {{ \Carbon\Carbon::parse($event->fundraisingCampaign->end_date)->format('M d, Y') }}
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

            @if($event->volunteerEvent?->location)
            <div class="card-location">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                {{ $event->volunteerEvent->location }}
            </div>
            @else
            <div class="card-location">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                {{ $event->eventCategory }} Campaign
            </div>
            @endif

            <h3 class="card-title">{{ $event->title }}</h3>
            <p class="card-desc">{{ $event->eventShortDesc ?? $event->eventLongDesc }}</p>

            <div class="progress-container">
                <div class="progress-bar-bg">
                    @php
                        $percentage = 0;
                        if($event->fundraisingCampaign) {
                            $percentage = ($event->fundraisingCampaign->collected_amount / $event->fundraisingCampaign->target_amount) * 100;
                        } elseif($event->volunteerEvent && $event->volunteerEvent->capacity > 0) {
                            $percentage = ($event->volunteerEvent->applications->count() / $event->volunteerEvent->capacity) * 100;
                        }
                    @endphp
                    <div class="progress-bar-fill {{ $event->fundraisingCampaign ? 'fill-fundraising' : 'fill-volunteer' }}" style="width: {{ min(100, $percentage) }}%;"></div>
                </div>
            </div>

            <div class="card-footer-stats">
                <div class="stat-text">
                    @if($event->fundraisingCampaign)
                        <h4>RM {{ number_format($event->fundraisingCampaign->collected_amount, 0) }} raised</h4>
                        <p>Goal: RM {{ number_format($event->fundraisingCampaign->target_amount, 0) }}</p>
                    @else
                        <h4>{{ $event->volunteerEvent->applications->count() }} / {{ $event->volunteerEvent->capacity }} volunteers</h4>
                        <p>Slots left: {{ max(0, $event->volunteerEvent->capacity - $event->volunteerEvent->applications->count()) }}</p>
                    @endif
                </div>
                <a href="{{ route('participant.events.show', $event->event_id) }}" class="btn btn-yellow" style="padding: 10px 18px; font-size: 13px;">View details</a>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column: span 3; text-align: center; padding: 100px 0;">
        <div style="background: var(--white); padding: 40px; border-radius: 20px; border: 1px dashed var(--border);">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--secondary); margin-bottom: 16px;"><circle cx="12" cy="12" r="10"></circle><line x1="8" y1="12" x2="16" y2="12"></line></svg>
            <h3 style="font-size: 18px; margin-bottom: 8px;">No events found</h3>
            <p style="color: var(--secondary); font-size: 14px;">Try adjusting your filters or search terms.</p>
        </div>
    </div>
    @endforelse
</div>

<div class="pagination-container">
    {{ $events->links() }}
</div>

@endsection
