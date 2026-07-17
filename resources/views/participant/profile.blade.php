@extends('layouts.participant')

@section('title', 'My Profile')

@push('styles')
<style>
    .page-heading { flex-shrink: 0; }
    .page-heading h2 { font-size: 28px; font-weight: 700; color: #1a1a2e; }
    .page-heading p { font-size: 18px; color: #64748b; margin-top: 8px; line-height: 1.7; max-width: 800px; }

    .profile-grid {
        display: grid;
        grid-template-columns: 300px 1fr;
        grid-template-areas: 
            ". tabs"
            "sidebar content";
        gap: 20px;
        align-items: stretch;
        margin-top: 24px;
    }

    .profile-tabs-wrapper {
        grid-area: tabs;
    }

    .profile-sidebar {
        grid-area: sidebar;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        padding: 32px 24px;
    }

    .profile-content { 
        grid-area: content;
        display: flex; 
        flex-direction: column; 
        gap: 20px; 
    }

    .page-profile-avatar-wrap {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto 24px;
    }

    .page-profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #00827F;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 700;
        border: 3px solid #E6F2F2;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .page-profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        image-rendering: -webkit-optimize-contrast; /* Sharpens the image in Webkit browsers */
    }

    .avatar-edit-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #00827F;
        border: 2px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.15s;
    }

    .avatar-edit-btn:hover { background: #006b68; }
    .avatar-edit-btn svg { width: 13px; height: 13px; fill: #fff; }

    .page-profile-name { font-size: 17px; font-weight: 600; color: #1a1a2e; }
    .page-profile-role { font-size: 12px; color: #64748b; margin-top: 3px; }
    .page-profile-email { font-size: 13px; color: #00827F; margin-top: 8px; word-break: break-all; }

    .profile-info {
        margin-top: 20px;
        padding-top: 18px;
        border-top: 0.5px solid #e2e8f0;
        text-align: left;
    }

    .info-item { margin-bottom: 12px; }
    .info-item:last-child { margin-bottom: 0; }
    .info-label { font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
    .info-value { font-size: 13px; color: #1a1a2e; margin-top: 3px; }



    .form-card {
        background: #fff;
        border: 0.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px 26px;
        height: 100%;
    }

    .form-card .card-title { font-size: 16px; font-weight: 600; color: #1a1a2e; margin-bottom: 18px; }

    .form-group { margin-bottom: 16px; }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        color: #1a1a2e;
        font-family: inherit;
        transition: border-color 0.15s, box-shadow 0.15s;
        background: #fff;
    }

    .form-input:focus {
        outline: none;
        border-color: #00827F;
        box-shadow: 0 0 0 3px rgba(0,130,127,0.1);
    }

    .form-input.readonly {
        background: #f8fafc;
        color: #94a3b8;
        cursor: not-allowed;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .btn {
        padding: 10px 22px;
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

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13px;
        margin-bottom: 16px;
    }

    .alert-success { background: #E6F2F2; color: #006b68; border: 1px solid #b2d8d8; }
    .alert-error { background: #FCEBEB; color: #A32D2D; border: 1px solid #f5c6c6; }

    .pw-note {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 6px;
    }

    .pw-requirements {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-top: 10px;
        padding: 12px;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .req-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #64748b;
        transition: color 0.2s;
    }

    .req-icon {
        width: 14px;
        height: 14px;
        flex-shrink: 0;
    }

    .req-item.valid {
        color: #00827F;
    }
    
    .req-item.invalid {
        color: #A32D2D;
    }

    .field-error {
        color: #A32D2D;
        font-size: 12px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .form-hint {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 4px;
    }

    /* Tabs CSS */
    .tabs-nav {
        display: flex;
        gap: 10px;
        margin-bottom: 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .tab-btn {
        padding: 12px 24px;
        background: transparent;
        border: none;
        border-bottom: 2px solid transparent;
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: -1px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tab-btn:hover {
        color: #00827F;
    }

    .tab-btn.active {
        color: #00827F;
        border-bottom-color: #00827F;
    }

    .tab-pane {
        display: none;
        height: 100%;
        animation: fadeIn 0.3s;
    }

    .tab-pane.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 1024px) {
        .profile-grid { 
            grid-template-columns: 1fr; 
            grid-template-areas: 
                "tabs"
                "sidebar"
                "content";
        }
    }
</style>
@endpush

@section('content')

<main class="main-content">
    <div class="page-container">
        <div class="page-heading">
            <h2>My Profile</h2>
            <p>Manage your profile details and preferences for CareConnect.</p>
        </div>





        <div class="profile-grid">
            {{-- Tabs --}}
            <div class="profile-tabs-wrapper">
                <div class="tabs-nav">
                    <button class="tab-btn active" onclick="switchTab('personal', this)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        Personal Details
                    </button>
                    <button class="tab-btn" onclick="switchTab('security', this)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        Security & Password
                    </button>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="profile-sidebar">
                <div class="page-profile-avatar-wrap">
                    <div class="page-profile-avatar">
                        @if($participant->profile_picture)
                            <img id="avatarPreview" src="{{ asset('storage/' . $participant->profile_picture) }}" alt="Profile">
                        @else
                            <span id="avatarInitials">{{ strtoupper(substr($participant->name, 0, 2)) }}</span>
                            <img id="avatarPreview" style="display:none;" alt="Profile">
                        @endif
                    </div>
                    <label for="avatarUpload" class="avatar-edit-btn" title="Change picture">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                    </label>
                </div>

                @error('profile_picture')
                    <div class="field-error" style="text-align: center; margin-bottom: 15px; justify-content: center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        {{ $message }}
                    </div>
                @enderror

                <input type="file" id="avatarUpload" name="profile_picture" accept="image/*" style="display:none;" form="personalDetailsForm" onchange="previewAvatar(this)">

                <div class="page-profile-name" style="text-align: center;">{{ $participant->name }}</div>
                <div class="page-profile-role" style="text-align: center;">Participant</div>
                <div class="page-profile-email" style="text-align: center; display: block; margin-bottom: 20px;">{{ $participant->email }}</div>

                <div class="profile-info">
                    <div class="info-item">
                        <div class="info-label">Phone</div>
                        <div class="info-value">{{ $participant->phone ?? 'Not set' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Member Since</div>
                        <div class="info-value">{{ $participant->created_at ? $participant->created_at->format('d M Y') : 'N/A' }}</div>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="profile-content">
                
                {{-- Edit Profile (name and phone only) --}}
                <div class="tab-pane active" id="tab-personal">
                    <div class="form-card">
                        <div class="card-title" style="display: flex; align-items: center; gap: 10px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="color: #00827F;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            Personal Details
                        </div>
                        <form method="POST" action="{{ route('participant.profile.update') }}" enctype="multipart/form-data" id="personalDetailsForm">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="form-label" for="name">Full Name</label>
                                <input type="text" id="name" name="name" class="form-input"
                                       value="{{ old('name', $participant->name) }}" required
                                       pattern="[A-Za-z\s\/\.']{3,}" title="Please enter a valid name using only letters, spaces, and common punctuation.">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="phone">Phone Number</label>
                                <input type="text" id="phone" name="phone" class="form-input"
                                       value="{{ old('phone', $participant->phone) }}" placeholder="012-3456789"
                                       pattern="01\d-\d{7,8}" title="Please enter a valid phone number (10-11 digits)" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-input readonly" value="{{ $participant->email }}" readonly disabled>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>

                {{-- Change Password --}}
                <div class="tab-pane" id="tab-security">
                    <div class="form-card">
                        <div class="card-title" style="display: flex; align-items: center; gap: 10px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="color: #00827F;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            Change Password
                        </div>
                        <form method="POST" action="{{ route('participant.profile.password') }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="form-label" for="current_password">Current Password</label>
                                <div style="position: relative;">
                                    <input type="password" id="current_password" name="current_password" class="form-input @error('current_password') is-invalid @enderror" style="padding-right: 40px;" required>
                                    <button type="button" onclick="togglePassword('current_password')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: transparent; border: none; cursor: pointer; color: #94a3b8; display: flex; align-items: center; justify-content: center;">
                                        <svg id="eye-icon-current_password" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="field-error">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="password">New Password</label>
                                    <div style="position: relative;">
                                        <input type="password" id="password" name="password" class="form-input @error('password') is-invalid @enderror" style="padding-right: 40px;" required>
                                        <button type="button" onclick="togglePassword('password')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: transparent; border: none; cursor: pointer; color: #94a3b8; display: flex; align-items: center; justify-content: center;">
                                            <svg id="eye-icon-password" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="field-error">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                                    <div style="position: relative;">
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" style="padding-right: 40px;" required>
                                        <button type="button" onclick="togglePassword('password_confirmation')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: transparent; border: none; cursor: pointer; color: #94a3b8; display: flex; align-items: center; justify-content: center;">
                                            <svg id="eye-icon-password_confirmation" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="pw-requirements" id="pwRequirements">
                                <div class="req-item" id="req-length">
                                    <svg viewBox="0 0 24 24" class="req-icon" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15" class="cross-line1"></line><line x1="9" y1="9" x2="15" y2="15" class="cross-line2"></line><polyline points="20 6 9 17 4 12" class="check-mark" style="display:none;"></polyline></svg>
                                    <span>At least 8 characters</span>
                                </div>
                                <div class="req-item" id="req-upper">
                                    <svg viewBox="0 0 24 24" class="req-icon" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15" class="cross-line1"></line><line x1="9" y1="9" x2="15" y2="15" class="cross-line2"></line><polyline points="20 6 9 17 4 12" class="check-mark" style="display:none;"></polyline></svg>
                                    <span>At least one uppercase letter</span>
                                </div>
                                <div class="req-item" id="req-number">
                                    <svg viewBox="0 0 24 24" class="req-icon" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15" class="cross-line1"></line><line x1="9" y1="9" x2="15" y2="15" class="cross-line2"></line><polyline points="20 6 9 17 4 12" class="check-mark" style="display:none;"></polyline></svg>
                                    <span>At least one number</span>
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById('eye-icon-' + inputId);
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
        }
    }
</script>
<script>
    function switchTab(tabId, btn) {
        // Hide all tab panes
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('active');
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active');
        });
        
        // Show selected tab and mark active
        document.getElementById('tab-' + tabId).classList.add('active');
        btn.classList.add('active');
    }

    // Optional: if there's a session error from password change, switch to security tab automatically
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('password_error') || $errors->has('current_password') || $errors->has('password'))
            document.querySelector('.tab-btn:nth-child(2)').click();
        @endif

        // Real-time password validation
        const passwordInput = document.getElementById('password');
        const reqLength = document.getElementById('req-length');
        const reqUpper = document.getElementById('req-upper');
        const reqNumber = document.getElementById('req-number');

        function updateReq(el, isValid) {
            if(isValid) {
                el.classList.remove('invalid');
                el.classList.add('valid');
                el.querySelector('.cross-line1').style.display = 'none';
                el.querySelector('.cross-line2').style.display = 'none';
                el.querySelector('.check-mark').style.display = 'block';
            } else {
                el.classList.remove('valid');
                el.classList.add('invalid');
                el.querySelector('.cross-line1').style.display = 'block';
                el.querySelector('.cross-line2').style.display = 'block';
                el.querySelector('.check-mark').style.display = 'none';
            }
        }

        if(passwordInput) {
            passwordInput.addEventListener('input', function(e) {
                const val = e.target.value;
                
                // Length check
                updateReq(reqLength, val.length >= 8);
                
                // Uppercase check
                updateReq(reqUpper, /[A-Z]/.test(val));
                
                // Number check
                updateReq(reqNumber, /[0-9]/.test(val));
            });

            // Trigger once on load in case browser autofills
            passwordInput.dispatchEvent(new Event('input'));
        }
    });

    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            if(input.files[0].size > 5 * 1024 * 1024) {
                alert('Image size must be less than 5MB.');
                input.value = '';
                return;
            }
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('avatarPreview');
                var initials = document.getElementById('avatarInitials');
                
                if(preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                if(initials) {
                    initials.style.display = 'none';
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush

@endsection
