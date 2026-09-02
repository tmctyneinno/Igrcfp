<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Cohort Application</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #edf2ff;
            font-family: Arial, Helvetica, sans-serif;
            color: #1f2937;
        }
        .wrapper {
            max-width: 700px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 18px 40px rgba(30, 41, 59, 0.08);
        }
        .header {
            background: linear-gradient(135deg, #111827 0%, #1d4ed8 100%);
            color: #ffffff;
            padding: 28px 30px;
        }
        .eyebrow {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            opacity: 0.82;
            margin-bottom: 8px;
        }
        .heading {
            margin: 0;
            font-size: 28px;
            line-height: 1.3;
        }
        .logo {
            display: block;
            max-width: 80px;
            height: auto;
            margin-bottom: 18px;
        }
        .content {
            padding: 28px 30px 10px;
        }
        .status-badge {
            display: inline-block;
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 16px;
        }
        .panel {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 22px;
            margin: 18px 0;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .info-grid td {
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 0;
            font-size: 14px;
            vertical-align: top;
        }
        .label {
            width: 35%;
            color: #475569;
            font-weight: 700;
        }
        .value {
            color: #0f172a;
            font-weight: 600;
        }
        .message-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #1d4ed8;
            border-radius: 10px;
            padding: 16px;
            color: #334155;
            line-height: 1.7;
        }
        .footer {
            padding: 18px 30px 28px;
            font-size: 12px;
            color: #64748b;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <img src="{{ asset('assets/images/logo-main.png') }}"  alt="IGRCFP Logo" class="logo">
            <div class="eyebrow">New Submission</div>
            <h1 class="heading">Cohort Application Received</h1>
        </div>

        <div class="content">
            <div class="status-badge">{{ $referenceId }}</div>

            <p style="margin: 0 0 10px; font-size: 15px; line-height: 1.7; color: #334155;">
                A new cohort application has been submitted and is ready for review.
            </p>

            <div class="panel">
                <h3 style="margin: 0 0 18px; color: #0f172a; font-size: 18px;">Applicant Details</h3>
                <table class="info-grid">
                    <tr>
                        <td class="label">Applicant</td>
                        <td class="value">{{ $application->full_name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email</td>
                        <td class="value">{{ $application->email }}</td>
                    </tr>
                    <tr>
                        <td class="label">Phone</td>
                        <td class="value">{{ $application->phone ?: 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Country</td>
                        <td class="value">{{ $application->country }}</td>
                    </tr>
                    <tr>
                        <td class="label">Cohort</td>
                        <td class="value">{{ $application->cohort }}</td>
                    </tr>
                    <tr>
                        <td class="label">Level</td>
                        <td class="value">{{ $application->level }}</td>
                    </tr>
                    <tr>
                        <td class="label">Discipline</td>
                        <td class="value">{{ $application->discipline ?: 'Not specified' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Submitted</td>
                        <td class="value">{{ $application->created_at?->format('d M Y, H:i') ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            @if($application->message)
                <div class="panel">
                    <h3 style="margin: 0 0 14px; color: #0f172a; font-size: 18px;">Applicant Message</h3>
                    <div class="message-box">
                        {{ $application->message }}
                    </div>
                </div>
            @endif
        </div>

        <div class="footer">
            <p style="margin: 0;">This email was automatically generated from the cohort application form.</p>
        </div>
    </div>
</body>
</html>
