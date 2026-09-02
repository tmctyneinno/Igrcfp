<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Received</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f3f6fb;
            font-family: Arial, Helvetica, sans-serif;
            color: #1f2937;
        }
        table {
            border-spacing: 0;
            width: 100%;
        }
        .wrapper {
            max-width: 680px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
            padding: 32px 30px;
            color: #ffffff;
        }
        .brand {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.6px;
        }
        .eyebrow {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            opacity: 0.82;
            margin-bottom: 10px;
        }
        .heading {
            font-size: 30px;
            line-height: 1.2;
            margin: 0;
            font-weight: 700;
        }
        .content {
            padding: 32px 30px 10px;
        }
        .panel {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 22px;
            margin-bottom: 22px;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .info-grid td {
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            vertical-align: top;
        }
        .label {
            color: #475569;
            width: 40%;
            font-weight: 700;
        }
        .value {
            color: #0f172a;
            font-weight: 600;
        }
        .button-row {
            text-align: center;
            padding: 10px 0 28px;
        }
        .button {
            display: inline-block;
            background: #1d4ed8;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 24px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
        }
        .footer {
            padding: 18px 30px 28px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #64748b;
            line-height: 1.7;
        }
        .footer a {
            color: #1d4ed8;
            text-decoration: none;
        }
        @media only screen and (max-width: 620px) {
            .wrapper { margin: 18px; }
            .heading { font-size: 24px; }
            .content, .header, .footer { padding-left: 18px !important; padding-right: 18px !important; }
            .label { width: 45%; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <img src="{{ asset('assets/images/logo-main.png') }}" alt="IGRCFP Logo" class="logo">
            <div class="eyebrow">IGRCFP Admissions</div>
            <div class="brand">Institute of Governance, Risk &amp; Compliance</div>
            <h1 class="heading">Application Received</h1>
        </div>

        <div class="content">
            <p style="margin-top: 0; font-size: 16px;">Dear <strong>{{ $application->full_name }}</strong>,</p>

            <p style="font-size: 16px; line-height: 1.7; color: #334155;">
                Thank you for submitting your application to the <strong>{{ $application->cohort }}</strong> cohort.
                We have successfully received your details and our admissions team will review your submission.
            </p>

            <div class="panel">
                <h3 style="margin: 0 0 18px; color: #0f172a; font-size: 18px;">Application Summary</h3>
                <table class="info-grid">
                    <tr>
                        <td class="label">Full name</td>
                        <td class="value">{{ $application->full_name }}</td>
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
                        <td class="label">Country</td>
                        <td class="value">{{ $application->country }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email</td>
                        <td class="value">{{ $application->email }}</td>
                    </tr>
                    <tr>
                        <td class="label">Phone</td>
                        <td class="value">{{ $application->phone ?: 'Not provided' }}</td>
                    </tr>
                </table>
            </div>

            <p style="font-size: 15px; line-height: 1.7; color: #334155;">
                Our team will review your application and contact you by email with the next steps. If you have any urgent questions,
                feel free to reach out to us at <a href="mailto:{{ $supportEmail }}" style="color: #1d4ed8;">{{ $supportEmail }}</a>
                or call <a href="tel:{{ $supportPhone }}" style="color: #1d4ed8;">{{ $supportPhone }}</a>.
            </p>

            <div class="button-row">
                <a href="https://www.igrcfp.org" class="button">Visit Our Website</a>
            </div>
        </div>

        <div class="footer">
            <p style="margin: 0 0 8px;">This is an automated confirmation email sent after your application submission.</p>
            <p style="margin: 0;">Please do not reply directly to this message. For further support, contact <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>.</p>
            <p style="margin: 12px 0 0;">&copy; {{ date('Y') }} IGRCFP. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
