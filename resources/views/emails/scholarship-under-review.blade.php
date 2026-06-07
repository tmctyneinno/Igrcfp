<!DOCTYPE html>
<html>
<head>
    <title>Scholarship Application Under Review</title>
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
            background-color: #1a237e;
            padding: 20px;
            text-align: center;
            color: white;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1a237e;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('assets/images/home-three/logo/logo-main.png') }}" alt="IGRCFP Logo" class="logo">
        <h2>The Institute of GRC and Financial Crime Prevention (IGRCFP)</h2>
        <h3>Scholarship Programme - Application Under Review</h3>
    </div>  
    
    <div class="content">
        <p>Dear {{ $application->full_name }},</p>

        <p>Thank you for submitting your application for the Institute of GRC and Financial Crime Prevention (IGRCFP) Scholarship Programme.</p>

        <p>We are pleased to confirm that your application has been successfully received and is currently <strong>under review</strong> by our Scholarship Assessment Committee.</p>

        <p>The review process involves a detailed evaluation of all submitted applications against the scholarship eligibility criteria, academic and professional achievements, demonstrated commitment to Governance, Risk, Compliance, Financial Crime Prevention, and the potential impact of the scholarship on the applicant's professional development.</p>

        <p>Due to the high volume of applications received, the assessment process may take some time. We kindly ask for your patience while the committee completes its review.</p>

        <p>Should additional information or supporting documentation be required, a member of the IGRCFP team will contact you directly.</p>

        <p>We appreciate your interest in IGRCFP and your commitment to advancing excellence within the Governance, Risk, Compliance, and Financial Crime Prevention profession.</p>

        <p>We will communicate the outcome of your application as soon as the review process has been completed.</p>

        <p>Thank you for your interest in the Institute of GRC and Financial Crime Prevention.</p>

        <p>Kind regards,</p>
        <p>
            <strong>Scholarship Administration Team</strong><br>
            Institute of GRC and Financial Crime Prevention (IGRCFP)<br>
            Email: scholarships@igrcfp.org<br>
            Website: <a href="https://www.igrcfp.org">www.igrcfp.org</a>
        </p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} Institute of GRC and Financial Crime Prevention. All rights reserved.</p>
    </div>
</body>
</html>