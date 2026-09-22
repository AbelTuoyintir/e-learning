@extends('layouts.app')
@section('title', 'Course Modules')
@section('content')

<style>
    /* Custom scrollbar for the panel */
    .module-panel-scroll::-webkit-scrollbar {
        width: 5px;
    }
    
    .module-panel-scroll::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.6);
        border-radius: 10px;
    }
    
    .module-panel-scroll::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 10px;
    }
    
    /* Number input styling */
    input[type="number"] {
        -moz-appearance: textfield;
    }
    
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        opacity: 0.5;
    }
    
    /* Loading spinner */
    .loading-spinner {
        border: 2px solid rgba(99, 102, 241, 0.2);
        border-top-color: #6366f1;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Status badge styles */
    .badge-active {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    
    .badge-draft {
        background: rgba(245, 158, 11, 0.15);
        color: #fbbf24;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
</style>

<div class="space-y-8" x-data="moduleManager()" x-init="init()">
    
    <!-- ===== HEADER SECTION ===== -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-panel rounded-3xl p-6 sm:p-8 shadow-xl border-glow">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 shrink-0">
                <i class="fas fa-layer-group text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-heading">
                    Course <span class="text-indigo-300 font-extrabold">Modules</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Organize your course content into structured learning modules</p>
            </div>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border border-slate-700/80 bg-slate-800/80 hover:bg-slate-800 text-slate-300 hover:text-white font-bold text-xs transition shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Courses</span>
            </a>
            <button @click="openPanel('create')"
                    class="group px-5 py-2.5 bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 hover:from-indigo-600 hover:to-purple-700 rounded-2xl font-bold text-xs text-white shadow-lg shadow-indigo-600/30 hover:scale-[1.02] active:scale-95 transition-all flex items-center gap-2">
                <i class="fas fa-plus group-hover:rotate-90 transition-transform duration-300"></i>
                <span>New Module</span>
            </button>
        </div>
    </div>
    
    <!-- ===== STATS CARDS ===== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Modules Card -->
        <div class="glass-card rounded-2xl p-5 shadow-lg flex items-center justify-between group">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 font-heading">Total Modules</span>
                <p class="text-2xl font-extrabold text-white mt-1 font-heading">{{ $modules->count() }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Available in course</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 group-hover:scale-110 transition-transform">
                <i class="fas fa-layer-group text-lg"></i>
            </div>
        </div>

        <!-- Active Modules Card -->
        <div class="glass-card rounded-2xl p-5 shadow-lg flex items-center justify-between group">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 font-heading">Active</span>
                <p class="text-2xl font-extrabold text-white mt-1 font-heading">{{ $modules->where('is_active', 1)->count() }}</p>
                <p class="text-[11px] text-emerald-400 font-semibold mt-0.5">Published modules</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 group-hover:scale-110 transition-transform">
                <i class="fas fa-circle-check text-lg"></i>
            </div>
        </div>

        <!-- Draft Modules Card -->
        <div class="glass-card rounded-2xl p-5 shadow-lg flex items-center justify-between group">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 font-heading">Draft</span>
                <p class="text-2xl font-extrabold text-white mt-1 font-heading">{{ $modules->where('is_active', 0)->count() }}</p>
                <p class="text-[11px] text-amber-400 font-semibold mt-0.5">In draft mode</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 group-hover:scale-110 transition-transform">
                <i class="fas fa-file-pen text-lg"></i>
            </div>
        </div>

        <!-- Avg Duration Card -->
        <div class="glass-card rounded-2xl p-5 shadow-lg flex items-center justify-between group">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 font-heading">Avg Duration</span>
                <p class="text-2xl font-extrabold text-white mt-1 font-heading">{{ round($modules->avg('duration_minutes') ?? 0) }}m</p>
                <p class="text-[11px] text-purple-400 font-semibold mt-0.5">Minutes per module</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 group-hover:scale-110 transition-transform">
                <i class="fas fa-clock text-lg"></i>
            </div>
        </div>
    </div>
    
    <!-- ===== SEARCH & FILTER BAR ===== -->
    <div class="glass-card rounded-2xl p-4 shadow-lg flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[200px]">
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input 
                x-model="search" 
                type="text" 
                placeholder="Search modules by title..." 
                class="w-full pl-9 pr-8 py-2.5 bg-slate-900/80 border border-slate-700/80 rounded-2xl text-xs font-semibold text-slate-200 placeholder-slate-500 focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500 transition"
            >
            <div x-show="search" x-transition class="absolute right-3 top-1/2 -translate-y-1/2">
                <button @click="search = ''" class="text-slate-400 hover:text-slate-200 transition">
                    <i class="fas fa-circle-xmark text-xs"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- ===== BULK ACTION BAR ===== -->
    <template x-if="selected.length > 0">
        <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="glass-card rounded-2xl p-4 border border-indigo-500/30 flex flex-col sm:flex-row items-center justify-between gap-3 shadow-lg">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400">
                    <i class="fas fa-check-double text-xs"></i>
                </div>
                <span class="text-xs font-semibold text-slate-300">
                    <span x-text="selected.length" class="font-extrabold text-indigo-400"></span> module(s) selected
                </span>
            </div>
            <div class="flex items-center gap-2">
                <button @click="bulkStatus(1)" class="px-3.5 py-1.5 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/30 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                    <i class="fas fa-play-circle text-xs"></i>
                    <span>Activate All</span>
                </button>
                <button @click="bulkStatus(0)" class="px-3.5 py-1.5 bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-500/30 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                    <i class="fas fa-pause-circle text-xs"></i>
                    <span>Draft All</span>
                </button>
                <button @click="bulkDelete" class="px-3.5 py-1.5 bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/30 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                    <i class="fas fa-trash-can text-xs"></i>
                    <span>Delete All</span>
                </button>
            </div>
        </div>
    </template>
    
    <!-- ===== MODULES LIST ===== -->
    <div class="glass-panel rounded-3xl shadow-xl p-6 sm:p-8 space-y-4">
        <div class="space-y-3">
            <template x-for="mod in filteredModules()" :key="mod.id">
                <div class="glass-card rounded-2xl p-5 hover:border-indigo-500/40 transition-all duration-300 group">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        
                        <!-- Left Section - Checkbox & Info -->
                        <div class="flex items-start lg:items-center gap-4">
                            <div class="relative mt-1 lg:mt-0">
                                <input 
                                    type="checkbox" 
                                    :checked="selected.includes(mod.id)" 
                                    @change="toggleSelect(mod.id)" 
                                    class="w-5 h-5 rounded-lg border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500 focus:ring-2 cursor-pointer"
                                >
                            </div>
                            
                            <div class="relative shrink-0">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                                    <i class="fas fa-book-open text-lg"></i>
                                </div>
                                <div class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-[10px] font-extrabold text-indigo-300 shadow-sm" x-text="mod.order"></div>
                            </div>
                            
                            <div>
                                <h3 class="font-bold text-base text-slate-100 group-hover:text-indigo-300 transition-colors font-heading" x-text="mod.title"></h3>
                                <div class="flex flex-wrap items-center gap-3 mt-1">
                                    <span class="text-xs text-slate-400 flex items-center gap-1.5">
                                        <i class="fas fa-book text-indigo-400 text-xs"></i>
                                        <span x-text="mod.course?.title ?? 'Unassigned'"></span>
                                    </span>
                                    <span class="text-xs text-slate-600">•</span>
                                    <span class="text-xs text-slate-400 flex items-center gap-1.5">
                                        <i class="fas fa-clock text-purple-400 text-xs"></i>
                                        <span x-text="(mod.duration_minutes || mod.duration || 0) + ' minutes'"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Section - Status & Actions -->
                        <div class="flex items-center justify-between lg:justify-end gap-4 border-t lg:border-t-0 border-slate-800/80 pt-3 lg:pt-0">
                            <!-- Status Badge -->
                            <div class="flex items-center gap-2">
                                <div class="px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1.5"
                                     :class="mod.is_active ? 'badge-active' : 'badge-draft'">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="mod.is_active ? 'bg-emerald-400' : 'bg-amber-400'"></span>
                                    <span x-text="mod.is_active ? 'Active' : 'Draft'"></span>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center gap-1">
                                <button @click="toggleStatus(mod)" 
                                        class="p-2 rounded-xl text-slate-400 hover:text-indigo-400 hover:bg-slate-800 transition-all"
                                        :title="mod.is_active ? 'Deactivate' : 'Activate'">
                                    <i class="fas fa-rotate text-sm"></i>
                                </button>
                                
                                <button @click="editModule(mod)" 
                                        class="p-2 rounded-xl text-slate-400 hover:text-indigo-400 hover:bg-slate-800 transition-all"
                                        title="Edit Module">
                                    <i class="fas fa-pen text-sm"></i>
                                </button>
                                
                                <a :href="'{{ route('admin.topics.create', 999999) }}'.replace('999999', mod.id)"
                                   class="p-2 rounded-xl text-slate-400 hover:text-emerald-400 hover:bg-slate-800 transition-all"
                                   title="Manage Topics">
                                    <i class="fas fa-folder-tree text-sm"></i>
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
                <div class="text-center py-16 text-slate-400">
                    <div class="w-16 h-16 rounded-2xl bg-slate-800 text-slate-400 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-inbox text-2xl"></i>
                    </div>
                    <p class="font-bold text-slate-200 text-base">No modules found</p>
                    <p class="text-xs text-slate-400 mt-1">Try adjusting your search or create a new module</p>
                    <button @click="openPanel('create')" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-2xl shadow-lg transition">
                        <i class="fas fa-plus"></i> Create Module
                    </button>
                </div>
            </template>
        </div>
    </div>
    
    <!-- ===== SLIDE PANEL (Module Form) ===== -->
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
        
        <!-- Top Accent Bar -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
        
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-slate-800 bg-slate-900/95">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white shadow-md shrink-0">
                    <i class="fas fa-layer-group text-base"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white font-heading" x-text="editMode ? 'Edit Module' : 'Create Module'"></h3>
                    <p class="text-xs text-slate-400 mt-0.5" x-text="editMode ? 'Update module information' : 'Add a new module to course'"></p>
                </div>
            </div>
            <button @click="closePanel()" class="w-8 h-8 rounded-xl bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 flex items-center justify-center transition">
                <i class="fas fa-xmark text-sm"></i>
            </button>
        </div>
        
        <!-- Form -->
        <form @submit.prevent="submitForm" class="flex-1 overflow-y-auto p-6 space-y-6 module-panel-scroll">
            
            <!-- Title -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">
                    Module Title <span class="text-rose-400">*</span>
                </label>
                <input 
                    type="text" 
                    x-model="form.title" 
                    required
                    placeholder="e.g. Introduction to Web Development"
                    class="w-full px-4 py-3 bg-slate-800/90 border border-slate-700/90 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium placeholder-slate-500"
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
                        class="w-full px-4 py-2.5 bg-slate-800/90 border border-slate-700/90 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium"
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
                        class="w-full px-4 py-2.5 bg-slate-800/90 border border-slate-700/90 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium"
                    >
                </div>
            </div>
            
            <!-- Active Status Toggle -->
            <div class="glass-card rounded-2xl p-4 border border-slate-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                            <i class="fas fa-power-off text-xs"></i>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-200 cursor-pointer font-heading">Active Status</label>
                            <p class="text-[11px] text-slate-400">Make this module available to students</p>
                        </div>
                    </div>
                    
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="form.is_active" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-800 rounded-full peer peer-checked:bg-indigo-600 transition-all duration-200"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all duration-200 peer-checked:translate-x-5"></div>
                    </label>
                </div>
            </div>
            
            <!-- Info Box -->
            <div class="glass-card rounded-2xl p-4 border border-indigo-500/20">
                <div class="flex gap-3">
                    <i class="fas fa-circle-info text-indigo-400 text-sm mt-0.5"></i>
                    <div class="text-xs text-slate-300">
                        <p class="font-bold text-indigo-300 mb-0.5 font-heading">Quick Tip</p>
                        <p class="text-slate-400 text-[11px] leading-relaxed">Modules can be reordered anytime. Inactive modules won't be visible to enrolled students.</p>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center gap-3 pt-4 border-t border-slate-800">
                <button type="button" @click="closePanel()" class="flex-1 px-4 py-2.5 bg-slate-800 border border-slate-700 text-slate-300 rounded-2xl font-bold text-xs hover:bg-slate-700 transition">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 text-white rounded-2xl font-bold text-xs shadow-lg shadow-indigo-600/30 transition" :disabled="isLoading">
                    <i class="fas mr-1.5" :class="editMode ? 'fa-pen' : 'fa-floppy-disk'"></i>
                    <span x-text="editMode ? 'Update Module' : 'Save Module'"></span>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Loading Overlay -->
    <div x-show="isLoading" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-[60] flex items-center justify-center" style="display: none;">
        <div class="bg-slate-900 rounded-3xl p-6 flex items-center gap-4 shadow-2xl border border-slate-800">
            <div class="loading-spinner"></div>
            <span class="text-slate-200 font-semibold text-xs">Processing changes...</span>
        </div>
    </div>
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
                    m.title.toLowerCase().includes(this.search.toLowerCase()) &&
                    (this.courseFilter === '' || m.course_id == this.courseFilter) &&
                    (this.statusFilter === '' || m.is_active == this.statusFilter)
                );
            },

            selectedCount() {
                return this.selected.length;
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
                Swal.fire({
                    title: 'Change Status',
                    text: `Do you want to ${mod.is_active ? 'deactivate' : 'activate'} "${mod.title}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6366f1',
                    cancelButtonColor: '#334155',
                    confirmButtonText: 'Yes, change it',
                    background: '#0f172a',
                    color: '#fff',
                    customClass: { popup: 'rounded-2xl border border-slate-800' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        mod.is_active = !mod.is_active;
                        this.saveModuleStatus(mod);
                        showSuccess(`Module ${mod.is_active ? 'activated' : 'deactivated'} successfully.`);
                    }
                });
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
                if (this.selected.length === 0) {
                    showError('Please select modules to update.');
                    return;
                }

                const actionText = status ? 'activate' : 'deactivate';
                Swal.fire({
                    title: `Bulk ${actionText}`,
                    text: `Are you sure you want to ${actionText} ${this.selected.length} module(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6366f1',
                    cancelButtonColor: '#334155',
                    confirmButtonText: `Yes, ${actionText}`,
                    background: '#0f172a',
                    color: '#fff',
                    customClass: { popup: 'rounded-2xl border border-slate-800' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.modules.filter(m => this.selected.includes(m.id)).forEach(m => m.is_active = status);
                        this.bulkUpdateStatus(status);
                        const count = this.selected.length;
                        this.selected = [];
                        showSuccess(`${count} module(s) updated.`);
                    }
                });
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
                if (this.selected.length === 0) {
                    showError('Please select modules to delete.');
                    return;
                }

                Swal.fire({
                    title: 'Delete Modules',
                    text: `You are about to delete ${this.selected.length} module(s). This action cannot be undone!`,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#334155',
                    confirmButtonText: 'Yes, delete them',
                    cancelButtonText: 'Cancel',
                    background: '#0f172a',
                    color: '#fff',
                    customClass: { popup: 'rounded-2xl border border-slate-800' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.executeBulkDelete();
                    }
                });
            },

            executeBulkDelete() {
                this.isLoading = true;
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
                        showSuccess(`${data.deleted || 'Selected'} module(s) removed.`);
                    } else {
                        showError(data.message || 'Failed to delete modules.');
                    }
                })
                .catch(error => {
                    showError('Something went wrong. Please try again.');
                })
                .finally(() => {
                    this.isLoading = false;
                });
            },

            deleteModule(id, title) {
                Swal.fire({
                    title: 'Delete Module',
                    text: `Are you sure you want to delete "${title}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#334155',
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    background: '#0f172a',
                    color: '#fff',
                    customClass: { popup: 'rounded-2xl border border-slate-800' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.executeDelete(id);
                    }
                });
            },

            executeDelete(id) {
                this.isLoading = true;
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
                        showSuccess('Module removed successfully.');
                    } else {
                        showError(data.message || 'Failed to delete module.');
                    }
                })
                .catch(error => {
                    showError('Something went wrong. Please try again.');
                })
                .finally(() => {
                    this.isLoading = false;
                });
            },

            editModule(mod) {
                this.openPanel('edit', mod);
            },

            submitForm() {
                if (!this.form.title.trim()) {
                    showError('Module title is required.');
                    return;
                }

                const url = this.editMode ? `/admin/modules/${this.form.id}` : `/admin/courses/${this.form.course_id}/modules/store`;
                const method = this.editMode ? 'PUT' : 'POST';
                
                this.isLoading = true;
                
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
                            showSuccess('Module updated successfully.');
                        } else {
                            this.modules.push(data.module || this.form);
                            showSuccess('New module added successfully.');
                        }
                        this.closePanel();
                    } else {
                        showError(data.message || 'Failed to save module.');
                    }
                })
                .catch(error => {
                    showError('Something went wrong. Please try again.');
                })
                .finally(() => {
                    this.isLoading = false;
                });
            },

            init() {
                @if($errors->any())
                    this.slideOpen = true;
                    this.editMode = {{ isset($module) && $module ? 'true' : 'false' }};
                    @if(isset($module) && $module)
                        this.form = {
                            id: {{ $module->id ?? 'null' }},
                            course_id: '{{ $module->course_id ?? '' }}',
                            title: '{{ addslashes(old('title', $module->title ?? '')) }}',
                            order: {{ old('order', $module->order ?? 0) }},
                            duration_minutes: {{ old('duration_minutes', $module->duration_minutes ?? 30) }},
                            is_active: {{ old('is_active', $module->is_active ?? 1) ? 'true' : 'false' }}
                        };
                    @endif
                @endif

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
