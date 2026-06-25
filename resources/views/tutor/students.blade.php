@extends('layouts.app')

@section('title', 'Course Students')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Students Enrolled in: {{ $course->title }}</h1>
            <p class="text-gray-600 mt-1">Manage and view performance of your students.</p>
        </div>
        <a href="{{ route('tutor.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-600 text-sm font-medium">
                    <tr>
                        <th class="px-6 py-4">Student Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Enrolled At</th>
                        <th class="px-6 py-4">Payment Status</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($enrollments as $enrollment)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($enrollment->student->firstname) }}&background=random" class="w-8 h-8 rounded-full mr-3">
                                <span class="font-medium text-slate-800">{{ $enrollment->student->firstname }} {{ $enrollment->student->lastname }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $enrollment->student->email }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $enrollment->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $enrollment->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($enrollment->payment_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('tutor.student.performance', [$course->id, $enrollment->student->id]) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                <i class="fas fa-chart-line mr-1"></i> Performance
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                            No students enrolled in this course yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($enrollments->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $enrollments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
