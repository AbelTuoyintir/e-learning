@extends('layouts.studentNavBar')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-slate-800">Course Checkout</h1>
            <p class="text-slate-500 mt-1">Review your course and complete purchase.</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">{{ $course->title }}</h2>
                    <p class="text-slate-600 mt-2">{{ $course->description ?? 'No description provided.' }}</p>
                    <div class="mt-4 space-y-1 text-sm text-slate-600">
                        <p><span class="font-semibold">Category:</span> {{ $course->category ?? 'General' }}</p>
                        <p><span class="font-semibold">Instructor:</span> {{ $course->instructor ?? 'TBA' }}</p>
                        <p><span class="font-semibold">Duration:</span> {{ $course->duration ?? 'N/A' }} hour(s)</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-slate-500">Total</p>
                    <p class="text-3xl font-bold text-emerald-600">GHS {{ number_format((float) $course->price, 2) }}</p>
                </div>
            </div>

            <div class="mt-6 rounded-xl bg-amber-50 border border-amber-200 p-4 text-sm text-amber-800">
                This checkout currently records payment in-app and enrolls you immediately.
            </div>

            <div class="mt-6 flex flex-wrap items-center gap-3">
                <form action="{{ route('students.courses.purchase', $course->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition">
                        <i class="fas fa-lock mr-2"></i>Pay with Paystack
                    </button>
                </form>

                <a href="{{ route('students.courses') }}"
                   class="px-6 py-3 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-100 font-semibold transition">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
