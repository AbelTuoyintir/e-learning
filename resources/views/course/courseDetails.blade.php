@extends('layouts.studentNavBar')   {{-- your student layout --}}
@section('title', $course->title)
@section('content')

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-50">

  <!-- Hero Section -->
  <section class="relative h-80 md:h-96 bg-slate-800 overflow-hidden">
    {{-- Background image --}}
    @if($course->image)
    <img src="{{ Storage::url($course->image) }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-30">
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 to-transparent"></div>

    <div class="relative max-w-5xl mx-auto px-6 h-full flex flex-col justify-end pb-10 text-white">
      <div class="flex items-start gap-4">
        {{-- Icon / Logo --}}
        <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur grid place-items-center text-3xl">
          <i class="fas fa-graduation-cap text-white"></i>
        </div>
        <div class="flex-1">
          <h1 class="text-4xl font-extrabold tracking-tight">{{ $course->title }}</h1>
          <p class="mt-2 text-slate-200">By {{ $course->instructor ?? 'Admin' }} • {{ $course->duration }} hours • {{ $course->category }}</p>
        </div>
      </div>

      {{-- CTA --}}
      <div class="mt-6 flex items-center gap-4">
        <button class="px-6 py-3 bg-indigo-600 text-white rounded-xl shadow-lg hover:bg-indigo-700 transition transform hover:-translate-y-0.5">
          <i class="fas fa-play mr-2"></i>Enroll Now
        </button>
        <button class="px-6 py-3 bg-white/20 backdrop-blur text-white rounded-xl hover:bg-white/30 transition">
          <i class="fas fa-bookmark mr-2"></i>Save
        </button>
      </div>
    </div>
  </section>

  <!-- Tabs -->
  <div class="max-w-5xl mx-auto px-6 py-8" x-data="{ tab: 'overview' }">

    <!-- Tab Buttons -->
    <div class="flex items-center gap-4 border-b border-slate-200 mb-8">
      <button @click="tab = 'overview'" :class="tab==='overview' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'" class="pb-3 px-1 border-b-2 font-medium transition">
        Overview
      </button>
      <button @click="tab = 'modules'" :class="tab==='modules' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'" class="pb-3 px-1 border-b-2 font-medium transition">
        Modules & Topics
      </button>
      <button @click="tab = 'reviews'" :class="tab==='reviews' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'" class="pb-3 px-1 border-b-2 font-medium transition">
        Reviews
      </button>
    </div>

    <!-- Tab Panels -->
    <div class="space-y-10">

      <!-- Overview -->
      <div x-show="tab==='overview'" class="prose prose-slate max-w-none">
        <h2 class="text-2xl font-bold text-slate-800 mb-4">What you'll learn</h2>
        <div class="bg-white rounded-2xl shadow border border-slate-100 p-6">
          {!! nl2br(e($course->description)) !!}
        </div>

        <h3 class="text-xl font-bold text-slate-800 mt-8 mb-4">Key Features</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-100">
            <i class="fas fa-check-circle text-green-500 mt-1"></i>
            <div>
              <h4 class="font-semibold text-slate-800">Self-paced Learning</h4>
              <p class="text-sm text-slate-500">Learn at your own speed with lifetime access</p>
            </div>
          </div>
          <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-100">
            <i class="fas fa-certificate text-indigo-500 mt-1"></i>
            <div>
              <h4 class="font-semibold text-slate-800">Certificate of Completion</h4>
              <p class="text-sm text-slate-500">Earn a certificate after finishing all modules</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Modules & Topics -->
      <div x-show="tab==='modules'">
        <h2 class="text-2xl font-bold text-slate-800 mb-6">Course Modules</h2>

        @forelse($course->modules as $module)
        <div x-data="{ open: false }" class="mb-4 bg-white rounded-2xl shadow border border-slate-100 overflow-hidden">
          <!-- Module Header -->
          <button @click="open = !open" class="w-full flex items-center justify-between p-5 text-left hover:bg-slate-50 transition">
            <div class="flex items-center gap-4">
              <span class="w-10 h-10 grid place-items-center rounded-xl bg-indigo-100 text-indigo-600 font-bold">{{ $loop->iteration }}</span>
              <div>
                <h3 class="font-semibold text-slate-800">{{ $module->title }}</h3>
                <p class="text-sm text-slate-500">{{ $module->duration_minutes }} min • {{ $module->topics->count() }} topics</p>
              </div>
            </div>
            <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas text-slate-400"></i>
          </button>

          <!-- Topics Accordion -->
          <div x-show="open" x-collapse class="border-t border-slate-100">
            <div class="p-5 space-y-3">
              @forelse($module->topics as $topic)
              <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
                <i class="fas fa-play-circle text-indigo-500"></i>
                <span class="text-sm text-slate-700">{{ $topic->title }}</span>
                <span class="ml-auto text-xs text-slate-500">{{ $topic->duration_minutes ?? '--' }} min</span>
              </div>
              @empty
              <p class="text-sm text-slate-500">No topics added yet.</p>
              @endforelse
            </div>
          </div>
        </div>
        @empty
        <div class="text-center py-10 text-slate-500">
          <i class="fas fa-layer-group text-4xl mb-3"></i>
          <p>No modules published yet.</p>
        </div>
        @endforelse
      </div>

      <!-- Reviews -->
      <div x-show="tab==='reviews'">
        <h2 class="text-2xl font-bold text-slate-800 mb-6">Student Reviews</h2>
        <div class="space-y-4">
          @for($i=0;$i<3;$i++)
          <div class="bg-white rounded-2xl shadow border border-slate-100 p-5">
            <div class="flex items-center gap-3 mb-3">
              <img src="https://i.pravatar.cc/40?img={{ $i+5 }}" alt="" class="w-10 h-10 rounded-full">
              <div>
                <div class="font-semibold text-slate-800">Student {{ $i+1 }}</div>
                <div class="flex text-yellow-400 text-xs">
                  @for($j=0;$j<5;$j++) <i class="fas fa-star"></i> @endfor
                </div>
              </div>
            </div>
            <p class="text-sm text-slate-600">Great course! Very well structured and easy to follow.</p>
          </div>
          @endfor
        </div>
      </div>

    </div>
  </div>

</div>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
  // optional collapse helper
  document.addEventListener('alpine:init', () => {
    Alpine.directive('collapse', el => {
      let expanded = false;
      el.style.display = 'none';
      Alpine.effect(() => {
        if (expanded) Alpine.transition(el, () => { el.style.display = 'block'; }, 'collapse');
        else Alpine.transition(el, () => { el.style.display = 'none'; }, 'collapse');
      });
      Alpine.mutateDom(() => expanded = el._x_show);
    });
  });
</script>
@endsection
