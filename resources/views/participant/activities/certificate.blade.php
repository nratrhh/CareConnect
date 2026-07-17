<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Certificate - {{ $application->participant->name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Montserrat:wght@400;600;700;800&family=Pinyon+Script&family=Herr+Von+Muellerhoff&display=swap');

        :root {
            --primary: #0F6E56;
            --gold: #D4AF37;
            --dark: #1a1a2e;
            --bg: #f9fafb;
        }

        body {
            margin: 0;
            padding: 40px 0;
            background: #e2e8f0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Montserrat', sans-serif;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* A4 Portrait Size */
        .certificate-wrapper {
            width: 210mm;
            height: 297mm;
            background: #ffffff;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            box-sizing: border-box;
            background-image: repeating-linear-gradient(45deg, transparent, transparent 40px, rgba(0,0,0,0.01) 40px, rgba(0,0,0,0.01) 80px);
        }

        /* Corner Vectors */
        .bg-shape {
            position: absolute;
            z-index: 0;
            pointer-events: none;
        }

        .bg-shape.top-left {
            top: 0;
            left: 0;
            width: 350px;
            height: 300px;
        }

        .bg-shape.bottom-right {
            bottom: 0;
            right: 0;
            width: 380px;
            height: 320px;
        }

        /* Certificate ID */
        .cert-meta {
            position: absolute;
            top: 30px;
            right: 40px;
            font-family: 'Montserrat', sans-serif;
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            z-index: 10;
            text-align: right;
        }

        /* Content */
        .cert-content {
            position: relative;
            z-index: 1;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 50px;
            box-sizing: border-box;
            text-align: center;
        }

        .org-logo-text {
            font-family: 'Cinzel', serif;
            color: #1a1a2e;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .org-logo-text img {
            height: 65px;
            width: auto;
            object-fit: contain;
        }

        .cert-title {
            font-family: 'Cinzel', serif;
            color: var(--gold);
            font-size: 36px;
            font-weight: 700;
            margin: 0 0 25px;
            line-height: 1.2;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .cert-text-small {
            font-size: 15px;
            color: #1a1a2e;
            font-weight: 600;
            margin-bottom: 40px;
        }

        .recipient-name {
            font-family: 'Montserrat', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: #1a1a2e;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0 0 35px;
            border-bottom: 2px solid #1a1a2e;
            padding-bottom: 10px;
            display: inline-block;
            min-width: 350px;
        }

        .cert-body-text {
            font-size: 15px;
            color: #374151;
            max-width: 500px;
            margin: 0 auto 20px;
            line-height: 1.6;
            font-weight: 500;
        }

        .event-name {
            font-size: 20px;
            font-weight: 800;
            color: #1a1a2e;
            margin: 0 0 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .event-location {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a2e;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .event-date-label {
            font-size: 14px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 5px;
        }

        .event-date {
            font-size: 16px;
            font-weight: 800;
            color: #1a1a2e;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 25px;
        }

        .islamic-quote {
            font-size: 13px;
            font-style: italic;
            color: #6b7280;
            margin-bottom: 50px;
            max-width: 450px;
            line-height: 1.6;
            font-family: 'Montserrat', sans-serif;
            position: relative;
        }
        
        .islamic-quote::before, .islamic-quote::after {
            content: '〜';
            color: var(--gold);
            margin: 0 5px;
        }

        /* Signatures */
        .signatures {
            display: flex;
            justify-content: center;
            width: 100%;
            margin-top: 60px;
        }

        .sig-block {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sig-line {
            width: 250px;
            border-bottom: 1.5px solid #1a1a2e;
            margin-bottom: 8px;
        }

        .sig-name {
            font-size: 12px;
            font-weight: 700;
            color: #1a1a2e;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sig-title {
            font-size: 11px;
            font-weight: 600;
            color: #d4af37;
            text-transform: uppercase;
        }

        /* Action Bar (Not Printed) */
        .action-bar {
            width: 210mm;
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-print {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-family: inherit;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }

        .btn-print:hover { background: #005a46; }

        @media print {
            @page { size: A4 portrait; margin: 0; }
            body { background: #fff; align-items: flex-start; padding: 0; }
            .certificate-wrapper { box-shadow: none; margin: 0; }
            .action-bar { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="certificate-wrapper">
        
        <!-- Top Left Corner Wave -->
        <svg class="bg-shape top-left" viewBox="0 0 350 300" preserveAspectRatio="none">
            <path d="M0,0 L350,0 C250,150 150,50 0,300 Z" fill="#1a1a2e"/>
            <path d="M0,20 L320,0 C220,130 120,40 0,280" fill="none" stroke="#D4AF37" stroke-width="4"/>
            <path d="M0,0 L280,0 C180,100 100,30 0,220 Z" fill="#00827F"/>
        </svg>

        <!-- Bottom Right Corner Wave -->
        <svg class="bg-shape bottom-right" viewBox="0 0 380 320" preserveAspectRatio="none">
            <path d="M380,320 L0,320 C100,200 200,280 380,0 Z" fill="#1a1a2e"/>
            <path d="M380,300 L20,320 C120,210 220,290 380,20" fill="none" stroke="#D4AF37" stroke-width="4"/>
            <path d="M380,320 L80,320 C180,240 260,300 380,100 Z" fill="#00827F"/>
        </svg>

        <div class="cert-meta">
            No: {{ 'CERT-' . date('Y') . '-' . str_pad($application->application_id, 4, '0', STR_PAD_LEFT) }}<br>
            Date: {{ date('d M Y') }}
        </div>
        
        <div class="cert-content">
            
            <div class="org-logo-text">
                <img src="{{ asset('images/logo.png') }}" alt="CareConnect Logo">
                UMMAH RELIEF PROJECT
            </div>
            
            <div class="cert-title">Certificate of Appreciation</div>
            
            <div class="cert-text-small">THIS IS TO CERTIFY THAT</div>
            
            <div class="recipient-name">{{ $application->participant->name }}</div>
            
            <div class="cert-body-text">
                has honorably served and demonstrated exceptional dedication, compassion, and commitment as a volunteer for the successful execution of
            </div>
            
            <div class="event-name">{{ $application->volunteerEvent->event->title }}</div>
            
            <div class="cert-body-text" style="margin-bottom:5px;">Held At</div>
            <div class="event-location">{{ $application->volunteerEvent->location }}</div>

            <div class="event-date-label">On</div>
            <div class="event-date">{{ \Carbon\Carbon::parse($application->volunteerEvent->eventDate)->format('d F Y') }}</div>

            <div class="islamic-quote">
                "The best of people are those who are most beneficial to others."
            </div>

            <div class="signatures">
                <div class="sig-block">
                    <!-- Blank space for real signature -->
                    <div style="height: 60px;"></div>
                    <div class="sig-line"></div>
                    <div class="sig-name">MUHAMMAD MUZZAMIL BIN MOKHETAR</div>
                    <div class="sig-title">Chairman, UMMAH RELIEF PROJECT</div>
                </div>
            </div>

        </div>
    </div>

    <div class="action-bar">
        <button class="btn-print" onclick="window.print()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Print / Save PDF
        </button>
    </div>

</body>
</html>
