<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - EduHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a'
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
        .gradient-text { background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .hero-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-white text-slate-800">

    <!-- Navigation -->
    <header class="sticky top-0 z-50 bg-white/85 backdrop-blur-md border-b border-slate-200 shadow-sm">
        <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 text-primary-600 font-extrabold text-2xl">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5-10-5-10 5z"/></svg>
                <span class="gradient-text">EduHub</span>
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

    <!-- Hero Section -->
    <section class="hero-gradient text-white py-20">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-4">About EduHub</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">Empowering learners worldwide through accessible, quality education</p>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold mb-4">Our Mission</h2>
                    <p class="text-slate-600 text-lg leading-relaxed mb-6">
                        At EduHub, we believe that quality education should be accessible to everyone, regardless of their background or circumstances. Our mission is to democratize learning by providing engaging, interactive, and affordable courses that empower individuals to reach their full potential.
                    </p>
                    <div class="flex gap-4">
                        <div class="bg-primary-50 p-4 rounded-xl">
                            <i class="fas fa-users text-3xl text-primary-600 mb-2"></i>
                            <p class="font-bold text-2xl">50,000+</p>
                            <p class="text-sm text-slate-600">Active Learners</p>
                        </div>
                        <div class="bg-primary-50 p-4 rounded-xl">
                            <i class="fas fa-book text-3xl text-primary-600 mb-2"></i>
                            <p class="font-bold text-2xl">500+</p>
                            <p class="text-sm text-slate-600">Courses</p>
                        </div>
                        <div class="bg-primary-50 p-4 rounded-xl">
                            <i class="fas fa-chalkboard-user text-3xl text-primary-600 mb-2"></i>
                            <p class="font-bold text-2xl">200+</p>
                            <p class="text-sm text-slate-600">Expert Instructors</p>
                        </div>
                    </div>
                </div>
                <div>
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Team collaboration" class="rounded-2xl shadow-xl">
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">Our Core Values</h2>
                <p class="text-slate-600 text-lg">The principles that guide everything we do</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-primary-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-heart text-2xl text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Inclusivity</h3>
                    <p class="text-slate-600">Creating a learning environment where everyone feels welcome and represented.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-primary-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-lightbulb text-2xl text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Innovation</h3>
                    <p class="text-slate-600">Embracing cutting-edge technology to enhance the learning experience.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-primary-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-handshake text-2xl text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Integrity</h3>
                    <p class="text-slate-600">Operating with transparency and honesty in all our relationships.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">Meet Our Leadership</h2>
                <p class="text-slate-600 text-lg">Passionate educators and technologists driving change</p>
            </div>
            <div class="grid md:grid-cols-4 gap-6">
                <div class="text-center">
                    <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="CEO" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <h4 class="font-bold">Dr. Sarah Johnson</h4>
                    <p class="text-sm text-primary-600">CEO & Founder</p>
                </div>
                <div class="text-center">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="CTO" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <h4 class="font-bold">Michael Chen</h4>
                    <p class="text-sm text-primary-600">Chief Technology Officer</p>
                </div>
                <div class="text-center">
                    <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="COO" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <h4 class="font-bold">Dr. Amara Okonkwo</h4>
                    <p class="text-sm text-primary-600">Chief Learning Officer</p>
                </div>
                <div class="text-center">
                    <img src="https://randomuser.me/api/portraits/men/52.jpg" alt="CMO" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <h4 class="font-bold">David Williams</h4>
                    <p class="text-sm text-primary-600">Head of Community</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 py-12">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p>© 2025 EduHub. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>