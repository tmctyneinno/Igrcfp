<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: #c0392b; padding: 30px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 22px; }
        .body { padding: 30px; color: #333; }
        .alert-box { background: #fdf0ef; border-left: 4px solid #c0392b; padding: 15px 20px; border-radius: 4px; margin-bottom: 20px; }
        .footer { background: #f9f9f9; padding: 20px 30px; font-size: 12px; color: #999; text-align: center; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Account Locked</h1>
        </div>
        <div class="body">
            <p>Hi <strong>{{ $user->name }}</strong>,</p>
            <div class="alert-box">
                Your account has been <strong>temporarily locked</strong> due to 5 consecutive failed login attempts.
            </div>
            <p>Your account will be automatically unlocked in <strong>30 minutes</strong>.</p>
            <p>If this wasn't you, we strongly recommend resetting your password immediately to secure your account.</p>
            <p>
                <a href="{{ route('password.request') }}" 
                   style="display:inline-block;background:#c0392b;color:#fff;padding:12px 24px;border-radius:4px;text-decoration:none;font-weight:bold;">
                    Reset My Password
                </a>
            </p>
            <p style="color:#999;font-size:13px;">
                If you made these attempts yourself and simply forgot your password, 
                please use the reset link above.
            </p>
        </div>
        <div class="footer">
            This is an automated security alert. Please do not reply to this email.
        </div>
    </div>
</body>
</html>