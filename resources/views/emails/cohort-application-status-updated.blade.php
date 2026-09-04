<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cohort Application Status Update</title>
    <style>
        body { margin: 0; padding: 0; background: #f4f7fb; font-family: Arial, Helvetica, sans-serif; color: #1f2937; }
        .wrapper { max-width: 680px; margin: 30px auto; background: #ffffff; border-radius: 18px; overflow: hidden; box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08); }
        .header { background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%); padding: 28px 30px; color: #fff; }
        .brand { font-size: 22px; font-weight: 700; letter-spacing: 0.5px; }
        .eyebrow { font-size: 11px; letter-spacing: 1.8px; text-transform: uppercase; opacity: 0.8; margin-bottom: 8px; }
        .heading { margin: 0; font-size: 28px; line-height: 1.2; }
        .content { padding: 32px 30px 12px; }
        .status-pill { display: inline-block; background: #dbeafe; color: #1d4ed8; border-radius: 999px; padding: 8px 14px; font-weight: 700; font-size: 12px; margin-bottom: 20px; }
        .panel { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 22px; margin-bottom: 22px; }
        .footer { padding: 18px 30px 28px; font-size: 12px; color: #64748b; background: #f8fafc; border-top: 1px solid #e2e8f0; }
        .btn { display: inline-block; padding: 12px 20px; background: #1d4ed8; color: #fff !important; border-radius: 8px; text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="eyebrow">IGRCFP Admissions</div>
            <div class="brand">Institute of Governance, Risk &amp; Compliance</div>
            <h1 class="heading">Application Status Update</h1>
        </div>

        <div class="content">
            <div class="status-pill">{{ $statusLabel }}</div>

            <p style="font-size: 16px; line-height: 1.7; color: #334155; margin-top: 0;">
                Dear <strong>{{ $application->full_name }}</strong>,
            </p>

            <p style="font-size: 16px; line-height: 1.7; color: #334155;">
                @if($status === 'new')
                    Thank you for applying to the {{ $application->cohort }} cohort. We have received your application and our admissions team will review it shortly.
                @elseif($status === 'reviewing')
                    Your cohort application is currently under review. We will update you again as soon as a decision is reached.
                @elseif($status === 'admitted')
                    We are pleased to inform you that your application has been admitted. Our team will share the next steps with you soon.
                @elseif($status === 'rejected')
                    After careful review, we are unable to proceed with your application at this stage.
                @elseif($status === 'withdrawn')
                    Your cohort application status has been updated to withdrawn.
                @else
                    Your cohort application status has been updated.
                @endif
            </p>

            @if($status === 'rejected' && $reason)
                <div class="panel">
                    <h3 style="margin: 0 0 12px; font-size: 18px; color: #0f172a;">Reason</h3>
                    <p style="margin: 0; line-height: 1.7; color: #334155;">{{ $reason }}</p>
                </div>
            @endif

            <div class="panel">
                <h3 style="margin: 0 0 12px; font-size: 18px; color: #0f172a;">Application Summary</h3>
                <p style="margin: 0; line-height: 1.7; color: #334155;">
                    <strong>Cohort:</strong> {{ $application->cohort }}<br>
                    <strong>Level:</strong> {{ $application->level }}<br>
                    <strong>Discipline:</strong> {{ $application->discipline ?: 'Not specified' }}
                </p>
            </div>

            <p style="font-size: 15px; line-height: 1.7; color: #334155;">
                If you need assistance, please contact us at <a href="mailto:{{ $supportEmail }}" style="color: #1d4ed8;">{{ $supportEmail }}</a>.
            </p>
        </div>

        <div class="footer">
            <p style="margin: 0;">Thank you for your interest in IGRCFP.</p>
        </div>
    </div>
</body>
</html>
