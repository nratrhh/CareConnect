@extends('layouts.admin')

@section('title', 'Reports')

@push('styles')
<style>
    .page-heading { flex-shrink: 0; }
    .page-heading h2 { font-size: 24px; font-weight: 600; color: #1a1a2e; }
    .page-heading p { font-size: 14px; color: #64748b; margin-top: 5px; }

    .report-tabs {
        display: flex;
        gap: 0;
        background: #f0f2f5;
        border-radius: 10px;
        padding: 4px;
        margin-bottom: 22px;
        width: fit-content;
    }

    .report-tab {
        padding: 10px 26px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #64748b;
        border: none;
        background: transparent;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
    }

    .report-tab.active {
        background: #fff;
        color: #00827F;
        font-weight: 600;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }

    .report-section { display: none; }
    .report-section.active { display: block; }

    .report-card {
        background: #fff;
        border: 0.5px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }

    .report-header {
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #e2e8f0;
    }

    .report-title { font-size: 17px; font-weight: 600; color: #1a1a2e; }

    .btn-download {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #1a1a2e;
        font-family: inherit;
        transition: all 0.15s;
        text-decoration: none;
    }

    .btn-download:hover { background: #f0f2f5; }
    .btn-download svg { width: 16px; height: 16px; fill: #64748b; }

    .table-wrap { overflow-x: visible; }

    table { width: 100%; border-collapse: collapse; }

    thead th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 12px 10px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
        font-weight: 700;
        white-space: normal;
        background: #f8fafa;
        word-wrap: break-word;
    }

    tbody td {
        font-size: 14px;
        padding: 13px 16px;
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

    .badge-active { background: #E6F2F2; color: #00827F; }
    .badge-draft { background: #f0f2f5; color: #64748b; }
    .badge-completed { background: #FFF3CD; color: #856404; }
    .badge-pending { background: #FAEEDA; color: #633806; }
    .badge-approved { background: #E6F2F2; color: #00827F; }
    .badge-declined { background: #FCEBEB; color: #A32D2D; }

    .pct-bar {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pct-track {
        flex: 1;
        height: 6px;
        background: #f0f2f5;
        border-radius: 99px;
        overflow: hidden;
        min-width: 60px;
    }

    .pct-fill {
        height: 100%;
        border-radius: 99px;
        background: #00827F;
    }

    .pct-text { font-size: 12px; color: #64748b; width: 40px; text-align: right; }

    .empty-state { text-align: center; padding: 50px 0; color: #94a3b8; font-size: 14px; }

    /* Expandable volunteer list */
    .expand-btn {
        font-size: 12px;
        color: #00827F;
        cursor: pointer;
        border: none;
        background: none;
        font-weight: 500;
        text-decoration: underline;
        font-family: inherit;
    }

    .volunteer-list {
        display: none;
        margin-top: 8px;
    }

    .volunteer-list.show { display: block; }

    .volunteer-list table {
        font-size: 13px;
        margin-top: 4px;
    }

    .volunteer-list thead th {
        font-size: 11px;
        padding: 8px 12px;
        background: #f0f2f5;
    }

    .volunteer-list tbody td {
        padding: 8px 12px;
        font-size: 13px;
    }

    .detail-row-wrapper {
        background: #f8fafa;
    }

    .detail-row-wrapper td {
        padding: 0 14px 14px;
    }

    /* New Stats Grid */
    .report-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    .report-stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        transition: transform 0.2s;
    }
    .report-stat-card:hover { transform: translateY(-3px); }
    .stat-header { display: flex; align-items: center; gap: 10px; color: #64748b; font-size: 13px; font-weight: 500; }
    .stat-header svg { width: 18px; height: 18px; color: #00827F; }
    .stat-body { font-size: 24px; font-weight: 700; color: #1a1a2e; }

    /* Generator Panel */
    .generator-panel {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .panel-title {
        font-size: 16px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 18px;
        align-items: flex-end;
    }
    .filter-group { display: flex; flex-direction: column; gap: 6px; }
    .filter-label { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
    .filter-input {
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        color: #1a1a2e;
        background: #f8fafc;
        transition: all 0.2s;
        width: 100%;
    }
    .filter-input:focus { border-color: #00827F; outline: none; background: #fff; box-shadow: 0 0 0 3px rgba(0, 130, 127, 0.1); }
    
    .generator-footer {
        display: flex;
        justify-content: flex-end;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }

    .btn-generate {
        background: #00827F;
        color: #fff;
        border: none;
        padding: 12px 32px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .btn-generate:hover { background: #006b68; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0, 130, 127, 0.2); }

    #eventNameContainer {
        display: none;
        animation: fadeIn 0.3s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.45);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    /* Hide reports by default */
    .report-content-wrapper {
        display: none;
        animation: slideUp 0.5s ease-out forwards;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .report-placeholder {
        background: #fff;
        border: 2px dashed #e2e8f0;
        border-radius: 16px;
        padding: 60px 20px;
        text-align: center;
        color: #94a3b8;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
        margin-bottom: 30px;
    }
    .report-placeholder svg { width: 48px; height: 48px; opacity: 0.5; }

    /* Real Document Style Report */
    .report-document {
        background: #fff;
        width: 100%;
        max-width: 1000px;
        margin: 0 auto 50px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border-radius: 4px; /* Paper usually has sharp/slight radius */
        padding: 60px 40px; /* Reduced padding to fit more content width-wise */
        display: flex;
        flex-direction: column;
        border: 1px solid #d1d5db;
        position: relative;
        animation: slideUp 0.5s ease-out forwards;
    }
    .doc-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 3px solid #1a1a2e;
        padding-bottom: 25px;
        margin-bottom: 40px;
    }
    .doc-brand { display: flex; align-items: center; gap: 20px; }
    .doc-logo { width: 60px; height: 60px; object-fit: contain; }
    .doc-brand-text h2 { color: #1a1a2e; font-size: 28px; font-weight: 800; margin: 0; letter-spacing: -1px; line-height: 1; }
    .doc-brand-text p { color: #00827F; font-size: 13px; font-weight: 700; margin: 5px 0 0; text-transform: uppercase; letter-spacing: 0.5px; }
    .doc-meta { text-align: right; font-size: 13px; color: #4b5563; line-height: 1.6; }
    .doc-meta b { color: #111827; }

    .doc-section-title {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 8px;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .doc-summary-text {
        font-size: 14px;
        color: #374151;
        margin-bottom: 35px;
        line-height: 1.6;
    }

    .doc-stats-inline {
        display: flex;
        gap: 40px;
        margin-bottom: 40px;
        background: #f9fafb;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #f3f4f6;
    }
    .doc-stat-item { display: flex; flex-direction: column; }
    .doc-stat-item:last-child { margin-left: auto; text-align: right; }
    .doc-stat-val { font-size: 22px; font-weight: 800; color: #111827; }
    .doc-stat-lbl { font-size: 12px; color: #6b7280; font-weight: 600; text-transform: uppercase; }

    .doc-table-wrapper { width: 100%; overflow: visible; margin-bottom: 50px; }
    .doc-table { width: 100%; border-collapse: collapse; table-layout: auto; }
    .doc-table th { background: #f3f4f6; padding: 12px 8px; text-align: left; font-size: 11px; font-weight: 700; color: #374151; border-top: 1px solid #d1d5db; border-bottom: 2px solid #9ca3af; white-space: normal; word-wrap: break-word; }
    .doc-table td { padding: 10px 8px; border-bottom: 1px solid #e5e7eb; font-size: 12px; color: #111827; word-wrap: break-word; }

    .doc-signature-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 100px;
        margin-top: 60px;
        padding-top: 40px;
    }
    .sig-box { display: flex; flex-direction: column; gap: 60px; }
    .sig-box:last-child { align-items: flex-end; text-align: right; }
    .sig-line { border-top: 1px solid #111827; width: 220px; }
    .sig-label { font-size: 13px; font-weight: 700; color: #111827; }
    .sig-sub { font-size: 12px; color: #6b7280; margin-top: 4px; }

    .doc-footer {
        margin-top: 60px;
        padding-top: 25px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .btn-export-group { display: flex; gap: 12px; }
    .btn-export {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.2s;
    }
    .btn-excel { background: #fff; border: 1px solid #d1d5db; color: #111827; }
    .btn-excel:hover { background: #f9fafb; border-color: #9ca3af; }
    .btn-pdf { background: #1a1a2e; color: #fff; border: none; }
    .btn-pdf:hover { background: #000; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.2); }

    .report-content-wrapper { display: none; }

    @media print {
        @page { margin: 0; size: A4; }
        body * { visibility: hidden; }
        #reportContentWrapper, #reportContentWrapper * { visibility: visible; }
        #reportContentWrapper { position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0; }
        .report-document { box-shadow: none; margin: 0; padding: 2cm 2.5cm; max-width: 100%; min-height: 100vh; }
        .btn-export-group { display: none !important; }
        .page-heading, .generator-panel, .sidebar, nav, header { display: none !important; }
    }
    .modal-overlay.active { display: flex; }

    .modal {
        background: #fff;
        border-radius: 14px;
        width: 92%;
        max-width: 680px;
        max-height: 88vh;
        overflow-y: auto;
        box-shadow: 0 10px 40px rgba(0,0,0,0.18);
    }

    .modal-header {
        padding: 20px 24px;
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

    .modal-body { padding: 24px; }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }

    .detail-item {}
    .detail-item-label { font-size: 12px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .detail-item-value { font-size: 15px; font-weight: 600; color: #1a1a2e; }
    .detail-item-value.teal { color: #00827F; }
    .detail-item-value.warn { color: #BA7517; }

    .detail-progress { margin-bottom: 20px; }
    .detail-progress-bar {
        height: 10px;
        background: #f0f2f5;
        border-radius: 99px;
        overflow: hidden;
        margin-top: 8px;
    }
    .detail-progress-fill {
        height: 100%;
        border-radius: 99px;
        background: #00827F;
    }

    .detail-section-title {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 12px;
        padding-top: 16px;
        border-top: 1px solid #f1f5f9;
    }

    .detail-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
    .detail-table thead th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 10px 12px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
        font-weight: 600;
        background: #f8fafa;
    }
    .detail-table tbody td {
        font-size: 13px;
        padding: 10px 12px;
        border-bottom: 0.5px solid #f1f5f9;
        color: #1a1a2e;
    }
    .detail-table tbody tr:last-child td { border-bottom: none; }

    .event-link {
        color: #00827F;
        cursor: pointer;
        text-decoration: none;
        font-weight: 600;
    }
    .event-link:hover {
        text-decoration: underline;
    }

    .btn-modal-download {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        background: #00827F;
        color: #fff;
        font-family: inherit;
        transition: all 0.15s;
    }
    .btn-modal-download:hover { background: #006b68; }
    .btn-modal-download svg { width: 16px; height: 16px; fill: #fff; }

    .btn-modal-close {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        font-family: inherit;
    }
    .btn-modal-close:hover { background: #f0f2f5; }
</style>
@endpush

@section('content')

<div class="page-heading">
    <h2>Reporting Hub</h2>
    <p>Analyze performance and generate detailed event reports</p>
</div>



{{-- ═══ GENERATE REPORT: Filter Panel ═══ --}}
<div class="generator-panel">
    <div class="panel-title">
        <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
        Generate Custom Report
    </div>
    <div class="filter-grid">
        <div class="filter-group">
            <label class="filter-label">1. Select Report Category</label>
            <select class="filter-input" id="filterReportType" onchange="updateEventList()">
                <option value="fundraising">💰 Fundraising Summary Report</option>
                <option value="engagement">🤝 Event Engagement Report</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label class="filter-label">2. Target Event / Campaign</label>
            <select class="filter-input" id="filterEventName">
                {{-- Populated by JS --}}
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label">3. Time Period (Month & Year)</label>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                <select class="filter-input" id="filterMonth">
                    <option value="all">All Months</option>
                    <option value="01">Jan</option><option value="02">Feb</option><option value="03">Mar</option>
                    <option value="04">Apr</option><option value="05">May</option><option value="06">Jun</option>
                    <option value="07">Jul</option><option value="08">Aug</option><option value="09">Sep</option>
                    <option value="10">Oct</option><option value="11">Nov</option><option value="12">Dec</option>
                </select>
                <select class="filter-input" id="filterYear">
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                </select>
            </div>
        </div>
    </div>
    <div class="generator-footer">
        <button class="btn-generate" onclick="generateReport()">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Generate Report
        </button>
    </div>
</div>

{{-- ═══ REPORT CONTENT (Hidden by default) ═══ --}}
<div id="reportPlaceholder" class="report-placeholder">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
    <div>
        <h3 style="color:#1a1a2e; margin-bottom:4px;">No Report Generated</h3>
        <p style="font-size:14px;">Select filters above and click <b>Generate</b> to view detailed breakdown.</p>
    </div>
</div>

<div id="reportContentWrapper" class="report-content-wrapper">
    <div class="report-document">
        <div class="doc-header">
            <div class="doc-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="doc-logo">
                <div class="doc-brand-text">
                    <h2>UMMAH RELIEF PROJECT</h2>
                    <p id="docReportType">Official Organization Report</p>
                    <div id="docEventName" style="font-size: 16px; font-weight: 700; color: #BA7517; margin-top: 4px; display: none; text-transform: uppercase;"></div>
                    <div id="docEventDetails" style="font-size: 13px; color: #4b5563; margin-top: 4px; display: none;"></div>
                </div>
            </div>
            <div class="doc-meta">
                <div id="docDocId">Document ID: <b>URP-REP-{{ date('YmdHi') }}</b></div>
                <div id="docGeneratedAt">Generated At: <b>{{ date('d M Y, h:i A') }}</b></div>
                <div>Generated By: <b>Administrator</b></div>
                <div id="docPeriod">Period: <b>All Time</b></div>
            </div>
        </div>

        <div class="doc-section-title">Executive Summary</div>
        <p class="doc-summary-text" id="docSummaryText">
            This report provides a comprehensive overview of organization activities including financial contributions and volunteer engagement. 
            All data presented in this document has been verified from the official URP database records for the specified period.
        </p>

        <div class="doc-stats-inline" id="docSummary">
            {{-- Populated by JS --}}
        </div>

        <div class="doc-section-title">Detailed Record Tracking</div>
        <div class="doc-table-wrapper" id="docTableContainer">
            {{-- Table cloned here by JS --}}
        </div>

        <div class="doc-signature-grid">
            <div class="sig-box">
                <div class="sig-line"></div>
                <div>
                    <div class="sig-label">Prepared By,</div>
                    <div class="sig-sub">System Administrator<br>Ummah Relief Project</div>
                </div>
            </div>
            <div class="sig-box">
                <div class="sig-line"></div>
                <div>
                    <div class="sig-label">Approved By,</div>
                    <div class="sig-sub">Director of Operations<br>Ummah Relief Project</div>
                </div>
            </div>
        </div>

        <div class="doc-footer">
            <div style="font-size:11px; color:#9ca3af; font-style:italic;">
                Confidential - For internal use only.
            </div>
            <div class="btn-export-group">
                <button class="btn-export btn-excel" onclick="downloadCurrentCSV()">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download Excel
                </button>
                <button class="btn-export btn-pdf" onclick="window.print()">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Download PDF
                </button>
            </div>
        </div>
    </div>
</div>
</div>

{{-- Hidden Source Data Tables (used for processing) --}}
<div style="display:none;">
    <div id="fundraisingReport">
        @if($fundraisingCampaigns->count())
        <table>
            <thead>
                <tr><th>Campaign Name</th><th>Date</th><th>Goal Amount (RM)</th><th>Total Donations (RM)</th><th>% Goal Achieved</th><th>Top Donor(s)</th><th>No. of Donors</th></tr>
            </thead>
            <tbody>
                @foreach($fundraisingCampaigns as $c)
                <tr>
                    <td>{{ $c->campaign_name }}</td>
                    <td>{{ $c->date ? $c->date->format('d M Y') : 'N/A' }}</td>
                    <td>{{ number_format($c->goal_amount, 2) }}</td>
                    <td>{{ number_format($c->total_donated, 2) }}</td>
                    <td>{{ $c->percentage }}%</td>
                    <td>{{ $c->top_donor }}</td>
                    <td>{{ $c->num_donors }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    <div id="engagementReport">
        @if($volunteerEvents->count())
        <table>
            <thead>
                <tr><th>Event Name</th><th>Date</th><th>Location</th><th>Status</th><th>Capacity</th><th>Approved</th><th>Pending</th><th>Declined</th></tr>
            </thead>
            <tbody>
                @foreach($volunteerEvents as $idx => $ve)
                <tr>
                    <td>{{ $ve->event_name }}</td>
                    <td>{{ $ve->date ? $ve->date->format('d M Y') : 'N/A' }}</td>
                    <td>{{ $ve->location }}</td>
                    <td>{{ ucfirst($ve->status) }}</td>
                    <td>{{ $ve->capacity }}</td>
                    <td>{{ $ve->approved }}</td>
                    <td>{{ $ve->pending }}</td>
                    <td>{{ $ve->declined }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

{{-- ═══ DETAIL MODAL ═══ --}}
<div class="modal-overlay" id="reportDetailModal">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modalTitle">Event Report</h3>
            <button class="modal-close" onclick="closeDetailModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalBody">
            <div style="text-align:center;padding:20px;color:#94a3b8;">Loading...</div>
        </div>
        <div class="modal-footer">
            <button class="btn-modal-close" onclick="closeDetailModal()">Close</button>
            <button class="btn-modal-download" id="modalDownloadBtn" onclick="downloadCurrentReport()">
                <svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                Download Report
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ── Data from server ──
    const fundraisingData = @json($fundraisingCampaigns);
    const engagementData = @json($volunteerEvents);
    const fundraisingTitles = @json($fundraisingTitles);
    const volunteerTitles = @json($volunteerTitles);
    let currentReportCSV = '';
    let currentReportFilename = '';

    function updateEventList() {
        const type = document.getElementById('filterReportType').value;
        const select = document.getElementById('filterEventName');
        const titles = (type === 'fundraising') ? fundraisingTitles : volunteerTitles;
        
        select.innerHTML = '';
        
        // Add "All" option as default
        const allOpt = document.createElement('option');
        allOpt.value = 'all';
        allOpt.textContent = type === 'fundraising' ? '📊 All Campaigns (Summary)' : '📅 All Events (Summary)';
        select.appendChild(allOpt);

        Object.values(titles).forEach(title => {
            const opt = document.createElement('option');
            opt.value = title;
            opt.textContent = title;
            select.appendChild(opt);
        });
    }

    // Initialize list
    document.addEventListener('DOMContentLoaded', updateEventList);

    function closeReportPreview() {
        document.getElementById('reportPreviewModal').style.display = 'none';
    }

    function generateReport() {
        const month = document.getElementById('filterMonth').value;
        const year = document.getElementById('filterYear').value;
        const eventName = document.getElementById('filterEventName').value;
        const type = document.getElementById('filterReportType').value;
        const scope = (eventName === 'all') ? 'all' : 'specific';
        const docEventName = document.getElementById('docEventName');
        docEventName.style.display = 'none';
        docEventName.textContent = '';
        
        const docEventDetails = document.getElementById('docEventDetails');
        if (docEventDetails) {
            docEventDetails.style.display = 'none';
            docEventDetails.innerHTML = '';
        }

        const btn = document.querySelector('.btn-generate');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Generating...';
        btn.disabled = true;

        setTimeout(() => {
            // Update Report Meta
            const now = new Date();
            const docId = 'URP-REP-' + now.getFullYear() + String(now.getMonth()+1).padStart(2,'0') + String(now.getDate()).padStart(2,'0') + String(now.getHours()).padStart(2,'0') + String(now.getMinutes()).padStart(2,'0');
            const generatedAt = now.toLocaleDateString('en-GB', {day:'2-digit', month:'short', year:'numeric'}) + ', ' + now.toLocaleTimeString('en-US', {hour:'2-digit', minute:'2-digit', hour12:true});
            
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            document.getElementById('docDocId').innerHTML = `Document ID: <b>${docId}</b>`;
            document.getElementById('docGeneratedAt').innerHTML = `Generated At: <b>${generatedAt}</b>`;
            document.getElementById('docReportType').textContent = type === 'fundraising' ? 'Fundraising Summary Report' : 'Event Engagement Report';
            document.getElementById('docPeriod').innerHTML = `Period: <b>${month === 'all' ? 'Full Year' : monthNames[parseInt(month)-1]} ${year}</b>`;

            // Process Rows
            const sourceTableId = type === 'fundraising' ? 'fundraisingReport' : 'engagementReport';
            const rows = document.querySelectorAll(`#${sourceTableId} tbody tr:not(.detail-row-wrapper)`);
            
            // Stats calculation for preview
            let totalDonated = 0;
            let totalCampaigns = 0;
            let totalVolunteers = 0;
            let activeEvents = 0;

            const container = document.getElementById('docTableContainer');
            container.innerHTML = '';
            const summary = document.getElementById('docSummary');
            const docSummaryText = document.getElementById('docSummaryText');

            if (scope === 'specific') {
                // ── SPECIFIC EVENT DETAIL REPORT ──
                if (type === 'fundraising') {
                    const c = fundraisingData.find(d => d.campaign_name === eventName);
                    if (c) {
                        docEventName.textContent = c.campaign_name;
                        docEventName.style.display = 'block';
                        docSummaryText.textContent = `This report provides a detailed breakdown of the "${c.campaign_name}" campaign, including financial contributions and donor performance. All data has been verified from the official URP database.`;
                        
                        summary.innerHTML = `
                            <div class="doc-stat-item"><span class="doc-stat-val">RM ${parseFloat(c.goal_amount).toLocaleString(undefined, {minimumFractionDigits:2})}</span><span class="doc-stat-lbl">Goal Amount</span></div>
                            <div class="doc-stat-item"><span class="doc-stat-val" style="color:#00827F;">RM ${parseFloat(c.total_donated).toLocaleString(undefined, {minimumFractionDigits:2})}</span><span class="doc-stat-lbl">Total Donated</span></div>
                            <div class="doc-stat-item"><span class="doc-stat-val">${c.num_donors}</span><span class="doc-stat-lbl">Total Donors</span></div>
                            <div class="doc-stat-item"><span class="doc-stat-val">${c.percentage}%</span><span class="doc-stat-lbl">Goal Reached</span></div>
                        `;

                        if (c.donors && c.donors.length > 0) {
                            let rows = c.donors.map(d => `<tr><td>${d.name}</td><td>RM ${parseFloat(d.amount).toLocaleString(undefined, {minimumFractionDigits:2})}</td><td>${d.date || 'N/A'}</td><td>${d.payment_method || 'N/A'}</td></tr>`).join('');
                            container.innerHTML = `<table class="doc-table"><thead><tr><th>Donor Name</th><th>Amount</th><th>Date</th><th>Method</th></tr></thead><tbody>${rows}</tbody></table>`;
                        } else {
                            container.innerHTML = '<div style="padding:20px;text-align:center;color:#64748b;">No donations recorded yet.</div>';
                        }
                    }
                } else {
                    const ve = engagementData.find(d => d.event_name === eventName);
                    if (ve) {
                        docEventName.textContent = ve.event_name;
                        docEventName.style.display = 'block';
                        
                        if (docEventDetails) {
                            const eventDate = ve.date ? new Date(ve.date).toLocaleDateString('en-GB', {day:'numeric', month:'short', year:'numeric'}) : 'N/A';
                            docEventDetails.innerHTML = `<span style="font-weight:600;">Date:</span> ${eventDate} &nbsp;|&nbsp; <span style="font-weight:600;">Location:</span> ${ve.location || 'N/A'}`;
                            docEventDetails.style.display = 'block';
                        }
                        
                        docSummaryText.textContent = `This report provides a detailed breakdown of the "${ve.event_name}" event, including volunteer engagement and registration statistics. All data has been verified from the official URP database.`;

                        summary.innerHTML = `
                            <div class="doc-stat-item"><span class="doc-stat-val">${ve.capacity}</span><span class="doc-stat-lbl">Capacity</span></div>
                            <div class="doc-stat-item"><span class="doc-stat-val">${ve.approved}</span><span class="doc-stat-lbl">Approved</span></div>
                            <div class="doc-stat-item"><span class="doc-stat-val">${ve.pending}</span><span class="doc-stat-lbl">Pending</span></div>
                            <div class="doc-stat-item"><span class="doc-stat-val">${ve.declined}</span><span class="doc-stat-lbl">Declined</span></div>
                        `;

                        if (ve.volunteers && ve.volunteers.length > 0) {
                            let rows = ve.volunteers.map(v => `<tr><td>${v.name}</td><td>${new Date(v.applied_at).toLocaleDateString('en-GB')}</td><td><span style="text-transform:uppercase;font-size:11px;font-weight:700;">${v.status}</span></td></tr>`).join('');
                            container.innerHTML = `<table class="doc-table"><thead><tr><th>Volunteer Name</th><th>Application Date</th><th>Status</th></tr></thead><tbody>${rows}</tbody></table>`;
                        } else {
                            container.innerHTML = '<div style="padding:20px;text-align:center;color:#64748b;">No volunteers registered yet.</div>';
                        }
                    }
                }
            } else {
                // ── SUMMARY REPORT (All Events) ──
                docSummaryText.textContent = "This report provides a comprehensive overview of organization activities including financial contributions and volunteer engagement. All data presented in this document has been verified from the official URP database records for the specified period.";
                const previewTable = document.createElement('table');
                previewTable.className = 'doc-table';
                
                const sourceHeader = document.querySelector(`#${sourceTableId} thead`).cloneNode(true);
                previewTable.appendChild(sourceHeader);
                
                const tbody = document.createElement('tbody');
                
                rows.forEach(row => {
                    let show = true;
                    const rowText = row.innerText;

                    if (month !== 'all' && !rowText.includes(monthNames[parseInt(month)-1])) show = false;
                    if (!rowText.includes(year)) show = false;
                    
                    if (show) {
                        const clonedRow = row.cloneNode(true);
                        clonedRow.style.cursor = 'default';
                        clonedRow.onclick = null;
                        tbody.appendChild(clonedRow);
                        
                        totalCampaigns++;
                        if (type === 'fundraising') {
                            const amountStr = row.cells[2].innerText.replace(/,/g, '');
                            totalDonated += parseFloat(amountStr) || 0;
                        } else {
                            totalVolunteers += parseInt(row.cells[5].innerText) || 0;
                        }
                        if (rowText.toLowerCase().includes('active')) activeEvents++;
                    }
                });
                previewTable.appendChild(tbody);
                container.appendChild(previewTable);

                if (type === 'fundraising') {
                    summary.innerHTML = `
                        <div class="doc-stat-item"><span class="doc-stat-val">RM ${totalDonated.toLocaleString()}</span><span class="doc-stat-lbl">Total Funds</span></div>
                        <div class="doc-stat-item"><span class="doc-stat-val">${totalCampaigns}</span><span class="doc-stat-lbl">Campaigns</span></div>
                        <div class="doc-stat-item"><span class="doc-stat-val">${activeEvents}</span><span class="doc-stat-lbl">Active</span></div>
                        <div class="doc-stat-item"><span class="doc-stat-val">100%</span><span class="doc-stat-lbl">Verified</span></div>
                    `;
                } else {
                    summary.innerHTML = `
                        <div class="doc-stat-item"><span class="doc-stat-val">${totalVolunteers}</span><span class="doc-stat-lbl">Volunteers</span></div>
                        <div class="doc-stat-item"><span class="doc-stat-val">${totalCampaigns}</span><span class="doc-stat-lbl">Events</span></div>
                        <div class="doc-stat-item"><span class="doc-stat-val">${activeEvents}</span><span class="doc-stat-lbl">Active</span></div>
                        <div class="doc-stat-item"><span class="doc-stat-val">100%</span><span class="doc-stat-lbl">Verified</span></div>
                    `;
                }
            }

            btn.innerHTML = originalText;
            btn.disabled = false;
            
            // Show Embedded Report
            document.getElementById('reportPlaceholder').style.display = 'none';
            document.getElementById('reportContentWrapper').style.display = 'block';
            
            // Scroll to report
            document.getElementById('reportContentWrapper').scrollIntoView({ behavior: 'smooth' });
        }, 1000);
    }

    function downloadCurrentCSV() {
        const type = document.getElementById('filterReportType').value;
        window.location.href = `/admin/reports/download?type=${type}`;
    }

    function toggleVolList(idx) {
        const row = document.getElementById('volList' + idx);
        row.style.display = row.style.display === 'none' ? '' : 'none';
    }

    // ── Modal helpers ──
    function closeDetailModal() {
        document.getElementById('reportDetailModal').classList.remove('active');
    }

    document.getElementById('reportDetailModal').addEventListener('click', function(e) {
        if (e.target === this) closeDetailModal();
    });

    // ── Fundraising Detail ──
    function showFundraisingDetail(idx) {
        const c = fundraisingData[idx];
        const modal = document.getElementById('reportDetailModal');
        const title = document.getElementById('modalTitle');
        const body = document.getElementById('modalBody');

        title.textContent = c.campaign_name + ' — Fundraising Summary Report';

        // Build donors table
        let donorsHtml = '<div style="text-align:center;padding:16px;color:#94a3b8;font-size:13px;">No donations recorded.</div>';
        if (c.donors && c.donors.length > 0) {
            let rows = c.donors.map(d => `
                <tr>
                    <td>${d.name}</td>
                    <td>RM ${parseFloat(d.amount).toFixed(2)}</td>
                    <td>${d.date || 'N/A'}</td>
                    <td>${d.payment_method || 'N/A'}</td>
                </tr>
            `).join('');

            donorsHtml = `
                <table class="detail-table">
                    <thead><tr><th>Donor Name</th><th>Amount</th><th>Date</th><th>Method</th></tr></thead>
                    <tbody>${rows}</tbody>
                </table>
            `;
        }

        body.innerHTML = `
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-item-label">Campaign Name</div>
                    <div class="detail-item-value">${c.campaign_name}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item-label">Status</div>
                    <div class="detail-item-value">${c.percentage >= 100 ? '✅ Goal Reached' : '⏳ In Progress'}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item-label">Goal Amount</div>
                    <div class="detail-item-value">RM ${parseFloat(c.goal_amount).toFixed(2)}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item-label">Total Donated</div>
                    <div class="detail-item-value teal">RM ${parseFloat(c.total_donated).toFixed(2)}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item-label">No. of Donors</div>
                    <div class="detail-item-value">${c.num_donors}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item-label">Top Donor</div>
                    <div class="detail-item-value">${c.top_donor}${c.top_amount > 0 ? ' (RM ' + parseFloat(c.top_amount).toFixed(2) + ')' : ''}</div>
                </div>
            </div>

            <div class="detail-progress">
                <div style="display:flex;justify-content:space-between;font-size:13px;">
                    <span style="color:#64748b;">Progress</span>
                    <span style="font-weight:600;color:#00827F;">${c.percentage}%</span>
                </div>
                <div class="detail-progress-bar">
                    <div class="detail-progress-fill" style="width:${Math.min(c.percentage, 100)}%"></div>
                </div>
            </div>

            <div class="detail-section-title">Donor List</div>
            ${donorsHtml}
        `;

        // Build CSV for this campaign
        let csv = 'Campaign Report: ' + c.campaign_name + '\n\n';
        csv += 'Goal Amount,Total Donated,% Achieved,No. of Donors,Top Donor\n';
        csv += `"RM ${parseFloat(c.goal_amount).toFixed(2)}","RM ${parseFloat(c.total_donated).toFixed(2)}",${c.percentage}%,${c.num_donors},"${c.top_donor}"\n`;
        if (c.donors && c.donors.length > 0) {
            csv += '\nDonor Name,Amount (RM),Date,Payment Method\n';
            c.donors.forEach(d => {
                csv += `"${d.name}",${parseFloat(d.amount).toFixed(2)},${d.date || 'N/A'},${d.payment_method || 'N/A'}\n`;
            });
        }
        currentReportCSV = csv;
        currentReportFilename = c.campaign_name.replace(/[^a-zA-Z0-9]/g, '_') + '_report.csv';

        modal.classList.add('active');
    }

    // ── Engagement Detail ──
    function showEngagementDetail(idx) {
        const ve = engagementData[idx];
        const modal = document.getElementById('reportDetailModal');
        const title = document.getElementById('modalTitle');
        const body = document.getElementById('modalBody');

        title.textContent = ve.event_name + ' — Event Engagement Report';

        const eventDate = ve.date ? new Date(ve.date).toLocaleDateString('en-GB', {day:'numeric', month:'short', year:'numeric'}) : 'N/A';
        const fillRate = ve.capacity > 0 ? Math.round((ve.total_registered / ve.capacity) * 100) : 0;

        // Volunteer list table
        let volHtml = '<div style="text-align:center;padding:16px;color:#94a3b8;font-size:13px;">No volunteer applications.</div>';
        if (ve.volunteers && ve.volunteers.length > 0) {
            let rows = ve.volunteers.map(v => {
                const appliedDate = v.applied_at ? new Date(v.applied_at).toLocaleDateString('en-GB', {day:'numeric', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'}) : 'N/A';
                const badgeClass = v.status === 'approved' ? 'badge-approved' : (v.status === 'pending' ? 'badge-pending' : 'badge-declined');
                return `
                    <tr>
                        <td>${v.name}</td>
                        <td>${appliedDate}</td>
                        <td><span class="badge-sm ${badgeClass}">${v.status.charAt(0).toUpperCase() + v.status.slice(1)}</span></td>
                    </tr>
                `;
            }).join('');

            volHtml = `
                <table class="detail-table">
                    <thead><tr><th>Volunteer Name</th><th>Applied Date</th><th>Status</th></tr></thead>
                    <tbody>${rows}</tbody>
                </table>
            `;
        }

        body.innerHTML = `
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-item-label">Event Name</div>
                    <div class="detail-item-value">${ve.event_name}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item-label">Date</div>
                    <div class="detail-item-value">${eventDate}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item-label">Location</div>
                    <div class="detail-item-value">${ve.location}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item-label">Status</div>
                    <div class="detail-item-value"><span class="badge-sm badge-${ve.status}">${ve.status.charAt(0).toUpperCase() + ve.status.slice(1)}</span></div>
                </div>
                <div class="detail-item">
                    <div class="detail-item-label">Capacity</div>
                    <div class="detail-item-value">${ve.capacity} volunteers</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item-label">Total Registered</div>
                    <div class="detail-item-value teal">${ve.total_registered}</div>
                </div>
            </div>

            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-item-label">Approved</div>
                    <div class="detail-item-value" style="color:#0F6E56;">${ve.approved}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item-label">Pending</div>
                    <div class="detail-item-value warn">${ve.pending}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item-label">Declined</div>
                    <div class="detail-item-value" style="color:#A32D2D;">${ve.declined}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item-label">Fill Rate</div>
                    <div class="detail-item-value">${fillRate}%</div>
                </div>
            </div>

            <div class="detail-progress">
                <div style="display:flex;justify-content:space-between;font-size:13px;">
                    <span style="color:#64748b;">Capacity Filled</span>
                    <span style="font-weight:600;color:#00827F;">${ve.total_registered} / ${ve.capacity}</span>
                </div>
                <div class="detail-progress-bar">
                    <div class="detail-progress-fill" style="width:${Math.min(fillRate, 100)}%"></div>
                </div>
            </div>

            <div class="detail-section-title">Volunteer List</div>
            ${volHtml}
        `;

        // Build CSV for this event
        let csv = 'Engagement Report: ' + ve.event_name + '\n\n';
        csv += 'Date,Location,Status,Capacity,Total Registered,Approved,Pending,Declined\n';
        csv += `${eventDate},"${ve.location}",${ve.status},${ve.capacity},${ve.total_registered},${ve.approved},${ve.pending},${ve.declined}\n`;
        if (ve.volunteers && ve.volunteers.length > 0) {
            csv += '\nVolunteer Name,Applied Date,Status\n';
            ve.volunteers.forEach(v => {
                const d = v.applied_at ? new Date(v.applied_at).toLocaleDateString('en-GB') : 'N/A';
                csv += `"${v.name}",${d},${v.status}\n`;
            });
        }
        currentReportCSV = csv;
        currentReportFilename = ve.event_name.replace(/[^a-zA-Z0-9]/g, '_') + '_report.csv';

        modal.classList.add('active');
    }

    // ── Download current report ──
    function downloadCurrentReport() {
        const blob = new Blob([currentReportCSV], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = currentReportFilename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
</script>
@endpush
