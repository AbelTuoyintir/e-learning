@extends('layouts.app')
@section('title','Modules')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-[#0f172a] to-[#4c86e2] text-slate-100"
     x-data="moduleManager()">

  <!-- ===== HEADER ===== -->
  <header class="max-w-7xl mx-auto px-6 pt-10 pb-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-purple-400">
          Modules
        </h1>
        <p class="text-slate-400 text-sm mt-1">Organise course content into bite-sized modules</p>
      </div>
      <button @click="slideOpen = true"  class="group px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur rounded-xl border border-white/20 flex items-center gap-2 transition">
        <i class="fas fa-plus text-indigo-400 group-hover:scale-110 transition"></i>
        <span>New Module</span>
      </button>
    </div>
  </header>

  <!-- ===== STATS ===== -->
  <section class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @php
      $total = $modules->count();
      $active = $modules->where('is_active',1)->count();
    @endphp
    <x-stat-card value="{{ $total }}" label="Total" icon="fa-layer-group" color="indigo"/>
    <x-stat-card value="{{ $active }}" label="Active" icon="fa-check-circle" color="green"/>
    <x-stat-card value="{{ $total - $active }}" label="Draft" icon="fa-file-alt" color="orange"/>
    <x-stat-card value="{{ $modules->avg('duration_minutes') ?? 0 }}m" label="Avg" icon="fa-clock" color="purple"/>
  </section>

  <!-- ===== SEARCH / FILTER ===== -->
  <section class="max-w-7xl mx-auto px-6 mb-6">
    <div class="relative">
      <input x-model="search" type="text" placeholder="Search modules..." class="w-full pl-10 pr-4 py-3 bg-white/5 backdrop-blur rounded-xl border border-white/10 placeholder-slate-400 focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition">
      <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
    </div>
  </section>

  <!-- ===== BULK BAR ===== -->
  <template x-if="selected.length">
    <div class="max-w-7xl mx-auto px-6 mb-4">
      <div class="bg-white/5 backdrop-blur border border-white/10 rounded-xl p-3 flex items-center justify-between">
        <span class="text-sm text-slate-300"><span x-text="selected.length"></span> selected</span>
        <div class="flex items-center gap-2">
          <button @click="bulkStatus(1)" class="px-3 py-1.5 bg-green-500/20 text-green-300 rounded-lg text-xs hover:bg-green-500/30 transition">Mark Active</button>
          <button @click="bulkStatus(0)" class="px-3 py-1.5 bg-orange-500/20 text-orange-300 rounded-lg text-xs hover:bg-orange-500/30 transition">Mark Draft</button>
          <button @click="bulkDelete" class="px-3 py-1.5 bg-rose-500/20 text-rose-300 rounded-lg text-xs hover:bg-rose-500/30 transition">Delete</button>
        </div>
      </div>
    </div>
  </template>

  <!-- ===== MODULE LIST ===== -->
  <section class="max-w-7xl mx-auto px-6 pb-10">
    <div class="space-y-3" id="moduleList">
      <template x-for="mod in filteredModules()" :key="mod.id">
        <div class="group bg-white/5 backdrop-blur rounded-2xl border border-white/10 p-5 hover:border-white/20 transition">

          <!-- Row -->
          <div class="flex items-center justify-between gap-4">

            <!-- Left -->
            <div class="flex items-center gap-4">
              <input type="checkbox" :checked="selected.includes(mod.id)" @change="toggleSelect(mod.id)" class="w-4 h-4 rounded bg-white/10 border-white/20 text-indigo-400 focus:ring-indigo-400">
              <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 grid place-items-center text-white text-lg shadow-lg">
                <i class="fas fa-layer-group"></i>
              </div>
              <div>
                <h3 class="font-bold text-slate-100" x-text="mod.title"></h3>
                <p class="text-sm text-slate-400" x-text="mod.course?.title ?? '—'"></p>
              </div>
            </div>

            <!-- Center -->
            <div class="hidden md:flex items-center gap-6 text-sm text-slate-300">
              <div class="flex items-center gap-2"><i class="fas fa-sort-numeric-up text-indigo-400"></i><span x-text="'Order: '+mod.order"></span></div>
              <div class="flex items-center gap-2"><i class="fas fa-clock text-indigo-400"></i><span x-text="mod.duration_minutes+' min'"></span></div>
              <div class="flex items-center gap-2">
                <span class="px-2 py-1 text-xs rounded-full" :class="mod.is_active ? 'bg-green-400/20 text-green-300' : 'bg-orange-400/20 text-orange-300'" x-text="mod.is_active ? 'Active' : 'Draft'"></span>
              </div>
            </div>

            <!-- Right -->
            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition">
              <button @click="toggleStatus(mod)" class="p-2 text-slate-300 hover:text-white transition" title="Toggle status"><i class="fas fa-sync"></i></button>
              <button @click=editModule(mod)" class="p-2 text-slate-300 hover:text-white transition" title="Edit"><i class="fas fa-edit"></i></button>
             <a  href="{{ route('admin.topics.create', $module->id) }}"
                class="p-2 text-green-400 hover:text-green-300 transition"
                title="Add Topic">
                    <i class="fas fa-book-open"></i>
                </a>

              <button @click="deleteModule(mod.id)" class="p-2 text-rose-400 hover:text-rose-300 transition" title="Delete"><i class="fas fa-trash"></i></button>
            </div>
          </div>

        </div>
      </template>

      <!-- Empty -->
      <template x-if="!filteredModules().length">
        <div class="text-center py-16 text-slate-400">
          <i class="fas fa-inbox text-5xl mb-4"></i>
          <p>No modules found.</p>
        </div>
      </template>
    </div>
  </section>

  <!-- ===== SLIDE-OVER FORM ===== -->
  <div x-show="slideOpen" x-transition class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50" @click.self="slideOpen=false">
    <div class="absolute right-0 top-0 h-full w-full max-w-md bg-[#1e293b] shadow-2xl flex flex-col border-l border-white/10" x-show="slideOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b border-white/10">
        <h3 class="text-lg font-semibold text-slate-100" x-text="editMode ? 'Edit Module' : 'New Module'"></h3>
        <button @click="slideOpen=false" class="text-slate-400 hover:text-white transition"><i class="fas fa-times"></i></button>
      </div>

   @php
        // Determine if we are in edit mode
        $isEdit = isset($module); // pass $module from controller when editing
    @endphp

<form action="{{ $isEdit ? route('modules.update', $module->id) : route('modules.store', $course->id) }}" method="POST" class="flex-1 overflow-y-auto p-6 space-y-5">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <h1 class="text-blue-600 text-center text-bold text-2xl">Module Details</h1>

    <input type="hidden" name="course_id" value="{{ $course->id }}">

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Title *</label>
        <input type="text" name="title" value="{{ old('title', $isEdit ? $module->title : '') }}" required
            placeholder="e.g. Introduction to HTML"
            class="w-full px-4 py-2 text-gray-950 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-400">
    </div>

    <div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Order</label>
            <input type="number" name="order" value="{{ old('order', $isEdit ? $module->order : 0) }}" min="0"
                class="w-full px-4 py-2 text-gray-950 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-400">
        </div>
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $isEdit ? $module->is_active : 1) ? 'checked' : '' }}
            class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
        <label for="is_active" class="text-sm text-slate-700">Active immediately</label>
    </div>

    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
        <a href="{{ route('courses.modules', $course->id) }}" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition">Cancel</a>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
            {{ $isEdit ? 'Update Module' : 'Save Module' }}
        </button>
    </div>
