<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Your Student Portal</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* RESET & BASE */
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;
             background:#f4f7fa;color:#445;line-height:1.6;-webkit-font-smoothing:antialiased}
        a{color:#5b63d3;text-decoration:none;font-weight:600}
        a:hover{text-decoration:underline}

        /* LAYOUT */
        .wrap{max-width:600px;margin:40px auto;padding:0 20px}
        .card{background:#fff;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.08);overflow:hidden}

        /* HEADER */
        .head{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);padding:50px 40px 40px;text-align:center;color:#fff}
        .head img{height:60px;margin-bottom:20px}
        .head h1{font-size:28px;font-weight:700;letter-spacing:.5px}
        .head p{font-size:16px;opacity:.9;margin-top:8px}

        /* BODY */
        .body{padding:40px}
        .welcome{font-size:18px;margin-bottom:25px}
        .badge{display:inline-block;background:#f3f4ff;color:#5b63d3;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600;margin-bottom:25px}

        /* CREDENTIALS */
        .cred{border:1px solid #e6e9f0;border-radius:10px;padding:20px;margin-bottom:25px;background:#fafbff}
        .cred .row{display:flex;align-items:center;margin-bottom:12px}
        .cred .row:last-child{margin-bottom:0}
        .cred .lbl{flex:0 0 90px;font-weight:600;font-size:14px;color:#667}
        .cred .val{flex:1;font-family:monospace;background:#eef0ff;padding:6px 10px;border-radius:6px;font-size:14px;word-break:break-all;color:#445}
        .cred .cpy{margin-left:10px;background:#5b63d3;color:#fff;border:none;padding:6px 10px;border-radius:6px;cursor:pointer;font-size:12px}
        .cred .cpy:hover{background:#4a52c2}

        /* CTA */
        .cta{background:#f3f4ff;border-radius:10px;padding:25px;text-align:center;margin-bottom:25px}
        .cta p{margin-bottom:15px;font-size:15px}
        .btn{display:inline-block;background:#5b63d3;color:#fff;padding:12px 28px;border-radius:8px;font-weight:600;font-size:15px;box-shadow:0 4px 12px rgba(91,99,211,.25)}
        .btn:hover{background:#4a52c2;box-shadow:0 6px 16px rgba(91,99,211,.35)}

        /* FOOTER */
        .foot{text-align:center;padding:0 40px 30px;font-size:13px;color:#889}
        .social{margin-top:20px}
        .social a{margin:0 6px;display:inline-block}
        .social img{height:22px;opacity:.7}
        .social a:hover img{opacity:1}
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <!-- Header -->
            <div class="head">
                <img src="https://i.ibb.co/2vWzRgj/logo-white.png" alt="Logo">
                <h1>Welcome to Your Student Portal</h1>
                <p>Your journey starts here—let’s make it amazing.</p>
            </div>

            <!-- Body -->
            <div class="body">
                <div class="badge">Account Created Successfully</div>

                <p class="welcome">Hi <strong>{{ $student->first_name }}</strong>,</p>
                <p>Your student account is ready! Below are your login credentials. Please keep them safe and change your password after your first login.</p>

                <!-- Credentials -->
                <div class="cred">
                    <div class="row">
                        <span class="lbl">Email:</span>
                        <span class="val">{{ $student->email }}</span>
                        <button class="cpy" onclick="copyToClipboard('{{ $student->email }}')">Copy</button>
                    </div>
                    <div class="row">
                        <span class="lbl">Password:</span>
                        <span class="val">{{ $password }}</span>
                        <button class="cpy" onclick="copyToClipboard('{{ $plainPassword }}')">Copy</button>
                    </div>
                </div>

                <!-- CTA -->
                <div class="cta">
                    <p>Ready to explore your courses?</p>
                    <a href="{{ url('/student/login') }}" class="btn">Log In Now</a>
                </div>

                <p>If you didn’t create this account, please ignore this email or <a href="mailto:support@school.com">contact support</a>.</p>
            </div>

            <!-- Footer -->
            <div class="foot">
                <p>&copy; {{ date('Y') }} Your School Name. All rights reserved.</p>
                <div class="social">
                    <a href="#"><img src="https://i.ibb.co/3sW5bFB/fb.png" alt="Facebook"></a>
                    <a href="#"><img src="https://i.ibb.co/n0JqL1r/tw.png" alt="Twitter"></a>
                    <a href="#"><img src="https://i.ibb.co/6r0hL1M/ig.png" alt="Instagram"></a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Copied to clipboard!');
            });
        }
    </script>
</body>
</html>
