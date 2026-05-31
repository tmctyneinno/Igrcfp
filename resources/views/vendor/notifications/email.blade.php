<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'IGRCFP') }}</title>
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
            max-width: 180px;
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
        .btn-primary {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .help-text {
            background: #f0f7ff;
            border-left: 4px solid #667eea;
            padding: 12px;
            margin: 20px 0;
            font-size: 14px;
            color: #555;
            word-break: break-all;
        }
        .expiry-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px 15px;
            border-radius: 4px;
            margin: 15px 0;
            color: #856404;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header with Logo -->
        <div class="email-header">
            @if(isset($logo))
                <img src="{{ $logo }}" alt="IGRCFP Logo" class="email-logo">
            @endif
            <h1 style="color: white; margin: 0; font-size: 24px;">IGRCFP</h1>
        </div>

        <!-- Email Content -->
        <div class="email-body">
            {{ $slot ?? '' }}
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