@extends('layouts.admin')

@section('title', 'Volunteer Applications')

@push('styles')
<style>
    .page-heading { flex-shrink: 0; }
    .page-heading h2 { font-size: 24px; font-weight: 600; color: #1a1a2e; }
    .page-heading p { font-size: 14px; color: #64748b; margin-top: 5px; }

    .table-card {
        background: #fff;
        border: 0.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 26px 28px;
    }

    .table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .table-header .card-title { font-size: 18px; font-weight: 600; color: #1a1a2e; margin: 0; }

    .stat-pills { display: flex; gap: 10px; }

    .stat-pill {
        font-size: 12px;
        padding: 6px 14px;
        border-radius: 999px;
        font-weight: 500;
    }

    .pill-pending { background: #FFB800; color: #fff; }
    .pill-approved { background: #28A745; color: #fff; }
    .pill-declined { background: #DC3545; color: #fff; }

    .table-wrap { overflow-x: auto; }

    table { width: 100%; border-collapse: collapse; }

    thead th {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 12px 14px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
        font-weight: 600;
        white-space: nowrap;
    }

    tbody td {
        font-size: 14px;
        padding: 14px 14px;
        border-bottom: 0.5px solid #f1f5f9;
        color: #1a1a2e;
        vertical-align: middle;
    }

    tbody tr:hover { background: #f8fafa; }
    tbody tr:last-child td { border-bottom: none; }

    .badge-sm {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 999px;
        font-weight: 500;
        display: inline-block;
    }

    .badge-pending { background: #FFB800; color: #fff; }
    .badge-approved { background: #28A745; color: #fff; }
    .badge-declined { background: #DC3545; color: #fff; }
    .badge-cancelled { background: #6c757d; color: #fff; }

    .status-select {
        padding: 4px 8px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        background: #FFB800;
        color: #fff;
    }
    .status-select option { background: #fff; color: #1a1a2e; }

    .action-btns { display: flex; gap: 6px; flex-wrap: wrap; }

    .btn-sm {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        font-family: inherit;
        transition: all 0.1s;
    }

    .btn-approve { background: #E6F2F2; color: #00827F; border: 1px solid #00827F; }
    .btn-approve:hover { background: #00827F; color: #fff; }
    .btn-decline { background: #FCEBEB; color: #A32D2D; border: 1px solid #A32D2D; }
    .btn-decline:hover { background: #A32D2D; color: #fff; }
    .btn-cancel { background: #f0f2f5; color: #64748b; }
    .btn-cancel:hover { background: #e2e8f0; }

    /* Pastel Action Buttons */
    .btn-pastel {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-pastel-view { background: #E6F2F2; color: #00827F; }
    .btn-pastel-view:hover { background: #00827F; color: #fff; }

    .empty-state { text-align: center; padding: 50px 0; color: #94a3b8; font-size: 14px; }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 18px;
    }

    .alert-success { background: #E6F2F2; color: #006b68; border: 1px solid #b2d8d8; }

    .applicant-info { display: flex; align-items: center; gap: 12px; }

    .applicant-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #E6F2F2;
        color: #00827F;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
        flex-shrink: 0;
    }

    .applicant-name { font-weight: 500; }
    .applicant-email { font-size: 12px; color: #64748b; margin-top: 2px; }

    /* Decline Modal Specific Styles */
    .reason-radio-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 16px;
    }
    
    .reason-option {
        display: flex;
        align-items: flex-start;
        padding: 14px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        background: #f8fafc;
    }

    .reason-option:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    .reason-option.selected {
        background: #fef2f2;
        border-color: #fca5a5;
    }

    .reason-radio {
        margin-top: 3px;
        margin-right: 12px;
        accent-color: #ef4444;
        cursor: pointer;
    }

    .reason-text {
        display: flex;
        flex-direction: column;
    }

    .reason-title {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
    }

    .reason-desc {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }

    .other-reason-input {
        display: none;
        width: 100%;
        margin-top: 12px;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        color: #1e293b;
        font-family: inherit;
        resize: vertical;
        min-height: 80px;
    }

    .other-reason-input:focus {
        outline: none;
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    .btn-danger {
        background: #ef4444;
        color: #fff;
        border: none;
    }
    .btn-danger:hover {
        background: #dc2626;
    }
</style>
@endpush

@section('content')

<div class="page-heading">
    <h2>Volunteer Applications</h2>
    <p>Review and manage volunteer applications</p>
</div>



@if($activeEvents->count() > 0)
    <div style="margin-bottom: 24px;">
        <h3 style="display: flex; align-items: center; gap: 10px; font-size: 20px; font-weight: 700; color: #1a202c; margin-bottom: 16px; letter-spacing: -0.3px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1a202c" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 3 3 21 21 21"></polyline>
                <polyline points="7 14 11 10 15 14 21 8"></polyline>
            </svg>
            Active Events Progress
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 16px;">
            @foreach($activeEvents as $ve)
                @php
                    $approved = $ve->applications->count();
                    $pct = $ve->capacity > 0 ? min(100, ($approved / $ve->capacity) * 100) : 0;
                @endphp
                <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <div style="font-weight: 600; font-size: 14px; color: #1a1a2e; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $ve->event->title }}">{{ $ve->event->title }}</div>
                    <div style="font-size: 12px; color: #64748b; margin-bottom: 12px;">{{ $approved }} of {{ $ve->capacity }} slots filled</div>
                    <div style="height: 6px; background: #f1f5f9; border-radius: 99px; overflow: hidden;">
                        <div style="height: 100%; background: #00827F; width: {{ $pct }}%; border-radius: 99px;"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<div class="table-card">
    <div class="table-header">
        <div class="card-title">All Applications</div>
        <div class="stat-pills">
            <span class="stat-pill pill-pending">{{ $applications->where('status', 'pending')->count() }} Pending</span>
            <span class="stat-pill pill-approved">{{ $applications->where('status', 'approved')->count() }} Approved</span>
            <span class="stat-pill pill-declined">{{ $applications->where('status', 'declined')->count() }} Declined</span>
        </div>
    </div>

    <div class="table-wrap">
        @if($applications->count())
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Participant</th>
                    <th>Event Name</th>
                    <th>Applied At</th>
                    <th>Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $app)
                <tr>
                    <td><span style="color: #64748b; font-weight: 500;">{{ $app->formatted_id }}</span></td>
                    <td>
                        <div class="applicant-info">
                            <div class="applicant-avatar">{{ strtoupper(substr($app->participant->name ?? 'N', 0, 2)) }}</div>
                            <div>
                                <div class="applicant-name">{{ $app->participant->name ?? 'N/A' }}</div>
                                <div class="applicant-email">{{ $app->participant->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $app->volunteerEvent->event->title ?? 'N/A' }}</td>
                    <td>{{ $app->applied_at ? $app->applied_at->format('d M Y, H:i') : 'N/A' }}</td>
                    <td>
                        @if($app->status === 'pending')
                            <form id="status-form-{{ $app->application_id }}" method="POST" action="{{ route('admin.applications.updateStatus', $app->application_id) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <select class="status-select" onchange="handleStatusChange(this, {{ $app->application_id }})">
                                    <option value="pending" selected>Pending</option>
                                    <option value="approved">Approve</option>
                                    <option value="declined">Decline</option>
                                </select>
                            </form>
                        @else
                            <span class="badge-sm badge-{{ $app->status }}">{{ ucfirst($app->status) }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <button class="btn-pastel btn-pastel-view" onclick="viewApplication({{ $app->application_id }})">
                            <i class="fa-regular fa-eye"></i> View
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">No volunteer applications yet.</div>
        @endif
    </div>
</div>

<!-- Application View Modal -->
<div id="viewModal" class="modal-overlay" onclick="closeViewModal(event)" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 1000; padding: 20px;">
    <div class="modal-content" onclick="event.stopPropagation()" style="background: #fff; width: 100%; max-width: 500px; border-radius: 12px; overflow: hidden; position: relative; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
        <div style="padding: 20px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Application Details</h3>
            <button onclick="closeViewModal(null)" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #94a3b8;">&times;</button>
        </div>
        <div id="modalBody" style="padding: 24px;">
            <!-- Content loaded via AJAX -->
            <div style="text-align: center; color: #94a3b8;">Loading...</div>
        </div>
        <div id="modalFooter" style="padding: 16px 20px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px;">
            <button class="btn-sm btn-cancel" onclick="closeViewModal(null)">Close</button>
        </div>
    </div>
</div>

<!-- Decline Reason Modal -->
<div id="declineModal" class="modal-overlay" onclick="closeDeclineModal(event)" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 1100; padding: 20px; opacity: 0; transition: opacity 0.2s ease-out;">
    <div class="modal-content" onclick="event.stopPropagation()" style="background: #fff; width: 100%; max-width: 480px; border-radius: 16px; overflow: hidden; position: relative; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); transform: scale(0.95); transition: transform 0.2s ease-out;">
        
        <div style="padding: 24px 24px 16px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: flex-start; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: #fef2f2; color: #ef4444; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #0f172a;">Decline Application</h3>
                <p style="margin: 4px 0 0; font-size: 13px; color: #64748b;">Please select a reason for declining. This will be sent to the applicant.</p>
            </div>
            <button onclick="closeDeclineModal(null)" style="background: none; border: none; padding: 4px; margin-left: auto; margin-right: -8px; margin-top: -8px; cursor: pointer; color: #94a3b8; border-radius: 6px; transition: all 0.2s;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <form id="declineForm" method="POST" action="">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="declined">
            <input type="hidden" name="decline_reason" id="finalDeclineReason" value="">
            
            <div style="padding: 24px;">
                <div class="reason-radio-group">
                    <label class="reason-option">
                        <input type="radio" name="reason_selection" value="Quota is full" class="reason-radio" onchange="handleReasonSelect(this)">
                        <div class="reason-text">
                            <span class="reason-title">Quota is Full</span>
                            <span class="reason-desc">The volunteer capacity for this event has already been reached.</span>
                        </div>
                    </label>

                    <label class="reason-option">
                        <input type="radio" name="reason_selection" value="Invalid or incomplete information" class="reason-radio" onchange="handleReasonSelect(this)">
                        <div class="reason-text">
                            <span class="reason-title">Invalid Information</span>
                            <span class="reason-desc">The information provided is inaccurate, suspicious, or cannot be verified.</span>
                        </div>
                    </label>

                    <label class="reason-option">
                        <input type="radio" name="reason_selection" value="other" class="reason-radio" onchange="handleReasonSelect(this)">
                        <div class="reason-text">
                            <span class="reason-title">Other Reason</span>
                            <span class="reason-desc">Specify a custom reason for declining this application.</span>
                        </div>
                    </label>
                </div>
                
                <textarea id="otherReasonText" class="other-reason-input" placeholder="Please specify the reason (min 5 characters)..." oninput="validateDeclineForm()"></textarea>
            </div>

            <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="btn-sm btn-cancel" onclick="closeDeclineModal(null)" style="padding: 10px 20px; font-size: 14px;">Cancel</button>
                <button type="button" id="confirmDeclineBtn" class="btn-sm btn-danger" onclick="submitDeclineForm()" style="padding: 10px 20px; font-size: 14px; opacity: 0.5; cursor: not-allowed;" disabled>Decline Application</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function viewApplication(id) {
        const modal = document.getElementById('viewModal');
        const body = document.getElementById('modalBody');
        const footer = document.getElementById('modalFooter');
        
        modal.style.display = 'flex';
        body.innerHTML = '<div style="text-align: center; padding: 20px; color: #94a3b8;">Loading details...</div>';
        footer.innerHTML = '<button class="btn-sm btn-cancel" onclick="closeViewModal(null)">Close</button>';

        fetch(`{{ url('/admin/applications') }}/${id}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(app => {
            let statusHtml = '';
            if (app.status === 'pending') {
                statusHtml = `<span style="background: #FFB800; color: #fff; padding: 4px 10px; border-radius: 99px; font-size: 11px;">Pending</span>`;
            } else if (app.status === 'approved') {
                statusHtml = `<span style="background: #28A745; color: #fff; padding: 4px 10px; border-radius: 99px; font-size: 11px;">Approved</span>`;
            } else {
                statusHtml = `<span style="background: #DC3545; color: #fff; padding: 4px 10px; border-radius: 99px; font-size: 11px;">${app.status.charAt(0).toUpperCase() + app.status.slice(1)}</span>`;
            }

            body.innerHTML = `
                <div style="margin-bottom: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px;">Application ID</div>
                        <div style="font-weight: 700; color: #00827F; font-family: monospace;">${app.formatted_id}</div>
                    </div>
                    <div>
                        <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px;">Status</div>
                        ${statusHtml}
                    </div>
                </div>

                <div style="margin-bottom: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px;">Participant Info</div>
                        <div style="font-weight: 600; font-size: 15px; color: #1a1a2e;">${app.participant.name}</div>
                        <div style="font-size: 13px; color: #64748b;">${app.participant.email}</div>
                        <div style="font-size: 13px; color: #64748b;">${app.participant.phone || 'No phone'}</div>
                    </div>
                    <div>
                        <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px;">IC Number</div>
                        <div style="font-size: 13px; color: #1a1a2e;">${app.participant.ic_number || 'N/A'}</div>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px;">Event Applied For</div>
                    <div style="font-weight: 500; color: #1a1a2e;">${app.volunteer_event.event.title}</div>
                </div>

                <div style="margin-bottom: 20px;">
                    <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px;">Skills</div>
                    <div style="display: flex; flex-wrap: wrap; gap: 5px; margin-top: 5px;">
                        ${(app.skills || []).map(skill => `<span style="background: #f1f5f9; color: #475569; font-size: 11px; padding: 2px 8px; border-radius: 4px;">${skill}</span>`).join('') || '<span style="color:#94a3b8; font-size:12px;">No skills listed</span>'}
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px;">Additional Notes</div>
                    <div style="background: #f8fafc; padding: 12px; border-radius: 8px; font-size: 13px; color: #475569; border: 0.5px solid #e2e8f0;">
                        ${app.notes || 'No additional notes provided.'}
                    </div>
                </div>

                ${(app.status === 'declined' && app.decline_reason) ? `
                <div style="margin-bottom: 20px;">
                    <div style="font-size: 11px; color: #ef4444; font-weight: 700; text-transform: uppercase; margin-bottom: 5px;">Decline Reason</div>
                    <div style="background: #fef2f2; padding: 12px; border-radius: 8px; font-size: 13px; color: #991b1b; border: 1px solid #fca5a5;">
                        ${app.decline_reason}
                    </div>
                </div>` : ''}

                ${(app.status === 'cancelled' && app.cancel_reason) ? `
                <div style="margin-bottom: 20px;">
                    <div style="font-size: 11px; color: #f59e0b; font-weight: 700; text-transform: uppercase; margin-bottom: 5px;">Cancellation Reason (By Participant)</div>
                    <div style="background: #fffbeb; padding: 12px; border-radius: 8px; font-size: 13px; color: #92400e; border: 1px solid #fcd34d;">
                        ${app.cancel_reason}
                    </div>
                </div>` : ''}

                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 15px;">
                    <div>
                        <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px;">Applied At</div>
                        <div style="font-size: 12px; color: #1a1a2e;">${new Date(app.applied_at).toLocaleString()}</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px;">Status</div>
                        ${statusHtml}
                    </div>
                </div>
            `;

            if (app.status === 'pending') {
                footer.innerHTML = `
                    <form method="POST" action="{{ url('/admin/applications') }}/${app.application_id}/status" style="display:inline;">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn-sm btn-approve" style="background: #28A745; color: #fff; border:none; padding: 8px 16px;">Approve</button>
                    </form>
                    <button type="button" class="btn-sm btn-decline" onclick="openDeclineModal(${app.application_id})" style="background: #DC3545; color: #fff; border:none; padding: 8px 16px;">Decline</button>
                    <button class="btn-sm btn-cancel" onclick="closeViewModal(null)">Cancel</button>
                `;
            }
        });
    }

    function closeViewModal(e) {
        if (e === null || e.target.id === 'viewModal') {
            document.getElementById('viewModal').style.display = 'none';
        }
    }

    // --- Decline Modal Logic --- //
    let currentDeclineAppId = null;
    let pendingSelectElement = null;

    function handleStatusChange(selectEl, appId) {
        if (selectEl.value === 'approved') {
            // Append status input and submit
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'status';
            input.value = 'approved';
            selectEl.form.appendChild(input);
            selectEl.form.submit();
        } else if (selectEl.value === 'declined') {
            pendingSelectElement = selectEl;
            openDeclineModal(appId);
        }
    }

    function openDeclineModal(appId) {
        currentDeclineAppId = appId;
        
        // Reset form
        document.querySelectorAll('.reason-radio').forEach(r => r.checked = false);
        document.querySelectorAll('.reason-option').forEach(o => o.classList.remove('selected'));
        document.getElementById('otherReasonText').style.display = 'none';
        document.getElementById('otherReasonText').value = '';
        validateDeclineForm();

        const modal = document.getElementById('declineModal');
        modal.style.display = 'flex';
        // Trigger reflow for animation
        void modal.offsetWidth;
        modal.style.opacity = '1';
        modal.querySelector('.modal-content').style.transform = 'scale(1)';
    }

    function closeDeclineModal(e) {
        if (e === null || e.target.id === 'declineModal') {
            const modal = document.getElementById('declineModal');
            modal.style.opacity = '0';
            modal.querySelector('.modal-content').style.transform = 'scale(0.95)';
            
            setTimeout(() => {
                modal.style.display = 'none';
                // Reset select dropdown if cancelled
                if (pendingSelectElement) {
                    pendingSelectElement.value = 'pending';
                    pendingSelectElement = null;
                }
            }, 200);
        }
    }

    function handleReasonSelect(radio) {
        // Update styling
        document.querySelectorAll('.reason-option').forEach(o => o.classList.remove('selected'));
        radio.closest('.reason-option').classList.add('selected');
        
        // Show/hide other text area
        const otherTextArea = document.getElementById('otherReasonText');
        if (radio.value === 'other') {
            otherTextArea.style.display = 'block';
            otherTextArea.focus();
        } else {
            otherTextArea.style.display = 'none';
        }
        
        validateDeclineForm();
    }

    function validateDeclineForm() {
        const selectedRadio = document.querySelector('.reason-radio:checked');
        const confirmBtn = document.getElementById('confirmDeclineBtn');
        let isValid = false;

        if (selectedRadio) {
            if (selectedRadio.value !== 'other') {
                isValid = true;
            } else {
                const otherText = document.getElementById('otherReasonText').value.trim();
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

    function submitDeclineForm() {
        const selectedRadio = document.querySelector('.reason-radio:checked');
        let finalReason = '';

        if (selectedRadio.value === 'other') {
            finalReason = document.getElementById('otherReasonText').value.trim();
        } else {
            finalReason = selectedRadio.value;
        }

        document.getElementById('finalDeclineReason').value = finalReason;
        
        const form = document.getElementById('declineForm');
        form.action = `{{ url('/admin/applications') }}/${currentDeclineAppId}/status`;
        form.submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const showAppId = urlParams.get('show_app');
        if (showAppId) {
            viewApplication(showAppId);
        }
    });
</script>
@endpush
