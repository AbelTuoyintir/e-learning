@extends('layouts.app')

@section('title', 'Edit Quiz')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                <i class="fas fa-pen-to-square text-lg"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Quiz: {{ $quiz->title }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">Update quiz details, scoring, and parameters</p>
            </div>
        </div>

        <a href="{{ route('quizzes.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold text-xs transition">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Quizzes</span>
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <form action="{{ route('quizzes.update', $quiz->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include('manage._form', ['submitText' => 'Update Quiz Settings'])
        </form>
    </div>

</div>
@endsection
