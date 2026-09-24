<!DOCTYPE html>
<html>
<head>
    <title>Course Completion Certificate</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            color: #333;
            text-align: center;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }
        .container {
            border: 15px double #b8860b;
            padding: 50px;
            margin: 40px;
            background-color: #faf9f6;
            height: 80%;
            box-sizing: border-box;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #8b6508;
            letter-spacing: 2px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .title {
            font-size: 42px;
            font-weight: normal;
            margin-bottom: 5px;
            color: #222;
        }
        .subtitle {
            font-size: 18px;
            font-style: italic;
            margin-bottom: 30px;
            color: #666;
        }
        .presented-to {
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            color: #777;
        }
        .student-name {
            font-size: 36px;
            font-weight: bold;
            color: #8b6508;
            border-bottom: 2px solid #ddd;
            display: inline-block;
            padding-bottom: 5px;
            margin-bottom: 20px;
            min-width: 400px;
        }
        .reason {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 40px;
            color: #444;
        }
        .course-title {
            font-weight: bold;
            color: #111;
        }
        .footer {
            margin-top: 50px;
        }
        .date-section {
            display: inline-block;
            width: 45%;
            text-align: center;
        }
        .signature-section {
            display: inline-block;
            width: 45%;
            text-align: center;
        }
        .line {
            width: 200px;
            border-bottom: 1px solid #777;
            margin: 0 auto 10px auto;
        }
        .label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            {{ config('app.name', 'AI Learning Academy') }}
        </div>

        <div class="title">
            Certificate of Completion
        </div>

        <div class="subtitle">
            This is to certify that
        </div>

        <div class="presented-to">
            honorable student
        </div>

        <div class="student-name">
            {{ $student->name }}
        </div>

        <div class="reason">
            has successfully completed all requirements, modules, and assessments for the course
            <br>
            <span class="course-title">"{{ $course->title }}"</span>
        </div>

        <div class="footer">
            <div class="date-section">
                <div class="line"></div>
                <div class="label">Date of Completion</div>
                <div style="margin-top: 5px; font-weight: bold; color: #555;">{{ $completionDate }}</div>
            </div>

            <div class="signature-section">
                <div class="line"></div>
                <div class="label">Authorized Signature</div>
                <div style="margin-top: 5px; font-weight: bold; color: #555;">AI Learning Platform Administrator</div>
            </div>
        </div>
    </div>
</body>
</html>