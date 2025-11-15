@extends('layouts.app')   {{-- your admin layout --}}
@section('title','Edit Course')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-50">
  <div class="max-w-5xl mx-auto px-6 py-10">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Edit Course</h1>
        <p class="mt-2 text-slate-500">Update course details and save changes</p>
      </div>
      <div class="flex items-center gap-3">
        {{-- Delete trigger --}}
        <button x-data @click="$dispatch('open-delete')" class="px-4 py-2 border border-rose-300 text-rose-600 rounded-xl hover:bg-rose-50 transition">
          <i class="fas fa-trash mr-2"></i>Delete
        </button>
        <a href="{{ route('courses.show',$course) }}" target="_blank" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-xl hover:bg-slate-50 transition">
          <i class="fas fa-eye mr-2"></i>Preview
        </button>
        <button form="courseForm" type="submit" id="saveBtn" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 transition duration-200 font-medium">
          <span class="btn-text">Save Changes</span>
          <i class="btn-icon fas fa-save ml-2"></i>
        </button>
      </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">

      <!-- Card Header -->
      <div class="h-32 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute bottom-4 left-6 text-white">
          <div class="text-2xl font-bold">{{ $course->title }}</div>
          <div class="text-sm opacity-90">Last updated {{ $course->updated_at->diffForHumans() }}</div>
        </div>
        @if($course->image)
        <img src="{{ Storage::url($course->image) }}" alt="" class="absolute right-6 -bottom-6 w-24 h-24 rounded-2xl shadow-lg border-4 border-white object-cover">
        @endif
      </div>

      <!-- Form Body -->
      <form id="courseForm" action="{{ route('courses.update',$course) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
        @csrf @method('PUT')

        <!-- Course Name -->
        <div>
          <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Course Name *</label>
          <input type="text" name="title" id="name" value="{{ old('title',$course->title) }}" required
                 class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('title') border-red-300 @enderror">
          @error('title') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
          <textarea name="description" id="description" rows="4" placeholder="Enter course description..."
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('description') border-red-300 @enderror">{{ old('description',$course->description) }}</textarea>
          @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Two Column Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Instructor -->
          <div>
            <label for="instructor" class="block text-sm font-semibold text-slate-700 mb-2">Instructor</label>
            <input type="text" name="instructor" id="instructor" value="{{ old('instructor',$course->instructor) }}" placeholder="Instructor name"
                   class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('instructor') border-red-300 @enderror">
            @error('instructor') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>

          <!-- Duration -->
          <div>
            <label for="duration" class="block text-sm font-semibold text-slate-700 mb-2">Duration (hours)</label>
            <input type="number" name="duration" id="duration" min="0" value="{{ old('duration',$course->duration) }}" placeholder="Course duration"
                   class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('duration') border-red-300 @enderror">
            @error('duration') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>
        </div>

        <!-- Two Column Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Category -->
          <div>
            <label for="category" class="block text-sm font-semibold text-slate-700 mb-2">Category</label>
            <input type="text" name="category" id="category" value="{{ old('category',$course->category) }}" placeholder="Course category"
                   class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('category') border-red-300 @enderror">
            @error('category') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>

          <!-- Image Upload -->
          <div>
            <label for="image" class="block text-sm font-semibold text-slate-700 mb-2">Course Image</label>
            <input type="file" name="image" id="image" accept="image/*"
                   class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('image') border-red-300 @enderror">
            @error('image') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            <p class="mt-2 text-xs text-slate-500">Supported: JPEG, PNG, JPG, GIF. Max: 2 MB</p>
            @if($course->image)
            <div class="mt-3">
              <img src="{{ Storage::url($course->image) }}" alt="Current" class="w-24 h-24 rounded-xl object-cover shadow">
              <label class="inline-flex items-center gap-2 mt-2 text-sm text-slate-600">
                <input type="checkbox" name="remove_image" value="1"> Remove current image
              </label>
            </div>
            @endif
          </div>
        </div>

        <!-- Footer Actions -->
        <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-slate-200">
          <a href="{{ route('courses.index') }}" class="px-5 py-2.5 border border-slate-300 text-slate-700 rounded-xl hover:bg-slate-50 transition duration-200 font-medium">
            Cancel
          </a>
          <button type="submit" id="saveBtn" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 transition duration-200 font-medium">
            <span class="btn-text">Save Changes</span>
            <i class="btn-icon fas fa-save ml-2"></i>
          </button>
        </div>
      </form>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ open: false }" @open-delete.window="open = true" x-show="open" x-transition class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-6 text-center">
        <div class="w-14 h-14 grid place-items-center rounded-full bg-rose-100 text-rose-600 mx-auto mb-4">
          <i class="fas fa-exclamation-triangle text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-slate-800">Delete Course?</h3>
        <p class="text-slate-500 text-sm mt-2">This will also remove all modules & enrolments. Action cannot be undone.</p>
        <div class="flex items-center justify-center gap-3 mt-6">
          <button @click="open = false" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition">Cancel</button>
          <form action="{{ route('courses.destroy',$course) }}" method="POST" class="inline">
            @csrf @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition">Delete</button>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
  // Password toggle (if you use a password field)
  function togglePassword() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
      pwd.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      pwd.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }

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
