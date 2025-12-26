@extends('layouts.studentNavBar')

@section('title', 'Course Materials')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">{{ $course->title }} - Course Materials</h1>

        @if($course->modules->count() > 0)
            @foreach($course->modules as $module)
                <div class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-4">{{ $module->title }}</h2>

                    @if($module->topics->count() > 0)
                        @foreach($module->topics as $topic)
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <h3 class="text-xl font-medium text-gray-800 mb-2">{{ $topic->title }}</h3>
                                <p class="text-gray-600 mb-3">{{ $topic->description }}</p>

                                @if($topic->contents->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($topic->contents as $content)
                                            <div class="flex items-center justify-between bg-white p-3 rounded border">
                                                <div>
                                                    <span class="font-medium">{{ $content->title }}</span>
                                                    <span class="text-sm text-gray-500 ml-2">({{ $content->content_type }})</span>
                                                </div>
                                                <a href="{{ $content->content_url }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                                                    View Content
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500">No content available for this topic.</p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500">No topics available for this module.</p>
                    @endif
                </div>
            @endforeach
        @else
            <div class="text-center py-10">
                <p class="text-gray-500 text-lg">No modules available for this course yet.</p>
            </div>
        @endif

        <div class="mt-8">
            <a href="{{ route('students.enrolledcourses') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg">
                Back to Enrolled Courses
            </a>
        </div>
    </div>
</div>
@endsection
