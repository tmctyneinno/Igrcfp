<!DOCTYPE html>
<html>
<head>
    <title>Scholarship Accepted Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #1a237e 0%, #0d1b4a 100%);
            padding: 20px;
            text-align: center;
            color: white;
            border-radius: 10px 10px 0 0;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 15px;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            border: 1px solid #e0e0e0;
            border-top: none;
            border-radius: 0 0 10px 10px;
        }
        .info-box {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #1a237e 0%, #0d1b4a 100%);
            color: #ffffff !important;  /* Force white text */
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .button:hover {
            background: linear-gradient(135deg, #0d1b4a 0%, #1a237e 100%);
            text-decoration: none;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        a {
            color: #1a237e;
        }
        .info-box a {
            color: #1a237e;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('assets/images/home-three/logo/logo-white.png') }}" alt="IGRCFP Logo" class="logo">
        <h2>Institute of GRC and Financial Crime Prevention</h2>
        <h3>Scholarship Acceptance Notification</h3>
    </div>
    
    <div class="content">
        <p>Dear Scholarship Administration Team,</p>

        <p>A student has officially accepted their scholarship award. Please review the details below and proceed with the onboarding process.</p>

        <div class="info-box">
            <h3 style="color: #1a237e; margin-top: 0;">📋 Student Details:</h3>
            <p><strong>Full Name:</strong> {{ $application->full_name }}</p>
            <p><strong>Email:</strong> {{ $application->email }}</p>
            <p><strong>Phone:</strong> {{ $application->phone_number }}</p>
            <p><strong>Nationality:</strong> {{ $application->nationality }}</p>
            <p><strong>Country of Residence:</strong> {{ $application->country_of_residence }}</p>
            
            <h3 style="color: #1a237e; margin-top: 20px;">🎓 Academic Details:</h3>
            <p><strong>Academic Background:</strong> {{ $application->academic_background }}</p>
            <p><strong>Highest Qualification:</strong> {{ $application->highest_qualification }}</p>
            <p><strong>Institution:</strong> {{ $application->institution }}</p>
            <p><strong>Year Completed:</strong> {{ $application->year_completed }}</p>
            
            <h3 style="color: #1a237e; margin-top: 20px;">💼 Professional Details:</h3>
            <p><strong>Current Role:</strong> {{ $application->current_role }}</p>
            <p><strong>Organisation:</strong> {{ $application->organisation }}</p>
            
            <h3 style="color: #1a237e; margin-top: 20px;">📚 Preferred Programmes:</h3>
            <ul>
                @foreach($application->preferred_programmes as $programme)
                    <li>{{ $programme }}</li>
                @endforeach
            </ul>
            
            <h3 style="color: #1a237e; margin-top: 20px;">📅 Important Dates:</h3>
            <p><strong>Application Date:</strong> {{ $application->created_at->format('F j, Y, g:i a') }}</p>
            <p><strong>Acceptance Date:</strong> {{ now()->format('F j, Y, g:i a') }}</p>
        </div>

        <h3>✅ Required Actions:</h3>
        <ul>
            <li>Send onboarding instructions and access credentials</li>
            <li>Register the student in the selected programme(s)</li>
            <li>Generate student ID and scholarship certificate</li>
            <li>Schedule welcome orientation (if applicable)</li>
            <li>Update scholarship tracking system</li>
        </ul>

        <div style="text-align: center;">
            <a href="{{ route('admin.scholarships.show', $application->id) }}" class="button" style="color: #ffffff !important; text-decoration: none;">
                View Full Application
            </a>
        </div>

        <p>Please ensure all necessary steps are completed within 2-3 business days to provide a smooth onboarding experience for the scholarship recipient.</p>

        <p>Thank you for your prompt attention to this matter.</p>

        <p>Kind regards,<br>
        <strong>System Administrator</strong><br>
        Institute of GRC and Financial Crime Prevention (IGRCFP)</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} Institute of GRC and Financial Crime Prevention. All rights reserved.</p>
        <p>This is an automated notification from the scholarship management system.</p>
    </div>
</body>
</html>