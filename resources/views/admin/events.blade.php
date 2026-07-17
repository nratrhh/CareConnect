@extends('layouts.admin')

@section('title', 'Events')

@push('styles')
<style>
    .page-heading { flex-shrink: 0; }
    .page-heading h2 { font-size: 24px; font-weight: 600; color: #1a1a2e; }
    .page-heading p { font-size: 14px; color: #64748b; margin-top: 5px; }

    .events-layout {
        display: block; /* Removed grid layout */
    }

    /* ── Form Card ── */
    .form-card {
        background: #fff;
        border: 0.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 26px 28px;
    }

    .form-card .card-title {
        font-size: 18px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 20px;
    }

    .form-group { margin-bottom: 16px; }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 11px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        color: #1a1a2e;
        font-family: inherit;
        transition: border-color 0.15s, box-shadow 0.15s;
        background: #fff;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #00827F;
        box-shadow: 0 0 0 3px rgba(0,130,127,0.1);
    }

    .form-textarea { resize: vertical; min-height: 80px; }

    .char-counter {
        text-align: right;
        font-size: 11px;
        color: #64748b;
        margin-top: 4px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    /* Radio toggle */
    .radio-group {
        display: flex;
        gap: 0;
        background: #f0f2f5;
        border-radius: 8px;
        padding: 4px;
        margin-bottom: 16px;
    }

    .radio-option {
        flex: 1;
        text-align: center;
    }

    .radio-option input[type="radio"] { display: none; }

    .radio-option label {
        display: block;
        padding: 10px 0;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
    }

    .radio-option input[type="radio"]:checked + label {
        background: #fff;
        color: #00827F;
        font-weight: 600;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }

    .conditional-fields { display: none; }
    .conditional-fields.active { display: block; }

    .form-file {
        padding: 10px 14px;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        background: #f8fafc;
    }

    .form-file input[type="file"] {
        font-size: 13px;
        color: #64748b;
    }

    .btn-row {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn {
        padding: 11px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        font-family: inherit;
        transition: all 0.15s;
    }

    .btn-primary { background: #00827F; color: #fff; }
    .btn-primary:hover { background: #006b68; }
    .btn-secondary { background: #f0f2f5; color: #64748b; border: 1px solid #e2e8f0; }
    .btn-secondary:hover { background: #e2e8f0; }

    /* ── Table Card ── */
    .table-card {
        background: #fff;
        border: 0.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 26px 28px;
        overflow: hidden;
    }

    .table-card .card-title {
        font-size: 18px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 18px;
    }

    .table-wrap { overflow-x: auto; }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead th {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
        font-weight: 600;
        white-space: nowrap;
    }

    tbody td {
        font-size: 14px;
        padding: 14px 20px;
        border-bottom: 0.5px solid #f1f5f9;
        color: #1a1a2e;
        vertical-align: middle;
    }

    tbody tr:hover { background: #f8fafa; }
    tbody tr:last-child td { border-bottom: none; }

    .action-btns { display: flex; gap: 8px; justify-content: center; }

    /* Pastel Action Buttons */
    .btn-pastel {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.2s;
        text-decoration: none;
        font-family: inherit;
    }
    .btn-pastel-view { background: #E6F2F2; color: #00827F; }
    .btn-pastel-view:hover { background: #00827F; color: #fff; }
    
    .btn-pastel-edit { background: #FEF3C7; color: #D97706; }
    .btn-pastel-edit:hover { background: #D97706; color: #fff; }
    
    .btn-pastel-delete { background: #FCEBEB; color: #A32D2D; }
    .btn-pastel-delete:hover { background: #A32D2D; color: #fff; }

    .badge-sm {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 999px;
        font-weight: 500;
    }

    .badge-active { background: #DCFCE7; color: #16A34A; }
    .badge-draft { background: #f0f2f5; color: #64748b; }
    .badge-completed { background: #FEF3C7; color: #D97706; }

    .event-img-thumb {
        width: 44px;
        height: 44px;
        border-radius: 6px;
        object-fit: cover;
        background: #f0f2f5;
    }

    .empty-state {
        text-align: center;
        padding: 50px 0;
        color: #94a3b8;
        font-size: 14px;
    }

    /* ── Alert ── */
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success { background: #E6F2F2; color: #006b68; border: 1px solid #b2d8d8; }
    .alert-error { background: #FCEBEB; color: #A32D2D; border: 1px solid #f5c6c6; }

    /* ── Modal ── */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.4);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active { display: flex; }

    .modal {
        background: #fff;
        border-radius: 14px;
        width: 90%;
        max-width: 580px;
        max-height: 85vh;
        overflow-y: auto;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }

    .modal-header {
        padding: 18px 22px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-header h3 { font-size: 18px; font-weight: 600; color: #1a1a2e; }

    .modal-close {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: none;
        background: #f0f2f5;
        font-size: 18px;
        cursor: pointer;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close:hover { background: #e2e8f0; }

    .modal-body { padding: 22px 24px; }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    /* View modal detail rows */
    .detail-row {
        display: flex;
        padding: 10px 0;
        border-bottom: 0.5px solid #f1f5f9;
    }

    .detail-row:last-child { border-bottom: none; }
    .detail-label { width: 130px; font-size: 13px; color: #64748b; font-weight: 500; flex-shrink: 0; }
    .detail-value { font-size: 14px; color: #1a1a2e; flex: 1; }

    .detail-img {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 14px;
    }

    /* ── Benefits Checkbox Grid ── */
    .benefits-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .benefit-option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        background: #fff;
    }

    .benefit-option:hover {
        border-color: #00827F;
        background: #f0fdfa;
    }

    .benefit-option input[type="checkbox"] {
        accent-color: #00827F;
        width: 16px;
        height: 16px;
    }

    .benefit-option input[type="checkbox"]:checked + .benefit-label {
        color: #00827F;
        font-weight: 600;
    }

    .benefit-icon {
        font-size: 18px;
    }

    .benefit-label {
        font-size: 13px;
        color: #334155;
        font-weight: 500;
    }

    .benefit-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 500;
        background: #f0fdfa;
        color: #0d9488;
        border: 1px solid #99f6e4;
    }
</style>
@endpush

@section('content')

<div class="page-heading" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2>Event Management</h2>
        <p>Create, view, edit, and delete events</p>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('createModal').classList.add('active')" style="display: flex; align-items: center; gap: 8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Create Event
    </button>
</div>

<div class="events-layout">
    {{-- ═══ CREATE MODAL ═══ --}}
    <div class="modal-overlay" id="createModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Create New Event</h3>
                <button type="button" class="modal-close" onclick="closeModal('createModal')">&times;</button>
            </div>
            <div class="modal-body" style="padding: 24px;">

        <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Event Type Radio Toggle --}}
            <div class="form-label">Event Type</div>
            <div class="radio-group">
                <div class="radio-option">
                    <input type="radio" name="event_type" id="type_fundraising" value="fundraising" checked>
                    <label for="type_fundraising">Fundraising Campaign</label>
                </div>
                <div class="radio-option">
                    <input type="radio" name="event_type" id="type_volunteer" value="volunteer">
                    <label for="type_volunteer">Volunteer Event</label>
                </div>
            </div>

            {{-- Common Fields --}}
            <div class="form-group">
                <label class="form-label" for="title">Event Name</label>
                <input type="text" id="title" name="title" class="form-input" placeholder="Enter event name" value="{{ old('title') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="eventCategory">Event Category</label>
                <select id="eventCategory" name="eventCategory" class="form-select" required>
                    <option value="" disabled {{ old('eventCategory') ? '' : 'selected' }}>Select a category</option>
                    <option value="Flood Relief" {{ old('eventCategory') == 'Flood Relief' ? 'selected' : '' }}>Flood Relief</option>
                    <option value="Education" {{ old('eventCategory') == 'Education' ? 'selected' : '' }}>Education</option>
                    <option value="Health" {{ old('eventCategory') == 'Health' ? 'selected' : '' }}>Health</option>
                    <option value="Environment" {{ old('eventCategory') == 'Environment' ? 'selected' : '' }}>Environment</option>
                    <option value="Community Service" {{ old('eventCategory') == 'Community Service' ? 'selected' : '' }}>Community Service</option>
                    <option value="Disaster Relief" {{ old('eventCategory') == 'Disaster Relief' ? 'selected' : '' }}>Disaster Relief</option>
                    <option value="Animal Welfare" {{ old('eventCategory') == 'Animal Welfare' ? 'selected' : '' }}>Animal Welfare</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="eventShortDesc">Event Short Description (Snippet)</label>
                <input type="text" id="eventShortDesc" name="eventShortDesc" class="form-input" placeholder="A brief summary (max 150 chars)" maxlength="150" value="{{ old('eventShortDesc') }}" required>
                <div class="char-counter"><span id="charCount">0</span>/150</div>
            </div>

            <div class="form-group">
                <label class="form-label" for="eventLongDesc">Event Long Description</label>
                <textarea id="eventLongDesc" name="eventLongDesc" class="form-textarea" placeholder="Detailed description of the event" required>{{ old('eventLongDesc') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
            </div>

            {{-- Fundraising-specific fields --}}
            <div id="fundraisingFields" class="conditional-fields active">
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label" for="target_amount">Target Amount (RM)</label>
                        <input type="number" step="0.01" id="target_amount" name="target_amount" class="form-input" placeholder="0.00" value="{{ old('target_amount') }}">
                    </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="start_date">Start Date</label>
                        <input type="date" id="start_date" name="start_date" class="form-input" value="{{ old('start_date') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="end_date">End Date</label>
                        <input type="date" id="end_date" name="end_date" class="form-input" value="{{ old('end_date') }}">
                    </div>
                </div>
            </div>

            {{-- Volunteer-specific fields --}}
            <div id="volunteerFields" class="conditional-fields">
                <div class="form-group">
                    <label class="form-label" for="location">Event Location (e.g. Venue Name)</label>
                    <input type="text" id="location" name="location" class="form-input" placeholder="Enter location name" value="{{ old('location') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="location_details">Full Address (Optional)</label>
                    <textarea id="location_details" name="location_details" class="form-textarea" placeholder="Enter full address for map navigation" style="min-height: 60px;">{{ old('location_details') }}</textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="capacity">Volunteer Capacity</label>
                        <input type="number" id="capacity" name="capacity" class="form-input" placeholder="Number of volunteers needed" value="{{ old('capacity') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="eventDate">Event Date</label>
                        <input type="date" id="eventDate" name="eventDate" class="form-input" value="{{ old('eventDate') }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="start_time">Start Time</label>
                        <input type="time" id="start_time" name="start_time" class="form-input" value="{{ old('start_time', '08:00') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="end_time">End Time</label>
                        <input type="time" id="end_time" name="end_time" class="form-input" value="{{ old('end_time', '17:00') }}">
                    </div>
                </div>
                <div class="form-group" style="margin-top: 6px;">
                    <label class="form-label">What's Provided (Benefits)</label>
                    <div class="benefits-grid">
                        <label class="benefit-option">
                            <input type="checkbox" name="benefits[]" value="food">
                            <span class="benefit-label">Food & Beverages</span>
                        </label>
                        <label class="benefit-option">
                            <input type="checkbox" name="benefits[]" value="clothing">
                            <span class="benefit-label">T-Shirt / Attire</span>
                        </label>
                        <label class="benefit-option">
                            <input type="checkbox" name="benefits[]" value="transport">
                            <span class="benefit-label">Transportation</span>
                        </label>
                        <label class="benefit-option">
                            <input type="checkbox" name="benefits[]" value="certificate">
                            <span class="benefit-label">E-Certificate</span>
                        </label>
                        <label class="benefit-option">
                            <input type="checkbox" name="benefits[]" value="equipment">
                            <span class="benefit-label">Equipment / Kit</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Event Image (last field) --}}
            <div class="form-group">
                <label class="form-label">Event Image</label>
                <div class="form-file">
                    <input type="file" name="eventImg" accept="image/*" required>
                </div>
            </div>

                <div class="btn-row" style="justify-content: flex-end; margin-top: 24px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

    {{-- ═══ EVENT LIST ═══ --}}
    <div class="table-card">
        <div class="card-title">Event List</div>

        <div class="table-wrap">
            @if($events->count())
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Image</th>
                        <th>Event Name</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                    <tr>
                        <td><span style="color: #64748b; font-weight: 500;">{{ $event->formatted_id }}</span></td>
                        <td>
                            @if($event->eventImg)
                                <img src="{{ asset('storage/' . $event->eventImg) }}" class="event-img-thumb" alt="">
                            @else
                                <div class="event-img-thumb" style="display:flex;align-items:center;justify-content:center;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#cbd5e1"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                </div>
                            @endif
                        </td>
                        <td><strong>{{ $event->title }}</strong></td>
                        <td>
                            @if($event->eventCategory)
                                <div style="color: #1a1a2e; font-weight: 500; font-size: 14px; white-space: nowrap;">
                                    {{ $event->eventCategory }}
                                </div>
                            @else
                                <span style="color: #cbd5e1;">—</span>
                            @endif
                        </td>
                        <td>
                            @if($event->fundraisingCampaign && $event->fundraisingCampaign->start_date && $event->fundraisingCampaign->end_date)
                                <div style="white-space: nowrap; font-weight: 500; color: #1a1a2e;">{{ $event->fundraisingCampaign->start_date->format('d M Y') }}</div>
                                <div style="white-space: nowrap; color: #64748b; font-size: 12px; margin-top: 3px;">to {{ $event->fundraisingCampaign->end_date->format('d M Y') }}</div>
                            @elseif($event->volunteerEvent && $event->volunteerEvent->eventDate)
                                <div style="white-space: nowrap; font-weight: 500; color: #1a1a2e;">{{ $event->volunteerEvent->eventDate->format('d M Y') }}</div>
                            @else
                                <span style="color: #94a3b8; font-size: 13px;">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($event->fundraisingCampaign)
                                <span class="badge-sm" style="background:#DBEAFE;color:#1E40AF;">Fundraising</span>
                            @elseif($event->volunteerEvent)
                                <span class="badge-sm" style="background:#EDE9FE;color:#6D28D9;">Volunteer</span>
                            @else
                                <span class="badge-sm badge-draft">General</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-sm badge-{{ $event->status }}">{{ ucfirst($event->status) }}</span>
                        </td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-pastel btn-pastel-view" onclick="viewEvent({{ $event->event_id }})" title="View Details">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> View
                                </button>
                                <button class="btn-pastel btn-pastel-edit" onclick="editEvent({{ $event->event_id }})" title="Edit Event">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit
                                </button>
                                <form method="POST" action="{{ route('admin.events.destroy', $event->event_id) }}" style="display:inline;"
                                      onsubmit="return confirm('Are you sure you want to delete this event?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-pastel btn-pastel-delete" title="Delete Event">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">No events created yet. Use the form to create your first event.</div>
            @endif
        </div>
    </div>
</div>

{{-- ═══ VIEW MODAL ═══ --}}
<div class="modal-overlay" id="viewModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Event Details</h3>
            <button class="modal-close" onclick="closeModal('viewModal')">&times;</button>
        </div>
        <div class="modal-body" id="viewModalBody">
            <div style="text-align:center;padding:20px;color:#94a3b8;">Loading...</div>
        </div>
    </div>
</div>

{{-- ═══ EDIT MODAL ═══ --}}
<div class="modal-overlay" id="editModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Edit Event</h3>
            <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body" id="editModalBody">
                <div style="text-align:center;padding:20px;color:#94a3b8;">Loading...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ── Benefits helper functions ──
    const benefitLabels = {
        food: { label: 'Food & Beverages' },
        clothing: { label: 'T-Shirt / Attire' },
        transport: { label: 'Transportation' },
        certificate: { label: 'E-Certificate' },
        equipment: { label: 'Equipment / Kit' }
    };

    function formatBenefits(benefits) {
        if (!benefits || benefits.length === 0) return '<span style="color:#94a3b8;">None specified</span>';
        return benefits.map(b => {
            const info = benefitLabels[b] || { label: b };
            return `<span class="benefit-tag">${info.label}</span>`;
        }).join(' ');
    }

    function generateBenefitCheckboxes(currentBenefits) {
        const benefits = currentBenefits || [];
        return Object.entries(benefitLabels).map(([key, info]) => {
            const checked = benefits.includes(key) ? 'checked' : '';
            return `
                <label class="benefit-option">
                    <input type="checkbox" name="benefits[]" value="${key}" ${checked}>
                    <span class="benefit-label">${info.label}</span>
                </label>
            `;
        }).join('');
    }

    // ── Radio toggle logic ──
    function updateRequiredFields(type) {
        const isFundraising = type === 'fundraising';
        const isVolunteer = type === 'volunteer';

        // Fundraising fields
        const targetAmount = document.getElementById('target_amount');
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        if (targetAmount) targetAmount.required = isFundraising;
        if (startDate) startDate.required = isFundraising;
        if (endDate) endDate.required = isFundraising;

        // Volunteer fields
        const location = document.getElementById('location');
        const capacity = document.getElementById('capacity');
        const eventDate = document.getElementById('eventDate');
        const startTime = document.getElementById('start_time');
        const endTime = document.getElementById('end_time');
        if (location) location.required = isVolunteer;
        if (capacity) capacity.required = isVolunteer;
        if (eventDate) eventDate.required = isVolunteer;
        if (startTime) startTime.required = isVolunteer;
        if (endTime) endTime.required = isVolunteer;
    }

    document.querySelectorAll('input[name="event_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('fundraisingFields').classList.toggle('active', this.value === 'fundraising');
            document.getElementById('volunteerFields').classList.toggle('active', this.value === 'volunteer');
            updateRequiredFields(this.value);
        });
    });

    // Initialize required fields on load
    const checkedRadio = document.querySelector('input[name="event_type"]:checked');
    if (checkedRadio) {
        updateRequiredFields(checkedRadio.value);
    }

    // ── Modal helpers ──
    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    // Close modal on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('active');
        });
    });

    // ── Character Counter for Create Form ──
    const shortDescInput = document.getElementById('eventShortDesc');
    const charCountDisplay = document.getElementById('charCount');
    if (shortDescInput && charCountDisplay) {
        shortDescInput.addEventListener('input', function() {
            charCountDisplay.textContent = this.value.length;
        });
    }

    // ── View Event ──
    function viewEvent(id) {
        const modal = document.getElementById('viewModal');
        const body = document.getElementById('viewModalBody');
        body.innerHTML = '<div style="text-align:center;padding:20px;color:#94a3b8;">Loading...</div>';
        modal.classList.add('active');

        fetch(`{{ url('/admin/events') }}/${id}`)
            .then(r => r.json())
            .then(event => {
                let imgHtml = '';
                if (event.eventImg) {
                    imgHtml = `<img src="{{ asset('storage') }}/${event.eventImg}" class="detail-img" alt="">`;
                }

                let typeInfo = '';
                if (event.fundraising_campaign) {
                    typeInfo = `
                        <div class="detail-row"><div class="detail-label">Type</div><div class="detail-value">Fundraising Campaign</div></div>
                        <div class="detail-row"><div class="detail-label">Target</div><div class="detail-value">RM ${parseFloat(event.fundraising_campaign.target_amount).toFixed(2)}</div></div>
                        <div class="detail-row"><div class="detail-label">Collected</div><div class="detail-value">RM ${parseFloat(event.fundraising_campaign.collected_amount).toFixed(2)}</div></div>
                        <div class="detail-row"><div class="detail-label">Start Date</div><div class="detail-value">${event.fundraising_campaign.start_date}</div></div>
                        <div class="detail-row"><div class="detail-label">End Date</div><div class="detail-value">${event.fundraising_campaign.end_date}</div></div>
                    `;
                } else if (event.volunteer_event) {
                    typeInfo = `
                        <div class="detail-row"><div class="detail-label">Type</div><div class="detail-value">Volunteer Event</div></div>
                        <div class="detail-row"><div class="detail-label">Location</div><div class="detail-value">${event.volunteer_event.location || '—'}</div></div>
                        <div class="detail-row"><div class="detail-label">Address</div><div class="detail-value">${event.volunteer_event.location_details || '—'}</div></div>
                        <div class="detail-row"><div class="detail-label">Date</div><div class="detail-value">${event.volunteer_event.eventDate ? event.volunteer_event.eventDate.substring(0, 10) : 'N/A'}</div></div>
                        <div class="detail-row"><div class="detail-label">Time</div><div class="detail-value">${event.volunteer_event.start_time ? event.volunteer_event.start_time.substring(0, 5) : '—'} to ${event.volunteer_event.end_time ? event.volunteer_event.end_time.substring(0, 5) : '—'}</div></div>
                        <div class="detail-row"><div class="detail-label">Capacity</div><div class="detail-value">${event.volunteer_event.capacity} volunteers</div></div>
                        <div class="detail-row"><div class="detail-label">Applications</div><div class="detail-value">${event.volunteer_event.applications ? event.volunteer_event.applications.length : 0}</div></div>
                        <div class="detail-row"><div class="detail-label">What's Provided</div><div class="detail-value">${formatBenefits(event.volunteer_event.benefits)}</div></div>
                    `;
                }

                body.innerHTML = `
                    ${imgHtml}
                    <div class="detail-row"><div class="detail-label">Event ID</div><div class="detail-value"><strong>${event.formatted_id}</strong></div></div>
                    <div class="detail-row"><div class="detail-label">Title</div><div class="detail-value"><strong>${event.title}</strong></div></div>
                    <div class="detail-row"><div class="detail-label">Category</div><div class="detail-value">${event.eventCategory || '—'}</div></div>
                    <div class="detail-row"><div class="detail-label">Short Desc</div><div class="detail-value">${event.eventShortDesc || '—'}</div></div>
                    <div class="detail-row"><div class="detail-label">Long Desc</div><div class="detail-value">${event.eventLongDesc}</div></div>
                    <div class="detail-row"><div class="detail-label">Status</div><div class="detail-value">${event.status.charAt(0).toUpperCase() + event.status.slice(1)}</div></div>
                    ${typeInfo}
                `;
            })
            .catch(() => {
                body.innerHTML = '<div style="text-align:center;padding:20px;color:#e24b4a;">Failed to load event details.</div>';
            });
    }

    // ── Edit Event ──
    function editEvent(id) {
        const modal = document.getElementById('editModal');
        const body = document.getElementById('editModalBody');
        const form = document.getElementById('editForm');
        body.innerHTML = '<div style="text-align:center;padding:20px;color:#94a3b8;">Loading...</div>';
        modal.classList.add('active');
        
        // Update edit counter on load
        setTimeout(() => {
            const editInput = document.getElementById('editShortDesc');
            if (editInput) {
                document.getElementById('editCharCount').textContent = editInput.value.length;
                editInput.addEventListener('input', function() {
                    document.getElementById('editCharCount').textContent = this.value.length;
                });
            }
        }, 300);

        form.action = `{{ url('/admin/events') }}/${id}`;

        fetch(`{{ url('/admin/events') }}/${id}`)
            .then(r => r.json())
            .then(event => {
                // Format dates to YYYY-MM-DD for date inputs
                const eventDate = (event.volunteer_event && event.volunteer_event.eventDate) ? event.volunteer_event.eventDate.substring(0, 10) : '';
                const startDate = (event.fundraising_campaign && event.fundraising_campaign.start_date) ? event.fundraising_campaign.start_date.substring(0, 10) : '';
                const endDate = (event.fundraising_campaign && event.fundraising_campaign.end_date) ? event.fundraising_campaign.end_date.substring(0, 10) : '';

                let extraFields = '';

                if (event.fundraising_campaign) {
                    extraFields = `
                        <div style="font-size:12px;font-weight:600;color:#00827F;margin:14px 0 8px;text-transform:uppercase;letter-spacing:0.5px;">Fundraising Details</div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Target Amount (RM)</label>
                                <input type="number" step="0.01" name="target_amount" class="form-input" value="${event.fundraising_campaign.target_amount}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Collected Amount (Auto-updated)</label>
                                <div style="padding: 11px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: #f8fafc; color: #64748b;">
                                    RM ${event.fundraising_campaign.collected_amount}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-input" value="${startDate}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-input" value="${endDate}" required>
                            </div>
                        </div>
                    `;
                } else if (event.volunteer_event) {
                    extraFields = `
                        <div class="form-group" style="margin-top: 14px;">
                            <label class="form-label">Location Name (e.g. Venue)</label>
                            <input type="text" name="location" class="form-input" value="${event.volunteer_event.location || ''}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Full Address</label>
                            <textarea name="location_details" class="form-textarea" style="min-height: 60px;">${event.volunteer_event.location_details || ''}</textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Capacity</label>
                                <input type="number" name="capacity" class="form-input" value="${event.volunteer_event.capacity}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Event Date</label>
                                <input type="date" name="eventDate" class="form-input" value="${eventDate}" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Start Time</label>
                                <input type="time" name="start_time" class="form-input" value="${event.volunteer_event.start_time || ''}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">End Time</label>
                                <input type="time" name="end_time" class="form-input" value="${event.volunteer_event.end_time || ''}" required>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 6px;">
                            <label class="form-label">What's Provided (Benefits)</label>
                            <div class="benefits-grid">
                                ${generateBenefitCheckboxes(event.volunteer_event.benefits)}
                            </div>
                        </div>
                    `;
                }

                const categories = ['Flood Relief','Education','Health','Environment','Community Service','Disaster Relief','Animal Welfare'];
                const categoryOptions = categories.map(c => `<option value="${c}" ${event.eventCategory === c ? 'selected' : ''}>${c}</option>`).join('');

                body.innerHTML = `
                    <div class="form-group">
                        <label class="form-label">Event Name</label>
                        <input type="text" name="title" class="form-input" value="${event.title}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Event Category</label>
                        <select name="eventCategory" class="form-select" required>
                            <option value="" disabled>Select a category</option>
                            ${categoryOptions}
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Short Description (Snippet)</label>
                        <input type="text" name="eventShortDesc" id="editShortDesc" class="form-input" value="${event.eventShortDesc || ''}" maxlength="150" required>
                        <div class="char-counter"><span id="editCharCount">0</span>/150</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Long Description</label>
                        <textarea name="eventLongDesc" class="form-textarea" required>${event.eventLongDesc}</textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="grid-column: span 2;">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="active" ${event.status === 'active' ? 'selected' : ''}>Active</option>
                                <option value="draft" ${event.status === 'draft' ? 'selected' : ''}>Draft</option>
                                <option value="completed" ${event.status === 'completed' ? 'selected' : ''}>Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Event Image (leave blank to keep current)</label>
                        <div class="form-file">
                            <input type="file" name="eventImg" accept="image/*">
                        </div>
                    </div>
                    ${extraFields}
                `;
            })
            .catch(() => {
                body.innerHTML = '<div style="text-align:center;padding:20px;color:#e24b4a;">Failed to load event data.</div>';
            });
    }

    // Intercept form submission to show friendly E-Certificate warning
    document.getElementById('editForm').addEventListener('submit', function(e) {
        const statusSelect = this.querySelector('select[name="status"]');
        if (statusSelect && statusSelect.value === 'completed') {
            const friendlyMsg = "Ready to wrap up this event? 🌟\n\nJust a quick heads-up: Marking this event as 'Completed' will automatically send an E-Certificate to all Approved participants!\n\nIf anyone didn't show up, please take a quick moment to 'Decline' their application first so they don't receive a certificate by mistake.\n\nAll good to proceed?";
            
            const confirmed = confirm(friendlyMsg);
            if (!confirmed) {
                e.preventDefault(); 
            }
        }
    });

</script>
@endpush
