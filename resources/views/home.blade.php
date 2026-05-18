<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Google Font (optional) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CDN (swap to npm/your build later) -->
    <script src="https://cdn.tailwindcss.com"></script>

   @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tiny config so “sky” becomes your primary blue -->
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
            }
        }
    }
    }
    </script>

    <style>
        /* make Inter the default sans */
        body { font-family: 'Inter', sans-serif; }
        /* button helpers */
        .btn-primary { @apply inline-flex items-center justify-center px-5 py-2.5 bg-primary-600 text-white font-semibold rounded-lg shadow hover:bg-primary-700 transition; }
        .btn-outline  { @apply inline-flex items-center justify-center px-5 py-2.5 border border-primary-600 text-primary-600 font-semibold rounded-lg hover:bg-primary-50 transition; }
        .btn-filter   { @apply px-4 py-2 rounded-full text-sm font-medium border border-slate-300 text-slate-700 hover:bg-slate-100 transition; }
        .btn-filter.active { @apply bg-primary-600 text-white border-primary-600; }
        .badge        { @apply px-2 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-700; }
    </style>
</head>
<body class="bg-white text-slate-800">

  <!-- NAVBAR -->
  <header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b border-slate-200">
    <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <!-- Logo -->
      <a href="#" class="flex items-center gap-2 text-primary-600 font-bold text-xl">
        <!-- replace with your SVG or remove icon -->
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5-10-5-10 5z"/></svg>
        EduHub
      </a>

      <!-- Links -->
      <ul class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-600">
        <li><a href="#courses" class="hover:text-primary-600 transition">Courses</a></li>
        <li><a href="#catalog" class="hover:text-primary-600 transition">Catalog</a></li>
        <li><a href="#testimonials" class="hover:text-primary-600 transition">Testimonials</a></li>
        <li><a href="{{ route('login') }}" class="btn-primary bg-primary-600 text-white border rounded-md p-3 shadow-md hover:bg-primary-700 hover:text-white">Sign In</a></li>
      </ul>

      <!-- Mobile burger -->
      <button id="menu-btn" class="md:hidden p-2 rounded-md hover:bg-slate-100">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
             d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </nav>
  </header>

  <!-- HERO -->
  <section class="bg-gradient-to-br from-primary-300 to-white">
    <div class="max-w-7xl mx-auto px-6 py-20 md:py-32 grid md:grid-cols-2 gap-10 items-center">
      <div>
        <h1 class="text-4xl md:text-5xl font-bold leading-tight">
          Learn without limits <br>
          <span class="text-primary-600">at any age.</span>
        </h1>
        <p class="mt-4 text-slate-600 max-w-lg">
          From primary school basics to university-level courses, EduHub delivers
          interactive lessons that fit every learner’s journey.
        </p>
        <div class="mt-8 flex flex-wrap gap-3">
          <a href="#catalog" class="btn-primary">Explore Catalog</a>
          <a href="#" class="btn-outline">Start Free Trial</a>
        </div>
      </div>
      <div class="hidden md:block">
        <img class="rounded-2xl shadow-xl" src="{{ asset('image/students-learning1.png') }}" alt="Students learning online">
      </div>
    </div>
  </section>

  <!-- COURSES -->
  <section id="courses" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center max-w-2xl mx-auto">
        <h2 class="text-3xl font-bold">Featured Courses</h2>
        <p class="mt-2 text-slate-600">Hand-picked for basic-school & higher-ed learners.</p>
      </div>

      <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Card 1 -->
        <article class="border border-slate-200 rounded-2xl overflow-hidden hover:shadow-lg transition">
          <img class="h-48 w-full object-cover" src="https://source.unsplash.com/600x400/?math,kids" alt="Math for kids">
          <div class="p-6">
            <span class="text-xs font-semibold text-primary-600 bg-primary-100 px-2 py-1 rounded">Primary</span>
            <h3 class="mt-3 text-lg font-semibold">Fun with Mathematics</h3>
            <p class="text-sm text-slate-600 mt-1">Build confidence through games and real-world problems.</p>
            <div class="mt-4 flex items-center justify-between">
              <span class="text-slate-500 text-sm">6 weeks</span>
              <a href="#" class="text-primary-600 font-semibold text-sm hover:underline">View syllabus →</a>
            </div>
          </div>
        </article>

        <!-- Card 2 -->
        <article class="border border-slate-200 rounded-2xl overflow-hidden hover:shadow-lg transition">
          <img class="h-48 w-full object-cover" src="https://source.unsplash.com/600x400/?coding,university" alt="Coding">
          <div class="p-6">
            <span class="text-xs font-semibold text-primary-600 bg-primary-100 px-2 py-1 rounded">Higher-Ed</span>
            <h3 class="mt-3 text-lg font-semibold">Intro to Python</h3>
            <p class="text-sm text-slate-600 mt-1">From zero to data analysis in 8 weeks.</p>
            <div class="mt-4 flex items-center justify-between">
              <span class="text-slate-500 text-sm">8 weeks</span>
              <a href="#" class="text-primary-600 font-semibold text-sm hover:underline">View syllabus →</a>
            </div>
          </div>
        </article>

        <!-- Card 3 -->
        <article class="border border-slate-200 rounded-2xl overflow-hidden hover:shadow-lg transition">
          <img class="h-48 w-full object-cover" src="https://source.unsplash.com/600x400/?science,lab" alt="Science">
          <div class="p-6">
            <span class="text-xs font-semibold text-primary-600 bg-primary-100 px-2 py-1 rounded">Secondary</span>
            <h3 class="mt-3 text-lg font-semibold">Physics in Daily Life</h3>
            <p class="text-sm text-slate-600 mt-1">Interactive experiments you can do at home.</p>
            <div class="mt-4 flex items-center justify-between">
              <span class="text-slate-500 text-sm">4 weeks</span>
              <a href="#" class="text-primary-600 font-semibold text-sm hover:underline">View syllabus →</a>
            </div>
          </div>
        </article>
      </div>

      <div class="text-center mt-10">
        <a href="#catalog" class="btn-outline">View all courses</a>
      </div>
    </div>
  </section>

  <!-- CATALOG -->
  <section id="catalog" class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center max-w-2xl mx-auto">
        <h2 class="text-3xl font-bold">Full Course Catalog</h2>
        <p class="mt-2 text-slate-600">Filter by level, subject, or duration and find your next class.</p>
      </div>

      <!-- Filters -->
      <div class="mt-8 flex flex-wrap gap-3 justify-center">
        <button class="btn-filter active">All</button>
        <button class="btn-filter">Primary</button>
        <button class="btn-filter">Secondary</button>
        <button class="btn-filter">Higher-Ed</button>
      </div>

      <!-- Table -->
      <div class="mt-10 overflow-auto rounded-2xl border border-slate-200 bg-white">
        <table class="w-full text-left text-sm">
          <thead class="bg-slate-100 text-slate-700">
            <tr>
              <th class="px-6 py-3 font-semibold">Course</th>
              <th class="px-6 py-3 font-semibold">Level</th>
              <th class="px-6 py-3 font-semibold">Duration</th>
              <th class="px-6 py-3 font-semibold">Enroll</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200">
            <tr>
              <td class="px-6 py-4 font-medium">English Grammar Basics</td>
              <td class="px-6 py-4"><span class="badge">Primary</span></td>
              <td class="px-6 py-4">5 weeks</td>
              <td class="px-6 py-4"><a href="#" class="text-primary-600 font-semibold hover:underline">Enroll →</a></td>
            </tr>
            <tr>
              <td class="px-6 py-4 font-medium">World History: 1900-Present</td>
              <td class="px-6 py-4"><span class="badge">Secondary</span></td>
              <td class="px-6 py-4">6 weeks</td>
              <td class="px-6 py-4"><a href="#" class="text-primary-600 font-semibold hover:underline">Enroll →</a></td>
            </tr>
            <tr>
              <td class="px-6 py-4 font-medium">Data Structures & Algorithms</td>
              <td class="px-6 py-4"><span class="badge">Higher-Ed</span></td>
              <td class="px-6 py-4">10 weeks</td>
              <td class="px-6 py-4"><a href="#" class="text-primary-600 font-semibold hover:underline">Enroll →</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- TESTIMONIALS -->
  <section id="testimonials" class="py-20 bg-gradient-to-br from-primary-300 to-white">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center max-w-2xl mx-auto">
        <h2 class="text-3xl font-bold">Loved by learners worldwide</h2>
        <p class="mt-2 text-slate-600">Real feedback from students, parents and educators.</p>
      </div>

      <div class="mt-12 grid md:grid-cols-3 gap-8">
        <!-- Card 1 -->
        <blockquote class="bg-primary-50 p-6 rounded-2xl border border-primary-100">
          <div class="flex text-primary-500">★★★★★</div>
          <p class="mt-3 text-slate-700 italic">
            “My 8-year-old actually asks to do math now. The game-style lessons are brilliant!”
          </p>
          <footer class="mt-4 text-sm text-slate-600">— Maria, parent</footer>
        </blockquote>

        <!-- Card 2 -->
        <blockquote class="bg-primary-50 p-6 rounded-2xl border border-primary-100">
          <div class="flex text-primary-500">★★★★★</div>
          <p class="mt-3 text-slate-700 italic">
            “I cleared my first Python interview thanks to the university-level course. concise and practical.”
          </p>
          <footer class="mt-4 text-sm text-slate-600">— Aisha, undergrad</footer>
        </blockquote>

        <!-- Card 3 -->
        <blockquote class="bg-primary-50 p-6 rounded-2xl border border-primary-100">
          <div class="flex text-primary-500">★★★★★</div>
          <p class="mt-3 text-slate-700 italic">
            “The detailed catalog lets me pick exactly what my classroom needs.”
          </p>
          <footer class="mt-4 text-sm text-slate-600">— Mr. Thompson, teacher</footer>
        </blockquote>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-slate-900 text-slate-300">
    <div class="max-w-7xl mx-auto px-6 py-12 flex flex-col md:flex-row justify-between items-center gap-4">
      <p class="text-sm">© 2025 EduHub. All rights reserved.</p>
      <ul class="flex gap-4 text-sm">
        <li><a href="#" class="hover:text-white transition">About</a></li>
        <li><a href="#" class="hover:text-white transition">Privacy</a></li>
        <li><a href="#" class="hover:text-white transition">Contact</a></li>
      </ul>
    </div>
  </footer>

  <!-- tiny JS for mobile menu -->
  <script>
    const btn = document.getElementById('menu-btn');
    const nav = btn.nextElementSibling; // ul in navbar
    btn.addEventListener('click', () => nav.classList.toggle('hidden'));
  </script>
</body>
</html>
