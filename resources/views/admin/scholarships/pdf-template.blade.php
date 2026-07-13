<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Scholarship Application - {{ $application->full_name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif; 
            font-size: 13px; 
            line-height: 1.7; 
            color: #1a1a2e; 
            padding: 0;
            background: #fff;
        }

        /* ─── Header ─────────────────────────────────── */
        .header { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            background: linear-gradient(135deg, #0A2463 0%, #0d2f7a 100%);
            padding: 5px 4px;
            margin-bottom: 0;
            border-radius: 8px 8px 0 0;
        }
        .header-logo { 
            height: 60px; 
            width: auto; 
            filter: brightness(0) invert(1);
        }
        .header-text { 
            text-align: right; 
            color: #fff;
        }
        .header h1 { 
            color: #0A1A2F; 
            margin: 0 0 4px; 
            font-size: 24px; 
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .header .subtitle { 
            margin: 0; 
            color: #0A1A2F; 
            font-size: 13px; 
            font-weight: 400;
        }
        .header .meta-info {
            margin-top: 8px;
            font-size: 11px;
            color: #0A1A2F;
        }

        /* ─── Content Container ──────────────────────── */
        .content {
            padding: 30px;
            border: 2px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
        }

        /* ─── Section Titles ─────────────────────────── */
        .section-title { 
            font-size: 15px; 
            font-weight: 700; 
            color: #0A2463; 
            margin: 30px 0 15px; 
            padding-bottom: 10px; 
            border-bottom: 2px solid #0A2463;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.3px;
        }
        .section-title:first-child {
            margin-top: 0;
        }
        .section-title .icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            background: #0A2463;
            color: #fff;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
        }

        /* ─── Tables ──────────────────────────────────── */
        .info-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 5px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }
        .info-table th, 
        .info-table td { 
            padding: 12px 15px; 
            text-align: left; 
            border-bottom: 1px solid #f3f4f6; 
            font-size: 13px;
        }
        .info-table tr:last-child th,
        .info-table tr:last-child td {
            border-bottom: none;
        }
        .info-table th { 
            width: 35%; 
            font-weight: 600; 
            color: #0A2463; 
            background: #f8fafc;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-table td {
            color: #374151;
        }

        /* ─── Programmes List ────────────────────────── */
        .programme-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .programme-list li {
            padding: 10px 15px;
            margin-bottom: 6px;
            background: #f8fafc;
            border-left: 3px solid #0A2463;
            border-radius: 0 6px 6px 0;
            font-size: 13px;
            color: #374151;
            font-weight: 500;
        }
        .programme-list li::before {
            content: "◆";
            color: #0A2463;
            margin-right: 10px;
            font-size: 8px;
            vertical-align: middle;
        }

        /* ─── Personal Statement ─────────────────────── */
        .statement { 
            background: #f8fafc; 
            padding: 20px; 
            border-radius: 8px; 
            white-space: pre-wrap; 
            line-height: 1.8; 
            border: 1px solid #e5e7eb;
            border-left: 4px solid #0A2463;
            font-size: 13px;
            color: #374151;
        }

        /* ─── Status Badges ──────────────────────────── */
        .badge { 
            display: inline-block; 
            padding: 6px 14px; 
            border-radius: 20px; 
            font-size: 12px; 
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-pending { 
            background: #fef3c7; 
            color: #92400e; 
            border: 1px solid #fcd34d;
        }
        .badge-under_review { 
            background: #dbeafe; 
            color: #1e40af; 
            border: 1px solid #93c5fd;
        }
        .badge-accepted { 
            background: #dcfce7; 
            color: #166534; 
            border: 1px solid #86efac;
        }
        .badge-rejected { 
            background: #fee2e2; 
            color: #991b1b; 
            border: 1px solid #fca5a5;
        }
        .badge-shortlisted { 
            background: #e0e7ff; 
            color: #3730a3; 
            border: 1px solid #a5b4fc;
        }

        /* ─── Info Box ───────────────────────────────── */
        .info-box {
            background: #f0f4ff;
            border: 1px solid #c7d2fe;
            border-radius: 8px;
            padding: 15px 20px;
            margin: 10px 0;
        }
        .info-box strong {
            color: #0A2463;
        }

        /* ─── Status Section ─────────────────────────── */
        .status-section {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        .status-detail {
            background: #f8fafc;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
            border: 1px solid #e5e7eb;
        }
        .status-detail p {
            margin: 5px 0;
            font-size: 13px;
        }
        .status-detail strong {
            color: #0A2463;
        }

        /* ─── Footer ──────────────────────────────────── */
        .footer { 
            margin-top: 30px; 
            padding-top: 20px; 
            border-top: 2px solid #e5e7eb; 
            font-size: 11px; 
            color: #9ca3af; 
            text-align: center; 
            line-height: 1.8;
        }
        .footer strong {
            color: #6b7280;
        }
        .footer .separator {
            display: inline-block;
            margin: 0 8px;
            color: #d1d5db;
        }

        /* ─── Watermark ──────────────────────────────── */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-25deg);
            font-size: 120px;
            color: rgba(10, 36, 99, 0.03);
            font-weight: 900;
            pointer-events: none;
            z-index: -1;
            white-space: nowrap;
            letter-spacing: 10px;
        }

        /* ─── Page Break ─────────────────────────────── */
        .page-break {
            page-break-before: always;
        }

        /* ─── Page 2 Header ──────────────────────────── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 20px;
        }
        .page-header-logo {
            height: 35px;
            width: auto;
        }
        .page-header-info {
            text-align: right;
            font-size: 10px;
            color: #6b7280;
        }
        .page-header-info strong {
            color: #0A2463;
        }

        @media print {
            body {
                padding: 0;
            }
            .header {
                border-radius: 0;
            }
            .content {
                border: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    {{-- Watermark --}}
    <div class="watermark">IGRCFP</div>

    {{-- ============ PAGE 1 ============ --}}
    
    {{-- Header --}}
    <div class="header">
        <img src="{{ public_path('assets/images/home-three/logo/logo-main.png') }}" alt="IGRCFP Logo" class="header-logo">
        <div class="header-text">
            <h1>IGRCFP Scholarship Application</h1>
            <p class="subtitle">Emerging Professionals Scholarship Programme 2026</p>
            <p class="meta-info">
                Application #{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }} 
                | Submitted: {{ $application->created_at->format('F j, Y \a\t g:i A') }}
            </p>
        </div>
    </div>

    {{-- Content Page 1 --}}
    <div class="content">

        {{-- Personal Information --}}
        <div class="section-title">
            <span class="icon">1</span>
            Personal Information
        </div>
        <table class="info-table">
            <tr>
                <th>Full Name</th>
                <td>{{ $application->full_name }}</td>
            </tr>
            <tr>
                <th>Email Address</th>
                <td style="color: #0A2463;">{{ $application->email }}</td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td>{{ $application->phone_number }}</td>
            </tr>
            <tr>
                <th>Nationality</th>
                <td>{{ $application->nationality }}</td>
            </tr>
            <tr>
                <th>Country of Residence</th>
                <td>{{ $application->country_of_residence }}</td>
            </tr>
        </table>

        {{-- Academic & Professional Background --}}
        <div class="section-title">
            <span class="icon">2</span>
            Academic &amp; Professional Background
        </div>
        <table class="info-table">
            <tr>
                <th>Highest Qualification</th>
                <td><strong>{{ $application->highest_qualification }}</strong></td>
            </tr>
            <tr>
                <th>Institution</th>
                <td>{{ $application->institution }}</td>
            </tr>
            <tr>
                <th>Year Completed</th>
                <td>{{ $application->year_completed }}</td>
            </tr>
            @if($application->current_role)
            <tr>
                <th>Current Role</th>
                <td>{{ $application->current_role }}</td>
            </tr>
            @endif
            @if($application->organisation)
            <tr>
                <th>Organisation</th>
                <td>{{ $application->organisation }}</td>
            </tr>
            @endif
            @if($application->academic_background)
            <tr>
                <th>Additional Background</th>
                <td>{{ $application->academic_background }}</td>
            </tr>
            @endif
        </table>

        {{-- Footer Page 1 --}}
        <div class="footer">
            <strong>Institute of Governance, Risk, Compliance &amp; Financial Crime Prevention (IGRCFP)</strong>
            <br>
            Page 1 of 2
            <span class="separator">|</span>
            www.igrcfp.org
            <span class="separator">|</span>
            Application Reference: IGRCFP-SCH-{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}
        </div>
    </div>

    {{-- ============ PAGE BREAK ============ --}}
    <div class="page-break"></div>

    {{-- ============ PAGE 2 ============ --}}
    
    {{-- Page 2 Header --}}
    <div class="page-header">
        <img src="{{ public_path('assets/images/home-three/logo/logo-main.png') }}" alt="IGRCFP Logo" class="page-header-logo">
        <div class="page-header-info" style="padding-right:20px">
            <strong>IGRCFP Scholarship Application</strong><br>
            #{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }} | {{ $application->full_name }}<br>
            {{ now()->format('F j, Y') }}
        </div>
    </div>

    {{-- Content Page 2 --}}
    <div class="content" style="border: 2px solid #e5e7eb; border-radius: 8px;">
        
        {{-- Selected Programmes --}}
        <div class="section-title" style="margin-top: 0;">
            <span class="icon">3</span>
            Selected Programme(s)
        </div>
        <ul class="programme-list">
            @foreach($application->preferred_programmes as $prog)
                <li>{{ $prog }}</li>
            @endforeach
        </ul>

        {{-- Personal Statement --}}
        <div class="section-title">
            <span class="icon">4</span>
            Personal Statement
        </div>
        <div class="statement">{{ $application->personal_statement }}</div>

        {{-- Application Status --}}
        <div class="section-title">
            <span class="icon">5</span>
            Application Status
        </div>
        <div class="status-section">
            <span class="badge badge-{{ $application->status }}">
                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
            </span>
            <span style="font-size: 12px; color: #6b7280;">
                Last updated: {{ $application->updated_at->format('F j, Y \a\t g:i A') }}
            </span>
        </div>

        @if($application->admin_notes || $application->rejection_reason)
        <div class="status-detail">
            @if($application->rejection_reason)
            <p>
                <strong>⚠ Rejection Reason:</strong><br>
                {{ $application->rejection_reason }}
            </p>
            @endif
            @if($application->admin_notes)
            <p style="margin-top: {{ $application->rejection_reason ? '10px' : '0' }};">
                <strong>📝 Admin Notes:</strong><br>
                {{ $application->admin_notes }}
            </p>
            @endif
        </div>
        @endif

        @if($application->status === 'accepted')
        <div class="info-box" style="margin-top: 15px;">
            <strong>✅ Next Steps:</strong> The scholarship committee has approved your application. You will receive a formal acceptance letter via email with further instructions on enrollment and programme commencement.
        </div>
        @endif

        @if($application->status === 'pending')
        <div class="info-box" style="margin-top: 15px;">
            <strong>⏳ Under Review:</strong> Your application is currently being reviewed by our scholarship committee. You will be notified of the outcome via email within 4-6 weeks after the application deadline.
        </div>
        @endif

        @if($application->status === 'under_review')
        <div class="info-box" style="margin-top: 15px;">
            <strong>🔍 In Review:</strong> Your application has been shortlisted and is undergoing detailed review. You may be contacted for additional information or an interview.
        </div>
        @endif

        {{-- Footer Page 2 --}}
        <div class="footer">
            <strong>Institute of Governance, Risk, Compliance &amp; Financial Crime Prevention (IGRCFP)</strong>
            <br>
            Page 2 of 2
            <span class="separator">|</span>
            www.igrcfp.org
            <span class="separator">|</span>
            scholarships@igrcfp.org
            <span class="separator">|</span>
            Emerging Professionals Scholarship Programme 2026
            <br>
            <span style="font-size: 10px;">
                This document was generated on {{ now()->format('F j, Y \a\t g:i A') }} 
                | Application Reference: IGRCFP-SCH-{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}
            </span>
        </div>

    </div>
</body>
</html>