@extends('layouts.participant')

@section('title', 'Make a Donation - ' . $event->title)

@push('styles')
<style>
    .donation-page {
        max-width: 600px;
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

    .form-container {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
        padding: 32px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .section-label {
        font-size: 11px;
        font-weight: 700;
        color: #4b5563;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }

    /* Campaign Box */
    .campaign-box {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        background: #fafafa;
        margin-bottom: 16px;
    }

    .campaign-icon {
        width: 40px;
        height: 40px;
        background: #e6f2f2;
        color: #00827F;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 18px;
    }

    .campaign-info { flex: 1; }
    .campaign-info h4 { font-size: 14px; font-weight: 700; color: #111827; margin-bottom: 2px; }
    .campaign-info p { font-size: 12px; color: #6b7280; }

    /* Progress Bar */
    .progress-stats {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        font-weight: 600;
        color: #4b5563;
        margin-bottom: 8px;
    }
    .progress-stats span { color: #111827; font-weight: 700; }
    .prog-bar-container { height: 6px; background: #e5e7eb; border-radius: 999px; overflow: hidden; margin-bottom: 32px; }
    .prog-bar-fill { height: 100%; background: #00827F; border-radius: 999px; }

    .divider { height: 1px; background: #e5e7eb; margin: 24px 0; }

    /* Amount Buttons */
    .amount-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 16px;
    }

    .amount-btn {
        padding: 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #fff;
        font-size: 13px;
        font-weight: 700;
        color: #111827;
        cursor: pointer;
        transition: all 0.2s;
    }

    .amount-btn:hover { border-color: #00827F; color: #00827F; background: #f0fafb; }
    .amount-btn.active { border-color: #00827F; background: #f0fafb; color: #00827F; }

    .custom-input-wrapper {
        position: relative;
        margin-bottom: 24px;
    }
    
    .custom-input-wrapper span {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        font-weight: 700;
        color: #6b7280;
    }

    .custom-input-wrapper input {
        width: 100%;
        padding: 16px 16px 16px 48px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        font-size: 16px;
        font-weight: 700;
        color: #111827;
        font-family: inherit;
        outline: none;
        box-sizing: border-box;
    }

    /* Payment Methods Selection */
    .payment-method-selector {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 24px;
    }

    .method-btn {
        padding: 16px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        background: #fff;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .method-btn svg { color: #6b7280; }
    .method-btn span { font-size: 13px; font-weight: 700; color: #4b5563; }

    .method-btn:hover { border-color: #00827F; }
    .method-btn.active { border-color: #00827F; background: #f0fafb; }
    .method-btn.active svg { color: #00827F; }
    .method-btn.active span { color: #00827F; }

    /* Bank Grid */
    .bank-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 16px;
    }

    .bank-btn {
        padding: 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #fff;
        font-size: 12px;
        font-weight: 600;
        color: #4b5563;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }

    .bank-btn:hover { border-color: #00827F; color: #00827F; }
    .bank-btn.active { border-color: #00827F; color: #00827F; background: #f0fafb; }

    /* Stripe Card Element */
    #card-element {
        padding: 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: white;
        margin-bottom: 12px;
    }

    #card-errors {
        color: #ef4444;
        font-size: 12px;
        margin-bottom: 16px;
    }

    .info-alert {
        background: #e6f2f2;
        border: 1px solid #b2d8d8;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 11px;
        color: #006b68;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 24px;
    }

    /* Summary */
    .summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        color: #4b5563;
        margin-bottom: 8px;
    }
    .summary-row.total {
        font-size: 15px;
        font-weight: 800;
        color: #111827;
        margin-top: 16px;
        margin-bottom: 0;
    }
    .summary-row.total span { color: #111827; }

    /* Actions */
    .btn-donate {
        width: 100%;
        padding: 16px;
        background: #00827F;
        border: none;
        color: #fff;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.2s;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-donate:hover { background: #006b68; }
    .btn-donate:disabled { background: #9ca3af; cursor: not-allowed; }

    .btn-cancel {
        width: 100%;
        padding: 12px;
        background: transparent;
        border: 1px solid #d1d5db;
        color: #4b5563;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        text-align: center;
        text-decoration: none;
        display: block;
        margin-top: 12px;
    }
</style>
@endpush

@section('content')

<div class="donation-page">
    <h1 class="page-title">Make a Donation</h1>
    <p class="page-subtitle">Empower our mission with your contribution.</p>

    <div class="form-container">
        <div class="section-label">CAMPAIGN</div>
        <div class="campaign-box">
            <div class="campaign-icon">$</div>
            <div class="campaign-info">
                <h4>{{ $event->title }}</h4>
                <p>Fundraising Campaign</p>
            </div>
        </div>

        @php
            $collected = $event->fundraisingCampaign->collected_amount ?? 0;
            $target = $event->fundraisingCampaign->target_amount ?? 1;
            $percent = min(100, round(($collected / $target) * 100));
        @endphp
        <div class="progress-stats">
            <div>Raised: <span>RM {{ number_format($collected, 0) }}</span></div>
            <div>Goal: <span>RM {{ number_format($target, 0) }}</span></div>
        </div>
        <div class="prog-bar-container">
            <div class="prog-bar-fill" style="width: {{ $percent }}%"></div>
        </div>

        <div class="divider"></div>

        <form id="payment-form">
            @csrf
            <div class="section-label">Choose Amount</div>
            <div class="amount-grid">
                <button type="button" class="amount-btn active" onclick="setAmount(10, this)">RM 10</button>
                <button type="button" class="amount-btn" onclick="setAmount(50, this)">RM 50</button>
                <button type="button" class="amount-btn" onclick="setAmount(100, this)">RM 100</button>
                <button type="button" class="amount-btn" onclick="setAmount(200, this)">RM 200</button>
                <button type="button" class="amount-btn" onclick="setAmount(500, this)">RM 500</button>
                <button type="button" class="amount-btn" onclick="setAmount('custom', this)">Custom</button>
            </div>

            <div class="custom-input-wrapper">
                <span>RM</span>
                <input type="number" id="amount" name="amount" value="10" step="0.01" min="1" required>
            </div>

            <div class="divider"></div>

            <div class="section-label">PAYMENT METHOD</div>
            <div class="payment-method-selector">
                <div class="method-btn active" id="method-card" onclick="selectMethod('card')">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                    <span>Credit/Debit Card</span>
                </div>
                <div class="method-btn" id="method-bank" onclick="selectMethod('bank')">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span>Online Banking</span>
                </div>
            </div>

            <input type="hidden" name="payment_method_type" id="payment_method_type" value="card">

            <!-- Card Section -->
            <div id="card-section">
                <div class="section-label">CARD DETAILS</div>
                <div id="card-element"></div>
                <div id="card-errors" role="alert"></div>
            </div>

            <!-- Bank Section (Billplz FPX) -->
            <div id="bank-section" style="display: none;">
                <div class="section-label">SELECT YOUR BANK</div>
                <div class="bank-grid">
                    <button type="button" class="bank-btn active" data-bank="MBBEMYKL" onclick="selectBank(this)">Maybank</button>
                    <button type="button" class="bank-btn" data-bank="CIBBMYKL" onclick="selectBank(this)">CIMB Bank</button>
                    <button type="button" class="bank-btn" data-bank="PBBEMYKL" onclick="selectBank(this)">Public Bank</button>
                    <button type="button" class="bank-btn" data-bank="RHBBMYKL" onclick="selectBank(this)">RHB Bank</button>
                    <button type="button" class="bank-btn" data-bank="HLBBMYKL" onclick="selectBank(this)">Hong Leong</button>
                    <button type="button" class="bank-btn" data-bank="BIMBMYKL" onclick="selectBank(this)">Bank Islam</button>
                    <button type="button" class="bank-btn" data-bank="AMMBMYKL" onclick="selectBank(this)">AmBank</button>
                    <button type="button" class="bank-btn" data-bank="BKRMMYKL" onclick="selectBank(this)">Bank Rakyat</button>
                </div>
                <div class="info-alert">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    You will be redirected to complete the FPX bank transfer securely.
                </div>
            </div>

            <div class="divider"></div>

            <!-- Summary -->
            <div class="section-label">SUMMARY</div>
            <div class="summary-row">
                <span>Donation amount</span>
                <span id="sumAmount">RM 10.00</span>
            </div>
            <div class="summary-row">
                <span>Transaction fee</span>
                <span>RM 0.50</span>
            </div>
            <div class="summary-row total">
                <span>Total</span>
                <span id="sumTotal">RM 10.50</span>
            </div>

            <button id="submit-button" class="btn-donate">
                <span id="button-text">DONATE NOW</span>
                <span id="spinner" style="display: none;">Processing...</span>
            </button>
            
            <a href="{{ route('participant.events.show', $event->event_id) }}" class="btn-cancel">Cancel</a>
        </form>

        <!-- Hidden form for Billplz FPX submission -->
        <form id="fpx-form" method="POST" action="{{ route('participant.events.donate.fpx', $event->event_id) }}" style="display: none;">
            @csrf
            <input type="hidden" name="amount" id="fpx-amount" value="10">
            <input type="hidden" name="bank_code" id="fpx-bank-code" value="MBBEMYKL">
        </form>
    </div>
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ $stripeKey }}');
    const elements = stripe.elements();
    
    let isProcessing = false;

    window.addEventListener('beforeunload', function (e) {
        if (isProcessing) {
            e.preventDefault();
            e.returnValue = 'Please wait. Your payment is processing. Are you sure you want to leave this page?';
        }
    });
    
    const card = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
                fontFamily: '"Plus Jakarta Sans", sans-serif',
                '::placeholder': { color: '#aab7c4' },
            },
        }
    });
    
    card.mount('#card-element');

    function setAmount(val, btn) {
        document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const input = document.getElementById('amount');
        if (val === 'custom') {
            input.value = '';
            input.focus();
        } else {
            input.value = val;
        }
        calcTotal();
    }

    function calcTotal() {
        const input = document.getElementById('amount');
        let amount = parseFloat(input.value);
        if (isNaN(amount)) amount = 0;

        const fee = 0.50;
        const total = amount + fee;

        document.getElementById('sumAmount').innerText = 'RM ' + amount.toFixed(2);
        document.getElementById('sumTotal').innerText = 'RM ' + total.toFixed(2);
    }

    // Initialize total
    document.getElementById('amount').addEventListener('input', calcTotal);
    calcTotal();

    function selectMethod(method) {
        document.querySelectorAll('.method-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('method-' + method).classList.add('active');
        document.getElementById('payment_method_type').value = method;

        if (method === 'card') {
            document.getElementById('card-section').style.display = 'block';
            document.getElementById('bank-section').style.display = 'none';
        } else {
            document.getElementById('card-section').style.display = 'none';
            document.getElementById('bank-section').style.display = 'block';
        }
    }

    function selectBank(btn) {
        document.querySelectorAll('.bank-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('fpx-bank-code').value = btn.dataset.bank;
    }

    const form = document.getElementById('payment-form');
    const submitBtn = document.getElementById('submit-button');
    const btnText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const amount = document.getElementById('amount').value;
        const methodType = document.getElementById('payment_method_type').value;
        if (!amount || amount < 1) return;

        setLoading(true);

        if (methodType === 'card') {
            // STRIPE FLOW
            try {
                const response = await fetch('{{ route("participant.events.donate.intent") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ amount: amount, event_id: '{{ $event->event_id }}' })
                });

                if (!response.ok) {
                    const errText = await response.text();
                    try {
                        const errData = JSON.parse(errText);
                        throw new Error(errData.message || 'An error occurred processing the payment intent.');
                    } catch (e) {
                        throw new Error('Server error: ' + response.statusText);
                    }
                }

                const data = await JSON.parse(await response.text());
                if (data.error) throw new Error(data.error);

                const { paymentIntent, error } = await stripe.confirmCardPayment(data.clientSecret, {
                    payment_method: { card: card }
                });

                if (error) {
                    document.getElementById('card-errors').textContent = error.message;
                    setLoading(false);
                } else if (paymentIntent.status === 'succeeded') {
                    const storeResponse = await fetch('{{ route("participant.events.donate.store", $event->event_id) }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({
                            amount: amount,
                            payment_intent_id: paymentIntent.id,
                            payment_method: 'Credit/Debit Card'
                        })
                    });
                    
                    if (!storeResponse.ok) {
                        const errText = await storeResponse.text();
                        try {
                            const errData = JSON.parse(errText);
                            throw new Error(errData.message || 'An error occurred saving the donation.');
                        } catch (e) {
                            throw new Error('Server error: ' + storeResponse.statusText);
                        }
                    }
                    
                    const storeData = await JSON.parse(await storeResponse.text());
                    if (storeData.success) {
                        isProcessing = false;
                        window.location.href = storeData.redirect;
                    }
                }
            } catch (err) {
                document.getElementById('card-errors').textContent = err.message;
                setLoading(false);
            }
        } else {
            // BILLPLZ FPX FLOW — submit hidden form with selected bank
            isProcessing = false;
            document.getElementById('fpx-amount').value = amount;
            document.getElementById('fpx-form').submit();
        }
    });

    function setLoading(isLoading) {
        isProcessing = isLoading;
        if (isLoading) {
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            spinner.style.display = 'inline';
        } else {
            submitBtn.disabled = false;
            btnText.style.display = 'inline';
            spinner.style.display = 'none';
        }
    }
</script>
@endpush

@section('footer')
    <!-- Empty footer to avoid layout issues -->
@endsection

@endsection
