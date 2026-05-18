@extends('layouts.studentNavBar')

@section('content')
<div class="container mx-auto px-4 py-8 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300 ">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $course->title }} - Learning Materials</h1>
        <p class="text-gray-600">{{ $course->description ?? 'No description available' }}</p>
    </div>

    @php
        // Debug: Check what's available
        // dd($course->modules);
    @endphp

    @forelse($course->modules ?? [] as $module)
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="p-6 border-b">
                <h2 class="text-2xl font-semibold text-gray-800">
                    Module {{ $loop->iteration }}: {{ $module->title }}
                </h2>
                @if($module->description)
                    <p class="text-gray-600 mt-2">{{ $module->description }}</p>
                @endif
            </div>

            @forelse($module->topics ?? [] as $topic)
                <div class="p-6 border-b last:border-b-0">
                    <h3 class="text-lg font-medium text-gray-800 mb-3">
                        {{ $loop->iteration }}. {{ $topic->title }}
                    </h3>

                    @if($topic->file_path)
                        <div class="flex items-center p-3 bg-indigo-50 rounded-lg mb-3">
                            <i class="fas fa-file-alt text-indigo-500 mr-3"></i>
                            <div class="flex-grow">
                                <span class="font-medium text-gray-700">{{ $topic->file_name ?? 'Topic Document' }}</span>
                                <br>
                                <small class="text-gray-500">Document (view only)</small>
                            </div>
                            <a href="{{ route('students.course.topics.document.read', [$course->id, $topic->id]) }}"
                               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                        </div>
                    @endif

                    <!-- Text Contents -->
                    @foreach($topic->contents ?? [] as $content)
                        @if($content->type === 'text')
                            <div class="bg-gray-50 p-4 rounded-lg mb-3">
                                <p class="text-gray-700 whitespace-pre-line">{{ $content->body }}</p>
                            </div>
                        @endif
                    @endforeach

                    <!-- File Contents -->
                    @foreach($topic->contents ?? [] as $content)
                        @if(in_array($content->type, ['image', 'video', 'pdf']))
                            <div class="flex items-center p-3 bg-blue-50 rounded-lg mb-3">
                                @switch($content->type)
                                    @case('image')
                                        <i class="fas fa-image text-blue-500 mr-3"></i>
                                        @break
                                    @case('video')
                                        <i class="fas fa-video text-blue-500 mr-3"></i>
                                        @break
                                    @case('pdf')
                                        <i class="fas fa-file-pdf text-blue-500 mr-3"></i>
                                        @break
                                    @default
                                        <i class="fas fa-file text-blue-500 mr-3"></i>
                                @endswitch
                                <div class="flex-grow">
                                    <span class="font-medium text-gray-700">{{ $content->file_name ?? 'File' }}</span>
                                    <br>
                                    <small class="text-gray-500">{{ $content->type }} file (view only)</small>
                                </div>
                                <a href="{{ route('students.course.materials.read', [$course->id, $content->id]) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                            </div>
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
