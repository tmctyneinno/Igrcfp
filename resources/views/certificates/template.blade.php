<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Completion - {{ $course->title }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;600&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        
        .certificate {
            width: 100%;
            max-width: 1000px;
            height: auto;
            min-height: 650px;
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
            border: 15px solid #0A1F44;
            border-radius: 4px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            margin: 0 auto;
        }
        
        .certificate-inner {
            border: 2px solid #D4AF37;
            margin: 15px;
            min-height: calc(100% - 30px);
            position: relative;
            padding: 30px 40px;
            display: flex;
            flex-direction: column;
        }
        
        .corner-decoration {
            position: absolute;
            width: 50px;
            height: 50px;
        }
        
        .corner-tl {
            top: 5px;
            left: 5px;
            border-top: 3px solid #D4AF37;
            border-left: 3px solid #D4AF37;
        }
        
        .corner-tr {
            top: 5px;
            right: 5px;
            border-top: 3px solid #D4AF37;
            border-right: 3px solid #D4AF37;
        }
        
        .corner-bl {
            bottom: 5px;
            left: 5px;
            border-bottom: 3px solid #D4AF37;
            border-left: 3px solid #D4AF37;
        }
        
        .corner-br {
            bottom: 5px;
            right: 5px;
            border-bottom: 3px solid #D4AF37;
            border-right: 3px solid #D4AF37;
        }
        
        .certificate-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .certificate-logo {
            height: 60px;
            width: auto;
        }
        
        .certificate-logo-placeholder {
            height: 60px;
            width: auto;
            padding: 10px 20px;
            background: linear-gradient(135deg, #0A1F44 0%, #1a3a6e 100%);
            color: white;
            font-weight: bold;
            font-size: 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .certificate-header-text {
            text-align: center;
        }
        
        .organization-name {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            color: #0A1F44;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        
        .organization-tagline {
            font-size: 11px;
            color: #666;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        
        .certificate-title {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 700;
            color: #D4AF37;
            text-transform: uppercase;
            letter-spacing: 6px;
            margin: 10px 0;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .presented-to {
            font-size: 13px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        
        .student-name {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: #0A1F44;
            margin: 8px 0;
            text-transform: capitalize;
            border-bottom: 2px solid #D4AF37;
            display: inline-block;
            padding-bottom: 6px;
        }
        
        .certificate-text {
            font-size: 13px;
            color: #444;
            line-height: 1.5;
            margin: 10px 0;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .course-name {
            font-size: 20px;
            font-weight: 600;
            color: #0A1F44;
            margin: 10px 0;
        }
        
        .certificate-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 20px;
            padding-top: 15px;
        }
        
        .signature-section {
            text-align: center;
        }
        
        .signature-line {
            width: 160px;
            height: 1px;
            background: #999;
            margin: 8px 0 4px;
        }
        
        .signature-name {
            font-weight: 600;
            font-size: 13px;
            color: #333;
        }
        
        .signature-title {
            font-size: 10px;
            color: #666;
        }
        
        .date-section {
            text-align: center;
        }
        
        .certificate-number {
            font-size: 10px;
            color: #999;
            margin-top: 15px;
            text-align: center;
        }
        
        .seal {
            position: absolute;
            bottom: 50px;
            right: 70px;
            width: 80px;
            height: 80px;
            border: 2px solid #D4AF37;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0.6;
            transform: rotate(-15deg);
        }
        
        .seal-text {
            font-size: 8px;
            color: #D4AF37;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .seal-icon {
            font-size: 20px;
            color: #D4AF37;
        }
        
        .verification-link {
            margin-top: 10px;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
        
        .verification-link a {
            color: #0A1F44;
            text-decoration: none;
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('assets/images/home-three/logo/logo-main.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }
    @endphp
    
    <div class="certificate">
        <div class="certificate-inner">
            <div class="corner-decoration corner-tl"></div>
            <div class="corner-decoration corner-tr"></div>
            <div class="corner-decoration corner-bl"></div>
            <div class="corner-decoration corner-br"></div>
            
            <div class="certificate-header">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="IGRCFP Logo" class="certificate-logo">
                @else
                    <div class="certificate-logo-placeholder">IGRCFP</div>
                @endif
                <div class="certificate-header-text">
                    <div class="organization-name">{{ config('app.name', 'IGRCFP') }}</div>
                    <div class="organization-tagline">Institute of GRC & Financial Crime Prevention</div>
                </div>
            </div>
            
            <div style="text-align: center; flex: 1;">
                <div class="certificate-title">Certificate</div>
                <div class="presented-to">of Completion</div>
                <div class="presented-to" style="margin-top: 15px;">This is to certify that</div>
                <div class="student-name">{{ $student->name }}</div>
                <div class="certificate-text">
                    has successfully completed the requirements for
                </div>
                <div class="course-name">{{ $course->title }}</div>
                <div class="certificate-text">
                    demonstrating proficiency and understanding of the course material<br>
                    with a completion date of
                </div>
                <div style="font-size: 15px; font-weight: 600; color: #0A1F44; margin: 8px 0;">
                    {{ $completion_date }}
                </div>
            </div>
            
            <div class="certificate-footer">
                <div class="signature-section">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $instructor_name ?? 'Course Instructor' }}</div>
                    <div class="signature-title">Lead Instructor</div>
                </div>
                
                <div class="date-section">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $completion_date }}</div>
                    <div class="signature-title">Date of Completion</div>
                </div>
                
                <div class="signature-section">
                    <div class="signature-line"></div>
                    <div class="signature-name">Institute Director</div>
                    <div class="signature-title">IGRCFP</div>
                </div>
            </div>
            
            <div class="certificate-number">
                Certificate Number: {{ $certificate_number }}<br>
                <div class="verification-link">
                    Verify this certificate at: 
                    <a href="{{ $verification_url ?? route('certificate.verify', $certificate_number) }}">
                        {{ $verification_url ?? route('certificate.verify', $certificate_number) }}
                    </a>
                </div>
            </div>
            
            <div class="seal">
                <div class="seal-icon">🏆</div>
                <div class="seal-text">IGRCFP</div>
                <div class="seal-text">CERTIFIED</div>
            </div>
        </div>
    </div>
</body>
</html>