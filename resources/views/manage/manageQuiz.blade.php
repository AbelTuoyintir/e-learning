@extends('layouts.app')

@section('title', 'Quiz Management')

@section('content')
<div class="space-y-8">

    <!-- Header & Action Area -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900/80 backdrop-blur-md rounded-3xl p-6 sm:p-8 shadow-xl border border-white/10">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-purple-600 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/20">
                <i class="fas fa-clipboard-question text-xl"></i>
            </div>
            <div>
                <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Quiz Management</h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Configure assessment quizzes, question bank limits, and passing criteria</p>
            </div>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('quizzes.create') }}"
               class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 hover:scale-[1.02] active:scale-95 transition-all">
                <i class="fas fa-plus text-xs"></i>
                <span>Create New Quiz</span>
            </a>
        </div>
    </div>

    <!-- Stats Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl p-5 border border-white/10 shadow-lg flex items-center justify-between hover-lift">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Total Quizzes</span>
                <p class="text-2xl font-black text-white mt-1 font-heading">{{ $quizzes->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center text-base">
                <i class="fas fa-list-check"></i>
            </div>
        </div>

        <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl p-5 border border-white/10 shadow-lg flex items-center justify-between hover-lift">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Question Limits</span>
                <p class="text-2xl font-black text-white mt-1 font-heading">60 Items / Quiz</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center text-base">
                <i class="fas fa-cubes"></i>
            </div>
        </div>

        <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl p-5 border border-white/10 shadow-lg flex items-center justify-between hover-lift">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Status</span>
                <div class="flex items-center gap-1.5 mt-1 text-xs font-bold text-emerald-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Assessments Active</span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-base">
                <i class="fas fa-shield-check"></i>
            </div>
        </div>
    </div>

    <!-- Quizzes Table Container Card -->
    <div class="bg-slate-900/80 backdrop-blur-md rounded-3xl shadow-xl border border-white/10 overflow-hidden">

        <!-- Toolbar & Search -->
        <div class="p-6 border-b border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-bold text-white">Configured Assessments</h2>
                <span class="px-3 py-0.5 rounded-full text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                    {{ $quizzes->count() }} Active
                </span>
            </div>

            <!-- Search input -->
            <div class="relative max-w-xs w-full">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-search text-xs"></i>
                </div>
                <input type="text" id="quizSearchInput" onkeyup="filterQuizzes()" placeholder="Search quiz title..."
                       class="w-full pl-10 pr-4 py-2 bg-slate-800 border border-white/10 rounded-xl text-xs text-white placeholder-slate-400 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
            </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="quizzesTable">
                <thead>
                    <tr class="bg-slate-950/60 border-b border-white/10 text-[10px] font-extrabold uppercase tracking-widest text-slate-400">
                        <th class="px-6 py-4"># ID</th>
                        <th class="px-6 py-4">Quiz Details</th>
                        <th class="px-6 py-4">Difficulty</th>
                        <th class="px-6 py-4">Question Usage</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-xs font-medium">
                    @forelse ($quizzes as $quiz)
                    <tr class="hover:bg-white/5 transition-colors group quiz-row">
                        <!-- ID -->
                        <td class="px-6 py-4 font-mono text-xs font-bold text-indigo-400">
                            #{{ $quiz->id }}
                        </td>

                        <!-- Title & Description -->
                        <td class="px-6 py-4 max-w-md">
                            <p class="font-bold text-white group-hover:text-indigo-400 transition-colors text-sm quiz-title">
                                {{ $quiz->title }}
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5 line-clamp-1">
                                {{ $quiz->description ?: 'No specific description provided.' }}
                            </p>
                        </td>

                        <!-- Difficulty Badge -->
                        <td class="px-6 py-4">
                            @php
                                $diffColors = [
                                    'easy'   => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                    'medium' => 'bg-amber-500/10 text-amber-300 border-amber-500/30',
                                    'hard'   => 'bg-rose-500/10 text-rose-400 border-rose-500/30'
                                ];
                                $diffClass = $diffColors[strtolower($quiz->difficulty ?? 'easy')] ?? 'bg-slate-800 text-slate-300 border-white/10';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold border capitalize {{ $diffClass }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ strtolower($quiz->difficulty) === 'hard' ? 'bg-rose-400' : (strtolower($quiz->difficulty) === 'medium' ? 'bg-amber-400' : 'bg-emerald-400') }}"></span>
                                {{ $quiz->difficulty ?? 'Easy' }}
                            </span>
                        </td>

                        <!-- Question Usage Meter -->
                        <td class="px-6 py-4">
                            @php
                                $limit = $quiz->question_limit ?? 60;
                                $count = $quiz->questions_count ?? 0;
                                $percent = min(100, round(($count / max(1, $limit)) * 100));
                            @endphp
                            <div class="w-36">
                                <div class="flex justify-between items-center text-xs mb-1">
                                    <span class="font-semibold text-slate-200">{{ $count }} / {{ $limit }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold">{{ $percent }}%</span>
                                </div>
                                <div class="w-full bg-slate-900 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-1.5 rounded-full transition-all duration-300" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        </td>

                        <!-- Action Buttons -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                {{-- Questions Bank --}}
                                <a href="{{ route('questions.index', $quiz) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-500/20 text-indigo-300 hover:bg-indigo-600 hover:text-white text-xs font-bold transition-all border border-indigo-500/30">
                                    <i class="fas fa-list-check text-xs"></i>
                                    <span>Questions</span>
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('quizzes.edit', $quiz->id) }}"
                                   class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-indigo-600/20 transition-all"
                                   title="Edit Quiz">
                                    <i class="fas fa-pen text-sm"></i>
                                </a>

                                {{-- Delete Form --}}
                                <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" class="inline delete-quiz-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 rounded-xl text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-all"
                                            title="Delete Quiz">
                                        <i class="fas fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-500">
                            <div class="w-16 h-16 rounded-3xl bg-slate-800 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-inbox text-2xl"></i>
                            </div>
                            <p class="font-bold text-slate-300">No quizzes available</p>
                            <p class="text-xs text-slate-500 mt-1 mb-4">Get started by creating your first assessment quiz.</p>
                            <a href="{{ route('quizzes.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold text-xs shadow-md">
                                <i class="fas fa-plus"></i> Create Quiz
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    function filterQuizzes() {
        const input = document.getElementById('quizSearchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.quiz-row');

        rows.forEach(row => {
            const title = row.querySelector('.quiz-title')?.textContent.toLowerCase() || '';
            if (title.includes(input)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Delete confirmation handler
    document.querySelectorAll('.delete-quiz-form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const result = await Swal.fire({
                title: 'Delete Quiz?',
                text: 'Are you sure you want to delete this quiz? All associated questions will be removed.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                background: '#0f172a',
                color: '#fff',
                customClass: {
                    popup: 'rounded-3xl border border-white/10',
                    confirmButton: 'rounded-xl px-5 py-2.5 font-bold',
                    cancelButton: 'rounded-xl px-5 py-2.5 font-bold'
                }
            });

            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endsection
