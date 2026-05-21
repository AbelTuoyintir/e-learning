@extends('layouts.app')
@section('title', 'Modules')
@section('content')

<style>
    /* Custom scrollbar for the panel */
    .module-panel-scroll::-webkit-scrollbar {
        width: 5px;
    }
    
    .module-panel-scroll::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
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
    
    /* Focus ring styling */
    .focus-ring:focus {
        outline: none;
        ring: 2px solid #6366f1;
        ring-offset: 2px;
    }
    
    /* Card hover effect */
    .module-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .module-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
        border-color: #c7d2fe;
    }
    
    /* Gradient text */
    .gradient-text {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    
    /* Loading spinner */
    .loading-spinner {
        border: 2px solid rgba(99, 102, 241, 0.2);
        border-top-color: #6366f1;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Status badge styles */
    .badge-active {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }
    
    .badge-draft {
        background: #fed7aa;
        color: #9a3412;
        border: 1px solid #fdba74;
    }
    
    /* Input focus styles */
    .input-focus:focus {
        border-color: #6366f1;
        ring: 2px solid #c7d2fe;
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50" x-data="moduleManager()" x-init="init()">
    
    <!-- ===== HEADER SECTION ===== -->
    <div class="relative overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-6 pt-10 pb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
                            <i class="fas fa-layer-group text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl font-extrabold tracking-tight text-slate-800">
                                Course <span class="gradient-text">Modules</span>
                            </h1>
                            <p class="text-slate-500 text-sm mt-1">Organize your course content into structured learning modules</p>
                        </div>
                    </div>
                </div>
                
                <button @click="openPanel('create')" 
                        class="group px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 rounded-xl font-semibold text-white shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105 flex items-center gap-2">
                    <i class="fas fa-plus group-hover:rotate-90 transition-transform duration-300"></i>
                    <span>New Module</span>
                </button>
            </div>
        </div>
    </div>
    
    <!-- ===== STATS CARDS ===== -->
    <div class="max-w-7xl mx-auto px-6 mb-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Total Modules Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-layer-group text-indigo-600 text-lg"></i>
                    </div>
                    <span class="text-xs text-slate-400">Total</span>
                </div>
                <p class="text-3xl font-bold text-slate-800">{{ $modules->count() }}</p>
                <p class="text-xs text-slate-500 mt-1">Available modules</p>
            </div>
            
            <!-- Active Modules Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                    <span class="text-xs text-slate-400">Active</span>
                </div>
                <p class="text-3xl font-bold text-slate-800">{{ $modules->where('is_active', 1)->count() }}</p>
                <p class="text-xs text-slate-500 mt-1">Published modules</p>
            </div>
            
            <!-- Draft Modules Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-alt text-orange-600 text-lg"></i>
                    </div>
                    <span class="text-xs text-slate-400">Draft</span>
                </div>
                <p class="text-3xl font-bold text-slate-800">{{ $modules->where('is_active', 0)->count() }}</p>
                <p class="text-xs text-slate-500 mt-1">In draft mode</p>
            </div>
            
            <!-- Avg Duration Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-clock text-purple-600 text-lg"></i>
                    </div>
                    <span class="text-xs text-slate-400">Avg Duration</span>
                </div>
                <p class="text-3xl font-bold text-slate-800">{{ round($modules->avg('duration_minutes') ?? 0) }}m</p>
                <p class="text-xs text-slate-500 mt-1">Minutes per module</p>
            </div>
        </div>
    </div>
    
    <!-- ===== SEARCH & FILTER BAR ===== -->
    <div class="max-w-7xl mx-auto px-6 mb-6">
        <div class="relative group">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
            <input 
                x-model="search" 
                type="text" 
                placeholder="Search modules by title..." 
                class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 placeholder-slate-400 text-slate-800 transition-all duration-200"
            >
            <div x-show="search" x-transition class="absolute right-4 top-1/2 -translate-y-1/2">
                <button @click="search = ''" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- ===== BULK ACTION BAR ===== -->
    <template x-if="selected.length > 0">
        <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="max-w-7xl mx-auto px-6 mb-4">
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-xl p-4 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-check-double text-indigo-600 text-sm"></i>
                    </div>
                    <span class="text-sm text-slate-700">
                        <span x-text="selected.length" class="font-bold text-indigo-600"></span> module(s) selected
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="bulkStatus(1)" class="px-4 py-2 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-play-circle"></i>
                        <span>Activate All</span>
                    </button>
                    <button @click="bulkStatus(0)" class="px-4 py-2 bg-orange-100 hover:bg-orange-200 text-orange-700 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-pause-circle"></i>
                        <span>Draft All</span>
                    </button>
                    <button @click="bulkDelete" class="px-4 py-2 bg-rose-100 hover:bg-rose-200 text-rose-700 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-trash-alt"></i>
                        <span>Delete All</span>
                    </button>
                </div>
            </div>
        </div>
    </template>
    
    <!-- ===== MODULES LIST ===== -->
    <div class="max-w-7xl mx-auto px-6 pb-12">
        <div class="space-y-3">
            <template x-for="mod in filteredModules()" :key="mod.id">
                <div class="module-card bg-white rounded-2xl border border-slate-200 p-5 hover:border-indigo-300 transition-all duration-300">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        
                        <!-- Left Section - Checkbox & Info -->
                        <div class="flex items-start lg:items-center gap-4">
                            <div class="relative">
                                <input 
                                    type="checkbox" 
                                    :checked="selected.includes(mod.id)" 
                                    @change="toggleSelect(mod.id)" 
                                    class="w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 focus:ring-2 cursor-pointer"
                                >
                            </div>
                            
                            <div class="relative">
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                                    <i class="fas fa-book-open text-indigo-600 text-xl"></i>
                                </div>
                                <div class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-slate-200 border border-white flex items-center justify-center text-[10px] font-bold text-slate-600" x-text="mod.order"></div>
                            </div>
                            
                            <div>
                                <h3 class="font-bold text-lg text-slate-800" x-text="mod.title"></h3>
                                <div class="flex flex-wrap items-center gap-3 mt-1">
                                    <span class="text-xs text-slate-500 flex items-center gap-1">
                                        <i class="fas fa-book text-indigo-500 text-xs"></i>
                                        <span x-text="mod.course?.title ?? 'Unassigned'"></span>
                                    </span>
                                    <span class="text-xs text-slate-300">•</span>
                                    <span class="text-xs text-slate-500 flex items-center gap-1">
                                        <i class="fas fa-clock text-purple-500 text-xs"></i>
                                        <span x-text="mod.duration_minutes + ' minutes'"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Section - Status & Actions -->
                        <div class="flex items-center justify-between lg:justify-end gap-4">
                            <!-- Status Badge -->
                            <div class="flex items-center gap-2">
                                <div class="px-3 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5" 
                                     :class="mod.is_active ? 'badge-active' : 'badge-draft'">
                                    <i class="fas" :class="mod.is_active ? 'fa-circle' : 'fa-circle-notch'"></i>
                                    <span x-text="mod.is_active ? 'Active' : 'Draft'"></span>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center gap-1">
                                <button @click="toggleStatus(mod)" 
                                        class="p-2 rounded-lg text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200"
                                        :title="mod.is_active ? 'Deactivate' : 'Activate'">
                                    <i class="fas fa-sync-alt text-sm"></i>
                                </button>
                                
                                <button @click="editModule(mod)" 
                                        class="p-2 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200"
                                        title="Edit Module">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                
                                <a :href="'{{ route('admin.topics.create', 999999) }}'.replace('999999', mod.id)"
                                   class="p-2 rounded-lg text-slate-500 hover:text-green-600 hover:bg-green-50 transition-all duration-200"
                                   title="Manage Topics">
                                    <i class="fas fa-book-open text-sm"></i>
                                </a>
                                
                                <button @click="deleteModule(mod.id, mod.title)" 
                                        class="p-2 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition-all duration-200"
                                        title="Delete Module">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            
            <!-- Empty State -->
            <template x-if="!filteredModules().length">
                <div class="text-center py-20">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-slate-100 mb-6">
                        <i class="fas fa-inbox text-5xl text-slate-400"></i>
                    </div>
                    <p class="text-slate-600 text-lg">No modules found</p>
                    <p class="text-slate-400 text-sm mt-1">Try adjusting your search or create a new module</p>
                    <button @click="openPanel('create')" class="mt-6 px-6 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-white font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i>Create Module
                    </button>
                </div>
            </template>
        </div>
    </div>
    
    <!-- ===== SLIDE PANEL (Module Form) ===== -->
    <div x-show="slideOpen" x-transition.opacity.duration.300 class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50" @click.self="closePanel()"></div>
    
    <div class="fixed right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col border-l border-slate-200 z-50"
         x-show="slideOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full">
        
        <!-- Top Accent Bar -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
        
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-slate-200 bg-slate-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-md">
                    <i class="fas fa-layer-group text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800" x-text="editMode ? 'Edit Module' : 'Create Module'"></h3>
                    <p class="text-xs text-slate-500 mt-0.5" x-text="editMode ? 'Update module information' : 'Add a new module to course'"></p>
                </div>
            </div>
            <button @click="closePanel()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 hover:bg-red-100 hover:text-red-600 transition-all duration-200 flex items-center justify-center">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        
        <!-- Form -->
        <form @submit.prevent="submitForm" class="flex-1 overflow-y-auto p-6 space-y-6 module-panel-scroll">
            
            <!-- Decorative Icon -->
            <div class="text-center mb-2">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-50 border border-indigo-100 mb-3">
                    <i class="fas fa-book-open text-2xl text-indigo-500"></i>
                </div>
                <h2 class="text-slate-600 text-sm font-medium">Module Details</h2>
            </div>
            
            <!-- Title -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    <i class="fas fa-heading text-xs text-indigo-500 mr-2"></i>
                    Module Title <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    x-model="form.title" 
                    required
                    placeholder="e.g., Introduction to Web Development"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 focus:outline-none transition-all duration-200"
                >
            </div>
            
            <!-- Order & Duration Row -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-sort-numeric-down text-xs text-indigo-500 mr-2"></i>
                        Display Order
                    </label>
                    <input 
                        type="number" 
                        x-model="form.order" 
                        min="0"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 focus:outline-none transition-all duration-200"
                    >
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-clock text-xs text-indigo-500 mr-2"></i>
                        Duration (min)
                    </label>
                    <input 
                        type="number" 
                        x-model="form.duration_minutes" 
                        min="1"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 focus:outline-none transition-all duration-200"
                    >
                </div>
            </div>
            
            <!-- Active Status Toggle -->
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class="fas fa-power-off text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700 cursor-pointer">Active Status</label>
                            <p class="text-xs text-slate-500">Make this module available to students</p>
                        </div>
                    </div>
                    
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="form.is_active" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 rounded-full peer peer-checked:bg-indigo-600 transition-all duration-200"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all duration-200 peer-checked:translate-x-5"></div>
                    </label>
                </div>
            </div>
            
            <!-- Info Box -->
            <div class="bg-indigo-50 rounded-xl p-4 border border-indigo-100">
                <div class="flex gap-3">
                    <i class="fas fa-info-circle text-indigo-500 text-sm mt-0.5"></i>
                    <div class="text-xs text-slate-600">
                        <p class="font-semibold text-indigo-700 mb-1">Quick Tip</p>
                        <p>Modules can be reordered anytime. Inactive modules won't be visible to students.</p>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                <button type="button" @click="closePanel()" class="flex-1 px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-slate-700 font-medium hover:bg-slate-200 transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold hover:from-indigo-500 hover:to-purple-500 transition-all duration-200 shadow-md" :disabled="isLoading">
                    <i class="fas" :class="editMode ? 'fa-pen' : 'fa-save'"></i>
                    <span x-text="editMode ? ' Update Module' : ' Save Module'"></span>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Loading Overlay -->
    <div x-show="isLoading" class="fixed inset-0 bg-black/50 z-[60] flex items-center justify-center" style="display: none;">
        <div class="bg-white rounded-2xl p-6 flex items-center gap-4 shadow-2xl">
            <div class="loading-spinner"></div>
            <span class="text-slate-700 font-medium">Processing...</span>
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
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, change it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        mod.is_active = !mod.is_active;
                        this.saveModuleStatus(mod);
                        Swal.fire('Updated!', `Module ${mod.is_active ? 'activated' : 'deactivated'} successfully.`, 'success');
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
                    Swal.fire('No Selection', 'Please select modules to update.', 'warning');
                    return;
                }

                const actionText = status ? 'activate' : 'deactivate';
                Swal.fire({
                    title: `Bulk ${actionText}`,
                    text: `Are you sure you want to ${actionText} ${this.selected.length} module(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6366f1',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: `Yes, ${actionText}`
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.modules.filter(m => this.selected.includes(m.id)).forEach(m => m.is_active = status);
                        this.bulkUpdateStatus(status);
                        this.selected = [];
                        Swal.fire('Completed!', `${this.selected.length} module(s) ${actionText}d.`, 'success');
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
                    Swal.fire('No Selection', 'Please select modules to delete.', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Delete Modules',
                    text: `You are about to delete ${this.selected.length} module(s). This action cannot be undone!`,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete them',
                    cancelButtonText: 'Cancel'
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
                        Swal.fire('Deleted!', `${data.deleted || this.selected.length} module(s) removed.`, 'success');
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to delete modules.', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
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
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel'
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
                        Swal.fire('Deleted!', 'Module removed successfully.', 'success');
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to delete module.', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
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
                    Swal.fire('Validation Error', 'Module title is required.', 'error');
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
                            Swal.fire('Updated!', 'Module updated successfully.', 'success');
                        } else {
                            this.modules.push(data.module || this.form);
                            Swal.fire('Created!', 'New module added successfully.', 'success');
                        }
                        this.closePanel();
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to save module.', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
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