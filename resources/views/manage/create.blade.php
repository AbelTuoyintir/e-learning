@extends('layouts.app')

@section('title', 'Create Quiz')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-500/30">
                <i class="fas fa-plus text-lg"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-white tracking-tight font-heading">Create New Quiz</h1>
                <p class="text-xs text-slate-400 mt-0.5">Set up assessment parameters, limits, time per question, and scoring rules</p>
            </div>
        </div>

        <a href="{{ route('quizzes.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border border-slate-700/80 bg-slate-800/80 hover:bg-slate-800 text-slate-300 hover:text-white font-bold text-xs transition">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Quizzes</span>
        </a>
    </div>

    <!-- Form Container -->
    <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
        <form action="{{ route('quizzes.store') }}" method="POST" enctype="multipart/form-data">
            @include('manage._form', ['submitText' => 'Save & Create Quiz'])
        </form>
    </div>

</div>
@endsection
