<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #dc2626; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
       .logo {
            display: block;
            max-width: 80px;
            height: auto;
            margin-bottom: 18px;
        }
        .content { background-color: #f9fafb; padding: 20px; border: 1px solid #e5e7eb; border-top: none; }
        .button { display: inline-block; background-color: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin-top: 20px; }
        .footer { text-align: center; font-size: 12px; color: #6b7280; margin-top: 20px; }
        .reason-box { background-color: #fff; border-left: 4px solid #dc2626; padding: 10px; margin: 15px 0; font-style: italic; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img style="width:40px" src="{{ asset('assets/images/home-three/logo/logo-main.png') }}" alt="IGRCFP Logo" class="logo">
            <h2>The Institute of GRC and Financial Crime Prevention (IGRCFP)</h2>
       
            <h3>Enrollment Update</h3>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            
            <p>We are writing to inform you that your enrollment in the course <strong>"{{ $course->title }}"</strong> has been revoked.</p>
            
            @if(!empty($reason))
                <div class="reason-box">
                    <strong>Reason:</strong> {{ $reason }}
                </div>
            @endif

            <p>If you believe this is an error or if you wish to apply for this course through the scholarship program, please contact our support team.</p>
            
            <center>
                <a href="{{ route('dashboard.courses.index') }}" class="button" style="color:white">Browse Other Courses</a>
            </center>
        </div>
        
        <div class="footer">
            <p>Thank you,<br>{{ config('app.name') }} Team</p>
        </div>
    </div>
</body>
</html>