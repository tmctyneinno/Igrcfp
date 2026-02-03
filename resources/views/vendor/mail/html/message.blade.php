<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject ?? 'Email from IGRCFP' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            text-align: center;
        }
        .logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 20px;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-footer {
            background: #f7f9fc;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #eaeaea;
            color: #666;
            font-size: 14px;
        }
        .btn-reset {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            margin: 20px 0;
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
        }
        .expiry-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px 15px;
            border-radius: 4px;
            margin: 15px 0;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header with Logo -->
        <div class="email-header">
            <img src="{{ asset('assets/images/home-three/logo/logo-main.png') }}" 
                 alt="IGRCFP Logo" 
                 class="logo">
            <h1 style="color: white; margin: 0; font-size: 24px;">IGRCFP</h1>
        </div>

        <!-- Email Body -->
        <div class="email-body">
            <h2>Hello!</h2>
            
            <p>You are receiving this email because we received a password reset request for your account.</p>
            
            <!-- Reset Button -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $actionUrl }}" class="btn-reset">
                    Reset Password
                </a>
            </div>

            <!-- Expiry Notice -->
            <div class="expiry-notice">
                ⏰ This password reset link will expire in 60 minutes.
            </div>

            <!-- Help Text -->
            <div class="help-text">
                <strong>If you're having trouble clicking the button</strong>, copy and paste the URL below into your web browser:<br>
                <code style="word-break: break-all; background: #f8f9fa; padding: 8px; border-radius: 4px; display: block; margin-top: 8px;">
                    {{ $actionUrl }}
                </code>
            </div>

            <p>If you did not request a password reset, no further action is required.</p>

            <p>Regards,<br>
            <strong>IGRCFP Team</strong></p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p style="margin: 0;">
                © {{ date('Y') }} IGRCFP. All rights reserved.<br>
                <small>This is an automated message, please do not reply to this email.</small>
            </p>
        </div>
    </div>
</body>
</html>