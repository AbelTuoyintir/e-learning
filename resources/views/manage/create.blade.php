@extends('layouts.app')

@section('title', 'Create Quiz')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900/80 backdrop-blur-md rounded-3xl p-6 sm:p-8 shadow-xl border border-white/10">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-500/20">
                <i class="fas fa-plus text-xl"></i>
            </div>
            <div>
                <h1 class="font-heading text-2xl font-extrabold text-white tracking-tight">Create New Quiz</h1>
                <p class="text-xs text-slate-400 mt-0.5">Set up assessment parameters, capacity limits, and options</p>
            </div>
        </div>

        <a href="{{ route('quizzes.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border border-white/10 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs transition">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Quizzes</span>
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-slate-900/80 backdrop-blur-md rounded-3xl p-6 sm:p-8 shadow-xl border border-white/10">
        <form action="{{ route('quizzes.store') }}" method="POST" enctype="multipart/form-data">
            @include('manage._form', ['submitText' => 'Save & Create Quiz'])
        </form>
    </div>

</div>
@endsection