</form>


<!-- Delete Form - Fixed to handle null deleteId -->
<form x-ref="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

</div>

<script>
function moduleManager() {
  return {
    modules: @json($modules->values()),
    search: '',
    courseFilter: '',
    statusFilter: '',
    selected: [],
    slideOpen: false,
    editMode: false,
    deleteId: null,
    form: { id:null, course_id:'', title:'', order:0, duration_minutes:30, is_active:true },

    filteredModules() {
      return this.modules.filter(m =>
        m.title.toLowerCase().includes(this.search.toLowerCase()) &&
        (this.courseFilter === '' || m.course_id == this.courseFilter) &&
        (this.statusFilter === '' || m.is_active == this.statusFilter)
      );
    },

    toggleSelect(id) {
      this.selected = this.selected.includes(id)
        ? this.selected.filter(i => i !== id)
        : [...this.selected, id];
    },

    toggleStatus(mod) {
      mod.is_active = !mod.is_active;
    },

    bulkStatus(status) {
      this.modules.filter(m => this.selected.includes(m.id)).forEach(m => m.is_active = status);
      this.selected = [];
    },

    bulkDelete() {
      if (confirm(`Delete ${this.selected.length} module(s)?`)) {
        // axios.post('/modules/bulk-delete', { ids: this.selected }).then(() => location.reload());
        this.selected = [];
      }
    },

    deleteModule(id) {
      this.deleteId = id;   // only store ID → form is outside x-show
    },

    editModule(mod) {
      this.form = { ...mod };
      this.editMode = true;
      this.slideOpen = true;
    }
  };
}
</script>

@endsection
