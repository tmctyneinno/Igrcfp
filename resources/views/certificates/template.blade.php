<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Certificate - {{ $student->name ?? 'Candidate' }}</title> 
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    @page {
        size: A4 portrait;
        margin: 0;
    }

    html, body {
        width: 210mm;
        height: 297mm;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #1a2b4c;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .page {
        width: 100%;
        height: 100%;
        padding: 10mm;
        position: relative;
    }

    .border-outer {
        border: 2px solid #1a2b4c;
        padding: 2mm;
        height: 100%;
    }

    .border-inner {
        border: 1px solid #1a2b4c;
        height: 100%;
        padding: 12mm 25mm 18mm 25mm;
        text-align: center;
        position: relative;
    }

    .seal {
        width: 30mm;
        height: 30mm;
        margin: 0 auto 3mm auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .seal img {
        width: 100%;
        height: 100%;
        object-fit: contain; /* Prevents image distortion */
    }

    .tagline {
        font-style: italic;
        font-size: 14px;
        color: #4a5b7c;
        margin-bottom: 4mm;
    }

    .kicker {
        font-size: 13px;
        font-weight: bold;
        letter-spacing: 2px;
        color: #9a2b2b;
        margin-bottom: 2mm;
    }

    .title {
        font-size: 38px;
        font-weight: bold;
        letter-spacing: 4px;
        color: #1a2b4c;
        margin-bottom: 2mm;
    }

    .subtitle {
        font-size: 20px;
        color: #34456c;
        margin-bottom: 4mm;
    }

    .divider {
        width: 35mm;
        border-bottom: 2px solid #9a2b2b;
        margin: 0 auto 6mm auto;
    }

    .intro {
        font-size: 15px;
        color: #4a5b7c;
        margin-bottom: 2mm;
    }

    .student-name {
        font-size: 30px;
        font-weight: bold;
        color: #1a2b4c;
        margin-bottom: 2mm;
        font-family: 'Georgia', serif;
    }

    .reg-id {
        font-size: 10px;
        color: #6a7ba0;
        margin-bottom: 5mm;
    }

    .body-text {
        font-size: 14px;
        color: #4a5b7c;
        line-height: 1.5;
        width: 75%;
        margin: 0 auto 5mm auto;
    }

    .date-line {
        font-size: 12px;
        color: #34456c;
        margin-bottom: 8mm;
    }

    .signature-block {
        margin: 0 auto 2mm auto;
    }

    .signature-line {
        margin: 0 auto 2mm auto;
        border-bottom: 1px solid #1a2b4c;
        height: 8mm;
        width: 60mm;

    }

    .signature-name {
        font-size: 15px;
        font-weight: bold;
        color: #1a2b4c;
        margin-top: 2mm;
    }

    .signature-credentials {
        font-size: 11px;
        color: #6a7ba0;
        letter-spacing: 0.5px;
        margin-top: 1mm;
    }

    .signature-title {
        font-size: 12x;
        color: #34456c;
        font-weight: bold;
        margin-top: 1mm;
    }

    .footer {
        position: absolute;
        bottom: 12mm;
        left: 25mm;
        right: 25mm;
        border-top: 1px solid #d3d8e4;
        padding-top: 2mm;
        font-size: 9px;
        color: #6a7ba0;
        width: calc(100% - 50mm);
    }

    .footer-left {
        display: inline-block;
        width: 49%;
        text-align: left;
    }

    .footer-right {
        display: inline-block;
        width: 49%;
        text-align: right;
    }

    .footer strong {
        color: #1a2b4c;
    }
</style>
</head>
<body>
    @php
        // 1. Handle Logo
        $logoPath = public_path('assets/images/logo-main.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        } else {
            // Safe SVG fallback if logo is missing (prevents Chrome crash)
            $logoBase64 = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"><circle cx="50" cy="50" r="40" fill="#eef1f7" stroke="#1a2b4c" stroke-width="3"/><text x="50" y="55" font-size="12" text-anchor="middle" fill="#1a2b4c" font-weight="bold">LOGO</text></svg>');
        }

        // 2. Handle Stamp (with dynamic MIME type detection)
        $stampPath = public_path('assets/images/igrcfp_stamp.png'); // Check if this is .png or .jpeg on your server
        $stampBase64 = '';
        if (file_exists($stampPath)) {
            $stampData = file_get_contents($stampPath);
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $stampPath);
            finfo_close($finfo);
            $stampBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($stampData);
        } else {
            // Safe SVG fallback if stamp is missing
            $stampBase64 = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"><circle cx="50" cy="50" r="40" fill="none" stroke="#9a2b2b" stroke-width="2" stroke-dasharray="4"/><text x="50" y="55" font-size="10" text-anchor="middle" fill="#9a2b2b">STAMP</text></svg>');
        }
        // 3. Handle Signature (with dynamic MIME type detection)
        $signaturePath = public_path('assets/images/igrcfp_signature.PNG'); // Check if this is .png or .jpeg on your server
        $signatureBase64 = '';
        if (file_exists($signaturePath)) {
            $signatureData = file_get_contents($signaturePath);
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $signaturePath);
            finfo_close($finfo);
            $signatureBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($signatureData);
        } else {
            // Safe SVG fallback if signature is missing
            $signatureBase64 = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"><circle cx="50" cy="50" r="40" fill="none" stroke="#9a2b2b" stroke-width="2" stroke-dasharray="4"/><text x="50" y="55" font-size="10" text-anchor="middle" fill="#9a2b2b">SIGNATURE</text></svg>');
        }
         // 4. Handle Signature (with dynamic MIME type detection)
        $progressPath = public_path('assets/images/igrcfp_progress.png'); // Check if this is .png or .jpeg on your server
        $progressBase64 = '';
        if (file_exists($progressPath)) {
            $progressData = file_get_contents($progressPath);
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $progressPath);
            finfo_close($finfo);
            $progressBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($progressData);
        } else {
            // Safe SVG fallback if signature is missing
            $progressBase64 = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"><circle cx="50" cy="50" r="40" fill="none" stroke="#9a2b2b" stroke-width="2" stroke-dasharray="4"/><text x="50" y="55" font-size="10" text-anchor="middle" fill="#9a2b2b">SIGNATURE</text></svg>');
        }
    @endphp

    <div class="page">
        <div class="border-outer">
            <div class="border-inner">

                <!-- Top Logo -->
                <div class="seal">
                    <img src="{{ $logoBase64 }}" alt="Logo">
                </div>

                <div class="tagline">Empowering Professionals, Shaping Global Standards, Safeguarding Systems.</div>

                <div class="kicker">PROFESSIONAL QUALIFICATION</div>
                <div class="title">CERTIFICATE</div>
                <div class="subtitle">in {{ $course->title ?? 'Governance, Risk & Compliance' }}</div>

                <div class="divider"></div>

                <div class="intro">This is to certify that</div>
                <div class="student-name">{{ $student->name ?? 'Candidate Name' }}</div>
                <div class="reg-id">Registration ID: {{ $registration_id ?? '2026-01' }}</div>

                <div class="body-text">
                    has satisfied all the requirements prescribed by the Institute and is hereby awarded the above qualification.
                </div>

                <!-- Official Stamp -->
                @if($progressPath)
                <div class="seal" style="margin-top: -10mm; margin-bottom: -5mm;">
                    <img src="{{ $progressBase64 }}" alt="IGRCFP Stamp" style="width: 80mm; height: auto;">
                </div>
                @endif

                <div class="date-line">Awarded on the {{ $completion_date ?? '30th of April, 2027' }}</div>
                <!--  Signature -->
                @if($signatureBase64)
                <div>
                    <img src="{{ $signatureBase64 }}" alt="IGRCFP Signature" style="width: 60mm; height: auto;">
                </div>
                @endif
                <div class="signature-block" style="margin-top: -8mm;">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $instructor_name ?? 'Dr. Foluso Amusa' }}</div>
                    <div class="signature-credentials">{{ $instructor_credentials ?? 'PhD · FIGRCFP · FAGRC · FICA · FIIM · FAPM' }}</div>
                    <div class="signature-title">{{ $instructor_title ?? 'Founder & President, IGRCFP' }}</div>
                </div>

                <!-- Official Stamp -->
                @if($stampBase64)
                <div class="seal" style="margin-top: 5mm;">
                    <img src="{{ $stampBase64 }}" alt="IGRCFP Stamp">
                </div>
                @endif

                <div class="footer">
                    <div class="footer-left">
                        Certificate ID: <strong>{{ $certificate_number ?? '2026GRC-CERT01' }}</strong>
                    </div>
                    <div class="footer-right">
                        Verify @ <strong>https://igrcfp.org</strong>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>