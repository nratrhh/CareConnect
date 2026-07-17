<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Receipt - Participant View</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: #f3f4f6; color: #111827; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px; }

        .receipt-card { background: #fff; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.12); width: 100%; max-width: 520px; overflow: hidden; position: relative; border: 1px solid #e5e7eb; }
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

        @media print {
            @page { margin: 0; }
            body { background: #fff; padding: 40px; display: block; }
            .receipt-card { box-shadow: none; max-width: 100%; position: static; margin: 0 auto; border: none; }
            .receipt-actions { display: none; }
        }
    </style>
</head>
<body>

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
                <span style="color: #00827F; font-weight: 600;">{{ strtoupper($donation->status ?? 'SUCCESS') }}</span>
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
                    <p>{{ $donation->transaction_id ?? $donation->payment_intent_id ?? $donation->bill_code ?? 'N/A' }}</p>
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
                <p><span>Name:</span> {{ $donation->participant->name ?? 'Anonymous' }}</p>
                <p><span>Email:</span> {{ $donation->participant->email ?? 'N/A' }}</p>
            </div>

            <div class="rd-section">
                <h5>Campaign</h5>
                <p><span>Event:</span> {{ $donation->fundraisingCampaign->event->title ?? 'N/A' }}</p>
                <p><span>Category:</span> Fundraising</p>
            </div>

            <div class="rd-section">
                <h5>Payment</h5>
                <p><span>Method:</span> {{ $donation->payment_method }}</p>
            </div>

            <div class="receipt-total">
                <span>Amount Paid</span>
                <span>RM {{ number_format($donation->amount + ($donation->payment_method == 'Credit/Debit Card' ? 0.50 : 0.50), 2) }}</span>
            </div>
        </div>

        <div class="receipt-footer">
            <div class="rf-stamp">OFFICIAL<br>DONOR<br>COPY</div>
            <div class="rf-bottom">
                CareConnect &copy; 2026 &middot; Ummah Relief Project
            </div>
        </div>

        <div class="receipt-actions">
            <button class="btn-rcpt-dl" onclick="window.print()">PRINT / DOWNLOAD</button>
            <a href="{{ route('participant.activities.index') }}" class="btn-rcpt-home">RETURN TO MY ACTIVITIES</a>
        </div>
    </div>

</body>
</html>
