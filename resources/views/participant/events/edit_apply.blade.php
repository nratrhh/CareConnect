@extends('layouts.participant')

@section('title', 'Edit Volunteer Application')

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
</style>
@endpush

@section('content')

<div class="volunteer-page">
    <h1 class="page-title">Edit Volunteer Application</h1>
    <p class="page-subtitle">Update your application details</p>

    <!-- Event Banner -->
    <div class="event-banner">
        <div class="event-banner-left">
            <div class="event-banner-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
            <div class="event-banner-info">
                <h3>{{ $application->volunteerEvent->event->title }}</h3>
                <p>{{ \Carbon\Carbon::parse($application->volunteerEvent->eventDate)->format('d F Y') }} · {{ $application->volunteerEvent->location }}</p>
            </div>
        </div>
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
        <form action="{{ route('participant.applications.update', $application->application_id) }}" method="POST">
            @csrf
            @method('PUT')
            
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
                        @php
                            $userSkills = $application->skills ?? [];
                            $allSkills = ['First Aid', 'Teaching', 'Cooking', 'Driving', 'Photography', 'Logistics', 'Medical', 'IT / Tech'];
                        @endphp
                        @foreach($allSkills as $skill)
                        <label>
                            <input type="checkbox" name="skills[]" class="skill-check" value="{{ $skill }}" {{ in_array($skill, $userSkills) ? 'checked' : '' }}>
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
                    <textarea name="notes" class="form-textarea" placeholder="Tell us more about your experience...">{{ old('notes', $application->notes) }}</textarea>
                </div>
            </div>

            <p class="form-footer-note">
                By clicking <strong>"Update Application"</strong>, your changes will be saved.
            </p>

            <div class="form-actions">
                <a href="{{ route('participant.activities.index') }}" class="app-btn app-btn-cancel">Cancel</a>
                <button type="submit" class="app-btn app-btn-submit">Update Application</button>
            </div>
        </form>
    </div>
</div>

@endsection
