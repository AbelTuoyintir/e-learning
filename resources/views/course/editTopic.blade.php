@extends('layouts.app')

@section('title', 'Edit Topic')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Topic</h1>
        <a href="{{ route('admin.topics.create', $topic->module_id) }}"
           class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
            Back to Topics
        </a>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.topics.update', $topic->id) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="module_id" value="{{ $topic->module_id }}">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Topic Title *</label>
                <input type="text" name="title" value="{{ old('title', $topic->title) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Order</label>
                    <input type="number" name="order" min="0" value="{{ old('order', $topic->order) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('order')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select name="is_active"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="1" {{ old('is_active', $topic->is_active) ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !old('is_active', $topic->is_active) ? 'selected' : '' }}>Draft</option>
                    </select>
                    @error('is_active')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">YouTube Video URL</label>
                <input type="url" name="video_url" value="{{ old('video_url', $topic->video_url) }}"
                       placeholder="https://www.youtube.com/watch?v=..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                @error('video_url')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Document (PDF or PPTX)</label>
                <input type="file" name="document" accept=".pdf,.pptx,application/pdf,application/vnd.openxmlformats-officedocument.presentationml.presentation"
                       class="w-full px-3 py-2 border border-gray-300 rounded-xl">
                <p class="text-xs text-gray-500 mt-1">Uploading a new file replaces the current one. Max size: 10MB.</p>
                @if($topic->file_name)
                    <p class="text-sm text-gray-700 mt-2">Current document: <span class="font-medium">{{ $topic->file_name }}</span></p>
                @endif
                @error('document')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.topics.create', $topic->module_id) }}"
                   class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
                    Update Topic
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
