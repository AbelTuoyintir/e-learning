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
                <label class="block text-sm font-semibold text-gray-700 mb-2">YouTube Video URLs</label>

                <div id="video-list" class="space-y-2">
                    @php
                        $existingUrls = [];
                        if ($topic->relationLoaded('videos') || method_exists($topic, 'videos')) {
                            $existingUrls = $topic->videos?->pluck('url')->filter()->values()->all() ?? [];
                        }
                        if (empty($existingUrls) && !empty($topic->video_url)) {
                            $existingUrls = [$topic->video_url];
                        }
                        $videoUrls = old('video_urls', $existingUrls);
                        if (empty($videoUrls)) { $videoUrls = ['']; }
                    @endphp

                    @foreach ($videoUrls as $i => $url)
                        <div class="video-row flex gap-2">
                            <input type="url" name="video_urls[]" value="{{ $url }}"
                                placeholder="https://www.youtube.com/watch?v=..."
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <button type="button" class="remove-video px-3 py-2 rounded-xl border border-red-300 text-red-600 hover:bg-red-50 transition">
                                Remove
                            </button>
                        </div>
                        @error("video_urls.$i")
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    @endforeach
                </div>

                <button type="button" id="add-video"
                        class="mt-3 px-4 py-2 text-sm rounded-lg border border-indigo-300 text-indigo-700 hover:bg-indigo-50 transition">
                    + Add another video
                </button>

                @error('video_urls')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Document (PDF or PPTX)</label>
                <input type="file" name="document"
                    accept=".pdf,.pptx,application/pdf,application/vnd.openxmlformats-officedocument.presentationml.presentation"
                    class="w-full px-3 py-2 border border-gray-300 rounded-xl">
                <p class="text-xs text-gray-500 mt-1">Uploading a new file replaces the current one. Max size: 1GB.</p>
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
<script>
    const list = document.getElementById('video-list');

    document.getElementById('add-video').addEventListener('click', () => {
        const row = list.querySelector('.video-row').cloneNode(true);
        row.querySelector('input').value = '';
        list.appendChild(row);
    });

    list.addEventListener('click', (e) => {
        if (!e.target.classList.contains('remove-video')) return;
        const rows = list.querySelectorAll('.video-row');
        if (rows.length > 1) {
            e.target.closest('.video-row').remove();
        } else {
            rows[0].querySelector('input').value = ''; // keep one empty field
        }
    });
</script>
@endsection
