@extends('layouts.app')

@section('title', 'Student Performance')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Performance: {{ $student->firstname }} {{ $student->lastname }}</h1>
            <p class="text-gray-600 mt-1">Course: {{ $course->title }}</p>
        </div>
        <a href="{{ route('tutor.course.students', $course->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Student List
        </a>
    </div>

    <!-- Student Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <p class="text-slate-500 text-sm font-medium">Quizzes Completed</p>
            <p class="text-3xl font-bold text-slate-800">{{ $results->count() }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <p class="text-slate-500 text-sm font-medium">Average Score</p>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($results->avg('percentage'), 1) }}%</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <p class="text-slate-500 text-sm font-medium">Highest Score</p>
            <p class="text-3xl font-bold text-green-600">{{ number_format($results->max('percentage'), 1) }}%</p>
        </div>
    </div>

    <!-- Assessment Results -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-xl font-bold text-slate-800">Assessment History</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-600 text-sm font-medium">
                    <tr>
                        <th class="px-6 py-4">Quiz Title</th>
                        <th class="px-6 py-4">Score</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date Taken</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($results as $result)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $result->quiz->title }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <span class="font-bold {{ $result->passed ? 'text-green-600' : 'text-red-600' }}">{{ number_format($result->percentage, 1) }}%</span>
                                <span class="text-xs text-slate-400 ml-2">({{ $result->score }}/{{ $result->total_possible_points }})</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $result->passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $result->passed ? 'Passed' : 'Failed' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $result->completed_at->format('M d, Y h:i A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">
                            No assessment data available for this student.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
