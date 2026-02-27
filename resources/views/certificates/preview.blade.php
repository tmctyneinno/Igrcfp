<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate of Completion</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Times New Roman', serif;
            background: #f0f2f5;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .certificate {
            width: 900px;
            margin: 0 auto;
            background: white;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        /* Decorative border */
        .certificate-border {
            border: 2px solid #c9a959;
            padding: 30px;
            position: relative;
            background: linear-gradient(to bottom, #ffffff, #faf9f6);
        }
        
        /* Inner decorative elements */
        .certificate::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 1px solid #c9a959;
            pointer-events: none;
        }
        
        /* Organization logo section */
        .logo-section {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 2px solid #c9a959;
        }
        
        .logo {
            max-width: 120px;
            height: auto;
            margin-bottom: 10px;
        }
        
        .org-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 5px 0;
        }
        
        .org-tagline {
            font-size: 12px;
            color: #7f8c8d;
            letter-spacing: 1px;
        }
        
        /* Main title */
        .certificate-title {
            text-align: center;
            margin: 30px 0 20px;
        }
        
        .certificate-title h1 {
            color: #2c3e50;
            font-size: 42px;
            font-weight: 300;
            text-transform: uppercase;
            letter-spacing: 5px;
            margin: 0;
            font-family: 'DejaVu Sans', 'Times New Roman', serif;
        }
        
        .certificate-title .subtitle {
            font-size: 16px;
            color: #7f8c8d;
            letter-spacing: 3px;
            margin-top: 5px;
        }
        
        /* Award text */
        .award-text {
            text-align: center;
            font-size: 18px;
            color: #34495e;
            margin: 25px 0 15px;
            font-style: italic;
        }
        
        /* Student name */
        .student-name {
            text-align: center;
            font-size: 48px;
            color: #c9a959;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-family: 'DejaVu Sans', 'Times New Roman', serif;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        
        /* Course information */
        .course-info {
            text-align: center;
            font-size: 20px;
            color: #2c3e50;
            margin: 20px 0;
            line-height: 1.6;
        }
        
        .course-name {
            font-size: 32px;
            color: #2c3e50;
            font-weight: bold;
            margin: 10px 0;
            padding: 10px 20px;
            background: linear-gradient(to right, transparent, #f8f3e9, transparent);
        }
        
        .grade {
            font-size: 18px;
            color: #27ae60;
            margin: 15px 0;
            font-weight: bold;
        }
        
        /* Date and signature section */
        .details-section {
            display: flex;
            justify-content: space-between;
            margin: 40px 0 20px;
            padding: 0 20px;
        }
        
        .date-box {
            text-align: center;
            flex: 1;
        }
        
        .date-label {
            font-size: 14px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .date-value {
            font-size: 18px;
            color: #2c3e50;
            font-weight: bold;
            margin-top: 5px;
            border-bottom: 2px solid #c9a959;
            padding-bottom: 5px;
            display: inline-block;
        }
        
        .signature-box {
            text-align: center;
            flex: 1;
        }
        
        .signature-line {
            width: 200px;
            height: 1px;
            border-bottom: 2px solid #2c3e50;
            margin: 10px auto;
        }
        
        .signature-name {
            font-size: 16px;
            color: #2c3e50;
            font-weight: bold;
            margin-top: 5px;
        }
        
        .signature-title {
            font-size: 12px;
            color: #7f8c8d;
        }
        
        /* Certificate number and verification */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
            text-align: center;
        }
        
        .certificate-number {
            font-size: 12px;
            color: #95a5a6;
            letter-spacing: 1px;
        }
        
        .verification {
            font-size: 10px;
            color: #bdc3c7;
            margin-top: 5px;
        }
        
        /* Gold seal/stamp effect */
        .seal {
            position: absolute;
            bottom: 50px;
            right: 50px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: radial-gradient(circle, #f1c40f, #c9a959);
            opacity: 0.1;
            z-index: 1;
        }
        
        /* Decorative corners */
        .corner {
            position: absolute;
            width: 30px;
            height: 30px;
            border: 2px solid #c9a959;
        }
        
        .corner-tl {
            top: 20px;
            left: 20px;
            border-right: none;
            border-bottom: none;
        }
        
        .corner-tr {
            top: 20px;
            right: 20px;
            border-left: none;
            border-bottom: none;
        }
        
        .corner-bl {
            bottom: 20px;
            left: 20px;
            border-right: none;
            border-top: none;
        }
        
        .corner-br {
            bottom: 20px;
            right: 20px;
            border-left: none;
            border-top: none;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <!-- Decorative corners -->
        <div class="corner corner-tl"></div>
        <div class="corner corner-tr"></div>
        <div class="corner corner-bl"></div>
        <div class="corner corner-br"></div>
        
        <!-- Decorative seal -->
        <div class="seal"></div>
        
        <div class="certificate-border">
            <!-- Logo Section -->
            <div class="logo-section">
                @if(isset($organization_logo) && $organization_logo)
                    <img src="{{ $organization_logo }}" alt="{{ $organization_name }}" class="logo">
                @else
                    <!-- Placeholder logo - replace with your actual logo -->
                    <div style="width: 120px; height: 120px; margin: 0 auto; background: #2c3e50; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 40px; font-weight: bold;">
                        {{ substr($organization_name ?? 'AI', 0, 2) }}
                    </div>
                @endif
                <div class="org-name">{{ $organization_name ?? 'ACME INSTITUTE' }}</div>
                <div class="org-tagline">{{ $organization_tagline ?? 'Excellence in Education' }}</div>
            </div>
            
            <!-- Certificate Title -->
            <div class="certificate-title">
                <h1>CERTIFICATE OF COMPLETION</h1>
                <div class="subtitle">PROUDLY PRESENTED TO</div>
            </div>
            
            <!-- Student Name -->
            <div class="student-name">
                {{ $student->name }}
            </div>
            
            <!-- Course Information -->
            <div class="course-info">
                <p>for successfully completing the course</p>
                <div class="course-name">
                    {{ $course->title }}
                </div>
                
                @if(isset($enrollment->final_grade) && $enrollment->final_grade)
                <div class="grade">
                    with a final grade of {{ $enrollment->final_grade }}
                </div>
                @endif
                
                <p style="font-size: 16px; color: #7f8c8d; margin-top: 15px;">
                    having demonstrated proficiency in all required modules and assessments
                </p>
            </div>
            
            <!-- Date and Signature Section -->
            <div class="details-section">
                <div class="date-box">
                    <div class="date-label">Date of Completion</div>
                    <div class="date-value">{{ $completion_date }}</div>
                </div>
                
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $instructor_name ?? 'Dr. John Smith' }}</div>
                    <div class="signature-title">Course Instructor</div>
                </div>
            </div>
            
            <!-- Footer with Certificate Number -->
            <div class="footer">
                <div class="certificate-number">
                    Certificate ID: {{ $certificate_number }}
                </div>
                <div class="verification">
                    Verify this certificate at {{ $verification_url ?? 'https://yourdomain.com/verify' }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>