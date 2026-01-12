{{-- resources/views/emails/contact/confirmation.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thank You for Contacting Us</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 40px; border-radius: 0 0 10px 10px; }
        .message-id { background: white; padding: 15px; border-radius: 5px; text-align: center; margin: 20px 0; font-family: monospace; }
        .info-box { background: white; padding: 25px; border-radius: 10px; margin: 25px 0; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .highlight { background: #e8f4fd; padding: 20px; border-radius: 10px; margin: 25px 0; }
        .btn { display: inline-block; background: #667eea; color: white; padding: 14px 28px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; color: #666; font-size: 12px; }
        .social-links { margin: 20px 0; }
        .social-links a { margin: 0 10px; color: #667eea; text-decoration: none; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0; font-size: 28px;">Thank You for Contacting Us!</h1>
        <p style="opacity: 0.9; margin-top: 10px;">We've received your message and will respond shortly.</p>
    </div>
    
    <div class="content">
        <p>Dear <strong>{{ $message->full_name }}</strong>,</p>
        
        <p>Thank you for reaching out to <strong>{{ config('app.name') }}</strong>. We have successfully received your inquiry and our team is reviewing it.</p>

        <div class="info-box">
            <h3 style="color: #667eea; margin-top: 0;">📋 Message Details</h3>
            <p><strong>Reference ID:</strong> <span class="message-id">#CM{{ str_pad($message->id, 6, '0', STR_PAD_LEFT) }}</span></p>
            <p><strong>Submitted:</strong> {{ $message->created_at->format('F j, Y \a\t g:i A') }}</p>
            <p><strong>Subject:</strong> Contact Form Submission</p>
        </div>

        <div class="highlight">
            <h3 style="color: #2c3e50; margin-top: 0;">⏰ What Happens Next?</h3>
            <ol style="margin: 15px 0; padding-left: 20px;">
                <li>Your message has been forwarded to our support team</li>
                <li>We aim to respond within <strong>{{ $responseTime }}</strong></li>
                <li>You will receive updates via this email address</li>
                <li>We may contact you at <strong>{{ $message->formatted_phone ?? 'your provided phone number' }}</strong> if needed</li>
            </ol>
        </div>

        <div class="info-box">
            <h3 style="color: #667eea; margin-top: 0;">📝 Your Message</h3>
            <blockquote style="border-left: 4px solid #667eea; margin: 15px 0; padding-left: 15px; font-style: italic;">
                {{ $message->message }}
            </blockquote>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <p><strong>Need immediate assistance?</strong></p>
            <p>
                Email: <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a><br>
                @if($supportPhone)
                Phone: <a href="tel:{{ $supportPhone }}">{{ $supportPhone }}</a>
                @endif
            </p>
            
            <div class="social-links">
                <a href="{{ config('app.social.facebook', '#') }}">Facebook</a> •
                <a href="{{ config('app.social.twitter', '#') }}">Twitter</a> •
                <a href="{{ config('app.social.linkedin', '#') }}">LinkedIn</a>
            </div>
        </div>

        <div class="footer">
            <p>This is an automated confirmation email. Please do not reply to this message.</p>
            <p>If you did not submit this contact form, please ignore this email or contact us immediately.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p style="font-size: 11px; opacity: 0.7;">
                <a href="{{ url('/privacy-policy') }}" style="color: #666;">Privacy Policy</a> | 
                <a href="{{ url('/unsubscribe') }}" style="color: #666;">Unsubscribe</a>
            </p>
        </div>
    </div>
</body>
</html>