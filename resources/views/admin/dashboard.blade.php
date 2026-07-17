@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        flex-shrink: 0;
    }
    .stat-card {
        background: #fff;
        border: 0.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 22px;
        display: flex;
        align-items: center;
        gap: 18px;
        min-width: 0; /* Allow shrinking */
    }

    @media (max-width: 1400px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    
    @media (max-width: 640px) {
        .stats-grid { grid-template-columns: 1fr; }
        .stat-card { padding: 18px; gap: 14px; }
    }
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .stat-icon svg { width: 26px; height: 26px; }
    .stat-label { font-size: 13px; color: #64748b; margin-bottom: 5px; }
    .stat-value { 
        font-size: 24px; /* Slightly smaller default */
        font-weight: 700; 
        color: #1a1a2e; 
        line-height: 1.2; 
        word-break: break-word; 
    }
    
    @media (max-width: 1200px) {
        .stat-value { font-size: 20px; }
    }
    .stat-delta { font-size: 12px; margin-top: 6px; }
    .delta-up { color: #0F6E56; }
    .delta-warn { color: #BA7517; }

    /* Breakdown Styles */
    .stat-card-expandable { flex-direction: column; align-items: stretch; gap: 0; }
    .stat-card-header { display: flex; align-items: center; gap: 18px; }
    .stat-breakdown { margin-top: 16px; padding-top: 14px; border-top: 1px dashed #e2e8f0; }
    .breakdown-title { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    .breakdown-item { display: flex; justify-content: space-between; gap: 10px; margin-bottom: 6px; }
    .breakdown-name { font-size: 12px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .breakdown-val { font-size: 12px; font-weight: 600; color: #1a1a2e; flex-shrink: 0; }

    .main-row {
        display: grid;
        grid-template-columns: 1.8fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    @media (max-width: 1024px) {
        .main-row { grid-template-columns: 1fr; }
    }

    .second-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }

    .card-flex {
        background: #fff;
        border: 0.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px 26px;
        display: flex;
        flex-direction: column;
        min-height: 0;
        overflow: hidden;
    }

    /* Calendar */
    .cal-nav-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        flex-shrink: 0;
    }
    .cal-month { font-size: 15px; font-weight: 600; color: #1a1a2e; }
    .cal-btn {
        width: 34px;
        height: 34px;
        border-radius: 6px;
        border: 0.5px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 18px;
        color: #64748b;
        background: transparent;
    }
    .cal-btn:hover { background: #f0f2f5; }
    .cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
        text-align: center;
    }
    .cal-dl { font-size: 12px; color: #94a3b8; padding: 8px 0; font-weight: 600; }
    .cal-d {
        font-size: 14px;
        color: #1a1a2e;
        padding: 10px 4px;
        border-radius: 8px;
        cursor: pointer;
        position: relative;
        line-height: 1;
    }
    .cal-d:hover { background: #f0f2f5; }
    .cal-d.today {
        background: var(--accent);
        color: #fff;
        border-radius: 50%;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        padding: 0;
        font-weight: 600;
    }
    .cal-d.has-ev::after {
        content: '';
        position: absolute;
        bottom: 3px;
        left: 50%;
        transform: translateX(-50%);
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: var(--accent);
    }
    .cal-d.muted { color: #cbd5e1; }

    /* Tooltip */
    .cal-tooltip {
        position: absolute;
        background: rgba(26, 26, 46, 0.95);
        backdrop-filter: blur(10px);
        color: #fff;
        padding: 12px 16px;
        border-radius: 14px;
        font-size: 13px;
        z-index: 1000;
        width: 240px;
        pointer-events: none;
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateY(10px) scale(0.95);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .cal-tooltip.visible {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    .tooltip-ev-item {
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }
    .tooltip-ev-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    .tooltip-accent {
        width: 4px;
        height: 100%;
        min-height: 40px;
        border-radius: 2px;
        flex-shrink: 0;
    }
    .tooltip-content {
        flex: 1;
    }
    .tooltip-header {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
    }
    .tooltip-type {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.5);
    }
    .tooltip-title {
        font-weight: 600;
        display: block;
        line-height: 1.4;
        color: #fff;
        font-size: 14px;
    }
    .tooltip-footer {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 6px;
        color: rgba(255, 255, 255, 0.7);
        font-size: 11px;
    }
    .tooltip-footer svg {
        width: 12px;
        height: 12px;
        opacity: 0.8;
    }
    .cal-legend {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 16px;
        flex-shrink: 0;
    }

    /* Events list */
    .ev-scroll { 
        flex: 1; 
        overflow-y: auto; 
        min-height: 0; 
        position: relative;
    }
    .ev-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px 0;
        border-bottom: 0.5px solid #f1f5f9;
        position: relative;
        z-index: 1;
    }
    /* Dynamic Timeline Line */
    .ev-item::after {
        content: '';
        position: absolute;
        left: 5.25px; /* Centered for 12px dot */
        top: 0;
        bottom: 0;
        width: 1.5px;
        background: #cbd5e1;
        z-index: 0;
    }
    .ev-item:first-child::after { top: 22px; } 
    .ev-item:last-child::after { height: 22px; bottom: auto; } 
    .ev-item:only-child::after { display: none; } 

    .ev-item:last-child { border-bottom: none; }
    .ev-dot { 
        width: 12px; 
        height: 12px; 
        border-radius: 50%; 
        flex-shrink: 0; 
        z-index: 2;
        margin-top: 4px;
        border: 2px solid #fff;
        box-shadow: 0 0 0 1px rgba(0,0,0,0.05);
    }
    .ev-name { font-size: 14px; font-weight: 500; color: #1a1a2e; }
    .ev-meta { font-size: 12px; color: #64748b; margin-top: 3px; }

    .badge {
        font-size: 10px;
        padding: 3px 10px;
        border-radius: 999px;
        font-weight: 500;
        white-space: nowrap;
        margin-left: auto;
        flex-shrink: 0;
    }
    .badge-fundraising { background: #E6F2F2; color: #00827F; }
    .badge-volunteer   { background: #EDE9FE; color: #6D28D9; }
    .badge-open        { background: #e0f7f5; color: #0F6E56; }

    .page-heading { flex-shrink: 0; }
    .page-heading h2 { font-size: 24px; font-weight: 600; color: #1a1a2e; }
    .page-heading p { font-size: 14px; color: #64748b; margin-top: 5px; }
</style>
@endpush

@section('content')

<div class="page-heading">
    <h2>Dashboard</h2>
    <p>{{ \Carbon\Carbon::now()->format('l, d F Y') }} &nbsp;·&nbsp; Welcome back, {{ auth()->user()->name ?? 'Admin' }}</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4;">
            <svg viewBox="0 0 24 24" fill="#10b981"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
        </div>
        <div>
            <div class="stat-label">Total Events</div>
            <div class="stat-value">{{ $totalEvents }}</div>
            <div class="stat-delta delta-up">↑ active this month</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f5f3ff;">
            <svg viewBox="0 0 24 24" fill="#8b5cf6"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
        </div>
        <div>
            <div class="stat-label">Funds Raised</div>
            <div class="stat-value">RM {{ number_format($totalFunds, 2) }}</div>
            <div class="stat-delta delta-up">↑ across {{ $totalFundraisingCampaigns }} campaigns</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0f9ff;">
            <svg viewBox="0 0 24 24" fill="#0ea5e9"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
        </div>
        <div>
            <div class="stat-label">Volunteers</div>
            <div class="stat-value">{{ $totalVolunteers }}</div>
            <div class="stat-delta delta-up">↑ across {{ $totalVolunteerEvents }} events</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FAEEDA;">
            <svg viewBox="0 0 24 24" fill="#854F0B"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.89 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm-1 7V3.5L18.5 9H13zm-1 9H7v-2h5v2zm3-4H7v-2h7v2z"/></svg>
        </div>
        <div>
            <div class="stat-label">Pending Volunteer Applications</div>
            <div class="stat-value" style="color:#BA7517;">{{ $pendingApplications }}</div>
            <div class="stat-delta delta-warn">requires review</div>
        </div>
    </div>
</div>

<div class="main-row" style="grid-template-columns: 2fr 1.2fr; margin-bottom: 24px;">
    <!-- Line Chart for Donations -->
    <div class="card-flex">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <div class="card-title" style="font-size:16px;font-weight:700;color:#1a1a2e;margin:0;">Monthly Donations (Last 6 Months)</div>
            <a href="{{ route('admin.fundraising.index') }}" style="font-size: 13px; color: #6D28D9; text-decoration: none; font-weight: 600;">View All Fundraising</a>
        </div>
        <div style="position: relative; height: 300px; width: 100%;">
            <canvas id="donationsChart"></canvas>
        </div>
    </div>

    <!-- Active Events (right side) -->
    <div class="card-flex">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <div class="card-title" style="font-size:16px;font-weight:700;color:#1a1a2e;margin:0;">Active Events</div>
            <a href="{{ route('admin.events.index') }}" style="font-size: 13px; color: #6D28D9; text-decoration: none; font-weight: 600;">View All</a>
        </div>
        <div class="ev-scroll">
            @forelse($activeEvents as $event)
            <div class="ev-item" style="padding: 16px 0;">
                @if($event->fundraisingCampaign)
                    <div class="ev-dot" style="background: #00827F;"></div>
                @else
                    <div class="ev-dot" style="background: #6D28D9;"></div>
                @endif
                <div style="flex:1; display:flex; justify-content:space-between; align-items:flex-start; gap: 16px;">
                    <div>
                        <div class="ev-name" style="margin-bottom: 4px; font-weight: 600; color: #1a1a2e; line-height: 1.3;">{{ $event->title }}</div>
                        <div class="ev-meta" style="color: #94a3b8; font-size: 12px;">
                            @if($event->fundraisingCampaign && $event->fundraisingCampaign->start_date && $event->fundraisingCampaign->end_date)
                                {{ $event->fundraisingCampaign->start_date->format('d M') }} - {{ $event->fundraisingCampaign->end_date->format('d M Y') }}
                            @elseif($event->volunteerEvent && $event->volunteerEvent->eventDate)
                                {{ $event->volunteerEvent->eventDate->format('d M Y') }}
                            @else
                                N/A
                            @endif
                            &nbsp;&middot;&nbsp; {{ $event->volunteerEvent?->location ?? 'Various' }}
                        </div>
                    </div>
                    <div>
                        @if($event->fundraisingCampaign)
                            <span class="badge" style="background: #e0f7f5; color: #00827F; padding: 4px 12px; font-size: 11px; border-radius: 99px; font-weight: 600;">Fundraising</span>
                        @elseif($event->volunteerEvent)
                            <span class="badge" style="background: #f3e8ff; color: #7e22ce; padding: 4px 12px; font-size: 11px; border-radius: 99px; font-weight: 600;">Volunteer</span>
                        @else
                            <span class="badge" style="background: #f1f5f9; color: #64748b; padding: 4px 12px; font-size: 11px; border-radius: 99px; font-weight: 600;">{{ ucfirst($event->status) }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:40px 0;color:#94a3b8;font-size:13px;">No active events at the moment.</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Donations Table -->
<!-- Recent Activities Table -->
<div class="main-row" style="grid-template-columns: 1fr;">
    <div class="card-flex" style="padding: 0;">
        <div style="display: flex; align-items: center; gap: 10px; padding: 24px 26px; border-bottom: 1px solid #f1f5f9;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#BA7517" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
            <div class="card-title" style="font-size:18px;font-weight:700;color:#1a1a2e;margin:0;">Recent Activities</div>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 14px 26px; font-size: 13px; font-weight: 600; color: #64748b;">Date</th>
                        <th style="padding: 14px 16px; font-size: 13px; font-weight: 600; color: #64748b;">Name</th>
                        <th style="padding: 14px 16px; font-size: 13px; font-weight: 600; color: #64748b;">Event Name</th>
                        <th style="padding: 14px 16px; font-size: 13px; font-weight: 600; color: #64748b;">Category</th>
                        <th style="padding: 14px 26px; font-size: 13px; font-weight: 600; color: #64748b;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentActivities as $activity)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 18px 26px; font-size: 14px; color: #64748b;">
                                {{ $activity['date'] ? \Carbon\Carbon::parse($activity['date'])->format('M d, Y') : 'N/A' }}
                            </td>
                            <td style="padding: 18px 16px; font-size: 14px; color: #1a1a2e; font-weight: 600;">
                                {{ $activity['name'] }}
                            </td>
                            <td style="padding: 18px 16px; font-size: 14px; color: #64748b;">
                                {{ $activity['event_name'] }}
                            </td>
                            <td style="padding: 18px 16px; font-size: 14px; font-weight: 600;">
                                @if($activity['category'] === 'Donation')
                                    <span style="color: #00827F;">Donation</span>
                                @elseif($activity['category'] === 'Volunteer')
                                    <span style="color: #6D28D9;">Volunteer</span>
                                @else
                                    <span style="color: #BA7517;">Event</span>
                                @endif
                            </td>
                            <td style="padding: 18px 26px;">
                                @php
                                    $status = strtolower($activity['status']);
                                @endphp
                                @if(in_array($status, ['succeeded', 'completed', 'approved']))
                                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background: #e0f7f5; color: #0F6E56; border-radius: 99px; font-size: 12px; font-weight: 600;">
                                        <div style="width: 5px; height: 5px; border-radius: 50%; background: #0F6E56;"></div>
                                        {{ ucfirst($status) }}
                                    </span>
                                @elseif(in_array($status, ['pending', 'processing']))
                                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background: #fef3c7; color: #d97706; border-radius: 99px; font-size: 12px; font-weight: 600;">
                                        <div style="width: 5px; height: 5px; border-radius: 50%; background: #d97706;"></div>
                                        {{ ucfirst($status) }}
                                    </span>
                                @else
                                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background: #fee2e2; color: #ef4444; border-radius: 99px; font-size: 12px; font-weight: 600;">
                                        <div style="width: 5px; height: 5px; border-radius: 50%; background: #ef4444;"></div>
                                        {{ ucfirst($status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 40px; text-align: center; color: #94a3b8; font-size: 14px;">No recent activities available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ─── Donations Chart ───
    const donationCtx = document.getElementById('donationsChart').getContext('2d');
    
    // Create gradient
    const gradient = donationCtx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(0, 130, 127, 0.4)');
    gradient.addColorStop(1, 'rgba(0, 130, 127, 0.0)');

    new Chart(donationCtx, {
        type: 'line',
        data: {
            labels: @json($donationChartLabels),
            datasets: [{
                label: 'Total Donations (RM)',
                data: @json($donationChartData),
                borderColor: '#00827F',
                backgroundColor: gradient,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#00827F',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ' RM ' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [4, 4], color: '#f1f5f9', drawBorder: false },
                    ticks: {
                        color: '#64748b',
                        callback: function(value) { return 'RM ' + value; }
                    }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { color: '#64748b' }
                }
            }
        }
    });



    const eventCalendarData = @json($eventCalendarData);
    let currentYear  = {{ \Carbon\Carbon::now()->year }};
    let currentMonth = {{ \Carbon\Carbon::now()->month - 1 }};
    const todayDate  = {{ \Carbon\Carbon::now()->day }};
    const todayMonth = {{ \Carbon\Carbon::now()->month - 1 }};
    const todayYear  = {{ \Carbon\Carbon::now()->year }};
    const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

    const tooltip = document.getElementById('calTooltip');

    function renderCalendar(year, month) {
        document.getElementById('calMonthLabel').textContent = months[month] + ' ' + year;
        const grid = document.getElementById('calGrid');
        while (grid.children.length > 7) grid.removeChild(grid.lastChild);
        const firstDay    = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const prevDays    = new Date(year, month, 0).getDate();
        for (let i = firstDay - 1; i >= 0; i--) {
            const d = document.createElement('div');
            d.className = 'cal-d muted';
            d.textContent = prevDays - i;
            grid.appendChild(d);
        }
        for (let d = 1; d <= daysInMonth; d++) {
            const cell    = document.createElement('div');
            const dateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');
            const isToday = (d === todayDate && month === todayMonth && year === todayYear);
            
            const eventsForDate = eventCalendarData[dateStr] || [];
            const hasEv = eventsForDate.length > 0;

            cell.className = 'cal-d' + (isToday ? ' today' : '') + (hasEv && !isToday ? ' has-ev' : '');
            cell.textContent = d;

            if (hasEv) {
                cell.onmouseenter = (e) => {
                    let html = '';
                    eventsForDate.forEach(ev => {
                        const accentColor = ev.type === 'Volunteer' ? '#6D28D9' : '#00827F';
                        const icon = ev.type === 'Volunteer' 
                            ? `<svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>`
                            : `<svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>`;
                        
                        html += `
                            <div class="tooltip-ev-item">
                                <div class="tooltip-accent" style="background: ${accentColor}"></div>
                                <div class="tooltip-content">
                                    <div class="tooltip-header">
                                        <span class="tooltip-type">${ev.type}</span>
                                    </div>
                                    <span class="tooltip-title">${ev.title}</span>
                                    <div class="tooltip-footer">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        <span>${ev.dday}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    tooltip.innerHTML = html;
                    tooltip.classList.add('visible');
                    
                    // Position tooltip
                    const rect = cell.getBoundingClientRect();
                    const parentRect = cell.offsetParent.getBoundingClientRect();
                    
                    // Center horizontal
                    let left = (rect.left - parentRect.left + rect.width / 2 - 120);
                    // Prevent overflow left
                    if (left < 10) left = 10;
                    // Prevent overflow right
                    if (left + 240 > parentRect.width - 10) left = parentRect.width - 250;
                    
                    tooltip.style.left = left + 'px';
                    tooltip.style.top = (rect.top - parentRect.top - tooltip.offsetHeight - 12) + 'px';
                };
                cell.onmouseleave = () => {
                    tooltip.classList.remove('visible');
                };
            }

            grid.appendChild(cell);
        }
        const totalCells = firstDay + daysInMonth;
        const remainder  = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
        for (let i = 1; i <= remainder; i++) {
            const d = document.createElement('div');
            d.className = 'cal-d muted';
            d.textContent = i;
            grid.appendChild(d);
        }
    }

    function prevMonth() {
        currentMonth--;
        if (currentMonth < 0) { currentMonth = 11; currentYear--; }
        renderCalendar(currentYear, currentMonth);
    }

    function nextMonth() {
        currentMonth++;
        if (currentMonth > 11) { currentMonth = 0; currentYear++; }
        renderCalendar(currentYear, currentMonth);
    }

    renderCalendar(currentYear, currentMonth);
</script>
@endpush