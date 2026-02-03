<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IGRCFP - Password Reset</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f5f7fa;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            text-align: center;
        }
        .email-logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 15px;
        }
        .email-body {
            padding: 40px;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px 40px;
            text-align: center;
            border-top: 1px solid #eaeaea;
            color: #666;
            font-size: 14px;
        }
        .btn-reset {
            display: inline-block;
            background: #0340aa;
            color: white !important; /* Add !important here */
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            margin: 25px 0;
            text-align: center;
        }
        .btn-reset:hover {
            opacity: 0.9;
        }
        .help-text {
            background: #f0f7ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
            color: #555;
            border-radius: 0 4px 4px 0;
        }
        .expiry-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            color: #856404;
            font-size: 15px;
        }
        .url-box {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            word-break: break-all;
            margin: 10px 0;
            border: 1px solid #eaeaea;
            color: #666;
        }
        h1, h2, h3 {
            color: #2d3748;
            margin-top: 0;
        }
        p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header with Logo -->
        <div class="email-header">
            <img src="{{ $logoUrl }}" alt="IGRCFP Logo" class="email-logo">
            <h2 style="color: white; margin: 10px 0 0 0; font-size: 24px;">IGRCFP</h2>
        </div>

        <!-- Email Body -->
        <div class="email-body">
            <h2>Hello!</h2>
            
            <p>You are receiving this email because we received a password reset request for your account.</p>
            
            <!-- Reset Button -->
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="btn-reset" >
                    Reset Password 
                </a>
            </div>

            <!-- Expiry Notice -->
            <div class="expiry-note">
                ⏰ <strong>Important:</strong> This password reset link will expire in 60 minutes.
            </div>

            <!-- Help Text -->
            <div class="help-text">
                <strong>If you're having trouble clicking the button above</strong>, copy and paste the URL below into your web browser:
                <div class="url-box">
                    {{ $resetUrl }}
                </div>
            </div>

            <p>If you did not request a password reset, please ignore this email. Your account remains secure.</p>

            <p><strong>Regards,</strong><br>
            IGRCFP Team</p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p style="margin: 0 0 10px 0;">
                © {{ date('Y') }} IGRCFP. All rights reserved.
            </p>
            <p style="margin: 0; font-size: 12px; color: #999;">
                This is an automated message, please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>