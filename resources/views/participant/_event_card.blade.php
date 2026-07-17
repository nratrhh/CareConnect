<div class="event-card">
    <div class="event-visual">
        <img src="{{ $event->eventImg ? asset('storage/'.$event->eventImg) : 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop' }}" alt="{{ $event->title }}" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop';">
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
                    {{ $event->volunteerEvent->applications->where('status', 'approved')->count() }} Volunteers
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
                        $percentage = ($event->fundraisingCampaign->collected_amount / max(1, $event->fundraisingCampaign->target_amount)) * 100;
                    } elseif($event->volunteerEvent && $event->volunteerEvent->capacity > 0) {
                        $percentage = ($event->volunteerEvent->applications->where('status', 'approved')->count() / $event->volunteerEvent->capacity) * 100;
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
                    @php
                        $validAppsCount = $event->volunteerEvent->applications->where('status', 'approved')->count();
                    @endphp
                    <h4>{{ $validAppsCount }} / {{ $event->volunteerEvent->capacity }} volunteers</h4>
                    <p>Slots left: {{ max(0, $event->volunteerEvent->capacity - $validAppsCount) }}</p>
                @endif
            </div>
            <a href="{{ route('participant.events.show', $event->event_id) }}" class="btn btn-yellow" style="padding: 8px 16px; font-size: 12px; font-weight: 700; white-space: nowrap; align-self: center;">View details</a>
        </div>
    </div>
</div>
