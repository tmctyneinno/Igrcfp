<!DOCTYPE html>
<html>
<head>
    <title>IGRCFP Scholarship Approval Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #1a237e 0%, #0d1b4a 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
            border-radius: 10px 10px 0 0;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            border: 1px solid #e0e0e0;
            border-top: none;
            border-radius: 0 0 10px 10px;
        }
        .badge {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            color: #1a237e;
            padding: 10px 20px;
            border-radius: 50px;
            display: inline-block;
            font-weight: bold;
            margin: 20px 0;
        }
        .award-details {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #ffd700;
        }
        .award-details p {
            margin: 8px 0;
        }
        .expectations {
            background-color: #e8eaf6;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .expectations ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .expectations li {
            margin: 8px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #1a237e 0%, #0d1b4a 100%);
            color: #ffffff !important;  /* Force white text */
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .button:hover {
            background: linear-gradient(135deg, #0d1b4a 0%, #1a237e 100%);
            text-decoration: none;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        .social-links {
            margin: 15px 0;
        }
        .social-links a {
            margin: 0 10px;
            color: #1a237e;
            text-decoration: none;
        }
        .highlight {
            color: #1a237e;
            font-weight: bold;
        }
        /* Ensure all links in button stay white */
        .button-link, .button-link:hover {
            color: #ffffff !important;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('assets/images/home-three/logo/logo-white.png') }}" alt="IGRCFP Logo" class="logo">
        <h2>The Institute of GRC and Financial Crime Prevention</h2>
        <h3>Scholarship Programme - Approval Notification</h3>
    </div> 
    
    <div class="content">
        <p>Dear <strong>{{ $application->full_name }}</strong>,</p>

        <p>Congratulations!</p>

        <p>On behalf of the <strong>The Institute of GRC and Financial Crime Prevention (IGRCFP)</strong>, we are delighted to inform you that your scholarship application has been <span class="highlight">successful</span>.</p>

        <div class="badge">🎓 SCHOLARSHIP AWARDED 🎓</div>

        <p>Following a thorough assessment by the Scholarship Assessment Committee, your application demonstrated strong merit, commitment to professional excellence, and alignment with the objectives of the IGRCFP Scholarship Programme.</p>

        <div class="award-details">
            <h4 style="color: #1a237e; margin-top: 0;">Your Scholarship Award Includes:</h4>
            <p><strong>• Scholarship Type:</strong> {{ $application->scholarship_type ?? 'IGRCFP Merit Scholarship' }}</p>
            <p><strong>• Award Value:</strong> {{ $application->award_value ?? 'Full Coverage' }}</p>
            <p><strong>• Programme/Event:</strong> {{ $application->programme_name ?? $application->post->title ?? 'Professional Certification Programme' }}</p>
            <p><strong>• Scholarship Duration:</strong> {{ $application->duration ?? '12 Months' }}</p>
            <p><strong>• Effective Date:</strong> {{ now()->format('F j, Y') }}</p>
        </div>

        <div class="expectations">
            <h4 style="color: #1a237e; margin-top: 0;">📋 As a scholarship recipient, you are expected to:</h4>
            <ul>
                <li>Maintain high standards of professional conduct and integrity.</li>
                <li>Actively participate in the approved programme, course, certification, membership, or event.</li>
                <li>Comply with all IGRCFP policies, regulations, and scholarship conditions.</li>
                <li>Serve as a positive ambassador for IGRCFP within your professional community.</li>
            </ul>
        </div>

        <p><strong>To formally accept this scholarship award, please reply to this email within 3 days confirming your acceptance.</strong></p>

        <p>Further information regarding onboarding, registration, access details, and scholarship conditions will be provided upon acceptance.</p>

        <p>We congratulate you on this achievement and look forward to supporting your professional and leadership development journey.</p>

        <p>Welcome to the growing community of IGRCFP Scholars.</p>

        <div style="text-align: center;">
            <a href="{{ route('scholarship.accept', $application->id) }}" class="button" style="color: #ffffff !important; text-decoration: none;">
                Accept Scholarship
            </a>
        </div>

        <p>Kind regards,</p>
        <p>
            <strong>Scholarship Administration Team</strong><br>
            The Institute of GRC and Financial Crime Prevention (IGRCFP)<br>
            Email: scholarships@igrcfp.org<br>
            Website: <a href="https://www.igrcfp.org">www.igrcfp.org</a>
        </p>
    </div>
    
    <div class="footer">
        <div class="social-links">
            <a href="#">LinkedIn</a> | 
            <a href="https://x.com/igrcfpofficial">Twitter</a> | 
            <a href="https://www.instagram.com/igrcfpofficial?igsh=MTlhdXJ0N2doYmJ3Mg==">Instagram</a> |
            <a href="#">Facebook</a> |
            <a href="#">YouTube</a>
        </div>
        <p>&copy; {{ date('Y') }} The Institute of GRC and Financial Crime Prevention. All rights reserved.</p>
        <p>This email was sent to {{ $application->email }}. If you did not apply for an IGRCFP scholarship, please ignore this email.</p>
    </div>
</body>
</html>