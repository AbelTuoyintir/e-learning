<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>EduHub | Learn Without Limits</title>
    <!-- Google Font (optional) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

   @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tailwind config with extended colors -->
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
                },
                animation: {
                    'fade-up': 'fadeUp 0.6s ease-out',
                    'float': 'float 4s ease-in-out infinite',
                },
                keyframes: {
                    fadeUp: {
                        '0%': { opacity: '0', transform: 'translateY(20px)' },
                        '100%': { opacity: '1', transform: 'translateY(0)' },
                    },
                    float: {
                        '0%, 100%': { transform: 'translateY(0px)' },
                        '50%': { transform: 'translateY(-10px)' },
                    }
                }
            }
        }
    }
    </script>

    <style>
        /* custom utility overrides / smoothness */
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
        .btn-primary { @apply inline-flex items-center justify-center px-5 py-2.5 bg-primary-600 text-white font-semibold rounded-xl shadow-md hover:bg-primary-700 transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5; }
        .btn-outline  { @apply inline-flex items-center justify-center px-5 py-2.5 border-2 border-primary-600 text-primary-600 font-semibold rounded-xl hover:bg-primary-50 transition-all duration-200; }
        .btn-filter   { @apply px-4 py-2 rounded-full text-sm font-medium border border-slate-300 text-slate-700 hover:bg-primary-100 hover:border-primary-300 transition-all; }
        .btn-filter.active { @apply bg-primary-600 text-white border-primary-600 shadow-sm; }
        .badge        { @apply px-2.5 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-700; }
        
        /* Hide scrollbar for cleaner look but keep functionality */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #3b82f6; border-radius: 10px; }
    </style>
