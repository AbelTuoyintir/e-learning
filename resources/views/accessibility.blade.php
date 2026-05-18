<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accessibility - EduHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: { 600: '#2563eb', 50: '#eff6ff', 100: '#dbeafe' } },
                    fontFamily: { 'sans': ['Inter', 'system-ui', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .accessibility-card { transition: all 0.3s ease; }
        .accessibility-card:hover { transform: translateX(5px); }
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
            <i class="fas fa-universal-access text-5xl text-primary-600 mb-4"></i>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Accessibility Statement</h1>
            <p class="text-slate-600 text-lg">EduHub is committed to ensuring digital accessibility for all learners</p>
        </div>

        <div class="bg-primary-50 p-6 rounded-xl mb-8 border-l-4 border-primary-600">
            <p class="text-slate-700">We are continuously working to improve the accessibility of our platform to ensure that individuals with disabilities can access all content, features, and functionality.</p>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl border border-slate-200 accessibility-card">
                <div class="flex items-start gap-4">
                    <i class="fas fa-keyboard text-2xl text-primary-600 mt-1"></i>
                    <div>
                        <h2 class="text-xl font-bold mb-2">Keyboard Navigation</h2>
                        <p class="text-slate-600">Our platform is fully accessible via keyboard alone. Use Tab to navigate, Enter to activate, and Escape to close modals.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 accessibility-card">
                <div class="flex items-start gap-4">
                    <i class="fas fa-eye text-2xl text-primary-600 mt-1"></i>
                    <div>
                        <h2 class="text-xl font-bold mb-2">Screen Reader Compatibility</h2>
                        <p class="text-slate-600">All content is coded with semantic HTML and ARIA landmarks to ensure compatibility with popular screen readers like NVDA, JAWS, and VoiceOver.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 accessibility-card">
                <div class="flex items-start gap-4">
                    <i class="fas fa-text-height text-2xl text-primary-600 mt-1"></i>
                    <div>
                        <h2 class="text-xl font-bold mb-2">Text Resizing & Contrast</h2>
                        <p class="text-slate-600">Users can resize text up to 200% without loss of functionality. Our color scheme meets WCAG 2.1 AA contrast requirements.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 accessibility-card">
                <div class="flex items-start gap-4">
                    <i class="fas fa-closed-captioning text-2xl text-primary-600 mt-1"></i>
                    <div>
                        <h2 class="text-xl font-bold mb-2">Captions & Transcripts</h2>
                        <p class="text-slate-600">All video content includes closed captions, and audio content is accompanied by written transcripts.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 accessibility-card">
                <div class="flex items-start gap-4">
                    <i class="fas fa-palette text-2xl text-primary-600 mt-1"></i>
                    <div>
                        <h2 class="text-xl font-bold mb-2">Customizable Display</h2>
                        <p class="text-slate-600">Users can adjust font size, color contrast, and enable high-contrast mode through their browser settings.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12 p-6 bg-slate-50 rounded-xl">
            <h2 class="text-2xl font-bold mb-4">Feedback & Assistance</h2>
            <p class="text-slate-600 mb-4">If you experience any accessibility barriers while using EduHub, please contact our accessibility team:</p>
            <div class="flex flex-wrap gap-4">
                <a href="mailto:accessibility@eduhub.com" class="inline-flex items-center gap-2 text-primary-600 font-semibold"><i class="fas fa-envelope"></i> accessibility@eduhub.com</a>
                <span class="inline-flex items-center gap-2 text-slate-600"><i class="fas fa-phone"></i> +1 (555) 123-4567</span>
            </div>
            <p class="text-sm text-slate-500 mt-4">We aim to respond to accessibility concerns within 2 business days.</p>
        </div>

        <div class="mt-8 text-center text-sm text-slate-500">
            <p>EduHub is committed to WCAG 2.1 Level AA compliance. Last updated: January 2025</p>
        </div>
    </div>

    <footer class="bg-slate-900 text-slate-300 py-12 mt-12">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p>© 2025 EduHub. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>