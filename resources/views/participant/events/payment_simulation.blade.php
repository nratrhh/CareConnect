<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Simulation - CareConnect</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f3f4f6; color: #111827; min-height: 100vh; display: flex; align-items: center; justify-content: center; }

        /* Screens container */
        .screen { display: none; width: 100%; max-width: 500px; }
        .screen.active { display: block; }
        
        /* SCENE 1 & 2: Bank Frame */
        .bank-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin: 20px;
        }

        .bank-header {
            background: #00827F; /* Branded Teal Blue */
            color: #fff;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .bank-header-title { font-size: 18px; font-weight: 800; }
        .bank-header-secure { font-size: 12px; display: flex; align-items: center; gap: 6px; }

        .fpx-bar {
            padding: 12px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .fpx-logo { background: #00827F; color: #fff; padding: 4px 8px; font-size: 11px; font-weight: 800; border-radius: 4px; }
        .fpx-text { font-size: 14px; color: #4b5563; }

        .bank-body { padding: 24px; }

        .summary-title { font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; margin-bottom: 16px; letter-spacing: 1px; }
        .summary-row { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px; color: #6b7280; }
        .summary-row span:last-child { color: #111827; font-weight: 500; text-align: right; }
        
        .summary-total {
            display: flex; justify-content: space-between; align-items: center;
            margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb;
            font-size: 15px; font-weight: 700; color: #111827; margin-bottom: 24px;
        }

        .sim-alert {
            background: #e6f2f2;
            border: 1px solid #b2d8d8;
            padding: 16px;
            border-radius: 8px;
            color: #006b68;
            font-size: 13px;
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .bank-info-box {
            border: 1px solid #e5e7eb;
            background: #fafafa;
            border-radius: 8px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }
        .bank-icon { width: 44px; height: 44px; background: #00827F; color: #fff; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; }
        .bank-name h4 { font-size: 14px; color: #111827; margin-bottom: 2px; }
        .bank-name p { font-size: 12px; color: #6b7280; }

        .bank-actions { display: grid; grid-template-columns: 1fr 2fr; gap: 12px; margin-bottom: 24px; }
        .bank-btn { padding: 14px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; text-align: center; font-family: inherit; transition: all 0.2s;}
        .bank-btn-confirm { background: #fff; border: 1px solid #d1d5db; color: #111827; }
        .bank-btn-confirm:hover { border-color: #9ca3af; background: #f9fafb; }
        .bank-btn-cancel { background: #fff; border: 1px solid #d1d5db; color: #4b5563; text-decoration: none; }
        
        .ssl-footer { text-align: center; font-size: 11px; color: #9ca3af; display: flex; align-items: center; justify-content: center; gap: 6px; }

        /* Loader */
        .loader-container { padding: 60px 20px; text-align: center; }
        .spinner { width: 48px; height: 48px; border: 4px solid #f3f4f6; border-top: 4px solid #00827F; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 24px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .loader-title { font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 8px; }
        .loader-desc { font-size: 13px; color: #6b7280; }

        /* Success Screen */
        .success-card { text-align: center; padding: 40px; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin: 20px; max-width: 400px; width: 100%;}
        .success-icon { width: 64px; height: 64px; background: #22c55e; border-radius: 50%; color: #fff; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; }
        .success-title { font-size: 20px; font-weight: 800; color: #111827; margin-bottom: 8px; }
        .success-amount { font-size: 32px; font-weight: 800; color: #00827F; margin-bottom: 32px; }
        
        .btn-view-receipt { display: block; width: 100%; padding: 14px; background: #00827F; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 700; cursor: pointer; border: none; }
        .btn-view-receipt:hover { background: #006b68; }

        /* Receipt Screen */
        .receipt-card { background: #fff; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.12); width: 100%; max-width: 520px; overflow: hidden; position: relative; border: 1px solid #e5e7eb; margin: 20px auto; }
        .receipt-header { padding: 30px; text-align: center; background: #fafafa; border-bottom: 1px solid #e5e7eb; }
        .receipt-logo { width: 50px; height: auto; margin-bottom: 12px; }
        .receipt-brand { font-size: 20px; font-weight: 800; letter-spacing: 3px; color: #111827; margin-bottom: 6px; }
        .receipt-type { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 1.5px; }

        .receipt-thankyou { padding: 35px 25px; text-align: center; border-bottom: 1px dashed #d1d5db; }
        .rt-icon { width: 50px; height: 50px; border: 2.5px solid #00827F; border-radius: 50%; color: #00827F; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-weight: 800; }
        .rt-title { font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 6px; }
        .rt-desc { font-size: 14px; color: #6b7280; }

        .receipt-details { padding: 35px; }
        .rd-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px; }
        .rd-block h5 { font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.8px; }
        .rd-block p { font-size: 14px; color: #111827; font-weight: 500;}
        .rd-block:nth-child(even) { text-align: right; }

        .rd-section { margin-bottom: 30px; }
        .rd-section h5 { font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 0.8px; }
        .rd-section p { font-size: 14px; color: #111827; font-weight: 500; margin-bottom: 6px; }
        .rd-section span { color: #6b7280; margin-right: 4px; }

        .receipt-total { background: #f8fafc; border-radius: 8px; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .receipt-total span:first-child { font-size: 13px; font-weight: 700; color: #111827; text-transform: uppercase; }
        .receipt-total span:last-child { font-size: 20px; font-weight: 800; color: #00827F; }

        .receipt-footer { padding: 30px; text-align: center; border-top: 1px solid #e5e7eb; position: relative;}
        .rf-stamp {
            position: absolute;
            right: -15px;
            top: 15px;
            width: 120px;
            height: 120px;
            border: 3px solid rgba(0, 130, 127, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: rotate(-15deg);
            pointer-events: none;
            color: rgba(0, 130, 127, 0.2);
            font-weight: 800;
            font-size: 14px;
            text-align: center;
            line-height: 1.2;
            letter-spacing: 1.5px;
        }

        .rf-bottom { font-size: 11px; color: #9ca3af; line-height: 1.6; }
        
        .receipt-actions { padding: 0 35px 35px; display: flex; flex-direction: column; gap: 12px; }
        .btn-rcpt-dl { width: 100%; padding: 14px; border: 1px solid #d1d5db; background: #fff; color: #111827; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; text-align: center; font-family: inherit; }
        .btn-rcpt-dl:hover { background: #f9fafb; }
        .btn-rcpt-home { width: 100%; padding: 14px; border: 1px solid #d1d5db; background: #fff; color: #4b5563; border-radius: 8px; font-size: 13px; font-weight: 700; text-align: center; text-decoration: none; font-family: inherit; display: block; box-sizing: border-box; }
        .btn-rcpt-home:hover { background: #f9fafb; }

        /* Print Styles */
        @media print {
            @page { margin: 0; } /* Removes browser URL and Date headers */
            body { background: white; padding: 0; margin: 0; }
            .screen { display: none !important; }
            #screen4 { display: block !important; max-width: 100% !important; margin: 0 !important; }
            .receipt-card {
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                margin: 0 auto !important;
                padding: 40px !important;
                max-width: 100% !important;
            }
            .receipt-actions { display: none !important; }
            .rf-stamp { border-color: rgba(0, 130, 127, 0.4) !important; color: rgba(0, 130, 127, 0.6) !important; }
        }
    </style>
</head>
<body>

    <!-- SCREEN 1: Payment Confirmation -->
    <div class="screen {{ session('from_bank') ? '' : 'active' }}" id="screen1">
        <div class="bank-card">
            <div class="bank-header">
                <div class="bank-header-title">{{ str_contains($donation->payment_method, 'Card') ? 'CareConnect Pay' : 'Bank Gateway' }}</div>
                <div class="bank-header-secure">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    Secure Payment
                </div>
            </div>
            <div class="fpx-bar">
                <div class="fpx-logo">{{ str_contains($donation->payment_method, 'Card') ? 'STRIPE' : 'FPX' }}</div>
                <div class="fpx-text">{{ str_contains($donation->payment_method, 'Card') ? 'Secured by Stripe' : 'Financial Process Exchange' }}</div>
            </div>
            
            <div class="bank-body">
                <div class="summary-title">Payment Summary</div>
                <div class="summary-row">
                    <span>Merchant</span>
                    <span>CareConnect</span>
                </div>
                <div class="summary-row">
                    <span>Description</span>
                    <span>{{ $event->title }}</span>
                </div>
                <div class="summary-row">
                    <span>Reference</span>
                    <span>{{ $donation->payment_intent_id ?? 'TXN-88472'.$donation->donation_id.'301' }}</span>
                </div>
                
                <div class="summary-total">
                    <span>Total Amount</span>
                    <span>RM {{ number_format($donation->amount + 0.50, 2) }}</span>
                </div>

                <div class="sim-alert">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <div>Final confirmation. Click <strong>Confirm Payment</strong> to complete your donation safely.</div>
                </div>

                <div class="bank-info-box">
                    <div class="bank-icon">{{ str_contains($donation->payment_method, 'Card') ? 'CC' : 'BK' }}</div>
                    <div class="bank-name">
                        <h4>{{ $donation->payment_method }}</h4>
                        <p>Authorized Transaction</p>
                    </div>
                </div>

                <div class="bank-actions">
                    <a href="{{ route('participant.events.show', $event->event_id) }}" class="bank-btn bank-btn-cancel">Cancel</a>
                    <button class="bank-btn bank-btn-confirm" onclick="processPayment()">Confirm Payment</button>
                </div>

                <div class="ssl-footer">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    256-bit SSL Encrypted · Verified by {{ str_contains($donation->payment_method, 'Card') ? 'Stripe' : 'FPX' }}
                </div>
            </div>
        </div>
    </div>

    <!-- SCREEN 2: Loader -->
    <div class="screen" id="screen2">
        <div class="bank-card">
            <div class="bank-header">
                <div class="bank-header-title">Processing...</div>
                <div class="bank-header-secure">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    Secure Payment
                </div>
            </div>
            
            <div class="loader-container">
                <div class="spinner"></div>
                <div class="loader-title">Completing your donation...</div>
                <div class="loader-desc">Please do not close this window</div>
            </div>
        </div>
    </div>

    <!-- SCREEN 3: Success -->
    <div class="screen {{ session('from_bank') ? 'active' : '' }}" id="screen3">
        <div class="success-card">
            <div class="success-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <div class="success-title">Donation Successful!</div>
            <div class="success-amount">RM {{ number_format($donation->amount + 0.50, 2) }}</div>
            <button class="btn-view-receipt" onclick="showReceipt()">View Receipt</button>
        </div>
    </div>

    <!-- SCREEN 4: Receipt -->
    <div class="screen" id="screen4" style="max-width:520px;">
        <div class="receipt-card">
            <div class="receipt-header">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="receipt-logo">
                <div class="receipt-brand">UMMAH RELIEF PROJECT</div>
                <div class="receipt-type">Official Donation Receipt</div>
            </div>
            
            <div class="receipt-thankyou">
                <div class="rt-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <div class="rt-title">Donation Verified</div>
                <div class="rt-desc">System Record: 
                    <span style="color: #00827F; font-weight: 600;">SUCCESS</span>
                </div>
            </div>

            <div class="receipt-details">
                <div class="rd-grid">
                    <div class="rd-block">
                        <h5>Receipt No</h5>
                        <p>RCP-2026{{ str_pad($donation->donation_id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="rd-block">
                        <h5>Payment ID</h5>
                        <p>{{ substr($donation->payment_intent_id ?? 'TXN-88472'.$donation->donation_id.'301', 0, 15) }}...</p>
                    </div>
                    <div class="rd-block">
                        <h5>Date</h5>
                        <p>{{ \Carbon\Carbon::parse($donation->donation_date)->format('d/m/Y') }}</p>
                    </div>
                    <div class="rd-block">
                        <h5>Time</h5>
                        <p>{{ \Carbon\Carbon::parse($donation->donation_date)->format('h:i A') }}</p>
                    </div>
                </div>

                <div class="rd-section">
                    <h5>Donor</h5>
                    <p><span>Name:</span> {{ $donation->participant->name }}</p>
                    <p><span>Email:</span> {{ $donation->participant->email }}</p>
                </div>

                <div class="rd-section">
                    <h5>Campaign</h5>
                    <p><span>Event:</span> {{ $event->title }}</p>
                    <p><span>Category:</span> Fundraising</p>
                </div>

                <div class="rd-section">
                    <h5>Payment</h5>
                    <p><span>Method:</span> {{ $donation->payment_method }}</p>
                </div>

                <div class="receipt-total">
                    <span>Amount Paid</span>
                    <span>RM {{ number_format($donation->amount + 0.50, 2) }}</span>
                </div>
            </div>

            <div class="receipt-footer">
                <div class="rf-stamp">OFFICIAL<br>DONOR<br>COPY</div>
                <div class="rf-bottom">
                    CareConnect &copy; 2026 &middot; Ummah Relief Project
                </div>
            </div>

            <div class="receipt-actions">
                <button class="btn-rcpt-dl" onclick="window.print()">Print / Download</button>
                <a href="{{ route('participant.activities.index') }}" class="btn-rcpt-home">Back</a>
            </div>
        </div>
    </div>

    <script>
        function processPayment() {
            document.getElementById('screen1').classList.remove('active');
            document.getElementById('screen2').classList.add('active');

            setTimeout(() => {
                document.getElementById('screen2').classList.remove('active');
                document.getElementById('screen3').classList.add('active');
                
                setTimeout(() => {
                    showReceipt();
                }, 2000);
            }, 2500);
        }

        function showReceipt() {
            document.getElementById('screen3').classList.remove('active');
            document.getElementById('screen4').classList.add('active');
        }

        // Auto display receipt after a short delay for a smoother experience
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('from_bank'))
                setTimeout(() => {
                    showReceipt();
                }, 2000);
            @endif
        });
    </script>
</body>
</html>
