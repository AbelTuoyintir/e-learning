{{-- resources/views/students/statistics.blade.php --}}
@extends('layouts.app')

@section('title', 'My Statistics')

@section('content')
<style>
    .stat-card {
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.15);
    }
    .progress-bar-animated {
        transition: width 1s ease-in-out;
    }
    .badge-icon {
        transition: all 0.3s ease;
    }
    .badge-icon:hover {
        transform: scale(1.1);
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-800">My Learning Statistics</h1>
                    <p class="text-slate-500 text-sm mt-1">Track your progress and performance across all quizzes</p>
                </div>
            </div>
        </div>

        <!-- Overview Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Total Attempts -->
            <div class="stat-card bg-white rounded-2xl shadow-md p-5 border-l-4 border-indigo-500">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-tasks text-indigo-600 text-xl"></i>
                    </div>
                    <span class="text-3xl font-bold text-indigo-600">{{ $stats->total_attempts ?? 0 }}</span>
                </div>
                <h3 class="text-slate-700 font-semibold">Total Attempts</h3>
                <p class="text-slate-400 text-xs mt-1">Quizzes completed</p>
            </div>

            <!-- Passed Quizzes -->
            <div class="stat-card bg-white rounded-2xl shadow-md p-5 border-l-4 border-green-500">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <span class="text-3xl font-bold text-green-600">{{ $stats->passed_quizzes ?? 0 }}</span>
                </div>
                <h3 class="text-slate-700 font-semibold">Passed Quizzes</h3>
                <p class="text-slate-400 text-xs mt-1">Successfully completed</p>
            </div>

            <!-- Average Score -->
            <div class="stat-card bg-white rounded-2xl shadow-md p-5 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center">
                        <i class="fas fa-chart-simple text-yellow-600 text-xl"></i>
                    </div>
                    <span class="text-3xl font-bold text-yellow-600">{{ round($stats->average_score ?? 0) }}%</span>
                </div>
                <h3 class="text-slate-700 font-semibold">Average Score</h3>
                <p class="text-slate-400 text-xs mt-1">Across all attempts</p>
            </div>

            <!-- Pass Rate -->
            <div class="stat-card bg-white rounded-2xl shadow-md p-5 border-l-4 border-purple-500">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-trophy text-purple-600 text-xl"></i>
                    </div>
                    <span class="text-3xl font-bold text-purple-600">{{ $passRate }}%</span>
                </div>
                <h3 class="text-slate-700 font-semibold">Pass Rate</h3>
                <p class="text-slate-400 text-xs mt-1">Overall success rate</p>
            </div>
        </div>

        <!-- Detailed Stats Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Score Range Card -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-chart-line text-indigo-500"></i>
                    <h3 class="font-semibold text-slate-800">Score Range</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-500">Highest Score</span>
                            <span class="font-bold text-green-600">{{ round($stats->highest_score ?? 0) }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full progress-bar-animated" style="width: {{ $stats->highest_score ?? 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-500">Average Score</span>
                            <span class="font-bold text-yellow-600">{{ round($stats->average_score ?? 0) }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full progress-bar-animated" style="width: {{ round($stats->average_score ?? 0) }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-500">Lowest Score</span>
                            <span class="font-bold text-orange-600">{{ round($stats->lowest_score ?? 0) }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-orange-500 h-2 rounded-full progress-bar-animated" style="width: {{ $stats->lowest_score ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Points Earned -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl shadow-md p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-indigo-100 text-sm">Total Points Earned</p>
                        <p class="text-4xl font-bold mt-1">{{ number_format($stats->total_points_earned ?? 0) }}</p>
                    </div>
                    <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fas fa-star text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-white/20">
                    <p class="text-indigo-100 text-sm">Average per Quiz: <strong>{{ round(($stats->total_points_earned ?? 0) / max($stats->total_attempts ?? 1, 1)) }} pts</strong></p>
                </div>
            </div>

            <!-- Achievement Badges -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-medal text-yellow-500"></i>
                    <h3 class="font-semibold text-slate-800">Achievement Badges</h3>
                </div>
                @if(count($badges) > 0)
                    <div class="flex flex-wrap gap-3">
                        @foreach($badges as $badge)
                            <div class="badge-icon group relative">
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-{{ $badge['color'] }}-100 to-{{ $badge['color'] }}-200 flex items-center justify-center shadow-md cursor-pointer">
                                    <i class="fas {{ $badge['icon'] }} text-{{ $badge['color'] }}-600 text-2xl"></i>
                                </div>
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none">
                                    {{ $badge['name'] }} ({{ $badge['count'] }})
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-500 text-sm">Complete more quizzes to earn badges!</p>
                @endif
            </div>
        </div>

        <!-- Performance by Course/Quiz -->
        <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
            <div class="flex items-center gap-2 mb-5">
                <i class="fas fa-chart-pie text-indigo-500"></i>
                <h3 class="font-semibold text-slate-800 text-lg">Performance by Quiz</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="text-left py-3 text-slate-600 font-semibold text-sm">Quiz Title</th>
                            <th class="text-center py-3 text-slate-600 font-semibold text-sm">Attempts</th>
                            <th class="text-center py-3 text-slate-600 font-semibold text-sm">Avg Score</th>
                            <th class="text-center py-3 text-slate-600 font-semibold text-sm">Best Score</th>
                            <th class="text-right py-3 text-slate-600 font-semibold text-sm">Trend</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($performanceByCourse as $performance)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                                <td class="py-3 text-slate-700 font-medium">
                                    {{ $performance->quiz->title ?? 'Unknown Quiz' }}
                                </td>
                                <td class="py-3 text-center text-slate-600">
                                    <span class="px-2 py-1 bg-slate-100 rounded-full text-xs">{{ $performance->attempts }}</span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="font-semibold {{ round($performance->avg_score) >= 70 ? 'text-green-600' : 'text-orange-600' }}">
                                        {{ round($performance->avg_score) }}%
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="font-semibold text-indigo-600">{{ round($performance->best_score) }}%</span>
                                </td>
                                <td class="py-3 text-right">
                                    @php
                                        $trend = $performance->avg_score >= 70 ? 'up' : ($performance->avg_score >= 50 ? 'stable' : 'down');
                                    @endphp
                                    @if($trend == 'up')
                                        <i class="fas fa-arrow-up text-green-500"></i>
                                    @elseif($trend == 'stable')
                                        <i class="fas fa-minus text-yellow-500"></i>
                                    @else
                                        <i class="fas fa-arrow-down text-red-500"></i>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-500">
                                    <i class="fas fa-inbox text-3xl mb-2 block"></i>
                                    No quiz attempts yet. Start taking quizzes to see your performance!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Quiz Results -->
        <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2">
                    <i class="fas fa-history text-indigo-500"></i>
                    <h3 class="font-semibold text-slate-800 text-lg">Recent Quiz Results</h3>
                </div>
                <a href="{{ route('student.results') }}" class="text-indigo-600 text-sm hover:text-indigo-700 font-medium">View All →</a>
            </div>
            <div class="space-y-3">
                @forelse($recentResults as $result)
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full {{ $result->passed ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center">
                                <i class="fas {{ $result->passed ? 'fa-check text-green-600' : 'fa-times text-red-600' }}"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-800">{{ $result->quiz->title ?? 'Quiz' }}</h4>
                                <p class="text-xs text-slate-500">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ $result->completed_at ? $result->completed_at->format('M d, Y - h:i A') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold {{ $result->score >= 70 ? 'text-green-600' : ($result->score >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ round($result->score) }}%
                            </span>
                            <p class="text-xs text-slate-500">{{ $result->points_earned ?? 0 }}/{{ $result->total_points ?? 0 }} pts</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-500">
                        <i class="fas fa-clipboard-list text-3xl mb-2 block"></i>
                        <p>No recent quiz results available.</p>
                        <a href="{{ route('student.quizzes') }}" class="inline-block mt-3 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700 transition">
                            Browse Quizzes
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Tips Section -->
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-100">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-lightbulb text-indigo-600"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-800 mb-1">Learning Tips</h4>
                    <p class="text-slate-600 text-sm">
                        @if(($stats->average_score ?? 0) < 70)
                            Keep practicing! Review your incorrect answers and try taking quizzes again.
                        @elseif(($stats->average_score ?? 0) < 85)
                            Great progress! Challenge yourself with more difficult quizzes to improve further.
                        @else
                            Excellent performance! You're mastering the material. Consider helping other students.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Animate progress bars on load
    document.addEventListener('DOMContentLoaded', function() {
        const progressBars = document.querySelectorAll('.progress-bar-animated');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    });
</script>
@endsection