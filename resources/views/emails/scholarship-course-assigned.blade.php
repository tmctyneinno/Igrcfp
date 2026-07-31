<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #0A1E36; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background-color: #f9fafb; padding: 20px; border: 1px solid #e5e7eb; border-top: none; }
        .button { display: inline-block; background-color: #0A1E36; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin-top: 20px; }
        .footer { text-align: center; font-size: 12px; color: #6b7280; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img style="width:50px" src="{{ asset('assets/images/home-three/logo/logo-main.png') }}" alt="IGRCFP Logo" class="logo">
            <h2>The Institute of GRC and Financial Crime Prevention (IGRCFP)</h2>
       
            <h2>Scholarship Course Assigned</h2>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            
            <p>Congratulations! You have been assigned the following course under the scholarship program:</p>
            
            <ul style="list-style-type: none; padding: 0;">
                <li><strong>Course:</strong> {{ $course->title }}</li>
                <li><strong>Level:</strong> {{ $course->level }}</li>
                <li><strong>Duration:</strong> {{ $course->duration }}</li>
            </ul>
            
            <p>You can now access this course for free from your dashboard.</p>
            
            <center>
                <a href="{{ route('dashboard.courses.show', $course->slug) }}" class="button">Go to Course</a>
            </center> 
        </div>
        
        <div class="footer">
            <p>Thank you,<br>{{ config('app.name') }} Team</p>
        </div>
    </div>
</body>
</html>