@extends('layouts.participant')

@section('title', 'My Activities')

@push('styles')
<style>
    .stats-row {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        gap: 24px !important;
        margin-bottom: 40px !important;
        width: 100% !important;
    }

    .stat-card {
        flex: 1;
        min-width: 0;
        background: var(--white);
        border-radius: 16px;
        padding: 24px 32px;
        border: 1px solid var(--border);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 20px;
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-4px);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .stat-info h4 {
        font-size: 12px;
        font-weight: 700;
        color: var(--secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--dark);
        line-height: 1.2;
    }

    /* Tabs */
    .table-section {
        background: var(--white);
        border-radius: 20px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .tabs-header {
        display: flex;
        border-bottom: 1px solid var(--border);
        background: var(--light);
    }

    .tab-btn {
        padding: 20px 32px;
        font-size: 15px;
        font-weight: 700;
        color: var(--secondary);
        border: none;
        background: transparent;
        cursor: pointer;
        transition: all 0.2s;
        border-right: 1px solid var(--border);
    }

    .tab-btn.active {
        background: var(--white);
        color: var(--primary);
        box-shadow: inset 0 -3px 0 var(--primary);
    }

    .tab-content {
        display: none;
        padding: 0;
    }

    .tab-content.active {
        display: block;
    }

    /* Table Styling */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        text-align: left;
        padding: 16px 24px;
        font-size: 13px;
        font-weight: 600;
        color: var(--secondary);
        background: #F8FAFC;
        border-bottom: 1px solid var(--border);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        padding: 20px 24px;
        font-size: 14px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover {
        background: #F8FAFC;
    }

    .activity-cell {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .activity-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .icon-donation {
        background: #EFF6FF;
        color: #2563EB;
        border-radius: 50%;
    }

    .icon-volunteer {
        background: #F0FDF4;
        color: #16A34A;
    }

    .activity-name {
        font-weight: 600;
        color: var(--dark);
        font-size: 15px;
    }

    .activity-meta {
        font-size: 13px;
        color: var(--secondary);
        margin-top: 4px;
    }

    .receipt-link {
        color: var(--primary);
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--white);
        transition: all 0.2s;
        width: fit-content;
    }

    .receipt-link:hover {
        background: var(--light);
        border-color: var(--secondary);
    }

    .status-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 999px;
        text-transform: uppercase;
        display: inline-block;
    }
    
    .status-pending { background: #FFFBEB; color: #92400E; }
    .status-approved { background: #ECFDF5; color: #065F46; }
    .status-rejected, .status-declined { background: #FEF2F2; color: #991B1B; }
    .status-cancelled { background: #F3F4F6; color: #6B7280; }

    .action-link {
        color: var(--primary);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .action-link:hover {
        text-decoration: underline;
    }

    /* Modal Styling */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(4px);
        z-index: 1000;
        align-items: flex-start;
        justify-content: center;
        overflow-y: auto;
        padding: 40px 20px;
    }

    .modal-content {
        background: #fff;
        width: 100%;
        max-width: 600px;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-header {
        padding: 24px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
    }

    .pill-tag {
        display: inline-block;
        padding: 4px 10px;
        background: #f3f4f6;
        color: #4b5563;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        margin-right: 4px;
        margin-bottom: 4px;
    }

    .modal-close-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #f3f4f6;
        color: #4b5563;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .modal-close-btn:hover { background: #e5e7eb; }

    .modal-body { padding: 24px; }

    .modal-banner {
        width: 100%;
        height: 240px;
        border-radius: 12px;
        object-fit: cover;
        margin-bottom: 24px;
        background: #f3f4f6;
    }

    .detail-table {
        width: 100%;
        border-collapse: collapse;
    }

    .detail-row {
        border-bottom: 1px solid #f3f4f6;
    }

    .detail-row:last-child { border-bottom: none; }

    .detail-label {
        padding: 16px 0;
        width: 140px;
        font-size: 13px;
        color: #6b7280;
        vertical-align: top;
    }

    .detail-value {
        padding: 16px 0;
        font-size: 14px;
        color: #111827;
        line-height: 1.5;
    }

    .detail-value.bold {
        font-weight: 700;
    }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #f3f4f6;
        display: flex;
        justify-content: flex-end;
    }

    .btn-close-modal {
        padding: 10px 24px;
        border-radius: 8px;
        background: #00827F;
        color: #fff;
        font-weight: 700;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-close-modal:hover { background: #006b68; }
</style>
@endpush

@section('content')

<div style="margin-bottom: 40px;">
    <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">My Activities</h1>
    <p style="color: var(--secondary); font-size: 18px; line-height: 1.7; max-width: 800px;">Track your journey of impact.<br> Here you can find your volunteer activities and financial contributions to the community.</p>
</div>

<div class="stats-row" style="display: flex !important; flex-direction: row !important; gap: 24px !important; margin-bottom: 40px !important; width: 100% !important; flex-wrap: nowrap !important;">
    <!-- Total Donated -->
    <div class="stat-card" style="flex: 1 !important; display: flex !important; flex-direction: row !important; align-items: center !important;">
        <div class="stat-icon" style="background: #e0f7f5; color: #0F6E56;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path></svg>
        </div>
        <div class="stat-info">
            <h4>Total Donations</h4>
            <div class="stat-value">RM {{ number_format($totalDonated, 2) }}</div>
        </div>
    </div>
    
    <!-- Events Joined -->
    <div class="stat-card" style="flex: 1 !important; display: flex !important; flex-direction: row !important; align-items: center !important;">
        <div class="stat-icon" style="background: #EFF6FF; color: #2563EB;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div class="stat-info">
            <h4>Events Joined</h4>
            <div class="stat-value">{{ $totalVolunteerEvents }}</div>
        </div>
    </div>
</div>

<div class="table-section">
    <div class="tabs-header">
        <button class="tab-btn active" onclick="openTab(event, 'donationsTab')">Donation History</button>
        <button class="tab-btn" onclick="openTab(event, 'applicationsTab')">Volunteer Applications</button>
    </div>

    <div id="donationsTab" class="tab-content active">
        <table>
            <thead>
                <tr>
                    <th>Campaign</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donations as $donation)
                <tr>
                    <td>
                        <div class="activity-cell">
                            <div class="activity-icon icon-donation">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                            </div>
                            <div>
                                <div class="activity-name">{{ $donation->fundraisingCampaign->event->title ?? 'N/A' }}</div>
                                <div class="activity-meta">One-time Donation</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $donation->donation_date->format('d M Y') }}</td>
                    <td>
                        <span style="font-weight: 700; color: var(--dark); font-size: 15px; display: block;">RM {{ number_format($donation->amount, 2) }}</span>
                        <span style="font-size: 11px; text-transform: uppercase; color: var(--secondary);">Tax Deductible</span>
                    </td>
                    <td>{{ $donation->payment_method }}</td>
                    <td>
                        @php
                            $statusClass = match($donation->status) {
                                'succeeded' => 'background: #ecfdf5; color: #059669;',
                                'pending' => 'background: #fffbeb; color: #d97706;',
                                'failed' => 'background: #fef2f2; color: #dc2626;',
                                default => 'background: #f1f5f9; color: #64748b;'
                            };
                        @endphp
                        <span style="display: inline-block; padding: 4px 10px; border-radius: 99px; font-size: 11px; font-weight: 700; text-transform: uppercase; {{ $statusClass }}">
                            {{ $donation->status }}
                        </span>
                    </td>
                    <td>
                        @if($donation->status === 'succeeded')
                            <a href="{{ route('participant.activities.receipt', $donation->donation_id) }}" class="receipt-link">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                View Receipt
                            </a>
                        @else
                            <button class="receipt-link" style="background: #f1f5f9; color: #94a3b8; cursor: not-allowed; border: none; font-family: inherit;" disabled>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                No Receipt
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 48px; color: var(--secondary);">You haven't made any donations yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="applicationsTab" class="tab-content">
        <table>
            <thead>
                <tr>
                    <th>Volunteer Event</th>
                    <th>Date Applied</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr>
                    <td>
                        <div class="activity-cell">
                            <div class="activity-icon icon-volunteer">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                            </div>
                            <div>
                                <div class="activity-name">{{ $app->volunteerEvent->event->title ?? 'N/A' }}</div>
                                <div class="activity-meta">{{ $app->volunteerEvent->location ?? 'Volunteer Opportunity' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $app->applied_at->format('d M Y') }}</td>
                    <td>
                        @php
                            $statusClass = match($app->status) {
                                'pending' => 'status-pending',
                                'approved' => 'status-approved',
                                'rejected', 'declined' => 'status-rejected',
                                'cancelled' => 'status-cancelled',
                                default => '',
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ $app->status }}</span>
                    </td>
                    <td style="border-bottom: none;">
                        <button class="receipt-link" style="cursor: pointer; font-family: inherit; font-size: inherit;"
                                data-app-id="{{ $app->application_id }}"
                                data-title="{{ $app->volunteerEvent->event->title }}"
                                data-date="{{ $app->applied_at->format('d M Y') }}"
                                data-status="{{ $app->status }}"
                                data-skills="{{ json_encode($app->skills ?? []) }}"
                                data-notes="{{ $app->notes ?? 'No notes provided.' }}"
                                data-decline-reason="{{ $app->decline_reason ?? '' }}"
                                data-edit-url="{{ route('participant.applications.edit', $app->application_id) }}"
                                data-cancel-url="{{ route('participant.applications.destroy', $app->application_id) }}"
                                onclick="showApplicationDetails(this)">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                            View Application
                        </button>
                        
                        @php
                            $hasCert = is_array(optional($app->volunteerEvent)->benefits) && in_array('certificate', $app->volunteerEvent->benefits);
                        @endphp
                        @if($app->status === 'approved' && optional($app->volunteerEvent->event)->status === 'completed' && $hasCert)
                        <a href="{{ route('participant.activities.certificate', $app->application_id) }}" target="_blank" class="receipt-link" style="margin-top: 8px; color: #b45309; border-color: #fde68a; background: #fffbeb;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                            E-Certificate
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 48px; color: var(--secondary);">You haven't applied for any volunteer events yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Event Details Modal -->
<div id="eventModal" class="modal-overlay" onclick="closeModal(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>Event Details</h3>
            <div class="modal-close-btn" onclick="closeModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </div>
        </div>
        <div class="modal-body">
            <img id="modalImg" src="" alt="Event Banner" class="modal-banner">
            
            <table class="detail-table">
                <tr class="detail-row">
                    <td class="detail-label">Event ID</td>
                    <td id="modalId" class="detail-value">3</td>
                </tr>
                <tr class="detail-row">
                    <td class="detail-label">Title</td>
                    <td id="modalTitle" class="detail-value bold">Asnaf Student School Aid Fund</td>
                </tr>
                <tr class="detail-row">
                    <td class="detail-label">Category</td>
                    <td id="modalCategory" class="detail-value">Education</td>
                </tr>
                <tr class="detail-row">
                    <td class="detail-label">Short Desc</td>
                    <td id="modalShortDesc" class="detail-value">Education support program helping asnaf students with school supplies...</td>
                </tr>
                <tr class="detail-row">
                    <td class="detail-label">Long Desc</td>
                    <td id="modalLongDesc" class="detail-value">This program is an education support initiative aimed at assisting asnaf students...</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <a href="#" id="modalViewPage" class="btn-close-modal" style="text-decoration:none; background:#00827F; color:#fff;">View Page</a>
        </div>
    </div>
</div>

<!-- Application Details Modal -->
<div id="applicationModal" class="modal-overlay" onclick="closeAppModal(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>Application Details</h3>
            <div class="modal-close-btn" onclick="closeAppModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </div>
        </div>
        <div class="modal-body">
            <h4 id="appModalTitle" style="font-size: 20px; font-weight: 800; color: #111827; margin-bottom: 16px;"></h4>
            
            <table class="detail-table">
                <tr class="detail-row">
                    <td class="detail-label">Status</td>
                    <td class="detail-value">
                        <span id="appModalStatus" class="status-badge"></span>
                    </td>
                </tr>
                <tr class="detail-row">
                    <td class="detail-label">Date Applied</td>
                    <td id="appModalDate" class="detail-value bold"></td>
                </tr>
                <tr class="detail-row">
                    <td class="detail-label">Full Name</td>
                    <td class="detail-value">{{ Auth::guard('participant')->user()->name }}</td>
                </tr>
                <tr class="detail-row">
                    <td class="detail-label">Email</td>
                    <td class="detail-value">{{ Auth::guard('participant')->user()->email }}</td>
                </tr>
                <tr class="detail-row">
                    <td class="detail-label">Phone</td>
                    <td class="detail-value">{{ Auth::guard('participant')->user()->phone }}</td>
                </tr>
                <tr class="detail-row">
                    <td class="detail-label">Skills Offered</td>
                    <td id="appModalSkills" class="detail-value"></td>
                </tr>
                <tr class="detail-row">
                    <td class="detail-label">Notes</td>
                    <td id="appModalNotes" class="detail-value"></td>
                </tr>
                <tr class="detail-row" id="appModalReasonRow" style="display: none; background: #fef2f2;">
                    <td class="detail-label" style="color: #991b1b; font-weight: 700;">Decline Reason</td>
                    <td id="appModalReason" class="detail-value" style="color: #991b1b;"></td>
                </tr>
            </table>
        </div>
        <div class="modal-footer" id="appModalFooter" style="display: flex; justify-content: space-between; align-items: center; gap: 12px;">
            <div id="appModalCancelContainer" style="margin: 0;">
                <button type="button" class="btn-close-modal" onclick="openCancelModal()" style="background: #DC2626; color: #fff; border: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">Cancel Application</button>
            </div>
            <a href="#" id="appModalEditBtn" class="btn-close-modal" style="text-decoration:none; display:none; background: #00827F; color: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.05); margin-left: auto;">Update Details</a>
        </div>
    </div>
</div>

<!-- Cancel Application Modal -->
<div id="cancelModal" class="modal-overlay" onclick="closeCancelModal(event)" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 1100; padding: 20px; opacity: 0; transition: opacity 0.2s ease-out;">
    <div class="modal-content" onclick="event.stopPropagation()" style="background: #fff; width: 100%; max-width: 480px; border-radius: 16px; overflow: hidden; position: relative; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); transform: scale(0.95); transition: transform 0.2s ease-out;">
        
        <div style="padding: 24px 24px 16px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: flex-start; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: #fef2f2; color: #ef4444; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #0f172a;">Cancel Application</h3>
                <p style="margin: 4px 0 0; font-size: 13px; color: #64748b;">Please select a reason for cancelling your volunteer application.</p>
            </div>
            <button type="button" onclick="closeCancelModal(null)" style="background: none; border: none; padding: 4px; margin-left: auto; margin-right: -8px; margin-top: -8px; cursor: pointer; color: #94a3b8; border-radius: 6px; transition: all 0.2s;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <form id="cancelReasonForm" method="POST" action="">
            @csrf @method('DELETE')
            <input type="hidden" name="cancel_reason" id="finalCancelReason" value="">
            
            <div style="padding: 24px;">
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <label style="display: flex; align-items: flex-start; padding: 14px 16px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; background: #f8fafc;" class="cancel-reason-option">
                        <input type="radio" name="cancel_reason_selection" value="Schedule conflict / Unable to commit time" class="cancel-reason-radio" onchange="handleCancelReasonSelect(this)" style="margin-top: 3px; margin-right: 12px; accent-color: #ef4444;">
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-size: 14px; font-weight: 600; color: #1e293b;">Schedule conflict / Unable to commit time</span>
                        </div>
                    </label>

                    <label style="display: flex; align-items: flex-start; padding: 14px 16px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; background: #f8fafc;" class="cancel-reason-option">
                        <input type="radio" name="cancel_reason_selection" value="Health issues / Emergency situation" class="cancel-reason-radio" onchange="handleCancelReasonSelect(this)" style="margin-top: 3px; margin-right: 12px; accent-color: #ef4444;">
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-size: 14px; font-weight: 600; color: #1e293b;">Health issues / Emergency situation</span>
                        </div>
                    </label>

                    <label style="display: flex; align-items: flex-start; padding: 14px 16px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; background: #f8fafc;" class="cancel-reason-option">
                        <input type="radio" name="cancel_reason_selection" value="No longer interested / Change of mind" class="cancel-reason-radio" onchange="handleCancelReasonSelect(this)" style="margin-top: 3px; margin-right: 12px; accent-color: #ef4444;">
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-size: 14px; font-weight: 600; color: #1e293b;">No longer interested / Change of mind</span>
                        </div>
                    </label>

                    <label style="display: flex; align-items: flex-start; padding: 14px 16px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; background: #f8fafc;" class="cancel-reason-option">
                        <input type="radio" name="cancel_reason_selection" value="other" class="cancel-reason-radio" onchange="handleCancelReasonSelect(this)" style="margin-top: 3px; margin-right: 12px; accent-color: #ef4444;">
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-size: 14px; font-weight: 600; color: #1e293b;">Others (Please specify)</span>
                        </div>
                    </label>
                </div>
                
                <textarea id="otherCancelReasonText" placeholder="Please specify the reason (min 5 characters)..." oninput="validateCancelForm()" style="display: none; width: 100%; margin-top: 12px; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #1e293b; font-family: inherit; resize: vertical; min-height: 80px; box-sizing: border-box;"></textarea>
            </div>

            <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="btn-close-modal" onclick="closeCancelModal(null)" style="padding: 10px 20px; font-size: 14px; background: #f1f5f9; color: #64748b; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Go Back</button>
                <button type="button" id="confirmCancelBtn" onclick="submitCancelForm()" style="padding: 10px 20px; font-size: 14px; background: #ef4444; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: not-allowed; opacity: 0.5;" disabled>Confirm Cancellation</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openTab(evt, tabName) {
        var i, tabContent, tabBtns;
        tabContent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabContent.length; i++) {
            tabContent[i].classList.remove("active");
        }
        tabBtns = document.getElementsByClassName("tab-btn");
        for (i = 0; i < tabBtns.length; i++) {
            tabBtns[i].classList.remove("active");
        }
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("active");
    }

    function showEventDetails(id, title, img, category, shortDesc, longDesc, url) {
        document.getElementById('modalId').innerText = id;
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('modalImg').src = img;
        document.getElementById('modalCategory').innerText = category;
        document.getElementById('modalShortDesc').innerText = shortDesc;
        document.getElementById('modalLongDesc').innerText = longDesc;
        document.getElementById('modalViewPage').href = url;
        
        document.getElementById('eventModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('eventModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function showApplicationDetails(btn) {
        const title = btn.getAttribute('data-title');
        const date = btn.getAttribute('data-date');
        const status = btn.getAttribute('data-status');
        const skillsJson = btn.getAttribute('data-skills');
        const notes = btn.getAttribute('data-notes');
        const editUrl = btn.getAttribute('data-edit-url');
        const cancelUrl = btn.getAttribute('data-cancel-url');

        document.getElementById('appModalTitle').innerText = title;
        document.getElementById('appModalDate').innerText = date;
        document.getElementById('appModalNotes').innerText = notes;
        
        // Status Badge Styling
        const statusEl = document.getElementById('appModalStatus');
        statusEl.innerText = status;
        statusEl.className = 'status-badge'; // reset
        if (status === 'pending') statusEl.classList.add('status-pending');
        else if (status === 'approved') statusEl.classList.add('status-approved');
        else if (status === 'rejected') statusEl.classList.add('status-rejected');

        // Decline Reason Visibility
        const reasonRow = document.getElementById('appModalReasonRow');
        const reasonText = document.getElementById('appModalReason');
        const declineReason = btn.getAttribute('data-decline-reason');
        
        if ((status === 'declined' || status === 'rejected') && declineReason) {
            reasonRow.style.display = 'table-row';
            reasonText.innerText = declineReason;
        } else {
            reasonRow.style.display = 'none';
        }

        // Skills Pill Rendering
        const skillsContainer = document.getElementById('appModalSkills');
        skillsContainer.innerHTML = '';
        try {
            const skills = JSON.parse(skillsJson);
            if (skills && skills.length > 0) {
                skills.forEach(skill => {
                    skillsContainer.innerHTML += `<span class="pill-tag">${skill}</span>`;
                });
            } else {
                skillsContainer.innerText = 'No specific skills listed.';
            }
        } catch (e) {
            skillsContainer.innerText = 'No specific skills listed.';
        }

        // Logic for Edit & Cancel buttons
        const editBtn = document.getElementById('appModalEditBtn');
        const cancelContainer = document.getElementById('appModalCancelContainer');
        const cancelForm = document.getElementById('cancelReasonForm');

        if (status === 'pending') {
            editBtn.style.display = 'inline-block';
            editBtn.href = editUrl;
            cancelContainer.style.display = 'block';
            cancelForm.action = cancelUrl;
        } else if (status === 'approved') {
            editBtn.style.display = 'none';
            cancelContainer.style.display = 'none'; // Users cannot cancel approved applications
        } else {
            // Rejected / Cancelled
            editBtn.style.display = 'none';
            cancelContainer.style.display = 'none';
        }

        document.getElementById('applicationModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeAppModal(e) {
        if (!e || e.target.id === 'applicationModal') {
            document.getElementById('applicationModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    // --- Cancel Modal Logic --- //
    function openCancelModal() {
        document.getElementById('applicationModal').style.display = 'none'; // Hide current modal
        
        // Reset form
        document.querySelectorAll('.cancel-reason-radio').forEach(r => r.checked = false);
        document.querySelectorAll('.cancel-reason-option').forEach(o => {
            o.style.background = '#f8fafc';
            o.style.borderColor = '#e2e8f0';
        });
        document.getElementById('otherCancelReasonText').style.display = 'none';
        document.getElementById('otherCancelReasonText').value = '';
        validateCancelForm();

        const modal = document.getElementById('cancelModal');
        modal.style.display = 'flex';
        void modal.offsetWidth; // trigger reflow
        modal.style.opacity = '1';
        modal.querySelector('.modal-content').style.transform = 'scale(1)';
    }

    function closeCancelModal(e) {
        if (e === null || e.target.id === 'cancelModal') {
            const modal = document.getElementById('cancelModal');
            modal.style.opacity = '0';
            modal.querySelector('.modal-content').style.transform = 'scale(0.95)';
            setTimeout(() => {
                modal.style.display = 'none';
                document.getElementById('applicationModal').style.display = 'flex'; // Show previous modal again
            }, 200);
        }
    }

    function handleCancelReasonSelect(radio) {
        document.querySelectorAll('.cancel-reason-option').forEach(o => {
            o.style.background = '#f8fafc';
            o.style.borderColor = '#e2e8f0';
        });
        const option = radio.closest('.cancel-reason-option');
        option.style.background = '#fef2f2';
        option.style.borderColor = '#fca5a5';
        
        const otherTextArea = document.getElementById('otherCancelReasonText');
        if (radio.value === 'other') {
            otherTextArea.style.display = 'block';
            otherTextArea.focus();
        } else {
            otherTextArea.style.display = 'none';
        }
        
        validateCancelForm();
    }

    function validateCancelForm() {
        const selectedRadio = document.querySelector('.cancel-reason-radio:checked');
        const confirmBtn = document.getElementById('confirmCancelBtn');
        let isValid = false;

        if (selectedRadio) {
            if (selectedRadio.value !== 'other') {
                isValid = true;
            } else {
                const otherText = document.getElementById('otherCancelReasonText').value.trim();
                if (otherText.length >= 5) {
                    isValid = true;
                }
            }
        }

        if (isValid) {
            confirmBtn.style.opacity = '1';
            confirmBtn.style.cursor = 'pointer';
            confirmBtn.disabled = false;
        } else {
            confirmBtn.style.opacity = '0.5';
            confirmBtn.style.cursor = 'not-allowed';
            confirmBtn.disabled = true;
        }
    }

    function submitCancelForm() {
        const selectedRadio = document.querySelector('.cancel-reason-radio:checked');
        let finalReason = '';

        if (selectedRadio.value === 'other') {
            finalReason = document.getElementById('otherCancelReasonText').value.trim();
        } else {
            finalReason = selectedRadio.value;
        }

        document.getElementById('finalCancelReason').value = finalReason;
        document.getElementById('cancelReasonForm').submit();
    }

    // Auto-open application modal if URL has ?show_app=ID
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const showAppId = urlParams.get('show_app');
        if (showAppId) {
            // Switch to applications tab
            document.querySelector('.tabs-header button:nth-child(2)').click();
            // Find the specific application button and click it
            const btn = document.querySelector(`button[data-app-id="${showAppId}"]`);
            if (btn) {
                setTimeout(() => btn.click(), 100);
            }
        }
    });
</script>
@endpush
