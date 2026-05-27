{{-- resources/views/emails/contact/submitted.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Contact Form Submission</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .message-box { background: white; border-left: 4px solid #667eea; padding: 20px; margin: 20px 0; }
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0; }
        .info-item { background: white; padding: 15px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .label { font-weight: bold; color: #667eea; display: block; margin-bottom: 5px; }
        .btn { display: inline-block; background: #667eea; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Contact Form Submission</h1>
        <p>You have received a new message from your website contact form.</p>
    </div>
    
    <div class="content">
        <div class="info-grid">
            <div class="info-item">
                <span class="label">From:</span>
                {{ $contactMessage->first_name }} {{ $contactMessage->last_name }}
            </div>
            <div class="info-item">
                <span class="label">Email:</span>
                <a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a>
            </div>
            <div class="info-item">
                <span class="label">Phone:</span>
                {{ $contactMessage->phone ? '+' . $contactMessage->country_code . ' ' . $contactMessage->phone : 'Not provided' }}
            </div>
            <div class="info-item">
                <span class="label">Submitted:</span>
                {{ $contactMessage->created_at->format('F j, Y \a\t g:i A') }}
            </div>
        </div>

        <div class="message-box">
            <strong class="label">Message:</strong>
            <p>{{ $contactMessage->message }}</p>
        </div>

        <div style="background: #e8f4fd; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>📋 Submission Details:</strong>
            <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                <li>IP Address: {{ $contactMessage->ip_address ?? 'Not recorded' }}</li>
                <li>Privacy Policy Agreed: {{ $contactMessage->privacy_agreed ? 'Yes' : 'No' }}</li>
                <li>Status: <strong style="color: #e74c3c;">New</strong></li>
            </ul>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('admin.contacts.show', $contactMessage) }}" class="btn">
                View in Admin Panel
            </a>
        </div>

        <div class="footer">
            <p>This is an automated notification from {{ config('app.name') }}.</p>
            <p>If you need assistance, please contact the system administrator.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>