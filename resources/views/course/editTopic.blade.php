@extends('layouts.app')

@section('title', 'Edit Topic')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 text-indigo-400 flex items-center justify-center font-bold shadow-inner">
                <i class="fas fa-pen-to-square text-lg"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-white tracking-tight font-heading">Edit Topic</h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Module Topic: <span class="font-bold text-indigo-400">{{ $topic->title }}</span></p>
            </div>
        </div>

        <a href="{{ route('admin.topics.create', $topic->module_id) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border border-slate-700/80 bg-slate-800/80 hover:bg-slate-800 text-slate-300 hover:text-white font-bold text-xs transition">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Topics</span>
        </a>
    </div>

    <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
        <form method="POST" action="{{ route('admin.topics.update', $topic->id) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="module_id" value="{{ $topic->module_id }}">

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-heading">Topic Title *</label>
                <input type="text" name="title" value="{{ old('title', $topic->title) }}" required
                       class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 font-medium">
                @error('title')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-heading">Display Order</label>
                    <input type="number" name="order" min="0" value="{{ old('order', $topic->order) }}"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 font-medium">
                    @error('order')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-heading">Status</label>
                    <select name="is_active"
                            class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 font-medium">
                        <option value="1" {{ old('is_active', $topic->is_active) ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !old('is_active', $topic->is_active) ? 'selected' : '' }}>Draft</option>
                    </select>
                    @error('is_active')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-heading">YouTube Video URL</label>
                <input type="url" name="video_url" value="{{ old('video_url', $topic->video_url) }}"
                       placeholder="https://www.youtube.com/watch?v=..."
                       class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 font-medium placeholder-slate-500">
                @error('video_url')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-heading">Document (PDF or PPTX)</label>
                <input type="file" name="document" accept=".pdf,.pptx,application/pdf,application/vnd.openxmlformats-officedocument.presentationml.presentation"
                       class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-200 text-xs font-medium">
                <p class="text-[11px] text-slate-400 mt-1">Uploading a new file replaces the current one. Max size: 10MB.</p>
                @if($topic->file_name)
                    <p class="text-xs text-indigo-300 mt-2">Current document: <span class="font-bold text-white">{{ $topic->file_name }}</span></p>
                @endif
                @error('document')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-800">
                <a href="{{ route('admin.topics.create', $topic->module_id) }}"
                   class="px-5 py-2.5 rounded-2xl border border-slate-700/80 bg-slate-800/80 text-slate-300 font-bold text-xs hover:bg-slate-800 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition">
                    Update Topic
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
