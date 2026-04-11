<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Ready</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #0A1F44 0%, #1a3a6e 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #0A1F44;
        }
        .message {
            margin-bottom: 30px;
            color: #555;
        }
        .certificate-details {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            border-left: 4px solid #D4AF37;
        }
        .detail-row {
            display: flex;
            margin-bottom: 12px;
        }
        .detail-label {
            font-weight: 600;
            width: 140px;
            color: #555;
        }
        .detail-value {
            flex: 1;
            color: #0A1F44;
            font-weight: 500;
        }
        .button-container {
            text-align: center;
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #0A1F44 0%, #1a3a6e 100%);
            color: white;
            text-decoration: none;
            padding: 14px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 8px rgba(10, 31, 68, 0.3);
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(10, 31, 68, 0.4);
        }
        .secondary-link {
            text-align: center;
            margin-top: 15px;
        }
        .secondary-link a {
            color: #666;
            font-size: 14px;
            text-decoration: none;
        }
        .secondary-link a:hover {
            color: #0A1F44;
            text-decoration: underline;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 13px;
            color: #888;
            border-top: 1px solid #e0e0e0;
        }
        .social-links {
            margin-top: 15px;
        }
        .social-links a {
            color: #888;
            margin: 0 8px;
            text-decoration: none;
        }
        .highlight {
            color: #D4AF37;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-icon">🎓</div>
            <h1>Certificate Generated!</h1>
            <p>Your achievement is now official</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Congratulations, {{ $userName }}! 🎉
            </div>
            
            <div class="message">
                <p>You've successfully completed all requirements for:</p>
                <h2 style="color: #0A1F44; margin: 15px 0; font-size: 22px;">{{ $courseTitle }}</h2>
                <p>Your certificate has been generated and is ready for download. This certificate validates your expertise and dedication to professional development.</p>
            </div>
            
            <div class="certificate-details">
                <div class="detail-row">
                    <span class="detail-label">Certificate Number:</span>
                    <span class="detail-value">{{ $certificateNumber }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Completion Date:</span>
                    <span class="detail-value">{{ $completionDate }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value" style="color: #16a34a;">✓ Verified</span>
                </div>
            </div>
            
            <div class="button-container">
                <a href="{{ $downloadUrl }}" class="button">
                    📥 Download Your Certificate
                </a>
            </div>
            
            <div class="secondary-link">
                <a href="{{ $verifyUrl }}" target="_blank">
                    🔗 Verify Certificate Online
                </a>
            </div>
            
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
                <p style="font-size: 14px; color: #666;">
                    <strong>What's next?</strong><br>
                    • Share your achievement on LinkedIn<br>
                    • Add this certificate to your resume<br>
                    • Explore advanced courses to continue learning
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name', 'IGRCFP') }}. All rights reserved.</p>
            <p>Institute of GRC & Financial Crime Prevention</p>
            <div class="social-links">
                <a href="#">LinkedIn</a> •
                <a href="#">Twitter</a> •
                <a href="#">Facebook</a>
            </div>
            <p style="margin-top: 15px; font-size: 12px;">
                This email was sent to confirm your certificate generation.<br>
                If you have any questions, please contact our support team.
            </p>
        </div>
    </div>
</body>
</html>