@extends('layouts.app')
@section('title', 'Topic Management')
@section('content')
<div class="space-y-8" x-data="topicManager()">

    <!-- HEADER & BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 via-indigo-600 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                <i class="fas fa-book-open text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-heading">
                    Topic Management
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Module: <span class="font-bold text-indigo-400">{{ $module->title }}</span></p>
            </div>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('courses.modules', $module->course_id) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border border-slate-700/80 bg-slate-800/80 hover:bg-slate-800 text-slate-300 font-bold text-xs transition">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Modules</span>
            </a>

            <button id="createTopicBtn" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 text-white font-bold text-xs shadow-lg shadow-indigo-500/30 hover:scale-[1.02] active:scale-95 transition-all">
                <i class="fas fa-plus"></i>
                <span>Create Topic</span>
            </button>
        </div>
    </div>

    <!-- TOPICS TABLE CARD -->
    <div class="glass-panel rounded-3xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/90 border-b border-slate-800 text-[11px] font-extrabold uppercase tracking-wider text-slate-400 font-heading">
                        <th class="px-6 py-4">Topic</th>
                        <th class="px-6 py-4">Module</th>
                        <th class="px-6 py-4">Course</th>
                        <th class="px-6 py-4">Order</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Created</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-sm">
                    @forelse($topics as $topicItem)
                    <tr class="hover:bg-slate-800/40 transition-colors group">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-200 group-hover:text-indigo-400 transition-colors font-heading">
                                {{ $topicItem->title }}
                            </p>
                            @if($topicItem->content)
                            <p class="text-xs text-slate-400 mt-0.5 line-clamp-1">{{ Str::limit($topicItem->content, 50) }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs font-semibold text-slate-300">
                            {{ $topicItem->module->title ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-xs font-semibold text-slate-300">
                            {{ $topicItem->module->course->title ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 font-mono text-xs font-bold text-slate-400">
                            #{{ $topicItem->order }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold {{ $topicItem->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-800 text-slate-400 border border-slate-700' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $topicItem->is_active ? 'bg-emerald-400' : 'bg-slate-500' }}"></span>
                                {{ $topicItem->is_active ? 'Active' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-400">
                            {{ $topicItem->created_at ? $topicItem->created_at->format('M d, Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.topics.edit', $topicItem->id) }}"
                                   class="p-2 rounded-xl text-slate-400 hover:text-indigo-400 hover:bg-slate-800 transition-all"
                                   title="Edit Topic">
                                    <i class="fas fa-pen text-sm"></i>
                                </a>

                                <form action="{{ route('admin.topics.destroy', $topicItem->id) }}" method="POST"
                                      class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 rounded-xl text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-all"
                                            title="Delete Topic"
                                            onclick="return confirm('Are you sure you want to delete this topic?')">
                                        <i class="fas fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-slate-400">
                            <div class="w-16 h-16 rounded-2xl bg-slate-800 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-inbox text-2xl"></i>
                            </div>
                            <p class="font-bold text-slate-200">No topics created yet</p>
                            <p class="text-xs text-slate-400 mt-1">Get started by creating your first topic for this module.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($topics->hasPages())
        <div class="p-4 border-t border-slate-800">
            {{ $topics->links() }}
        </div>
        @endif
    </div>

</div>

<!-- CREATE TOPIC MODAL -->
<div id="topicModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md hidden items-center justify-center z-50 p-4">
    <div class="bg-slate-900 w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-3xl shadow-2xl border border-slate-800 p-6 sm:p-8 relative">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800 mb-6">
            <h2 class="text-xl font-bold text-white font-heading">Create Topic</h2>
            <button id="closeTopicModal" class="w-8 h-8 rounded-xl bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.topics.store') }}" enctype="multipart/form-data" id="myForm" class="space-y-4">
            @csrf
            <input type="hidden" name="module_id" value="{{ $module->id }}">

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">Topic Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-2xl text-slate-100 text-sm focus:ring-2 focus:ring-indigo-500 transition">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">Display Order</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                           class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-2xl text-slate-100 text-sm focus:ring-2 focus:ring-indigo-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">Status</label>
                    <select name="is_active" class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-2xl text-slate-100 text-sm focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="1" {{ old('is_active', 1) ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !old('is_active', 1) ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">YouTube Video URL</label>
                <input type="url" name="video_url" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=..."
                       class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-2xl text-slate-100 text-sm focus:ring-2 focus:ring-indigo-500 transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">Document (PDF or PPTX)</label>
                <input type="file" name="document" accept=".pdf,.pptx,application/pdf,application/vnd.openxmlformats-officedocument.presentationml.presentation"
                       class="w-full px-4 py-2 bg-slate-800 border border-slate-700 rounded-2xl text-xs text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:bg-indigo-600 file:text-white file:text-xs">
            </div>

            <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
                <button type="button" id="cancelModalBtn" class="px-4 py-2.5 bg-slate-800 text-slate-300 rounded-2xl text-xs font-bold hover:bg-slate-700 transition">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-xs font-bold shadow-lg shadow-indigo-600/30 transition">
                    Save Topic
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function topicManager() { return {}; }

    document.addEventListener('DOMContentLoaded', function() {
        const openBtn = document.getElementById("createTopicBtn");
        const modal = document.getElementById("topicModal");
        const closeBtn = document.getElementById("closeTopicModal");
        const cancelBtn = document.getElementById("cancelModalBtn");

        if (openBtn && modal) {
            openBtn.addEventListener("click", () => {
                modal.classList.remove("hidden");
                modal.classList.add("flex");
            });
        }

        const closeModal = () => {
            if (modal) {
                modal.classList.add("hidden");
                modal.classList.remove("flex");
            }
        };

        if (closeBtn) closeBtn.addEventListener("click", closeModal);
        if (cancelBtn) cancelBtn.addEventListener("click", closeModal);
        if (modal) {
            modal.addEventListener("click", (e) => {
                if (e.target === modal) closeModal();
            });
        }
    });
</script>
@endsection
