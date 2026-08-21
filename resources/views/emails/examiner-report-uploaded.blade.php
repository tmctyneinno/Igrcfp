<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="color-scheme" content="light only" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Examiner Report Uploaded</title>
    <style>
        /* Base reset & spacing */
        * {
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0;
            padding: 32px 16px;
            background: #f4f6fa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #1f2937;
            line-height: 1.6;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(41, 53, 103, 0.10);
            overflow: hidden;
            border: 1px solid #eaeef5;
        }
        .email-header {
            padding: 32px 40px 20px 40px;
            background: #ffffff;
            border-bottom: 1px solid #edf2f7;
        }
        .header-content {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .logo {
            width: 54px;
            height: auto;
            flex-shrink: 0;
            border-radius: 6px;
        }
        .org-name {
            font-size: 18px;
            font-weight: 700;
            color: #293567;
            letter-spacing: -0.3px;
            line-height: 1.3;
        }
        .org-name span {
            font-weight: 300;
            color: #4a5a7a;
        }
        /* Primary color accent bar */
        .accent-bar {
            height: 4px;
            background: #293567;
            width: 100%;
        }
        .email-body {
            padding: 32px 40px 40px 40px;
        }
        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #293567;
            margin: 0 0 6px 0;
            letter-spacing: -0.4px;
        }
        .badge {
            display: inline-block;
            background: #eef0f7;
            color: #293567;
            font-size: 13px;
            font-weight: 600;
            padding: 4px 14px;
            border-radius: 30px;
            margin-bottom: 20px;
            letter-spacing: 0.3px;
            border: 1px solid #d5d9e8;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 6px;
        }
        .text-muted {
            color: #475569;
            font-size: 15px;
            margin-bottom: 16px;
        }
        .report-card {
            background: #f8f9fc;
            border-radius: 10px;
            padding: 20px 24px;
            margin: 20px 0 24px 0;
            border: 1px solid #e2e8f0;
            border-left-width: 5px;
            border-left-color: #293567;
        }
        .report-card p {
            margin: 4px 0;
            font-size: 15px;
        }
        .report-card strong {
            color: #293567;
            font-weight: 600;
        }
        .report-title {
            font-size: 17px;
            font-weight: 600;
            color: #293567;
            margin-bottom: 6px;
        }
        .divider {
            border: none;
            border-top: 1px solid #e9edf3;
            margin: 24px 0 20px 0;
        }
        .action-btn {
            display: inline-block;
            background: #293567;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 15px;
            padding: 12px 28px;
            border-radius: 40px;
            text-decoration: none;
            margin: 8px 0 12px 0;
            border: 1px solid #1f2a50;
            transition: background 0.2s, transform 0.1s;
        }
        .action-btn:hover {
            background: #1f2a50;
        }
        .action-btn:active {
            transform: scale(0.97);
        }
        .footnote {
            font-size: 14px;
            color: #64748b;
            margin-top: 24px;
            border-top: 1px solid #edf2f7;
            padding-top: 22px;
        }
        .signoff {
            margin-top: 4px;
            font-weight: 500;
            color: #1f2937;
        }
        .signoff strong {
            color: #293567;
        }
        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #293567;
            border-radius: 50%;
            margin-right: 6px;
        }
        /* Subtle primary color links */
        a:not(.action-btn) {
            color: #293567;
            text-decoration: none;
        }
        a:not(.action-btn):hover {
            text-decoration: underline;
        }
        @media (max-width: 500px) {
            body {
                padding: 16px 8px;
            }
            .email-header,
            .email-body {
                padding-left: 20px;
                padding-right: 20px;
            }
            .header-content {
                flex-wrap: wrap;
                gap: 10px;
            }
            .org-name {
                font-size: 16px;
            }
            .report-card {
                padding: 16px 18px;
            }
            .action-btn {
                display: block;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <div class="email-wrapper">

        <!-- Accent Bar (Primary Color) -->
        <div class="accent-bar"></div>

        <!-- HEADER -->
        <div class="email-header">
            <div class="header-content">
                <img
                    src="{{ asset('assets/images/home-three/logo/logo-main.png') }}"
                    alt="IGRCFP Logo"
                    class="logo"
                />
                <div class="org-name">
                    The Institute of GRC<br /><span>and Financial Crime Prevention</span>
                </div>
            </div>
        </div>

        <!-- BODY -->
        <div class="email-body">

            <div class="badge">
                <span class="status-dot"></span> Examiner Report
            </div>

            <h1>Examiner Report Uploaded</h1>

            <p class="greeting">Hello {{ $studentName }},</p>

            <p class="text-muted">
                An examiner's report has been uploaded for your assessment.
                Please find the details below:
            </p>

            <!-- Report Card -->
            <div class="report-card">
                <div class="report-title">{{ $assessmentTitle }}</div>
                <p><strong>Report:</strong> {{ $reportName }}</p>
                <p style="font-size:14px; color:#475569; margin-top:8px;">
                    <span style="background:#eef0f7; padding:2px 10px; border-radius:30px; font-size:13px; color:#293567;">
                        ✓ Uploaded
                    </span>
                </p>
            </div>

            <p style="font-size:15px; color:#1f2937;">
                Please log in to your account to view the full report and any additional assessment feedback.
            </p>

            <!-- CTA -->
            <a href="{{ url('/login') }}" class="action-btn">Log in to view report</a>

            <hr class="divider" />

            <p style="font-size:15px; color:#334155; margin:0;">
                If you have any questions, please contact our 
                <a href="mailto:support@igrcfp.com">support team</a>.
            </p>

            <!-- Sign-off -->
            <div class="footnote">
                <p style="margin:0 0 4px 0;">
                    Regards,
                </p>
                <p class="signoff">
                    <strong>{{ config('app.name') }}</strong>
                </p>
                <p style="font-size:13px; color:#94a3b8; margin-top:6px;">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>

        </div>
        <!-- /email-body -->

    </div>
    <!-- /email-wrapper -->

</body>
</html>