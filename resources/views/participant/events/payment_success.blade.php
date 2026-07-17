@extends('layouts.participant')

@section('title', 'Payment Successful')

@push('styles')
<style>
    .success-container {
        max-width: 500px;
        margin: 60px auto;
        text-align: center;
        background: white;
        padding: 48px;
        border-radius: 24px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .success-icon {
        width: 80px;
        height: 80px;
        background: #ecfdf5;
        color: #10b981;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
    }

    .success-title {
        font-size: 24px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 12px;
    }

    .success-message {
        font-size: 16px;
        color: #4b5563;
        margin-bottom: 32px;
        line-height: 1.6;
    }

    .donation-details {
        background: #f9fafb;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 32px;
        text-align: left;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .detail-label { color: #6b7280; }
    .detail-value { color: #111827; font-weight: 700; }

    .btn-done {
        display: block;
        width: 100%;
        padding: 16px;
        background: #00827F;
        color: white;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.2s;
    }

    .btn-done:hover { background: #006b68; transform: translateY(-2px); }
</style>
@endpush

@section('content')
<div class="success-container">
    <div class="success-icon">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
    </div>
    <h1 class="success-title">Donation Received!</h1>
    <p class="success-message">Thank you for your generous contribution to <strong>{{ $event->title }}</strong>. Your support helps us make a real difference.</p>

    <div class="donation-details">
        <div class="detail-row">
            <span class="detail-label">Amount Paid</span>
            <span class="detail-value">RM {{ number_format($donation->amount, 2) }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Transaction ID</span>
            <span class="detail-value" style="font-size: 10px;">{{ $donation->transaction_id ?? $donation->payment_intent_id ?? $donation->bill_code }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Payment Method</span>
            <span class="detail-value">{{ $donation->payment_method }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Date</span>
            <span class="detail-value">{{ $donation->donation_date->format('M d, Y H:i') }}</span>
        </div>
    </div>

    <a href="{{ route('participant.activities.receipt', $donation->donation_id) }}" class="btn-done" style="margin-bottom: 12px;">View Receipt</a>
    <a href="{{ route('participant.activities.index') }}" class="btn-done" style="background: white; color: #00827F; border: 2px solid #00827F;">Back</a>
</div>
@endsection
