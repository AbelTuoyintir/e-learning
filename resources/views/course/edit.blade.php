@extends('layouts.app')   {{-- your admin layout --}}
@section('title','Edit Course')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-[#0f172a] to-[#1e293b] text-slate-100">

  <!-- ===== HEADER ===== -->
  <header class="max-w-5xl mx-auto px-6 pt-10 pb-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-purple-400">
          Edit Course
        </h1>
        <p class="text-slate-400 text-sm mt-1">Update course details and save changes</p>
      </div>
      <div class="flex items-center gap-3">
        {{-- Delete trigger --}}
        <button x-data @click="$dispatch('open-delete')" class="group px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur rounded-xl border border-white/20 flex items-center gap-2 transition">
          <i class="fas fa-trash text-rose-400 group-hover:scale-110 transition"></i>
          <span>Delete</span>
        </button>
        <a href="{{ route('courses.show',$course) }}" target="_blank" class="group px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur rounded-xl border border-white/20 flex items-center gap-2 transition">
          <i class="fas fa-eye text-slate-300 group-hover:scale-110 transition"></i>
          <span>Preview</span>
        </a>
        <button form="courseForm" type="submit" id="saveBtn" class="group px-5 py-2.5 bg-indigo-600 text-white rounded-xl shadow-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 focus:ring-offset-[#1e293b] transition duration-200 font-medium">
          <span class="btn-text">Save Changes</span>
          <i class="btn-icon fas fa-save ml-2 group-hover:scale-110 transition"></i>
        </button>
      </div>
    </div>
  </header>

  <!-- ===== FORM CARD ===== -->
  <div class="max-w-5xl mx-auto px-6 pb-10">

    <!-- Glass Card -->
    <div class="bg-white/5 backdrop-blur rounded-3xl shadow-2xl border border-white/10 overflow-hidden">

      <!-- Header -->
      <div class="h-32 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute bottom-4 left-6 text-white">
          <div class="text-2xl font-bold">{{ $course->title }}</div>
          <div class="text-sm opacity-90">Last updated {{ $course->updated_at->diffForHumans() }}</div>
        </div>
        @if($course->image)
        <img src="{{ Storage::url($course->image) }}" alt="" class="absolute right-6 -bottom-6 w-24 h-24 rounded-2xl shadow-lg border-4 border-white/30 object-cover">
        @endif
      </div>

      <!-- Body -->
      <form id="courseForm" action="{{ route('courses.update',$course) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
        @csrf @method('PUT')

        <!-- Course Name -->
        <div>
          <label for="name" class="block text-sm font-semibold text-slate-300 mb-2">Course Name *</label>
          <input type="text" name="title" id="name" value="{{ old('title',$course->title) }}" required
                 class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl placeholder-slate-400 text-slate-100 focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('title') border-rose-400 @enderror">
          @error('title') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="block text-sm font-semibold text-slate-300 mb-2">Description</label>
          <textarea name="description" id="description" rows="4" placeholder="Enter course description..."
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl placeholder-slate-400 text-slate-100 focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('description') border-rose-400 @enderror">{{ old('description',$course->description) }}</textarea>
          @error('description') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
        </div>

        <!-- Two Column Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Instructor -->
          <div>
            <label for="instructor" class="block text-sm font-semibold text-slate-300 mb-2">Instructor</label>
            <input type="text" name="instructor" id="instructor" value="{{ old('instructor',$course->instructor) }}" placeholder="Instructor name"
                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl placeholder-slate-400 text-slate-100 focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('instructor') border-rose-400 @enderror">
            @error('instructor') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
          </div>

          <!-- Duration -->
          <div>
            <label for="duration" class="block text-sm font-semibold text-slate-300 mb-2">Duration (hours)</label>
            <input type="number" name="duration" id="duration" min="0" value="{{ old('duration',$course->duration) }}" placeholder="Course duration"
                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl placeholder-slate-400 text-slate-100 focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('duration') border-rose-400 @enderror">
            @error('duration') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
          </div>
        </div>

        <!-- Two Column Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Category -->
          <div>
            <label for="category" class="block text-sm font-semibold text-slate-300 mb-2">Category</label>
            <input type="text" name="category" id="category" value="{{ old('category',$course->category) }}" placeholder="Course category"
                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl placeholder-slate-400 text-slate-100 focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('category') border-rose-400 @enderror">
            @error('category') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
          </div>

          <!-- Image Upload -->
          <div>
            <label for="image" class="block text-sm font-semibold text-slate-300 mb-2">Course Image</label>
            <input type="file" name="image" id="image" accept="image/*"
                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-600 file:text-white file:cursor-pointer focus:ring-2 focus:ring-indigo-400 transition @error('image') border-rose-400 @enderror">
            @error('image') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
            <p class="mt-2 text-xs text-slate-400">Supported: JPEG, PNG, JPG, GIF. Max: 2 MB</p>
            @if($course->image)
            <div class="mt-3">
              <img src="{{ Storage::url($course->image) }}" alt="Current" class="w-24 h-24 rounded-xl object-cover shadow-lg border-2 border-white/20">
              <label class="inline-flex items-center gap-2 mt-2 text-sm text-slate-300">
                <input type="checkbox" name="remove_image" value="1" class="rounded bg-white/10 border-white/20 text-indigo-400 focus:ring-indigo-400"> Remove current image
              </label>
            </div>
            @endif
          </div>
        </div>

        <!-- Footer Actions -->
        <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-white/10">
          <a href="{{ route('courses.index') }}" class="px-5 py-2.5 border border-white/20 text-slate-300 rounded-xl hover:bg-white/10 transition duration-200 font-medium">
            Cancel
          </a>
          <button type="submit" id="saveBtn" class="group px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 focus:ring-offset-[#1e293b] transition duration-200 font-medium">
            <span class="btn-text">Save Changes</span>
            <i class="btn-icon fas fa-save ml-2 group-hover:scale-110 transition"></i>
          </button>
        </div>
      </form>
    </div>

  </div>


  <!-- ===== DELETE MODAL ===== -->
<div x-data="{ open: false }" @open-delete.window="open = true" x-show="open" x-transition class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
  <div class="bg-[#1e293b] border border-white/10 rounded-2xl shadow-xl w-full max-w-sm mx-4 p-6 text-center text-slate-100">
    <div class="w-14 h-14 grid place-items-center rounded-full bg-rose-500/20 text-rose-400 mx-auto mb-4">
      <i class="fas fa-exclamation-triangle text-2xl"></i>
    </div>
    <h3 class="text-lg font-semibold">Delete Course?</h3>
    <p class="text-slate-400 text-sm mt-2">This will also remove all modules & enrolments. Action cannot be undone.</p>
    <div class="flex items-center justify-center gap-3 mt-6">
      <button @click="open = false" class="px-4 py-2 border border-white/20 text-slate-300 rounded-lg hover:bg-white/10 transition">Cancel</button>
      <!-- Button triggers the **external** form -->
      <button @click="$refs.deleteForm.submit()" class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition">Delete</button>
    </div>
  </div>
</div>

<!-- INVISIBLE FORM OUTSIDE x-show -->
<form x-ref="deleteForm" action="{{ route('courses.destroy',$course) }}" method="POST" class="hidden">
  @csrf @method('DELETE')
</form>

</div>

<script>
  // Loading state on save
  document.getElementById('courseForm').addEventListener('submit', function () {
    const btn = document.getElementById('saveBtn');
    const btnText = btn.querySelector('.btn-text');
    const btnIcon = btn.querySelector('.btn-icon');
    btn.disabled = true;
    btn.classList.add('opacity-80', 'cursor-not-allowed');
    btnText.innerHTML = 'Saving...';
    btnIcon.classList.remove('fa-save');
    btnIcon.classList.add('fa-spinner', 'fa-spin');
  });
</script>

@endsection
