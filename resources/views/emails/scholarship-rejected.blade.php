<!DOCTYPE html>
<html>
<head>
    <title>IGRCFP Scholarship Application Update</title>
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
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            display: inline-block;
            font-weight: bold;
            margin: 20px 0;
        }
        .info-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
        .feedback-box {
            background-color: #fff3cd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
        .next-steps {
            background-color: #e8eaf6;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .next-steps ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin: 8px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #1a237e 0%, #0d1b4a 100%);
            color: #ffffff !important;
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
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('assets/images/home-three/logo/logo-white.png') }}" alt="IGRCFP Logo" class="logo">
        <h2>The Institute of GRC and Financial Crime Prevention</h2>
        <h3>Scholarship Programme - Application Update</h3>
    </div> 
    
    <div class="content">
        <p>Dear <strong>{{ $application->full_name }}</strong>,</p>

        <p>Thank you for your interest in the <strong>The Institute of GRC and Financial Crime Prevention (IGRCFP)</strong> Scholarship Programme.</p>

        <div class="badge">📋 APPLICATION STATUS UPDATE 📋</div>

        <p>After careful consideration of your application by our Scholarship Assessment Committee, we regret to inform you that your application for the IGRCFP Scholarship Programme has not been successful on this occasion.</p>

        <div class="info-box">
            <h4 style="color: #1a237e; margin-top: 0;">📊 Application Summary:</h4>
            <p><strong>Application ID:</strong> #{{ $application->id }}</p>
            <p><strong>Full Name:</strong> {{ $application->full_name }}</p>
            <p><strong>Email:</strong> {{ $application->email }}</p>
            <p><strong>Application Date:</strong> {{ $application->created_at->format('F j, Y') }}</p>
            <p><strong>Status:</strong> <span style="color: #dc3545;">Not Selected</span></p>
        </div>

        @if($reason)
        <div class="feedback-box">
            <h4 style="color: #856404; margin-top: 0;">📝 Feedback from the Committee:</h4>
            <p>{{ $reason }}</p>
        </div>
        @endif

        <div class="next-steps">
            <h4 style="color: #1a237e; margin-top: 0;">🌟 Next Steps & Opportunities:</h4>
            <p>While your application was not successful this time, we encourage you to:</p>
            <ul>
                <li>Consider reapplying for future scholarship cycles</li>
                <li>Explore our professional certification programmes (discounted rates available)</li>
                <li>Join our newsletter for updates on upcoming scholarships and opportunities</li>
                <li>Attend our webinars and workshops to strengthen your profile</li>
            </ul>
        </div>

        <div class="info-box">
            <h4 style="color: #1a237e; margin-top: 0;">💡 Helpful Resources:</h4>
            <p><strong>Free Learning Resources:</strong> Access our library of free resources to enhance your knowledge in GRC and Financial Crime Prevention.</p>
            <p><strong>Mentorship Programme:</strong> Join our mentorship programme to get guidance from industry experts.</p>
            <p><strong>Community Membership:</strong> Become a member of the IGRCFP community to stay connected and access exclusive benefits.</p>
        </div>

        <p>We appreciate the time and effort you invested in your application. The selection process was highly competitive, and the committee had to make difficult decisions based on the limited number of scholarships available.</p>

        <p>We wish you the very best in your professional journey and hope to see you engage with IGRCFP in other capacities.</p>

        <div style="text-align: center;">
            <a href="{{ url('/') }}" class="button" style="color: #ffffff !important; text-decoration: none;">
                Explore Other Opportunities
            </a>
        </div>

        <p>If you have any questions or would like feedback on your application, please don't hesitate to contact us.</p>

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