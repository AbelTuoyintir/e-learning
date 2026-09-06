@extends('layouts.app')

@section('title', 'Manage Questions')

@section('content')
<div class="space-y-8">

    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
        <div>
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-600 text-white flex items-center justify-center font-bold shadow-lg shadow-amber-500/30">
                    <i class="fas fa-cubes text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-heading">Question Bank</h1>
                    <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Quiz: <span class="font-bold text-indigo-400">{{ $quiz->title }}</span></p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <a href="{{ route('quizzes.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border border-slate-700/80 bg-slate-800/80 hover:bg-slate-800 text-slate-300 font-bold text-xs transition">
                <i class="fas fa-arrow-left"></i>
                <span>Quizzes</span>
            </a>

            <a href="{{ route('questions.create', $quiz->id) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 text-white font-bold text-xs shadow-lg shadow-indigo-500/30 hover:scale-[1.02] active:scale-95 transition-all">
                <i class="fas fa-plus"></i>
                <span>Add Question</span>
            </a>

            <a href="{{ route('questions.create', $quiz->id) }}#bulk-upload"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-purple-500 to-pink-600 text-white font-bold text-xs shadow-lg shadow-purple-500/30 hover:scale-[1.02] active:scale-95 transition-all">
                <i class="fas fa-file-csv"></i>
                <span>Import CSV</span>
            </a>
        </div>
    </div>

    <!-- Info Capacity Meter Bar -->
    <div class="p-4 bg-indigo-500/10 border border-indigo-500/20 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-indigo-200">
        <div class="flex items-center gap-2">
            <i class="fas fa-circle-info text-indigo-400 text-sm"></i>
            <span>Question Bank Capacity: <strong class="font-bold text-white">{{ $questionCount ?? $questions->total() }}</strong> of <strong class="font-bold text-white">{{ $questionLimit ?? ($quiz->question_limit ?? 60) }}</strong> capacity items</span>
        </div>
        <div class="w-full sm:w-48 bg-slate-900 rounded-full h-2 overflow-hidden border border-slate-800">
            @php
                $limit = $questionLimit ?? ($quiz->question_limit ?? 60);
                $curr = $questionCount ?? $questions->total();
                $pct = min(100, round(($curr / max(1, $limit)) * 100));
            @endphp
            <div class="bg-indigo-500 h-2 rounded-full transition-all duration-300" style="width: {{ $pct }}%"></div>
        </div>
    </div>

    <!-- Questions List -->
    <div class="space-y-4">
        @forelse($questions as $index => $question)
        <div class="glass-panel rounded-3xl p-6 shadow-xl hover:border-indigo-500/40 transition-all duration-200">
            <!-- Question Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-4 mb-4 border-b border-slate-800">
                <div class="flex items-start gap-3.5">
                    <span class="w-8 h-8 rounded-xl bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 font-extrabold text-xs flex items-center justify-center shrink-0 mt-0.5">
                        {{ $questions->firstItem() + $index }}
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-100 leading-snug font-heading">{{ $question->question_text }}</h3>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-300 border border-slate-700">
                                Points: {{ $question->points ?? 1 }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                Correct Answer: {{ strtoupper($question->correct_option) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action buttons -->
                <div class="flex items-center space-x-2 shrink-0">
                    <a href="{{ route('questions.edit', [$quiz->id, $question->id]) }}"
                       class="p-2 rounded-xl text-slate-400 hover:text-indigo-400 hover:bg-slate-800 transition-all"
                       title="Edit Question">
                        <i class="fas fa-pen text-sm"></i>
                    </a>

                    <form action="{{ route('questions.destroy', [$quiz->id, $question->id]) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this question?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="p-2 rounded-xl text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-all"
                                title="Delete Question">
                            <i class="fas fa-trash-can text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Options Grid -->
            @php
                $correct = strtoupper($question->correct_option);
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs font-medium">
                <div class="p-3.5 rounded-2xl border transition-all flex items-center justify-between {{ $correct === 'A' ? 'bg-emerald-500/20 border-emerald-500/40 text-emerald-300 font-bold' : 'glass-card text-slate-300' }}">
                    <div>
                        <span class="font-extrabold mr-1.5 {{ $correct === 'A' ? 'text-emerald-400' : 'text-slate-500' }}">A.</span>
                        {{ $question->option_a }}
                    </div>
                    @if($correct === 'A')
                        <span class="inline-flex items-center text-[10px] font-bold text-emerald-300 bg-emerald-500/30 px-2 py-0.5 rounded-full border border-emerald-500/40">
                            <i class="fas fa-circle-check mr-1"></i> Correct
                        </span>
                    @endif
                </div>

                <div class="p-3.5 rounded-2xl border transition-all flex items-center justify-between {{ $correct === 'B' ? 'bg-emerald-500/20 border-emerald-500/40 text-emerald-300 font-bold' : 'glass-card text-slate-300' }}">
                    <div>
                        <span class="font-extrabold mr-1.5 {{ $correct === 'B' ? 'text-emerald-400' : 'text-slate-500' }}">B.</span>
                        {{ $question->option_b }}
                    </div>
                    @if($correct === 'B')
                        <span class="inline-flex items-center text-[10px] font-bold text-emerald-300 bg-emerald-500/30 px-2 py-0.5 rounded-full border border-emerald-500/40">
                            <i class="fas fa-circle-check mr-1"></i> Correct
                        </span>
                    @endif
                </div>

                <div class="p-3.5 rounded-2xl border transition-all flex items-center justify-between {{ $correct === 'C' ? 'bg-emerald-500/20 border-emerald-500/40 text-emerald-300 font-bold' : 'glass-card text-slate-300' }}">
                    <div>
                        <span class="font-extrabold mr-1.5 {{ $correct === 'C' ? 'text-emerald-400' : 'text-slate-500' }}">C.</span>
                        {{ $question->option_c }}
                    </div>
                    @if($correct === 'C')
                        <span class="inline-flex items-center text-[10px] font-bold text-emerald-300 bg-emerald-500/30 px-2 py-0.5 rounded-full border border-emerald-500/40">
                            <i class="fas fa-circle-check mr-1"></i> Correct
                        </span>
                    @endif
                </div>

                <div class="p-3.5 rounded-2xl border transition-all flex items-center justify-between {{ $correct === 'D' ? 'bg-emerald-500/20 border-emerald-500/40 text-emerald-300 font-bold' : 'glass-card text-slate-300' }}">
                    <div>
                        <span class="font-extrabold mr-1.5 {{ $correct === 'D' ? 'text-emerald-400' : 'text-slate-500' }}">D.</span>
                        {{ $question->option_d }}
                    </div>
                    @if($correct === 'D')
                        <span class="inline-flex items-center text-[10px] font-bold text-emerald-300 bg-emerald-500/30 px-2 py-0.5 rounded-full border border-emerald-500/40">
                            <i class="fas fa-circle-check mr-1"></i> Correct
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="glass-panel rounded-3xl p-12 text-center text-slate-400 shadow-xl">
            <div class="w-16 h-16 rounded-2xl bg-slate-800 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-folder-open text-2xl"></i>
            </div>
            <h3 class="text-base font-bold text-slate-200 mb-1 font-heading">No Questions Added Yet</h3>
            <p class="text-xs text-slate-400 mb-4">Start by adding your first question or import via CSV file.</p>
            <a href="{{ route('questions.create', $quiz->id) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-bold text-xs rounded-2xl shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 transition">
                <i class="fas fa-plus"></i> Add First Question
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($questions->hasPages())
    <div class="pt-4">
        {{ $questions->links() }}
    </div>
    @endif

</div>
@endsection
