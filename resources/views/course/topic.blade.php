@extends('layouts.app')
@section('title', 'Topic Management')
@section('content')
<div class="space-y-8">

  <!-- ===== HEADER SECTION ===== -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-panel rounded-3xl p-6 sm:p-8 shadow-xl border-glow">
    <div class="flex items-center gap-3.5">
      <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 shrink-0">
        <i class="fas fa-folder-tree text-xl"></i>
      </div>
      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-heading">
          <span class="text-indigo-300 font-extrabold">Topic Management</span>
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 mt-0.5">
          Module: <span class="font-extrabold text-indigo-400">{{ $module->title }}</span>
        </p>
      </div>
    </div>

    <div class="flex items-center gap-3 shrink-0">
      <a href="{{ route('courses.modules', $module->course_id ?? 1) }}"
         class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border border-slate-700/80 bg-slate-800/80 hover:bg-slate-800 text-slate-300 hover:text-white font-bold text-xs transition shadow-sm">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Modules</span>
      </a>
      <button id="createTopicBtn" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 hover:scale-[1.02] active:scale-95 transition-all">
        <i class="fas fa-plus"></i>
        <span>Add Topic</span>
      </button>
    </div>
  </div>

  <!-- ===== TOPICS TABLE CARD ===== -->
  <div class="glass-panel rounded-3xl shadow-xl overflow-hidden">
    <!-- Header bar -->
    <div class="p-6 border-b border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <h2 class="text-lg font-bold text-white font-heading">Topics List</h2>
        <span class="px-3 py-0.5 rounded-full text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
          {{ $topics->count() }} Topics
        </span>
      </div>

      <!-- Search Filter Bar -->
      <div class="relative max-w-xs w-full">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
          <i class="fas fa-search text-xs"></i>
        </div>
        <input type="text" id="topicSearchInput" onkeyup="filterTopicTable()" placeholder="Search topic titles..."
               class="w-full pl-9 pr-4 py-2.5 bg-slate-900/80 border border-slate-700/80 rounded-2xl text-xs font-semibold text-slate-200 placeholder-slate-500 focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500 transition">
      </div>
    </div>

    <!-- Table View -->
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse" id="topicsTable">
        <thead>
          <tr class="bg-slate-900/90 border-b border-slate-800 text-[11px] font-extrabold uppercase tracking-wider text-slate-400 font-heading">
            <th class="px-6 py-4">Topic Details</th>
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
          <tr class="hover:bg-slate-800/40 transition-colors group topic-row">
            <!-- Topic Title & Preview -->
            <td class="px-6 py-4">
              <div class="flex items-center space-x-3.5">
                <div class="w-10 h-10 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold text-sm shrink-0">
                  <i class="fas fa-file-lines"></i>
                </div>
                <div>
                  <p class="font-bold text-slate-200 group-hover:text-indigo-400 transition-colors topic-title font-heading">
                    {{ $topicItem->title }}
                  </p>
                  @if($topicItem->video_url)
                    <p class="text-xs text-indigo-400/80 flex items-center gap-1 mt-0.5">
                      <i class="fab fa-youtube text-[10px]"></i> Video Included
                    </p>
                  @endif
                </div>
              </div>
            </td>

            <!-- Module -->
            <td class="px-6 py-4 text-slate-300 text-xs font-semibold">
              {{ $topicItem->module->title ?? 'N/A' }}
            </td>

            <!-- Course -->
            <td class="px-6 py-4 text-slate-400 text-xs">
              {{ $topicItem->module->course->title ?? 'N/A' }}
            </td>

            <!-- Order -->
            <td class="px-6 py-4 font-mono text-xs font-bold text-slate-300">
              #{{ $topicItem->order }}
            </td>

            <!-- Status -->
            <td class="px-6 py-4">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border {{ $topicItem->is_active ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border-amber-500/20' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $topicItem->is_active ? 'bg-emerald-400' : 'bg-amber-400' }}"></span>
                {{ $topicItem->is_active ? 'Active' : 'Draft' }}
              </span>
            </td>

            <!-- Created -->
            <td class="px-6 py-4 text-xs font-medium text-slate-400">
              {{ $topicItem->created_at ? $topicItem->created_at->format('M d, Y') : 'N/A' }}
            </td>

            <!-- Actions -->
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end space-x-2">
                <a href="{{ route('admin.topics.edit', $topicItem->id) }}"
                   class="p-2 rounded-xl text-slate-400 hover:text-indigo-400 hover:bg-slate-800 transition-all"
                   title="Edit Topic">
                  <i class="fas fa-pen text-sm"></i>
                </a>

                <form action="{{ route('admin.topics.destroy', $topicItem->id) }}" method="POST"
                      class="inline delete-topic-form">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                          class="p-2 rounded-xl text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-all"
                          title="Delete Topic">
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
                <i class="fas fa-folder-open text-2xl"></i>
              </div>
              <p class="font-bold text-slate-200">No topics created for this module</p>
              <p class="text-xs text-slate-400 mt-1 mb-4">Add your first learning topic to get started.</p>
              <button id="emptyCreateTopicBtn" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white font-bold text-xs rounded-xl shadow-md">
                <i class="fas fa-plus"></i> Create Topic
              </button>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    @if($topics->hasPages())
    <div class="p-4 border-t border-slate-800">
      {{ $topics->links() }}
    </div>
    @endif
  </div>
</div>

<!-- ===== TOPIC FORM MODAL POPUP ===== -->
<div id="topicModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md hidden items-center justify-center z-50 p-4">

  <div class="bg-slate-900 w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-3xl shadow-2xl relative border border-slate-800 flex flex-col">

    <!-- Modal Header -->
    <div class="sticky top-0 bg-slate-900/95 backdrop-blur-md border-b border-slate-800 px-6 py-4 flex justify-between items-center z-10">
      <h2 class="text-lg font-bold text-white flex items-center gap-2 font-heading">
        <i class="fas fa-folder-plus text-indigo-400"></i>
        Create Learning Topic
      </h2>
      <button id="closeTopicModal" class="w-8 h-8 rounded-xl bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 flex items-center justify-center transition">
        <i class="fas fa-xmark"></i>
      </button>
    </div>

    <!-- Form Content -->
    <form method="POST"
          action="{{ route('admin.topics.store') }}"
          enctype="multipart/form-data"
          id="myForm"
          class="p-6 sm:p-8 space-y-6">
      @csrf

      <input type="hidden" name="module_id" value="{{ $module->id }}">

      <!-- Title -->
      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">
          Topic Title <span class="text-rose-400">*</span>
        </label>
        <input type="text" name="title" value="{{ old('title', $topic->title ?? '') }}" required
               placeholder="e.g., Introduction to Variable Types"
               class="w-full px-4 py-3 bg-slate-800/90 border border-slate-700/90 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium placeholder-slate-500">
        @error('title')
            <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Order & Status Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">Display Order</label>
          <input type="number" name="order" value="{{ old('order', $topic->order ?? 0) }}" min="0"
                 class="w-full px-4 py-2.5 bg-slate-800/90 border border-slate-700/90 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium">
          @error('order')
              <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">Status</label>
          <select name="is_active"
                  class="w-full px-4 py-2.5 bg-slate-800/90 border border-slate-700/90 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium">
            <option value="1" {{ old('is_active', $topic->is_active ?? 1) ? 'selected' : '' }}>Active</option>
            <option value="0" {{ !old('is_active', $topic->is_active ?? 1) ? 'selected' : '' }}>Draft</option>
          </select>
        </div>
      </div>

      <!-- Video URL -->
      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">YouTube Video URL</label>
        <div class="relative">
          <i class="fab fa-youtube text-rose-500 absolute left-3.5 top-3.5 text-sm"></i>
          <input type="url" name="video_url" value="{{ old('video_url', $topic->video_url ?? '') }}"
                 placeholder="https://www.youtube.com/watch?v=..."
                 class="w-full pl-9 pr-4 py-2.5 bg-slate-800/90 border border-slate-700/90 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium placeholder-slate-500">
        </div>
        @error('video_url')
            <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Document Upload -->
      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">Document File (PDF or PPTX)</label>
        <input type="file" name="document" accept=".pdf,.pptx,application/pdf,application/vnd.openxmlformats-officedocument.presentationml.presentation"
               class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-2xl text-xs text-slate-300 file:mr-3 file:px-3 file:py-1 file:rounded-xl file:border-0 file:bg-indigo-600 file:text-white file:font-semibold">
        <p class="text-[11px] text-slate-400 mt-1">Maximum file size allowed: 10MB</p>
        @error('document')
            <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Action Footer -->
      <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
        <button type="button" id="cancelTopicBtn" class="px-4 py-2.5 bg-slate-800 text-slate-300 rounded-2xl font-bold text-xs hover:bg-slate-700 transition">
          Cancel
        </button>
        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 text-white rounded-2xl font-bold text-xs shadow-lg shadow-indigo-600/30 transition">
          <i class="fas fa-floppy-disk text-xs"></i>
          <span>Save Topic</span>
        </button>
      </div>

    </form>
  </div>
</div>

<script>
function filterTopicTable() {
    const input = document.getElementById('topicSearchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.topic-row');

    rows.forEach(row => {
        const title = row.querySelector('.topic-title')?.textContent.toLowerCase() || '';
        if (title.includes(input)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const openBtn = document.getElementById("createTopicBtn");
    const emptyOpenBtn = document.getElementById("emptyCreateTopicBtn");
    const modal = document.getElementById("topicModal");
    const closeBtn = document.getElementById("closeTopicModal");
    const cancelBtn = document.getElementById("cancelTopicBtn");

    function openModal() {
        if (modal) {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        }
    }

    function closeModal() {
        if (modal) {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }
    }

    if (openBtn) openBtn.addEventListener("click", openModal);
    if (emptyOpenBtn) emptyOpenBtn.addEventListener("click", openModal);
    if (closeBtn) closeBtn.addEventListener("click", closeModal);
    if (cancelBtn) cancelBtn.addEventListener("click", closeModal);

    if (modal) {
        modal.addEventListener("click", (e) => {
            if (e.target === modal) closeModal();
        });
    }

    // Delete confirmation dialogs
    document.querySelectorAll('.delete-topic-form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const result = await Swal.fire({
                title: 'Delete Topic?',
                text: 'Are you sure you want to delete this topic?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#334155',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                background: '#0f172a',
                color: '#fff',
                customClass: {
                    popup: 'rounded-2xl border border-slate-800'
                }
            });

            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endsection
