@extends('layouts.studentNavBar')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-gray-950 pb-20 transition-colors duration-300">
    <!-- Immersive Header -->
    <div class="bg-white dark:bg-gray-900 border-b dark:border-gray-800 shadow-sm mb-8">
        <div class="container mx-auto px-6 py-10">
            <nav class="flex mb-4 text-sm text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li><a href="{{ route('students.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a></li>
                    <li><i class="fas fa-chevron-right text-[10px] mx-2"></i></li>
                    <li><a href="{{ route('students.enrolledcourses') }}" class="hover:text-blue-600 transition">My Courses</a></li>
                    <li><i class="fas fa-chevron-right text-[10px] mx-2"></i></li>
                    <li class="font-semibold text-gray-800 dark:text-gray-200">{{ $course->title }}</li>
                </ol>
            </nav>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white leading-tight mb-3">
                        {{ $course->title }}
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl">
                        {{ $course->description ?? 'Master this course through our structured learning path.' }}
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="bg-blue-50 dark:bg-blue-900/30 px-4 py-2 rounded-xl border border-blue-100 dark:border-blue-800">
                        <span class="block text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Your Progress</span>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $course->progress ?? 0 }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6">

    @php
        // Debug: Check what's available
        // dd($course->modules);
    @endphp

    <div class="space-y-8">
    @forelse($course->modules ?? [] as $module)
        @php
            $isLocked = isset($lockedModuleIds) && in_array((int)$module->id, (array)$lockedModuleIds, true);
        @endphp

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden transition hover:shadow-md {{ $isLocked ? 'grayscale' : '' }}">
            <div class="p-8 border-b dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30 flex items-start justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-blue-600 text-white text-xs font-bold rounded-lg uppercase">Module {{ $loop->iteration }}</span>
                        @if($isLocked)
                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 text-xs font-bold border border-amber-100 dark:border-amber-800">
                                <i class="fas fa-lock mr-2"></i> Locked
                            </span>
                        @endif
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $module->title }}
                    </h2>
                    @if($module->description)
                        <p class="text-gray-600 dark:text-gray-400 mt-3">{{ $module->description }}</p>
                    @endif
                </div>
            </div>

            <div class="{{ $isLocked ? 'pointer-events-none' : '' }}">
            @forelse($module->topics ?? [] as $topic)
                <div class="p-8 border-b dark:border-gray-800 last:border-b-0 hover:bg-slate-50/50 dark:hover:bg-gray-800/20 transition group">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 group-hover:text-blue-600 transition flex items-center">
                            <span class="w-8 h-8 flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded-lg text-sm text-gray-500 mr-4">{{ $loop->iteration }}</span>
                            {{ $topic->title }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ml-12">
                    @if($topic->file_path)
                        <a href="{{ route('students.course.topics.document.read', [$course->id, $topic->id]) }}"
                           class="flex items-center p-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl hover:border-blue-300 dark:hover:border-blue-800 hover:shadow-sm transition">
                            <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center text-indigo-600 dark:text-indigo-400 mr-4">
                                <i class="fas fa-file-alt text-xl"></i>
                            </div>
                            <div class="flex-grow">
                                <span class="font-bold text-gray-800 dark:text-gray-200 block">{{ $topic->file_name ?? 'Topic Document' }}</span>
                                <small class="text-gray-500 uppercase text-[10px] font-bold">Document • View Only</small>
                            </div>
                            <i class="fas fa-chevron-right text-gray-300 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    @endif

                    <!-- Contents -->
                    @foreach($topic->contents ?? [] as $content)
                        @if($content->type === 'text')
                            <div class="col-span-full bg-slate-50 dark:bg-gray-800/50 p-5 rounded-xl border dark:border-gray-800 mb-2">
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed">{{ $content->body }}</p>
                            </div>
                        @elseif(in_array($content->type, ['image', 'video', 'pdf']))
                            <a href="{{ route('students.course.materials.read', [$course->id, $content->id]) }}"
                               class="flex items-center p-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl hover:border-blue-300 dark:hover:border-blue-800 hover:shadow-sm transition">
                                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center text-blue-600 dark:text-blue-400 mr-4">
                                    @switch($content->type)
                                        @case('image') <i class="fas fa-image text-xl"></i> @break
                                        @case('video') <i class="fas fa-play-circle text-xl"></i> @break
                                        @case('pdf') <i class="fas fa-file-pdf text-xl"></i> @break
                                        @default <i class="fas fa-file text-xl"></i>
                                    @endswitch
                                </div>
                                <div class="flex-grow">
                                    <span class="font-bold text-gray-800 dark:text-gray-200 block">{{ $content->file_name ?? 'Module Resource' }}</span>
                                    <small class="text-gray-500 uppercase text-[10px] font-bold">{{ $content->type }} • View Only</small>
                                </div>
                                <i class="fas fa-chevron-right text-gray-300 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        @endif
                    @endforeach

                    <!-- Video URL -->
                    @if($topic->video_url)
                        <div class="mb-3">
                            <h5 class="font-medium text-gray-700 mb-2">Video Content:</h5>
                            @if($topic->youtube_embed_url)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="relative w-full overflow-hidden rounded-lg bg-black" style="padding-top:56.25%;">
                                        <iframe
                                            src="{{ $topic->youtube_embed_url }}"
                                            title="YouTube video player"
                                            class="absolute inset-0 h-full w-full"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            referrerpolicy="strict-origin-when-cross-origin"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                    <a href="{{ $topic->video_url }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="mt-3 inline-flex items-center text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                        <i class="fas fa-external-link-alt mr-2"></i>
                                        Open on YouTube
                                    </a>
                                </div>
                            @else
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <a href="{{ $topic->video_url }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center">
                                        <i class="fas fa-external-link-alt mr-2"></i>
                                        Watch Video (External Link)
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    No topics in this module
                </div>
            @endforelse
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-book-open text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-xl font-medium text-gray-700 mb-2">No Modules Available</h3>
            <p class="text-gray-500">This course doesn't have any modules yet.</p>
        </div>
    @endforelse

    <div class="mt-8">
        <a href="{{ url()->previous() }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg inline-flex items-center transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Go Back
        </a>
    </div>
</div>
@endsection
