@extends('layouts.app')

@section('title', 'Create Quiz')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-panel rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-800/80">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-purple-600 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/30 shrink-0">
                <i class="fas fa-plus text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-heading">Create New Quiz</h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Set up assessment parameters, duration, passing grade, and question limits</p>
            </div>
        </div>

        <a href="{{ route('quizzes.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border border-slate-700/80 bg-slate-800/80 hover:bg-slate-800 text-slate-300 hover:text-white font-bold text-xs transition">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Quizzes</span>
        </a>
    </div>

    <!-- Form Container -->
    <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-800/80">
        <form action="{{ route('quizzes.store') }}" method="POST" enctype="multipart/form-data">
            @include('manage._form', ['submitText' => 'Save & Create Quiz'])
        </form>
    </div>

</div>
@endsection
