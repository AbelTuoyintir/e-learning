@extends('layouts.app')

@section('title', 'Manage Students')

@section('content')
<div class="space-y-8">

    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-panel rounded-3xl p-6 sm:p-8 shadow-xl border border-white/10">
        <div>
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 text-indigo-400 flex items-center justify-center font-bold shadow-inner">
                    <i class="fas fa-user-graduate text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight heading-font">Student Management Hub</h1>
                    <p class="text-xs text-slate-400 mt-0.5">View, monitor, and update student profiles, programs, and performance records</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-white/10 bg-slate-800/80 hover:bg-slate-800 text-slate-300 font-semibold text-xs transition">
                <i class="fas fa-arrow-left"></i>
                <span>Dashboard</span>
            </a>
            <button onclick="showInfo('Direct student registration via admin portal is enabled.', 'Add Student')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold text-xs shadow-lg shadow-indigo-500/25 transition">
                <i class="fas fa-plus"></i>
                <span>Add Student</span>
            </button>
        </div>
    </div>

    <!-- Students Table Card -->
    <div class="glass-panel rounded-3xl shadow-xl border border-white/10 overflow-hidden">

        <!-- Table Toolbar & Filters -->
        <div class="p-6 border-b border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-bold text-white heading-font">Registered Enrollees</h2>
                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                    {{ $students->count() }} Total
                </span>
            </div>

            <!-- Search Filter Bar -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative max-w-xs w-full">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-magnifying-glass text-xs"></i>
                    </div>
                    <input type="text" id="studentSearchInput" onkeyup="filterStudentTable()" placeholder="Search by name or email..."
                           class="w-full pl-9 pr-4 py-2.5 bg-slate-900/80 border border-white/10 rounded-2xl text-xs font-semibold text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>

                <div class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-300 text-xs font-semibold">
                    <i class="fas fa-circle-info text-blue-400"></i>
                    <span>Click actions to view analytics profile</span>
                </div>
            </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/80 border-b border-white/10 text-[10px] font-extrabold uppercase tracking-widest text-slate-400">
                        <th class="px-6 py-4">Student Profile</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">Program</th>
                        <th class="px-6 py-4">Registered Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-xs font-medium" id="studentsTableBody">
                    @forelse($students as $student)
                    <tr class="hover:bg-white/5 transition-colors group student-row">
                        <!-- Student Name & Avatar -->
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3.5">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->firstname . ' ' . $student->lastname) }}&background=6366f1&color=ffffff&bold=true"
                                     alt="{{ $student->firstname }}"
                                     class="w-10 h-10 rounded-xl object-cover ring-2 ring-indigo-500/30 shrink-0 shadow-md">
                                <div>
                                    <p class="font-bold text-white group-hover:text-indigo-300 transition-colors student-name heading-font text-sm">
                                        {{ $student->firstname }} {{ $student->lastname }}
                                    </p>
                                    @if($student->middlename)
                                    <p class="text-[11px] text-slate-400 font-medium">{{ $student->middlename }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Email -->
                        <td class="px-6 py-4 text-slate-300 font-medium student-email">
                            {{ $student->email }}
                        </td>

                        <!-- Phone -->
                        <td class="px-6 py-4 text-slate-400">
                            {{ $student->phone ?? '—' }}
                        </td>

                        <!-- Program Badge -->
                        <td class="px-6 py-4">
                            @if($student->program || $student->Program)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[11px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                <i class="fas fa-graduation-cap mr-1.5 text-indigo-400"></i>
                                {{ $student->program ?? $student->Program }}
                            </span>
                            @else
                            <span class="text-[11px] text-slate-500 italic">Unassigned</span>
                            @endif
                        </td>

                        <!-- Created At -->
                        <td class="px-6 py-4 text-xs font-medium text-slate-400">
                            {{ $student->created_at ? $student->created_at->format('M d, Y') : 'N/A' }}
                        </td>

                        <!-- Action Buttons -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <button onclick="showStudentDetails(this)" 
                                        data-student-id="{{ $student->id }}"
                                        data-route="{{ route('student.details', ['student' => $student->id]) }}"
                                        title="View Student Performance"
                                        class="p-2.5 rounded-xl text-slate-400 hover:text-indigo-300 hover:bg-indigo-500/20 transition-all border border-transparent hover:border-indigo-500/30">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>

                                <button onclick="editStudent(this)" 
                                        data-student-id="{{ $student->id }}"
                                        data-student-name="{{ $student->firstname }} {{ $student->lastname }}"
                                        data-student-firstname="{{ $student->firstname }}"
                                        data-student-lastname="{{ $student->lastname }}"
                                        data-student-email="{{ $student->email }}"
                                        data-student-phone="{{ $student->phone }}"
                                        data-student-program="{{ $student->program }}"
                                        data-student-status="{{ $student->status }}"
                                        data-route="{{ route('student.update', $student->id) }}"
                                        title="Edit Student Profile"
                                        class="p-2.5 rounded-xl text-slate-400 hover:text-indigo-300 hover:bg-indigo-500/20 transition-all border border-transparent hover:border-indigo-500/30">
                                    <i class="fas fa-pen text-xs"></i>
                                </button>

                                <button onclick="showInfo('Student record archiving enabled', 'Archive Student')"
                                        title="Delete Student"
                                        class="p-2.5 rounded-xl text-slate-400 hover:text-rose-400 hover:bg-rose-500/20 transition-all border border-transparent hover:border-rose-500/30">
                                    <i class="fas fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                            <div class="w-16 h-16 rounded-2xl bg-slate-800 text-slate-500 flex items-center justify-center mx-auto mb-3 border border-white/10">
                                <i class="fas fa-users-slash text-2xl"></i>
                            </div>
                            <p class="font-bold text-white heading-font">No students registered yet</p>
                            <p class="text-xs text-slate-400 mt-1">Students will appear here once registered on the platform.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Student Details Modal -->
<div id="studentDetailsModal" class="fixed inset-0 bg-black/80 backdrop-blur-md z-50 hidden overflow-y-auto">
    <div class="min-h-screen px-4 py-8 flex items-center justify-center">
        <div class="bg-slate-900 rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto relative border border-white/15">
            
            <!-- Modal Header -->
            <div class="sticky top-0 bg-slate-900/95 backdrop-blur-md border-b border-white/10 px-6 py-4 flex justify-between items-center z-10">
                <h2 class="text-lg font-bold text-white flex items-center gap-2 heading-font">
                    <i class="fas fa-user-graduate text-indigo-400"></i>
                    Student Academic Profile & Analytics
                </h2>
                <button onclick="closeStudentModal()" class="w-8 h-8 rounded-xl bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 flex items-center justify-center transition border border-white/10">
                    <i class="fas fa-xmark text-sm"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 sm:p-8" id="studentDetailsContent">
                <!-- Loading State -->
                <div id="loadingState" class="text-center py-12 space-y-3">
                    <i class="fas fa-circle-notch fa-spin text-3xl text-indigo-400"></i>
                    <p class="text-xs font-semibold text-slate-400">Loading student academic metrics...</p>
                </div>
                
                <!-- Content Container -->
                <div id="detailsContent" class="hidden"></div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div id="editStudentModal" class="fixed inset-0 bg-black/80 backdrop-blur-md z-50 hidden overflow-y-auto">
    <div class="min-h-screen px-4 py-8 flex items-center justify-center">
        <div class="bg-slate-900 rounded-3xl shadow-2xl max-w-xl w-full max-h-[90vh] overflow-y-auto relative border border-white/15">
            
            <!-- Modal Header -->
            <div class="sticky top-0 bg-slate-900/95 backdrop-blur-md border-b border-white/10 px-6 py-4 flex justify-between items-center z-10">
                <h2 class="text-lg font-bold text-white flex items-center gap-2 heading-font">
                    <i class="fas fa-user-pen text-indigo-400"></i>
                    Edit Student Profile
                </h2>
                <button onclick="closeEditModal()" class="w-8 h-8 rounded-xl bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 flex items-center justify-center transition border border-white/10">
                    <i class="fas fa-xmark text-sm"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 sm:p-8">
                <form id="editStudentForm" onsubmit="submitEditForm(event)" class="space-y-5">
                    <input type="hidden" id="edit_student_id" name="student_id">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- First Name -->
                        <div>
                            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">
                                First Name <span class="text-rose-400">*</span>
                            </label>
                            <input type="text" id="edit_firstname" name="firstname" required
                                   class="w-full px-4 py-2.5 bg-slate-950 border border-white/10 rounded-2xl text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition font-medium">
                        </div>
                        
                        <!-- Last Name -->
                        <div>
                            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">
                                Last Name <span class="text-rose-400">*</span>
                            </label>
                            <input type="text" id="edit_lastname" name="lastname" required
                                   class="w-full px-4 py-2.5 bg-slate-950 border border-white/10 rounded-2xl text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition font-medium">
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">
                                Email Address <span class="text-rose-400">*</span>
                            </label>
                            <input type="email" id="edit_email" name="email" required
                                   class="w-full px-4 py-2.5 bg-slate-950 border border-white/10 rounded-2xl text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition font-medium">
                        </div>
                        
                        <!-- Phone -->
                        <div>
                            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">
                                Phone Number
                            </label>
                            <input type="tel" id="edit_phone" name="phone"
                                   class="w-full px-4 py-2.5 bg-slate-950 border border-white/10 rounded-2xl text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition font-medium">
                        </div>
                        
                        <!-- Program -->
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">
                                Academic Program
                            </label>
                            <select id="edit_program" name="program"
                                    class="w-full px-4 py-2.5 bg-slate-950 border border-white/10 rounded-2xl text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition font-medium">
                                <option value="">Select Program</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Information Technology">Information Technology</option>
                                <option value="Business Administration">Business Administration</option>
                                <option value="Engineering">Engineering</option>
                                <option value="Medicine">Medicine</option>
                                <option value="Law">Law</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <!-- Status -->
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">
                                Account Status
                            </label>
                            <select id="edit_status" name="status"
                                    class="w-full px-4 py-2.5 bg-slate-950 border border-white/10 rounded-2xl text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition font-medium">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                                <option value="graduated">Graduated</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Password Optional Reset Section -->
                    <div class="pt-4 border-t border-white/10">
                        <h4 class="font-bold text-white text-xs mb-3 flex items-center gap-2 heading-font">
                            <i class="fas fa-lock text-indigo-400"></i>
                            Update Password (Optional)
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-medium text-slate-400 mb-1">New Password</label>
                                <input type="password" id="edit_password" name="password"
                                       class="w-full px-4 py-2 bg-slate-950 border border-white/10 rounded-xl text-xs text-white focus:ring-2 focus:ring-indigo-500"
                                       placeholder="Leave blank to keep current">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-slate-400 mb-1">Confirm Password</label>
                                <input type="password" id="edit_password_confirmation" name="password_confirmation"
                                       class="w-full px-4 py-2 bg-slate-950 border border-white/10 rounded-xl text-xs text-white focus:ring-2 focus:ring-indigo-500"
                                       placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex items-center gap-3 pt-4 border-t border-white/10">
                        <button type="button" onclick="closeEditModal()"
                                class="flex-1 px-4 py-2.5 bg-slate-800 text-slate-300 rounded-2xl font-bold text-xs hover:bg-slate-700 transition border border-white/10">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-2xl font-bold text-xs shadow-lg shadow-indigo-500/25 transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function filterStudentTable() {
    const input = document.getElementById('studentSearchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.student-row');

    rows.forEach(row => {
        const name = row.querySelector('.student-name')?.textContent.toLowerCase() || '';
        const email = row.querySelector('.student-email')?.textContent.toLowerCase() || '';

        if (name.includes(input) || email.includes(input)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Student Details Modal Logic
window.showStudentDetails = function (button) {
    const url = button.getAttribute('data-route');
    const modal = document.getElementById('studentDetailsModal');
    const loadingState = document.getElementById('loadingState');
    const detailsContent = document.getElementById('detailsContent');

    modal.classList.remove('hidden');
    loadingState.classList.remove('hidden');
    detailsContent.classList.add('hidden');

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderStudentDetails(data);
            loadingState.classList.add('hidden');
            detailsContent.classList.remove('hidden');
        } else {
            throw new Error('Failed to load details');
        }
    })
    .catch(() => {
        loadingState.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-triangle-exclamation text-3xl text-rose-500 mb-2"></i>
                <p class="text-xs text-rose-400 font-semibold">Unable to load student profile information</p>
                <button onclick="closeStudentModal()" class="mt-4 px-4 py-2 bg-slate-800 text-slate-300 text-xs font-semibold rounded-xl border border-white/10">Close</button>
            </div>
        `;
    });
};

function renderStudentDetails(data) {
    const student = data.student;
    const enrolledCourses = data.enrolled_courses || [];
    const stats = data.statistics || {};
    
    const html = `
        <!-- Profile Banner Header -->
        <div class="flex flex-col sm:flex-row items-center gap-5 p-6 bg-slate-950 rounded-2xl border border-white/10 mb-6">
            <img src="${student.avatar}" alt="${student.fullname}" class="w-16 h-16 rounded-2xl object-cover ring-2 ring-indigo-500/30 shadow-lg">
            <div class="text-center sm:text-left flex-1">
                <h3 class="text-xl font-extrabold text-white heading-font">${student.fullname}</h3>
                <p class="text-xs text-slate-400 mt-0.5">${student.email} • ${student.phone || 'No Phone'}</p>
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-2">
                    <span class="px-2.5 py-0.5 ${student.status === 'active' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30'} text-[10px] font-bold rounded-full">
                        ${student.status === 'active' ? 'Active Account' : 'Inactive'}
                    </span>
                    <span class="text-xs text-slate-400">Registered: ${student.registration_date}</span>
                </div>
            </div>
        </div>
        
        <!-- Stats Summary Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <div class="bg-indigo-500/10 border border-indigo-500/20 rounded-2xl p-4 text-center">
                <p class="text-2xl font-extrabold text-indigo-400 heading-font">${stats.total_courses || 0}</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1">Courses Enrolled</p>
            </div>
            <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-2xl p-4 text-center">
                <p class="text-2xl font-extrabold text-emerald-400 heading-font">${stats.completed_courses || 0}</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1">Completed</p>
            </div>
            <div class="bg-purple-500/10 border border-purple-500/20 rounded-2xl p-4 text-center">
                <p class="text-2xl font-extrabold text-purple-400 heading-font">${stats.total_quizzes_taken || 0}</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1">Quizzes Taken</p>
            </div>
            <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl p-4 text-center">
                <p class="text-2xl font-extrabold text-amber-400 heading-font">${stats.average_score || 0}%</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1">Average Score</p>
            </div>
        </div>

        <!-- Enrolled Courses -->
        <div class="space-y-3 mb-6">
            <h4 class="font-bold text-white text-xs uppercase tracking-wider flex items-center gap-2 heading-font">
                <i class="fas fa-book-bookmark text-indigo-400"></i>
                Enrolled Courses (${enrolledCourses.length})
            </h4>
            <div class="space-y-3">
                ${enrolledCourses.map(course => `
                    <div class="p-4 bg-slate-950 border border-white/10 rounded-2xl">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-bold text-white text-xs heading-font">${course.title}</p>
                                <p class="text-[11px] text-slate-400">Code: ${course.code} | Enrolled: ${course.enrolled_at}</p>
                            </div>
                            <span class="text-xs font-bold ${course.progress >= 100 ? 'text-emerald-400' : 'text-indigo-400'}">
                                ${course.progress}% Completed
                            </span>
                        </div>
                        <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden border border-white/5">
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2 rounded-full transition-all duration-300" style="width: ${course.progress}%"></div>
                        </div>
                    </div>
                `).join('')}
                ${enrolledCourses.length === 0 ? '<p class="text-xs text-slate-400 italic py-2">No courses enrolled yet.</p>' : ''}
            </div>
        </div>
    `;
    
    document.getElementById('detailsContent').innerHTML = html;
}

window.closeStudentModal = function() {
    document.getElementById('studentDetailsModal').classList.add('hidden');
};

function editStudent(button) {
    const studentId = button.getAttribute('data-student-id');
    const firstName = button.getAttribute('data-student-firstname');
    const lastName = button.getAttribute('data-student-lastname');
    const email = button.getAttribute('data-student-email');
    const phone = button.getAttribute('data-student-phone');
    const program = button.getAttribute('data-student-program');
    const status = button.getAttribute('data-student-status');
    
    document.getElementById('edit_student_id').value = studentId;
    document.getElementById('edit_firstname').value = firstName;
    document.getElementById('edit_lastname').value = lastName;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_phone').value = phone || '';
    document.getElementById('edit_program').value = program || '';
    document.getElementById('edit_status').value = status || 'active';
    
    document.getElementById('edit_password').value = '';
    document.getElementById('edit_password_confirmation').value = '';
    
    document.getElementById('editStudentModal').classList.remove('hidden');
}

window.closeEditModal = function() {
    document.getElementById('editStudentModal').classList.add('hidden');
};

function submitEditForm(event) {
    event.preventDefault();
    
    const studentId = document.getElementById('edit_student_id').value;
    const formData = {
        firstname: document.getElementById('edit_firstname').value,
        lastname: document.getElementById('edit_lastname').value,
        email: document.getElementById('edit_email').value,
        phone: document.getElementById('edit_phone').value,
        program: document.getElementById('edit_program').value,
        status: document.getElementById('edit_status').value,
        password: document.getElementById('edit_password').value,
        password_confirmation: document.getElementById('edit_password_confirmation').value,
    };

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    fetch(`/admin/student/${studentId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin',
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Student profile updated successfully!');
            closeEditModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showError(data.message || 'Failed to update student profile');
        }
    })
    .catch(() => {
        showError('An error occurred during student profile update');
    });
}
</script>
@endsection
