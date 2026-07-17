@extends('layouts.participant')

@section('title', 'Apply to Volunteer - ' . $event->title)

@push('styles')
<style>
    .volunteer-page {
        max-width: 680px;
        margin: 40px auto;
        padding: 0 20px;
        font-family: inherit;
    }

    .page-title {
        font-size: 22px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 4px;
    }

    .page-subtitle {
        font-size: 13px;
        color: #4b5563;
        margin-bottom: 24px;
    }

    /* Event Banner Card */
    .event-banner {
        background: #00827F; /* System Color */
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        color: #fff;
    }

    .event-banner-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .event-banner-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: rgba(255,255,255,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .event-banner-info h3 {
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .event-banner-info p {
        font-size: 12px;
        color: rgba(255,255,255,0.85);
    }

    .slots-pill {
        background: rgba(255,255,255,0.2);
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }

    /* Form Container */
    .form-container {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #fff;
        padding: 32px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }
    .form-row.full { grid-template-columns: 1fr; }

    .form-group label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: #4b5563;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .form-group label span {
        color: #ef4444; /* red star */
    }

    .form-input, .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        color: #111827;
        font-family: inherit;
        background: #fff;
        transition: border-color 0.2s;
    }
    .form-input:focus, .form-textarea:focus { outline: none; border-color: #00827F; }
    .form-input.readonly { background: #f3f4f6; color: #6b7280; cursor: not-allowed; }
    .form-textarea { height: 100px; resize: none; }

    .divider {
        height: 1px;
        background: #e5e7eb;
        margin: 24px 0;
    }

    .skills-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .skill-pill {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        border: 1px solid #d1d5db;
        border-radius: 999px;
        font-size: 12px;
        color: #4b5563;
        background: #fff;
        cursor: pointer;
        transition: all 0.2s;
        user-select: none;
    }

    .skill-check { display: none; }
    .skill-check:checked + .skill-pill {
        border-color: #00827F;
        background: #f0fafb;
        color: #00827F;
        font-weight: 600;
    }

    .skills-hint {
        font-size: 11px;
        color: #6b7280;
        margin-top: 8px;
    }

    .form-footer-note {
        font-size: 12px;
        color: #4b5563;
        text-align: center;
        margin-top: 24px;
        margin-bottom: 24px;
    }

    .form-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .app-btn {
        padding: 12px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.2s;
        width: 100%;
        box-sizing: border-box;
    }

    .app-btn-submit {
        background: #00827F;
        color: #fff;
        border: none;
    }
    .app-btn-submit:hover { background: #006b68; }

    .app-btn-cancel {
        background: #fff;
        color: #111827;
        border: 1px solid #d1d5db;
        text-decoration: none;
    }
    .app-btn-cancel:hover { background: #f9fafb; }

    @media (max-width: 600px) {
        .form-row { grid-template-columns: 1fr; }
        .form-actions { grid-template-columns: 1fr; }
    }

    /* Custom Confirmation Modal */
    .confirm-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 20px;
        backdrop-filter: blur(4px);
    }
    .confirm-modal {
        background: #fff;
        width: 100%;
        max-width: 400px;
        border-radius: 20px;
        padding: 32px;
        text-align: center;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .confirm-icon {
        width: 64px;
        height: 64px;
        background: #f0fafb;
        color: #00827F;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    .confirm-title {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 12px;
    }
    .confirm-text {
        font-size: 14px;
        color: #4b5563;
        line-height: 1.5;
        margin-bottom: 28px;
    }
    .confirm-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .btn-modal {
        padding: 12px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }
    .btn-modal-cancel { background: #f3f4f6; color: #4b5563; }
    .btn-modal-confirm { background: #00827F; color: #fff; }
    .btn-modal-cancel:hover { background: #e5e7eb; }
    .btn-modal-confirm:hover { background: #006b68; }
</style>
@endpush

@section('content')

<div class="volunteer-page">
    <h1 class="page-title">Volunteer Registration</h1>
    <p class="page-subtitle">Join us in making a difference</p>

    <!-- Event Banner -->
    <div class="event-banner">
        <div class="event-banner-left">
            <div class="event-banner-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
            <div class="event-banner-info">
                <h3>{{ $event->title }}</h3>
                <p>{{ \Carbon\Carbon::parse($event->volunteerEvent->eventDate)->format('d F Y') }} · {{ $event->volunteerEvent->location }}</p>
            </div>
        </div>
        @if($event->volunteerEvent->capacity)
            @php
                $applications = $event->volunteerEvent->applications()->where('status', 'approved')->count();
                $slotsLeft = max(0, $event->volunteerEvent->capacity - $applications);
            @endphp
            <div class="slots-pill">{{ $slotsLeft }} slots left</div>
        @else
            <div class="slots-pill">Open Slots</div>
        @endif
    </div>

    <!-- Form Container -->
    <div class="form-container">
        @if ($errors->any())
            <div class="alert alert-error" style="margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="volunteerForm" action="{{ route('participant.events.apply.store', $event->event_id) }}" method="POST">
            @csrf
            
            <div class="form-row full">
                <div class="form-group" style="margin-bottom:0;">
                    <label>FULL NAME <span>*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ Auth::guard('participant')->user()->name }}" required>
                </div>
            </div>

            <div class="form-row full">
                <div class="form-group" style="margin-bottom:0;">
                    <label>EMAIL <span>*</span></label>
                    <input type="email" class="form-input readonly" value="{{ Auth::guard('participant')->user()->email }}" readonly disabled>
                </div>
            </div>

            <div class="form-row full">
                <div class="form-group" style="margin-bottom:0;">
                    <label>PHONE NUMBER <span>*</span></label>
                    <input type="text" name="phone" class="form-input" value="{{ Auth::guard('participant')->user()->phone }}" required>
                </div>
            </div>

            <div class="divider"></div>

            <div class="form-row full">
                <div class="form-group" style="margin-bottom:0;">
                    <label>SKILLS</label>
                    <div class="skills-grid">
                        @foreach(['First Aid', 'Teaching', 'Cooking', 'Driving', 'Photography', 'Logistics', 'Medical', 'IT / Tech'] as $skill)
                        <label>
                            <input type="checkbox" name="skills[]" class="skill-check" value="{{ $skill }}">
                            <span class="skill-pill">{{ $skill }}</span>
                        </label>
                        @endforeach
                    </div>
                    <p class="skills-hint">Select all that apply</p>
                </div>
            </div>

            <div class="form-row full">
                <div class="form-group" style="margin-bottom:0;">
                    <label>ADDITIONAL NOTES</label>
                    <textarea name="notes" class="form-textarea" placeholder="Tell us more about your experience..."></textarea>
                </div>
            </div>

            <p class="form-footer-note">
                By clicking <strong>"Submit Application"</strong>, you agree to attend the event on the specified date and follow team guidelines.
            </p>

            <div class="form-actions">
                <a href="{{ route('participant.events.show', $event->event_id) }}" class="app-btn app-btn-cancel">Cancel</a>
                <button type="submit" class="app-btn app-btn-submit">Submit Application</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<!-- Confirmation Modal HTML -->
<div id="confirmModalOverlay" class="confirm-modal-overlay">
    <div class="confirm-modal">
        <div class="confirm-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div class="confirm-title">Confirm Submission?</div>
        <div class="confirm-text">
            By submitting, you agree to volunteer for this event and follow all team guidelines.
        </div>
        <div class="confirm-actions">
            <button type="button" class="btn-modal btn-modal-cancel" onclick="hideConfirmModal()">Cancel</button>
            <button type="button" class="btn-modal btn-modal-confirm" onclick="submitForm()">Yes, Submit</button>
        </div>
    </div>
</div>

<script>
    const form = document.getElementById('volunteerForm');
    const modal = document.getElementById('confirmModalOverlay');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        showConfirmModal();
    });

    function showConfirmModal() {
        modal.style.display = 'flex';
    }

    function hideConfirmModal() {
        modal.style.display = 'none';
    }

    function submitForm() {
        form.submit();
    }
</script>
@endpush