</head>
<body class="bg-white text-slate-800">

  <!-- NAVBAR -->
  <header class="sticky top-0 z-50 bg-white/85 backdrop-blur-md border-b border-slate-200 shadow-sm">
    <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <!-- Logo -->
      <a href="#" class="flex items-center gap-2 text-primary-600 font-extrabold text-2xl tracking-tight">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5-10-5-10 5z"/></svg>
        <span class="bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent">EduHub</span>
      </a>

      <!-- Links -->
      <ul class="hidden md:flex items-center gap-7 text-sm font-semibold text-slate-700">
        <li><a href="#courses" class="hover:text-primary-600 transition duration-200">Courses</a></li>
        <li><a href="#catalog" class="hover:text-primary-600 transition duration-200">Catalog</a></li>
        <li><a href="#testimonials" class="hover:text-primary-600 transition duration-200">Testimonials</a></li>
        <li><a href="{{ route('login') }}" class="btn-primary bg-primary-600 text-white rounded-xl px-5 py-2 shadow-md hover:shadow-lg">Sign In</a></li>
      </ul>

      <!-- Mobile burger -->
      <button id="menu-btn" class="md:hidden p-2 rounded-md hover:bg-slate-100 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
             d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </nav>
  </header>

  <!-- HERO with REAL IMAGE OF BLACK STUDENTS LEARNING (Authentic representation) -->
  <section class="bg-gradient-to-br from-primary-50 via-white to-blue-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 py-16 md:py-24 grid md:grid-cols-2 gap-12 items-center">
      <div class="animate-fade-up">
        <div class="inline-flex items-center gap-2 bg-primary-100 text-primary-700 px-4 py-1.5 rounded-full text-sm font-semibold mb-4">
          <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-600"></span>
          </span>
          Empowering Black Excellence
        </div>
        <h1 class="text-4xl md:text-6xl font-extrabold leading-tight tracking-tight">
          Learn without limits <br>
          <span class="text-primary-600">at any age.</span>
        </h1>
        <p class="mt-5 text-slate-600 text-lg max-w-lg leading-relaxed">
          From primary school basics to university-level mastery, EduHub delivers 
          interactive lessons that fit every learner’s journey — designed for today's scholars.
        </p>
        <div class="mt-10 flex flex-wrap gap-4">
          <a href="#catalog" class="btn-primary text-base px-7 py-3">Explore Catalog</a>
          <a href="#" class="btn-outline text-base px-7 py-3">Start Free Trial</a>
        </div>
        <div class="flex items-center gap-4 mt-8 text-sm text-slate-500">
          <div class="flex -space-x-2">
            <div class="w-8 h-8 rounded-full bg-primary-200 border-2 border-white flex items-center justify-center text-[10px] font-bold">👩🏿</div>
            <div class="w-8 h-8 rounded-full bg-primary-300 border-2 border-white flex items-center justify-center text-[10px] font-bold">🧑🏿</div>
            <div class="w-8 h-8 rounded-full bg-primary-400 border-2 border-white flex items-center justify-center text-[10px] font-bold text-white">+2k</div>
          </div>
          <span>2,500+ Black scholars active this month</span>
        </div>
      </div>

      <!-- AUTHENTIC IMAGE: Black students collaborating & learning with tech -->
      <div class="relative animate-fade-up" style="animation-delay: 0.1s;">
        <div class="relative rounded-2xl shadow-2xl overflow-hidden border-4 border-white/50 ring-1 ring-primary-200">
          <img 
            class="w-full h-auto object-cover transform transition duration-700 hover:scale-105" 
            src="https://images.unsplash.com/photo-1606761568499-6d2451b23c66?ixlib=rb-4.0.3&auto=format&fit=crop&w=1974&q=80" 
            alt="Black students learning together with tablets and laptop - collaborative education"
            loading="eager"
          >
        </div>
        <!-- floating badge -->
        <div class="absolute -bottom-5 -left-5 bg-white rounded-xl shadow-xl p-3 flex items-center gap-3 backdrop-blur-sm">
          <div class="bg-primary-100 p-2 rounded-full">
            <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 15h-2v-2h2zm0-4h-2V7h2z"/></svg>
          </div>
          <div>
            <p class="font-bold text-sm">96% success rate</p>
            <p class="text-xs text-slate-500">among active learners</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- COURSES SECTION -->
  <section id="courses" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center max-w-2xl mx-auto">
        <span class="text-primary-600 font-semibold text-sm uppercase tracking-wider">Our offerings</span>
        <h2 class="text-3xl md:text-4xl font-bold mt-2">Featured Courses</h2>
        <p class="mt-3 text-slate-600 text-lg">Hand-picked for basic-school & higher-ed learners, taught by passionate educators.</p>
      </div>

      <div class="mt-14 grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Card 1 - Math -->
        <div class="group border border-slate-200 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 bg-white">
          <img class="h-52 w-full object-cover" src="https://images.unsplash.com/photo-1635070041078-e363dbe005cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Math learning for kids">
          <div class="p-6">
            <span class="text-xs font-bold text-primary-600 bg-primary-100 px-3 py-1.5 rounded-full">Primary</span>
            <h3 class="mt-3 text-xl font-bold">Fun with Mathematics</h3>
            <p class="text-slate-600 mt-2">Build confidence through games and real-world problem-solving for young minds.</p>
            <div class="mt-5 flex items-center justify-between border-t pt-4 border-slate-100">
              <span class="text-slate-500 text-sm"><i class="far fa-clock mr-1"></i> 6 weeks</span>
              <a href="#" class="text-primary-600 font-semibold text-sm hover:underline">View syllabus →</a>
            </div>
          </div>
        </div>

        <!-- Card 2 - Python -->
        <div class="group border border-slate-200 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 bg-white">
          <img class="h-52 w-full object-cover" src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Diverse students coding">
          <div class="p-6">
            <span class="text-xs font-bold text-primary-600 bg-primary-100 px-3 py-1.5 rounded-full">Higher-Ed</span>
            <h3 class="mt-3 text-xl font-bold">Intro to Python</h3>
            <p class="text-slate-600 mt-2">From zero to data analysis in 8 weeks. Hands-on projects with real-world datasets.</p>
            <div class="mt-5 flex items-center justify-between border-t pt-4 border-slate-100">
              <span class="text-slate-500 text-sm"><i class="far fa-clock mr-1"></i> 8 weeks</span>
              <a href="#" class="text-primary-600 font-semibold text-sm hover:underline">View syllabus →</a>
            </div>
          </div>
        </div>

        <!-- Card 3 - Physics -->
        <div class="group border border-slate-200 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 bg-white">
          <img class="h-52 w-full object-cover" src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Black students science experiment">
          <div class="p-6">
            <span class="text-xs font-bold text-primary-600 bg-primary-100 px-3 py-1.5 rounded-full">Secondary</span>
            <h3 class="mt-3 text-xl font-bold">Physics in Daily Life</h3>
            <p class="text-slate-600 mt-2">Interactive experiments and real-life applications you can do at home or lab.</p>
            <div class="mt-5 flex items-center justify-between border-t pt-4 border-slate-100">
              <span class="text-slate-500 text-sm"><i class="far fa-clock mr-1"></i> 4 weeks</span>
              <a href="#" class="text-primary-600 font-semibold text-sm hover:underline">View syllabus →</a>
            </div>
          </div>
        </div>
      </div>

      <div class="text-center mt-12">
        <a href="#catalog" class="btn-outline text-primary-700 border-primary-500 hover:bg-primary-50">Browse all courses →</a>
      </div>
    </div>
  </section>

  <!-- FULL COURSE CATALOG -->
  <section id="catalog" class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center max-w-2xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold">Full Course Catalog</h2>
        <p class="mt-3 text-slate-600 text-lg">Filter by level, subject, or duration and find your next class.</p>
      </div>

      <!-- Filters -->
      <div class="mt-10 flex flex-wrap gap-3 justify-center">
        <button class="btn-filter active">All</button>
        <button class="btn-filter">Primary</button>
        <button class="btn-filter">Secondary</button>
        <button class="btn-filter">Higher-Ed</button>
      </div>

      <!-- Table with modern design -->
      <div class="mt-10 overflow-auto rounded-2xl border border-slate-200 bg-white shadow-md">
        <table class="w-full text-left text-sm">
          <thead class="bg-gradient-to-r from-slate-100 to-slate-50 text-slate-700">
            <tr>
              <th class="px-6 py-4 font-semibold text-base">Course</th>
              <th class="px-6 py-4 font-semibold text-base">Level</th>
              <th class="px-6 py-4 font-semibold text-base">Duration</th>
              <th class="px-6 py-4 font-semibold text-base">Enroll</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200">
            <tr class="hover:bg-primary-50/40 transition">
              <td class="px-6 py-4 font-medium">English Grammar & Composition</td>
              <td class="px-6 py-4"><span class="badge bg-primary-100">Primary</span></td>
              <td class="px-6 py-4">5 weeks</td>
              <td class="px-6 py-4"><a href="#" class="text-primary-600 font-semibold hover:underline flex items-center gap-1">Enroll →</a></td>
            </tr>
            <tr class="hover:bg-primary-50/40 transition">
              <td class="px-6 py-4 font-medium">World History: 1900-Present</td>
              <td class="px-6 py-4"><span class="badge bg-primary-100">Secondary</span></td>
              <td class="px-6 py-4">6 weeks</td>
              <td class="px-6 py-4"><a href="#" class="text-primary-600 font-semibold hover:underline">Enroll →</a></td>
            </tr>
            <tr class="hover:bg-primary-50/40 transition">
              <td class="px-6 py-4 font-medium">Data Structures & Algorithms</td>
              <td class="px-6 py-4"><span class="badge bg-primary-100">Higher-Ed</span></td>
              <td class="px-6 py-4">10 weeks</td>
              <td class="px-6 py-4"><a href="#" class="text-primary-600 font-semibold hover:underline">Enroll →</a></td>
            </tr>
            <tr class="hover:bg-primary-50/40 transition">
              <td class="px-6 py-4 font-medium">Creative Writing Workshop</td>
              <td class="px-6 py-4"><span class="badge bg-primary-100">Secondary</span></td>
              <td class="px-6 py-4">4 weeks</td>
              <td class="px-6 py-4"><a href="#" class="text-primary-600 font-semibold hover:underline">Enroll →</a></td>
            </tr>
            <tr class="hover:bg-primary-50/40 transition">
              <td class="px-6 py-4 font-medium">Introduction to AI & Machine Learning</td>
              <td class="px-6 py-4"><span class="badge bg-primary-100">Higher-Ed</span></td>
              <td class="px-6 py-4">12 weeks</td>
              <td class="px-6 py-4"><a href="#" class="text-primary-600 font-semibold hover:underline">Enroll →</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- TESTIMONIALS SECTION with diverse voices including Black educators & students -->
  <section id="testimonials" class="py-24 bg-gradient-to-br from-primary-50 via-white to-indigo-50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center max-w-2xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold">Loved by learners worldwide</h2>
        <p class="mt-3 text-slate-600 text-lg">Real feedback from Black students, parents, and educators shaping the future.</p>
      </div>

      <div class="mt-14 grid md:grid-cols-3 gap-8">
        <!-- Testimonial 1 -->
        <div class="bg-white p-7 rounded-2xl shadow-lg border border-primary-100 hover:shadow-xl transition">
          <div class="flex text-yellow-400 text-lg">★★★★★</div>
          <p class="mt-4 text-slate-700 leading-relaxed">
            “My 8-year-old actually asks to do math now. The culturally relevant examples and game-style lessons are brilliant!”
          </p>
          <footer class="mt-5 flex items-center gap-3 border-t pt-4">
            <div class="w-10 h-10 rounded-full bg-primary-200 flex items-center justify-center font-bold text-primary-800">MP</div>
            <div>
              <p class="font-bold text-slate-800">— Maria P.</p>
              <p class="text-xs text-slate-500">Parent & Advocate</p>
            </div>
          </footer>
        </div>

        <!-- Testimonial 2 -->
        <div class="bg-white p-7 rounded-2xl shadow-lg border border-primary-100 hover:shadow-xl transition">
          <div class="flex text-yellow-400 text-lg">★★★★★</div>
          <p class="mt-4 text-slate-700 leading-relaxed">
            “I cleared my first Python interview thanks to the university-level course. Concise, practical, and the community support is unmatched.”
          </p>
          <footer class="mt-5 flex items-center gap-3 border-t pt-4">
            <div class="w-10 h-10 rounded-full bg-primary-300 flex items-center justify-center font-bold text-white">AD</div>
            <div>
              <p class="font-bold text-slate-800">— Aisha D.</p>
              <p class="text-xs text-slate-500">Computer Science undergrad</p>
            </div>
          </footer>
        </div>

        <!-- Testimonial 3 -->
        <div class="bg-white p-7 rounded-2xl shadow-lg border border-primary-100 hover:shadow-xl transition">
          <div class="flex text-yellow-400 text-lg">★★★★★</div>
          <p class="mt-4 text-slate-700 leading-relaxed">
            “The detailed catalog lets me pick exactly what my classroom needs. It’s inclusive, up-to-date, and my students stay engaged.”
          </p>
          <footer class="mt-5 flex items-center gap-3 border-t pt-4">
            <div class="w-10 h-10 rounded-full bg-primary-400 flex items-center justify-center font-bold text-white">JT</div>
            <div>
              <p class="font-bold text-slate-800">— James T.</p>
              <p class="text-xs text-slate-500">High School Educator</p>
            </div>
          </footer>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-slate-900 text-slate-300">
    <div class="max-w-7xl mx-auto px-6 py-12 flex flex-col md:flex-row justify-between items-center gap-4">
      <div class="flex items-center gap-2">
        <svg class="w-6 h-6 text-primary-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5-10-5-10 5z"/></svg>
        <p class="text-sm">© 2025 EduHub — Empowering Black Excellence in Education.</p>
      </div>
      <ul class="flex gap-6 text-sm">
        <li><a href="{{ route('about') }}" class="hover:text-white transition">About</a></li>
        <li><a href="{{ route('privacy') }}" class="hover:text-white transition">Privacy</a></li>
        <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a></li>
        <li><a href="{{ route('accessibility') }}" class="hover:text-white transition">Accessibility</a></li>
      </ul>
    </div>
  </footer>

  <!-- Mobile menu script -->
  <script>
    const btn = document.getElementById('menu-btn');
    const navMenu = document.querySelector('header nav ul');
    if (btn && navMenu) {
      btn.addEventListener('click', () => {
        navMenu.classList.toggle('hidden');
        navMenu.classList.toggle('flex');
        navMenu.classList.toggle('flex-col');
        navMenu.classList.toggle('absolute');
        navMenu.classList.toggle('top-full');
        navMenu.classList.toggle('left-0');
        navMenu.classList.toggle('w-full');
        navMenu.classList.toggle('bg-white');
        navMenu.classList.toggle('p-6');
        navMenu.classList.toggle('shadow-xl');
        navMenu.classList.toggle('gap-4');
      });
    }

    // Basic active filter demo (visual only)
    const filterBtns = document.querySelectorAll('.btn-filter');
    filterBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        filterBtns.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        // In a real app you would filter the table; here just demo UI enhancement.
      });
    });
  </script>
</body>
</html>