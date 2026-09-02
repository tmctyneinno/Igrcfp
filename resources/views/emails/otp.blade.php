<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .logo {
            display: block;
            max-width: 80px;
            height: auto;
            margin-bottom: 18px;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 8px;
            margin: 20px 0;
            letter-spacing: 5px;
            color: #667eea;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img style="width:50px" src="{{ asset('assets/images/home-three/logo/logo-main.png') }}" alt="IGRCFP Logo" class="logo">
            <h2>The Institute of GRC and Financial Crime Prevention (IGRCFP)</h2>
       
            <h2>Email Verification</h2>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>Thank you for signing up! Please use the verification code below to complete your login:</p>
            
            <div class="otp-code">
                {{ $otp }}
            </div>
            
            <p>This code will expire in <strong>10 minutes</strong>.</p>
            <p>If you didn't request this code, please ignore this email.</p>
            
            <hr>
            <p><strong>Security Tip:</strong> Never share this code with anyone.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Your Application. All rights reserved.</p>
        </div>
    </div>
</body>
</html>