@extends('layouts.admin')

@section('title', 'Fundraising')

@push('styles')
<style>
    .page-heading { margin-bottom: 24px; }
    .page-heading h2 { font-size: 24px; font-weight: 600; color: #1a1a2e; }
    .page-heading p { font-size: 14px; color: #64748b; margin-top: 5px; }

    html { scrollbar-gutter: stable; }

    /* Summary Bar */
    .summary-bar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    .summary-card {
        background: #fff;
        border: 0.5px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px 20px;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
    }
    .summary-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px -5px rgba(0,0,0,0.05); }
    
    .card-top { display: flex; align-items: center; margin-bottom: 12px; }
    
    .summary-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .summary-info { display: flex; flex-direction: column; }
    .summary-label { font-size: 11px; color: #94a3b8; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
    .summary-value { font-size: 24px; font-weight: 600; color: #1a1a2e; line-height: 1; }

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

    .donor-info { display: flex; align-items: center; gap: 12px; }
    .donor-avatar {
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
    .donor-name { font-weight: 500; color: #1a1a2e; }
    .donor-email { font-size: 12px; color: #64748b; margin-top: 2px; }
    .amount-text { font-size: 15px; font-weight: 700; color: #0F6E56; }


    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 20px;
    }
    .modal-content {
        background: #fff;
        width: 100%;
        max-width: 600px;
        height: 85vh;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    #receiptFrame {
        width: 100%;
        height: 100%;
        border: none;
    }
    .modal-close {
        position: absolute;
        top: 15px;
        right: 15px;
        color: #64748b;
        background: #f1f5f9;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        font-size: 18px;
        cursor: pointer;
        z-index: 1001;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    /* Performance Section - Modern Slider */
    .perf-slider-outer { position: relative; display: flex; align-items: center; gap: 10px; margin-bottom: 32px; }
    .perf-slider-wrapper { flex: 1; overflow: hidden; padding: 10px 2px; }
    
    .perf-grid { 
        display: flex;
        gap: 15px;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .perf-item { 
        background: #fff; 
        border: 1px solid #f1f5f9; 
        border-radius: 12px; 
        padding: 18px; 
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        flex: 0 0 calc(33.333% - 10px); /* Show 3 cards */
        min-width: 280px;
        box-sizing: border-box;
    }
    .perf-item:hover { transform: translateY(-3px); box-shadow: 0 10px 20px -10px rgba(0,0,0,0.08); border-color: #00827F; }
    
    .perf-nav-btn {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #fff;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        z-index: 10;
        flex-shrink: 0;
        color: #64748b;
    }
    .perf-nav-btn:hover:not(:disabled) { background: #00827F; color: #fff; border-color: #00827F; }
    .perf-nav-btn:disabled { opacity: 0.2; cursor: not-allowed; }

    .perf-header { margin-bottom: 8px; }
    .perf-name { font-size: 14px; font-weight: 700; color: #1e293b; line-height: 1.4; display: block; margin-bottom: 6px; min-height: 38px; }
    
    .perf-status-badge { display: inline-block; font-size: 9px; padding: 1px 8px; border-radius: 99px; font-weight: 700; text-transform: uppercase; margin-bottom: 12px; }
    .status-badge-active { background: #ecfdf5; color: #059669; }

    .perf-main-stats { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 12px; }
    .perf-collected { font-size: 18px; font-weight: 800; color: #00827F; letter-spacing: -0.01em; }
    .perf-target { font-size: 11px; color: #94a3b8; font-weight: 500; }

    .perf-progress-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; font-size: 11px; }
    .perf-pct { font-weight: 700; color: #00827F; }
    .perf-donors { color: #64748b; font-weight: 500; }

    .perf-bar-bg { height: 6px; background: #f1f5f9; border-radius: 99px; overflow: hidden; margin-bottom: 15px; }
    .perf-bar-fill { height: 100%; background: linear-gradient(90deg, #00827F, #2dd4bf); border-radius: 99px; transition: width 0.6s ease; }

    .perf-meta { margin-top: auto; padding-top: 12px; border-top: 1px dashed #e2e8f0; display: flex; justify-content: space-between; font-size: 10px; color: #94a3b8; }
    .perf-date { display: flex; align-items: center; gap: 4px; }
</style>
@endpush

@section('content')

<div class="page-heading">
    <h2>Fundraising Campaigns</h2>
    <p>Monitor and manage fundraising performance</p>
</div>

{{-- Summary Bar --}}
@php
    $activeCount = $fundraisingCampaigns->filter(fn($c) => $c->event->status === 'active')->count();
    $totalCollected = $fundraisingCampaigns->sum('collected_amount');
    $successfulDonations = $donations->where('status', 'succeeded');
    $uniqueDonors = $successfulDonations->unique('participant_id')->count();
    $avgDonation = $successfulDonations->count() > 0 ? ($successfulDonations->sum('amount') / $successfulDonations->count()) : 0;
@endphp

<div class="summary-bar">
    {{-- Card 1: Active Campaigns --}}
    <div class="summary-card">
        <div class="card-top">
            <div class="summary-icon" style="background: #ecfdf5;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"></path><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"></path><path d="M9 12H4s.5-1 1-4c2 1 3 2 4 4Z"></path><path d="M12 15v5s1 .5 4 1c1-2 2-3 4-4Z"></path></svg>
            </div>
        </div>
        <div class="summary-info">
            <div class="summary-label">Active Campaigns</div>
            <div class="summary-value">{{ $activeCount }}</div>
        </div>
    </div>
    
    {{-- Card 2: Total Funds --}}
    <div class="summary-card">
        <div class="card-top">
            <div class="summary-icon" style="background: #f5f3ff;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2"><rect width="20" height="12" x="2" y="6" rx="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path></svg>
            </div>
        </div>
        <div class="summary-info">
            <div class="summary-label">Total Funds Raised</div>
            <div class="summary-value">
                @if($totalCollected >= 1000)
                    RM{{ number_format($totalCollected / 1000, 1) }}k
                @else
                    RM{{ number_format($totalCollected, 0) }}
                @endif
            </div>
        </div>
    </div>

    {{-- Card 3: Total Donors --}}
    <div class="summary-card">
        <div class="card-top">
            <div class="summary-icon" style="background: #fffbeb;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
        </div>
        <div class="summary-info">
            <div class="summary-label">Total Donors</div>
            <div class="summary-value">{{ number_format($uniqueDonors) }}</div>
        </div>
    </div>

    {{-- Card 4: Avg Donation --}}
    <div class="summary-card">
        <div class="card-top">
            <div class="summary-icon" style="background: #f0f9ff;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
            </div>
        </div>
        <div class="summary-info">
            <div class="summary-label">Avg. Donation</div>
            <div class="summary-value">RM{{ number_format($avgDonation, 2) }}</div>
        </div>
    </div>
</div>

<div class="section-heading" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 10px; margin: 0;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 3v18h18"></path><path d="m19 9-5 5-4-4-3 3"></path></svg>
        Campaign Performance
    </h3>

</div>

<div class="perf-slider-outer">
    @php
        $activeCampaigns = $fundraisingCampaigns->filter(function($c) {
            $isStatusActive = $c->event->status === 'active';
            $isNotPast = \Carbon\Carbon::parse($c->end_date)->gte(now()->startOfDay());
            $isWithinWindow = \Carbon\Carbon::parse($c->start_date)->lte(now()->addDays(30));
            return $isStatusActive && $isNotPast && $isWithinWindow;
        });
    @endphp

    @if($activeCampaigns->count() > 3)
    <button class="perf-nav-btn" id="perfPrev" disabled>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
    </button>
    @endif
    
    <div class="perf-slider-wrapper">
        <div class="perf-grid" id="perfTrack">
            @foreach($activeCampaigns as $camp)
            <div class="perf-item">
                <div class="perf-header">
                    <span class="perf-status-badge status-badge-active">
                        {{ $camp->event->status }}
                    </span>
                    <span class="perf-name" title="{{ $camp->event->title }}">{{ $camp->event->title }}</span>
                </div>

                <div class="perf-main-stats">
                    <div class="perf-collected">RM {{ number_format($camp->collected_amount, 2) }}</div>
                    <div class="perf-target">of RM {{ number_format($camp->target_amount, 0) }}</div>
                </div>

                <div class="perf-progress-row">
                    <div class="perf-pct">{{ $camp->percentage }}%</div>
                    <div class="perf-donors">{{ $camp->donations->count() }} Donors</div>
                </div>

                <div class="perf-bar-bg">
                    <div class="perf-bar-fill" style="width: {{ $camp->percentage }}%"></div>
                </div>

                <div class="perf-meta">
                    <div class="perf-date">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        Ends {{ \Carbon\Carbon::parse($camp->end_date)->format('M d, Y') }}
                    </div>
                    <div style="font-weight: 600;">
                        {{ \Carbon\Carbon::parse($camp->end_date)->diffForHumans() }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    @if($activeCampaigns->count() > 3)
    <button class="perf-nav-btn" id="perfNext">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
    </button>
    @endif
</div>

<div class="table-card">
    <div class="table-header">
        <div class="card-title">All Transactions</div>
    </div>

    <div class="table-wrap">
        @if($donations->count())
        <table class="data-table">
            <thead>
                <tr>
                    <th>Reference ID</th>
                    <th>Donor</th>
                    <th>Campaign</th>
                    <th>Date & Time</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donations as $donation)
                <tr>
                    <td><span style="color: #64748b; font-weight: 500; font-family: monospace;">
                        TRX-{{ str_pad($donation->donation_id, 6, '0', STR_PAD_LEFT) }}
                    </span></td>
                    <td>
                        <div class="donor-info">
                            <div class="donor-avatar">{{ strtoupper(substr($donation->participant->name ?? 'A', 0, 2)) }}</div>
                            <div>
                                <div class="donor-name">{{ $donation->participant->name ?? 'Anonymous' }}</div>
                                <div class="donor-email">{{ $donation->participant->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $donation->fundraisingCampaign->event->title ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($donation->donation_date)->format('d M Y, h:i A') }}</td>
                    <td><div class="amount-text">RM {{ number_format($donation->amount, 2) }}</div></td>
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
                    <td style="text-align: center;">
                        @if($donation->status === 'succeeded')
                            <button class="btn-pastel btn-pastel-view" onclick="viewReceipt({{ $donation->donation_id }})">
                                <i class="fa-solid fa-receipt"></i> View Receipt
                            </button>
                        @else
                            <button class="btn-pastel" style="background: #f1f5f9; color: #94a3b8; cursor: not-allowed;" disabled>
                                <i class="fa-solid fa-receipt"></i> View Receipt
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state" style="text-align: center; padding: 60px 20px; color: #94a3b8;">
            <p>No donations recorded yet.</p>
        </div>
        @endif
    </div>
</div>

<!-- Receipt Modal -->
<div id="receiptModal" class="modal-overlay" onclick="closeModal(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="closeModal(null)">&times;</button>
        <iframe id="receiptFrame" src="about:blank"></iframe>
    </div>
</div>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script>
    // Removed toggleCampaign logic as it's no longer an accordion

    function viewReceipt(id) {
        const modal = document.getElementById('receiptModal');
        const frame = document.getElementById('receiptFrame');
        
        modal.style.display = 'flex';
        frame.src = '{{ url('admin/fundraising') }}/' + id + '/receipt';
    }

    function closeModal(e) {
        document.getElementById('receiptModal').style.display = 'none';
        document.getElementById('receiptFrame').src = 'about:blank'; // Clear iframe
    }

    // Auto-open receipt modal if URL has ?show_receipt=ID
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const showReceiptId = urlParams.get('show_receipt');
        if (showReceiptId) {
            viewReceipt(showReceiptId);
            const table = $('.data-table').DataTable();
            table.search('TRX-' + String(showReceiptId).padStart(6, '0')).draw();
        }

        // Performance Slider Logic
        const track = document.getElementById('perfTrack');
        const prevBtn = document.getElementById('perfPrev');
        const nextBtn = document.getElementById('perfNext');
        
        if (track && prevBtn && nextBtn) {
            let currentIndex = 0;
            const cards = track.querySelectorAll('.perf-item');
            const totalCards = cards.length;
            const visibleCards = 3;

            function updateSlider() {
                const cardWidth = cards[0].offsetWidth + 20; // width + gap
                track.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
                
                prevBtn.disabled = currentIndex === 0;
                nextBtn.disabled = currentIndex >= totalCards - visibleCards;
            }

            nextBtn.addEventListener('click', () => {
                if (currentIndex < totalCards - visibleCards) {
                    currentIndex++;
                    updateSlider();
                }
            });

            prevBtn.addEventListener('click', () => {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateSlider();
                }
            });

            // Handle resize
            window.addEventListener('resize', updateSlider);
        }
    });
</script>
@endpush
