@extends('layouts.app')
@section('title','Edit Course')
@section('content')

<div class="space-y-6 max-w-5xl mx-auto" x-data="{ openDelete: false }">

  <!-- ===== HEADER ===== -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
    <div>
      <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white font-heading">
        Edit Course
      </h1>
      <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Update course catalog details, instructor info, pricing, and assets</p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
      <button @click="openDelete = true" class="px-4 py-2.5 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-400 rounded-2xl font-bold text-xs transition">
        <i class="fas fa-trash mr-1.5"></i> Delete
      </button>
      <a href="{{ route('courses.show',$course) }}" target="_blank" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 rounded-2xl font-bold text-xs transition">
        <i class="fas fa-eye mr-1.5"></i> Preview
      </a>
      <button form="courseForm" type="submit" id="saveBtn" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-xs shadow-lg shadow-indigo-600/30 transition">
        <span class="btn-text">Save Changes</span>
        <i class="btn-icon fas fa-save ml-2"></i>
      </button>
    </div>
  </div>

  <!-- ===== FORM CARD ===== -->
  <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-xl space-y-6">

    <div class="p-6 bg-gradient-to-r from-indigo-950 via-slate-900 to-purple-950 rounded-2xl border border-indigo-500/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="text-2xl font-extrabold text-white font-heading">{{ $course->title }}</div>
        <div class="text-xs text-slate-400 mt-1">Last updated {{ $course->updated_at->diffForHumans() }}</div>
      </div>
      @if($course->image)
      <img src="{{ Storage::url($course->image) }}" alt="" class="w-16 h-16 rounded-2xl shadow-md border border-indigo-500/30 object-cover shrink-0">
      @endif
    </div>

    <form id="courseForm" action="{{ route('courses.update',$course) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
      @csrf @method('PUT')

      <!-- Course Name -->
      <div>
        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-heading">Course Title *</label>
        <input type="text" name="title" id="name" value="{{ old('title',$course->title) }}" required
               class="w-full px-4 py-3 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 font-medium">
        @error('title') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
      </div>

      <!-- Description -->
      <div>
        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-heading">Description</label>
        <textarea name="description" id="description" rows="4" placeholder="Enter course description..."
                  class="w-full px-4 py-3 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 font-medium placeholder-slate-500">{{ old('description',$course->description) }}</textarea>
        @error('description') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
      </div>

      <!-- Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label for="instructor" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-heading">Instructor</label>
          <input type="text" name="instructor" id="instructor" value="{{ old('instructor',$course->instructor) }}" placeholder="Instructor name"
                 class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 font-medium">
          @error('instructor') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="duration" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-heading">Duration (hours)</label>
          <input type="number" name="duration" id="duration" min="0" value="{{ old('duration',$course->duration) }}" placeholder="Course duration"
                 class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 font-medium">
          @error('duration') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="price" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-heading">Price (GHS)</label>
          <input type="number" name="price" id="price" min="0" step="0.01" value="{{ old('price',$course->price ?? 0) }}" placeholder="0.00 for free"
                 class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 font-medium">
          @error('price') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="category" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-heading">Category</label>
          <input type="text" name="category" id="category" value="{{ old('category',$course->category) }}" placeholder="Course category"
                 class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 font-medium">
          @error('category') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="image" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-heading">Course Cover Image</label>
          <input type="file" name="image" id="image" accept="image/*"
                 class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-200 text-xs font-medium">
          @error('image') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
          @if($course->image)
          <div class="mt-3 flex items-center gap-3">
            <img src="{{ Storage::url($course->image) }}" alt="Current" class="w-16 h-16 rounded-2xl object-cover ring-2 ring-indigo-500/30">
            <label class="inline-flex items-center gap-2 text-xs text-slate-300">
              <input type="checkbox" name="remove_image" value="1" class="rounded bg-slate-800 border-slate-700 text-indigo-500 focus:ring-indigo-500"> Remove image
            </label>
          </div>
          @endif
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
        <a href="{{ route('courses.index') }}" class="px-5 py-2.5 border border-slate-700/80 bg-slate-800/80 text-slate-300 rounded-2xl font-bold text-xs hover:bg-slate-800 transition">
          Cancel
        </a>
        <button type="submit" id="saveBtn" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-xs shadow-lg shadow-indigo-600/30 transition">
          <span class="btn-text">Save Changes</span>
          <i class="btn-icon fas fa-save ml-2"></i>
        </button>
      </div>
    </form>
  </div>

  <!-- Delete Modal -->
  <div x-show="openDelete" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md flex items-center justify-center z-50 p-4" style="display: none;">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl w-full max-w-sm p-6 text-center text-slate-100">
      <div class="w-12 h-12 rounded-2xl bg-rose-500/20 text-rose-400 flex items-center justify-center mx-auto mb-3">
        <i class="fas fa-triangle-exclamation text-xl"></i>
      </div>
      <h3 class="text-lg font-bold font-heading">Delete Course?</h3>
      <p class="text-slate-400 text-xs mt-1">This will permanently remove the course and associated content.</p>
      <div class="flex items-center justify-center gap-3 mt-6">
        <button @click="openDelete = false" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-xl font-bold text-xs hover:bg-slate-700 transition">Cancel</button>
        <button @click="$refs.deleteForm.submit()" class="px-4 py-2 bg-rose-600 text-white rounded-xl font-bold text-xs shadow-md hover:bg-rose-700 transition">Delete</button>
      </div>
    </div>
  </div>

  <form x-ref="deleteForm" action="{{ route('courses.destroy',$course) }}" method="POST" class="hidden">
    @csrf @method('DELETE')
  </form>

</div>
@endsection
