@extends('layouts.app')
@section('title', 'Course Modules')
@section('content')

<div class="space-y-8" x-data="moduleManager()" x-init="init()">
    
    <!-- ===== HEADER SECTION ===== -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 via-indigo-600 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                <i class="fas fa-layer-group text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-heading">
                    Course <span class="text-indigo-400">Modules</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Organize course content into structured learning modules</p>
            </div>
        </div>

        <button @click="openPanel('create')"
                class="group px-5 py-3 bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 hover:from-indigo-600 hover:to-purple-700 rounded-2xl font-bold text-xs text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 transform hover:scale-105 flex items-center gap-2">
            <i class="fas fa-plus group-hover:rotate-90 transition-transform duration-300"></i>
            <span>New Module</span>
        </button>
    </div>
    
    <!-- ===== STATS CARDS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Total Modules Card -->
        <div class="glass-card rounded-2xl p-5 shadow-lg flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 font-heading">Total</span>
                <p class="text-2xl font-extrabold text-white mt-1 font-heading">{{ $modules->count() }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Available modules</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-base">
                <i class="fas fa-layer-group"></i>
            </div>
        </div>

        <!-- Active Modules Card -->
        <div class="glass-card rounded-2xl p-5 shadow-lg flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 font-heading">Active</span>
                <p class="text-2xl font-extrabold text-white mt-1 font-heading">{{ $modules->where('is_active', 1)->count() }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Published modules</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-base">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>

        <!-- Draft Modules Card -->
        <div class="glass-card rounded-2xl p-5 shadow-lg flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 font-heading">Draft</span>
                <p class="text-2xl font-extrabold text-white mt-1 font-heading">{{ $modules->where('is_active', 0)->count() }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">In draft mode</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-base">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>

        <!-- Avg Duration Card -->
        <div class="glass-card rounded-2xl p-5 shadow-lg flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 font-heading">Avg Duration</span>
                <p class="text-2xl font-extrabold text-white mt-1 font-heading">{{ round($modules->avg('duration_minutes') ?? 0) }}m</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Minutes per module</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-base">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    
    <!-- ===== SEARCH & FILTER BAR ===== -->
    <div class="glass-card rounded-2xl p-4 shadow-lg">
        <div class="relative max-w-md w-full">
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input 
                x-model="search" 
                type="text" 
                placeholder="Search modules by title..." 
                class="w-full pl-9 pr-4 py-2 bg-slate-900/80 border border-slate-700/80 rounded-xl text-xs font-medium text-slate-200 placeholder-slate-500 focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500 transition"
            >
            <div x-show="search" x-transition class="absolute right-3 top-1/2 -translate-y-1/2">
                <button @click="search = ''" class="text-slate-400 hover:text-white transition">
                    <i class="fas fa-times-circle text-xs"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- ===== BULK ACTION BAR ===== -->
    <template x-if="selected.length > 0">
        <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="glass-card rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-3 border-indigo-500/30">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-xs">
                    <i class="fas fa-check-double"></i>
                </div>
                <span class="text-xs font-semibold text-slate-200">
                    <span x-text="selected.length" class="font-bold text-indigo-400"></span> module(s) selected
                </span>
            </div>
            <div class="flex items-center gap-2">
                <button @click="bulkStatus(1)" class="px-3 py-1.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 hover:bg-emerald-600 hover:text-white rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5">
                    <i class="fas fa-play-circle"></i>
                    <span>Activate All</span>
                </button>
                <button @click="bulkStatus(0)" class="px-3 py-1.5 bg-amber-500/20 text-amber-300 border border-amber-500/30 hover:bg-amber-600 hover:text-white rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5">
                    <i class="fas fa-pause-circle"></i>
                    <span>Draft All</span>
                </button>
                <button @click="bulkDelete" class="px-3 py-1.5 bg-rose-500/20 text-rose-300 border border-rose-500/30 hover:bg-rose-600 hover:text-white rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5">
                    <i class="fas fa-trash-alt"></i>
                    <span>Delete All</span>
                </button>
            </div>
        </div>
    </template>
    
    <!-- ===== MODULES LIST ===== -->
    <div class="space-y-4">
        <template x-for="mod in filteredModules()" :key="mod.id">
            <div class="glass-panel rounded-3xl p-6 shadow-xl hover:border-indigo-500/30 transition-all duration-300">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    <!-- Left Section - Checkbox & Info -->
                    <div class="flex items-start lg:items-center gap-4">
                        <div class="relative mt-1 lg:mt-0">
                            <input
                                type="checkbox"
                                :checked="selected.includes(mod.id)"
                                @change="toggleSelect(mod.id)"
                                class="w-5 h-5 rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500 focus:ring-2 cursor-pointer"
                            >
                        </div>
                        
                        <div class="relative shrink-0">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 text-indigo-400 flex items-center justify-center font-bold">
                                <i class="fas fa-book-open text-lg"></i>
                            </div>
                            <div class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-300" x-text="mod.order"></div>
                        </div>
                        
                        <div>
                            <h3 class="font-bold text-base text-slate-100 font-heading" x-text="mod.title"></h3>
                            <div class="flex flex-wrap items-center gap-3 mt-1">
                                <span class="text-xs text-slate-400 flex items-center gap-1">
                                    <i class="fas fa-book text-indigo-400 text-xs"></i>
                                    <span x-text="mod.course?.title ?? 'Unassigned'"></span>
                                </span>
                                <span class="text-xs text-slate-600">•</span>
                                <span class="text-xs text-slate-400 flex items-center gap-1">
                                    <i class="fas fa-clock text-purple-400 text-xs"></i>
                                    <span x-text="mod.duration_minutes + ' minutes'"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Section - Status & Actions -->
                    <div class="flex items-center justify-between lg:justify-end gap-4">
                        <!-- Status Badge -->
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-[11px] font-bold border flex items-center gap-1.5"
                                  :class="mod.is_active ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border-amber-500/20'">
                                <span class="w-1.5 h-1.5 rounded-full" :class="mod.is_active ? 'bg-emerald-400' : 'bg-amber-400'"></span>
                                <span x-text="mod.is_active ? 'Active' : 'Draft'"></span>
                            </span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-1">
                            <button @click="toggleStatus(mod)"
                                    class="p-2 rounded-xl text-slate-400 hover:text-indigo-400 hover:bg-slate-800 transition-all"
                                    :title="mod.is_active ? 'Deactivate' : 'Activate'">
                                <i class="fas fa-sync-alt text-sm"></i>
                            </button>
                            
                            <button @click="editModule(mod)"
                                    class="p-2 rounded-xl text-slate-400 hover:text-indigo-400 hover:bg-slate-800 transition-all"
                                    title="Edit Module">
                                <i class="fas fa-pen text-sm"></i>
                            </button>

                            <a :href="'{{ route('admin.topics.create', 999999) }}'.replace('999999', mod.id)"
                               class="p-2 rounded-xl text-slate-400 hover:text-emerald-400 hover:bg-slate-800 transition-all"
                               title="Manage Topics">
                                <i class="fas fa-book-open text-sm"></i>
                            </a>

                            <button @click="deleteModule(mod.id, mod.title)"
                                    class="p-2 rounded-xl text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-all"
                                    title="Delete Module">
                                <i class="fas fa-trash-can text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Empty State -->
        <template x-if="!filteredModules().length">
            <div class="glass-panel rounded-3xl p-12 text-center text-slate-400 border border-slate-800">
                <div class="w-16 h-16 rounded-2xl bg-slate-800 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-inbox text-2xl"></i>
                </div>
                <p class="font-bold text-slate-200">No modules found</p>
                <p class="text-xs text-slate-400 mt-1 mb-4">Try adjusting your search or create a new module.</p>
                <button @click="openPanel('create')" class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-indigo-600 text-white font-bold text-xs shadow-md">
                    <i class="fas fa-plus"></i> Create Module
                </button>
            </div>
        </template>
    </div>
    
    <!-- ===== SLIDE PANEL (Module Form Modal) ===== -->
    <div x-show="slideOpen" x-transition.opacity.duration.300 class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50" @click.self="closePanel()" style="display: none;"></div>
    
    <div class="fixed right-0 top-0 h-full w-full max-w-md bg-slate-900 shadow-2xl flex flex-col border-l border-slate-800 z-50"
         x-show="slideOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         style="display: none;">
        
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-slate-800 bg-slate-900/95 backdrop-blur-md">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-md">
                    <i class="fas fa-layer-group text-base"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white font-heading" x-text="editMode ? 'Edit Module' : 'Create Module'"></h3>
                    <p class="text-xs text-slate-400" x-text="editMode ? 'Update module details' : 'Add a new module to course'"></p>
                </div>
            </div>
            <button @click="closePanel()" class="w-8 h-8 rounded-xl bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 flex items-center justify-center transition">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        
        <!-- Form -->
        <form @submit.prevent="submitForm" class="flex-1 overflow-y-auto p-6 space-y-5">
            
            <!-- Title -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">
                    Module Title <span class="text-rose-400">*</span>
                </label>
                <input 
                    type="text" 
                    x-model="form.title" 
                    required
                    placeholder="e.g., Introduction to Web Development"
                    class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium"
                >
            </div>
            
            <!-- Order & Duration Row -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">
                        Display Order
                    </label>
                    <input 
                        type="number" 
                        x-model="form.order" 
                        min="0"
                        class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium"
                    >
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">
                        Duration (min)
                    </label>
                    <input 
                        type="number" 
                        x-model="form.duration_minutes" 
                        min="1"
                        class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium"
                    >
                </div>
            </div>
            
            <!-- Active Status Toggle -->
            <div class="bg-slate-800/60 rounded-2xl p-4 border border-slate-700/80">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">
                            <i class="fas fa-power-off"></i>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-200 cursor-pointer font-heading">Active Status</label>
                            <p class="text-[11px] text-slate-400">Publish module to enrolled students</p>
                        </div>
                    </div>
                    
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="form.is_active" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-700 rounded-full peer peer-checked:bg-indigo-600 transition-all duration-200"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all duration-200 peer-checked:translate-x-5"></div>
                    </label>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center gap-3 pt-4 border-t border-slate-800">
                <button type="button" @click="closePanel()" class="flex-1 px-4 py-2.5 bg-slate-800 text-slate-300 font-bold text-xs rounded-2xl hover:bg-slate-700 transition">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-2xl shadow-lg shadow-indigo-600/30 transition">
                    <span x-text="editMode ? 'Update Module' : 'Save Module'"></span>
                </button>
            </div>
        </form>
    </div>
    
</div>

<script>
    function moduleManager() {
        return {
            modules: @json($modules->values()),
            search: '',
            selected: [],
            slideOpen: false,
            editMode: false,
            isLoading: false,
            form: { 
                id: null, 
                course_id: '{{ $course->id ?? '' }}', 
                title: '', 
                order: 0, 
                duration_minutes: 30, 
                is_active: true 
            },

            filteredModules() {
                return this.modules.filter(m =>
                    m.title.toLowerCase().includes(this.search.toLowerCase())
                );
            },

            openPanel(mode = 'create', module = null) {
                this.slideOpen = true;
                if (mode === 'edit' && module) {
                    this.editMode = true;
                    this.form = { 
                        id: module.id,
                        course_id: module.course_id,
                        title: module.title,
                        order: module.order || 0,
                        duration_minutes: module.duration_minutes || 30,
                        is_active: module.is_active
                    };
                } else {
                    this.editMode = false;
                    this.resetForm();
                }
            },

            closePanel() {
                this.slideOpen = false;
                this.resetForm();
                this.editMode = false;
            },

            resetForm() {
                this.form = {
                    id: null,
                    course_id: '{{ $course->id ?? '' }}',
                    title: '',
                    order: this.modules.length + 1,
                    duration_minutes: 30,
                    is_active: true
                };
            },

            toggleSelect(id) {
                this.selected = this.selected.includes(id)
                    ? this.selected.filter(i => i !== id)
                    : [...this.selected, id];
            },

            toggleStatus(mod) {
                mod.is_active = !mod.is_active;
                this.saveModuleStatus(mod);
                if (window.showSuccess) window.showSuccess(`Module status updated to ${mod.is_active ? 'Active' : 'Draft'}`);
            },

            saveModuleStatus(mod) {
                fetch(`/admin/modules/${mod.id}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ is_active: mod.is_active })
                }).catch(error => console.error('Error:', error));
            },

            bulkStatus(status) {
                if (this.selected.length === 0) return;
                this.modules.filter(m => this.selected.includes(m.id)).forEach(m => m.is_active = status);
                this.bulkUpdateStatus(status);
                this.selected = [];
                if (window.showSuccess) window.showSuccess('Bulk status updated');
            },

            bulkUpdateStatus(status) {
                fetch('/admin/modules/bulk-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ ids: this.selected, is_active: status })
                }).catch(error => console.error('Error:', error));
            },

            bulkDelete() {
                if (this.selected.length === 0) return;
                if (confirm(`Delete ${this.selected.length} modules?`)) {
                    this.executeBulkDelete();
                }
            },

            executeBulkDelete() {
                fetch('/admin/modules/bulk-delete', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ ids: this.selected })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.modules = this.modules.filter(m => !this.selected.includes(m.id));
                        this.selected = [];
                        if (window.showSuccess) window.showSuccess('Modules deleted');
                    }
                });
            },

            deleteModule(id, title) {
                if (confirm(`Delete module "${title}"?`)) {
                    this.executeDelete(id);
                }
            },

            executeDelete(id) {
                fetch(`/admin/modules/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.modules = this.modules.filter(m => m.id !== id);
                        if (window.showSuccess) window.showSuccess('Module deleted');
                    }
                });
            },

            editModule(mod) {
                this.openPanel('edit', mod);
            },

            submitForm() {
                if (!this.form.title.trim()) return;

                const url = this.editMode ? `/admin/modules/${this.form.id}` : `/admin/courses/${this.form.course_id}/modules/store`;
                const method = this.editMode ? 'PUT' : 'POST';
                
                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(this.form)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (this.editMode) {
                            const index = this.modules.findIndex(m => m.id === this.form.id);
                            if (index !== -1) {
                                this.modules[index] = { ...this.form };
                            }
                            if (window.showSuccess) window.showSuccess('Module updated');
                        } else {
                            this.modules.push(data.module || this.form);
                            if (window.showSuccess) window.showSuccess('Module created');
                        }
                        this.closePanel();
                    }
                });
            },

            init() {
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.slideOpen) {
                        this.closePanel();
                    }
                });
            }
        };
    }
</script>

@endsection