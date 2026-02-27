<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate of Completion</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .certificate {
            width: 800px;
            margin: 50px auto;
            background: white;
            border: 20px solid #1e3a8a;
            padding: 40px;
            text-align: center;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h1 {
            color: #1e3a8a;
            font-size: 48px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        h2 {
            color: #333;
            font-size: 32px;
            margin: 20px 0;
        }
        .student-name {
            font-size: 36px;
            color: #1e3a8a;
            border-bottom: 2px solid #1e3a8a;
            border-top: 2px solid #1e3a8a;
            padding: 20px;
            margin: 30px 0;
            font-weight: bold;
        }
        .course-name {
            font-size: 28px;
            color: #333;
            margin: 20px 0;
        }
        .date {
            margin: 40px 0 20px;
            color: #666;
        }
        .certificate-number {
            color: #999;
            font-size: 12px;
            margin-top: 30px;
        }
        .signature {
            margin-top: 50px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
            width: 300px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <h1>CERTIFICATE OF COMPLETION</h1>
        
        <p style="font-size: 18px; color: #666;">This is proudly presented to</p>
        
        <div class="student-name">
            {{ $student->name }}
        </div>
        
        <p style="font-size: 16px; color: #666;">for successfully completing the course</p>
        
        <div class="course-name">
            {{ $course->title }}
        </div>
        
        <p style="font-size: 14px; color: #666;">with a final grade of {{ $enrollment->final_grade ?? 'Pass' }}</p>
        
        <div class="date">
            Completed on: {{ $completion_date }}
        </div>
        
        <div class="signature">
            <p>_________________________</p>
            <p>Course Instructor</p>
        </div>
        
        <div class="certificate-number">
            Certificate #: {{ $certificate_number }}
        </div>
    </div>
</body>
</html>