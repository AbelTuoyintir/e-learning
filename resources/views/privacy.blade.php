<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - EduHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: { 600: '#2563eb', 50: '#eff6ff' } },
                    fontFamily: { 'sans': ['Inter', 'system-ui', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .privacy-card { transition: all 0.3s ease; }
        .privacy-card:hover { transform: translateY(-2px); }
    </style>
</head>
<body class="bg-white text-slate-800">

    <header class="sticky top-0 z-50 bg-white/85 backdrop-blur-md border-b border-slate-200">
        <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 text-primary-600 font-extrabold text-2xl">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5-10-5-10 5z"/></svg>
                <span>EduHub</span>
            </a>
            <ul class="hidden md:flex items-center gap-6 text-sm font-semibold text-slate-700">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition">Home</a></li>
                <li><a href="{{ route('about') }}" class="text-primary-600">About</a></li>
                <li><a href="{{ route('privacy') }}" class="hover:text-primary-600 transition">Privacy</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-primary-600 transition">Contact</a></li>
                <li><a href="{{ route('accessibility') }}" class="hover:text-primary-600 transition">Accessibility</a></li>
            </ul>
        </nav>
    </header>

    <div class="max-w-4xl mx-auto px-6 py-16">
        <div class="text-center mb-12">
            <i class="fas fa-shield-alt text-5xl text-primary-600 mb-4"></i>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Privacy Policy</h1>
            <p class="text-slate-600 text-lg">Last updated: January 1, 2025</p>
        </div>

        <div class="space-y-8">
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm privacy-card">
                <h2 class="text-2xl font-bold mb-3 flex items-center gap-2"><i class="fas fa-info-circle text-primary-600"></i> Information We Collect</h2>
                <p class="text-slate-600 leading-relaxed">We collect information you provide directly to us, such as when you create an account, enroll in courses, or contact us for support. This may include your name, email address, payment information, and learning progress data.</p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm privacy-card">
                <h2 class="text-2xl font-bold mb-3 flex items-center gap-2"><i class="fas fa-chart-line text-primary-600"></i> How We Use Your Information</h2>
                <ul class="list-disc list-inside text-slate-600 space-y-2 ml-4">
                    <li>To provide, maintain, and improve our educational services</li>
                    <li>To personalize your learning experience and track progress</li>
                    <li>To communicate with you about updates, new courses, and promotions</li>
                    <li>To process transactions and prevent fraudulent activities</li>
                </ul>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm privacy-card">
                <h2 class="text-2xl font-bold mb-3 flex items-center gap-2"><i class="fas fa-share-alt text-primary-600"></i> Information Sharing</h2>
                <p class="text-slate-600 leading-relaxed">We do not sell your personal information. We may share your information with trusted third-party service providers who assist us in operating our platform, processing payments, or analyzing data, under strict confidentiality agreements.</p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm privacy-card">
                <h2 class="text-2xl font-bold mb-3 flex items-center gap-2"><i class="fas fa-cookie-bite text-primary-600"></i> Cookies & Tracking</h2>
                <p class="text-slate-600 leading-relaxed">We use cookies and similar tracking technologies to enhance your browsing experience, analyze site traffic, and personalize content. You can control cookie preferences through your browser settings.</p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm privacy-card">
                <h2 class="text-2xl font-bold mb-3 flex items-center gap-2"><i class="fas fa-user-lock text-primary-600"></i> Data Security</h2>
                <p class="text-slate-600 leading-relaxed">We implement industry-standard security measures to protect your personal information, including encryption, secure servers, and regular security audits. However, no method of transmission over the internet is 100% secure.</p>
            </div>

            <div class="bg-primary-50 p-6 rounded-xl border border-primary-200">
                <h2 class="text-2xl font-bold mb-3">Your Rights</h2>
                <p class="text-slate-700 mb-3">You have the right to:</p>
                <ul class="list-disc list-inside text-slate-700 space-y-1 ml-4">
                    <li>Access and receive a copy of your personal data</li>
                    <li>Correct inaccurate or incomplete information</li>
                    <li>Request deletion of your account and associated data</li>
                    <li>Opt-out of marketing communications</li>
                </ul>
                <p class="mt-4 text-slate-600">To exercise these rights, contact us at <a href="mailto:privacy@eduhub.com" class="text-primary-600 font-semibold">privacy@eduhub.com</a></p>
            </div>
        </div>
    </div>

    <footer class="bg-slate-900 text-slate-300 py-12 mt-12">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p>© 2025 EduHub. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>