@extends('layouts.app')

@section('title', 'Edit Quiz')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-purple-500 via-indigo-600 to-indigo-700 text-white flex items-center justify-center font-bold shadow-lg shadow-purple-500/30">
                <i class="fas fa-pen-to-square text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-heading">Edit Quiz: {{ $quiz->title }}</h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Update assessment details, scoring thresholds, and parameters</p>
            </div>
        </div>

        <a href="{{ route('quizzes.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border border-slate-700/80 bg-slate-800/80 hover:bg-slate-800 text-slate-300 font-bold text-xs transition">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Quizzes</span>
        </a>
    </div>

    <!-- Form Container -->
    <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
        <form action="{{ route('quizzes.update', $quiz->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include('manage._form', ['submitText' => 'Update Quiz Settings'])
        </form>
    </div>

</div>
@endsection
