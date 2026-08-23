@extends('layouts.app')

@section('title', 'Manage Students')

@section('content')
<div class="space-y-8">

    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    <i class="fas fa-user-graduate text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manage Students</h1>
                    <p class="text-xs text-slate-500 mt-0.5">View, monitor, and update student profiles and performance</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold text-xs transition">
                <i class="fas fa-arrow-left"></i>
                <span>Dashboard</span>
            </a>
            <button onclick="showInfo('Direct student registration via admin portal is enabled.', 'Add Student')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md shadow-indigo-600/20 transition">
                <i class="fas fa-plus"></i>
                <span>Add Student</span>
            </button>
        </div>
    </div>

    <!-- Students Table Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">

        <!-- Table Toolbar -->
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-bold text-slate-900">Registered Students</h2>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                    {{ $students->count() }} Total
                </span>
            </div>

            <!-- Informational Banner Pill -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-blue-50/80 border border-blue-100 text-blue-700 text-xs font-medium">
                <i class="fas fa-circle-info text-blue-500"></i>
                <span>Click action icons to view complete academic profile or update details.</span>
            </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4">Student Profile</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">Program</th>
                        <th class="px-6 py-4">Registered Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($students as $student)
                    <tr class="hover:bg-slate-50/60 transition-colors group">
                        <!-- Student Name & Avatar -->
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3.5">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->firstname . ' ' . $student->lastname) }}&background=e0e7ff&color=4f46e5&bold=true"
                                     alt="{{ $student->firstname }}"
                                     class="w-10 h-10 rounded-xl object-cover ring-2 ring-slate-100 shrink-0">
                                <div>
                                    <p class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors student-name">
                                        {{ $student->firstname }} {{ $student->lastname }}
                                    </p>
                                    @if($student->middlename)
                                    <p class="text-xs text-slate-400 font-medium">{{ $student->middlename }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Email -->
                        <td class="px-6 py-4 text-slate-600 font-medium student-email">
                            {{ $student->email }}
                        </td>

                        <!-- Phone -->
                        <td class="px-6 py-4 text-slate-600">
                            {{ $student->phone ?? '—' }}
                        </td>

                        <!-- Program Badge -->
                        <td class="px-6 py-4">
                            @if($student->program || $student->Program)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                <i class="fas fa-graduation-cap mr-1.5 text-indigo-500"></i>
                                {{ $student->program ?? $student->Program }}
                            </span>
                            @else
                            <span class="text-xs text-slate-400 italic">Unassigned</span>
                            @endif
                        </td>

                        <!-- Created At -->
                        <td class="px-6 py-4 text-xs font-medium text-slate-500">
                            {{ $student->created_at ? $student->created_at->format('M d, Y') : 'N/A' }}
                        </td>

                        <!-- Action Buttons -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <button onclick="showStudentDetails(this)" 
                                        data-student-id="{{ $student->id }}"
                                        data-route="{{ route('student.details', ['student' => $student->id]) }}"
                                        title="View Student Details"
                                        class="p-2 rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
                                    <i class="fas fa-eye text-sm"></i>
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
                                        title="Edit Student Info"
                                        class="p-2 rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
                                    <i class="fas fa-pen text-sm"></i>
                                </button>

                                <button onclick="showInfo('Student record archiving enabled', 'Info')"
                                        title="Delete Student"
                                        class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all">
                                    <i class="fas fa-trash-can text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                            <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-300 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-users-slash text-2xl"></i>
                            </div>
                            <p class="font-semibold text-slate-700">No students registered yet</p>
                            <p class="text-xs text-slate-400 mt-1">Students will appear here once registered.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Student Details Modal -->
<div id="studentDetailsModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 hidden overflow-y-auto">
    <div class="min-h-screen px-4 py-8 flex items-center justify-center">
        <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto relative border border-slate-100">
            
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white/95 backdrop-blur-md border-b border-slate-100 px-6 py-4 flex justify-between items-center z-10">
                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-user-graduate text-indigo-600"></i>
                    Student Performance Profile
                </h2>
                <button onclick="closeStudentModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 hover:bg-slate-200 flex items-center justify-center transition">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 sm:p-8" id="studentDetailsContent">
                <!-- Loading State -->
                <div id="loadingState" class="text-center py-12 space-y-3">
                    <i class="fas fa-circle-notch fa-spin text-3xl text-indigo-600"></i>
                    <p class="text-xs font-semibold text-slate-500">Fetching student performance analytics...</p>
                </div>
                
                <!-- Content Container -->
                <div id="detailsContent" class="hidden"></div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div id="editStudentModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 hidden overflow-y-auto">
    <div class="min-h-screen px-4 py-8 flex items-center justify-center">
        <div class="bg-white rounded-3xl shadow-2xl max-w-xl w-full max-h-[90vh] overflow-y-auto relative border border-slate-100">
            
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white/95 backdrop-blur-md border-b border-slate-100 px-6 py-4 flex justify-between items-center z-10">
                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-user-pen text-indigo-600"></i>
                    Edit Student Profile
                </h2>
                <button onclick="closeEditModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 hover:bg-slate-200 flex items-center justify-center transition">
                    <i class="fas fa-xmark"></i>
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
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                First Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="edit_firstname" name="firstname" required
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        
                        <!-- Last Name -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                Last Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="edit_lastname" name="lastname" required
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                Email Address <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" id="edit_email" name="email" required
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        
                        <!-- Phone -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                Phone Number
                            </label>
                            <input type="tel" id="edit_phone" name="phone"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        
                        <!-- Program -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                Academic Program
                            </label>
                            <select id="edit_program" name="program"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
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
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                Account Status
                            </label>
                            <select id="edit_status" name="status"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                                <option value="graduated">Graduated</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Password Optional Reset Section -->
                    <div class="pt-4 border-t border-slate-100">
                        <h4 class="font-bold text-slate-800 text-sm mb-3 flex items-center gap-2">
                            <i class="fas fa-lock text-indigo-500"></i>
                            Update Password (Optional)
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1">New Password</label>
                                <input type="password" id="edit_password" name="password"
                                       class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500"
                                       placeholder="Leave blank to keep current">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1">Confirm Password</label>
                                <input type="password" id="edit_password_confirmation" name="password_confirmation"
                                       class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500"
                                       placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <button type="button" onclick="closeEditModal()"
                                class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl font-semibold text-xs hover:bg-slate-200 transition">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold text-xs shadow-md shadow-indigo-600/20 transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Student Details Modal Logic
window.showStudentDetails = function (button) {
    const studentId = button.getAttribute('data-student-id');
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
                <p class="text-xs text-rose-600 font-semibold">Unable to load student profile information</p>
                <button onclick="closeStudentModal()" class="mt-4 px-4 py-2 bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl">Close</button>
            </div>
        `;
    });
};

function renderStudentDetails(data) {
    const student = data.student;
    const enrolledCourses = data.enrolled_courses || [];
    const paymentHistory = data.payment_history || [];
    const recentResults = data.recent_results || [];
    const stats = data.statistics || {};
    
    const html = `
        <!-- Profile Banner Header -->
        <div class="flex flex-col sm:flex-row items-center gap-5 p-6 bg-slate-50 rounded-2xl border border-slate-100 mb-6">
            <img src="${student.avatar}" alt="${student.fullname}" class="w-16 h-16 rounded-2xl object-cover ring-4 ring-white shadow-xs">
            <div class="text-center sm:text-left flex-1">
                <h3 class="text-xl font-extrabold text-slate-900">${student.fullname}</h3>
                <p class="text-xs text-slate-500 mt-0.5">${student.email} • ${student.phone || 'No Phone'}</p>
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-2">
                    <span class="px-2.5 py-0.5 ${student.status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'} text-[11px] font-bold rounded-full">
                        ${student.status === 'active' ? 'Active Account' : 'Inactive'}
                    </span>
                    <span class="text-xs text-slate-400">Registered: ${student.registration_date}</span>
                </div>
            </div>
        </div>
        
        <!-- Stats Summary Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <div class="bg-indigo-50/60 border border-indigo-100 rounded-2xl p-4 text-center">
                <p class="text-2xl font-extrabold text-indigo-600">${stats.total_courses || 0}</p>
                <p class="text-[11px] font-semibold text-slate-500 mt-1">Courses Enrolled</p>
            </div>
            <div class="bg-emerald-50/60 border border-emerald-100 rounded-2xl p-4 text-center">
                <p class="text-2xl font-extrabold text-emerald-600">${stats.completed_courses || 0}</p>
                <p class="text-[11px] font-semibold text-slate-500 mt-1">Completed</p>
            </div>
            <div class="bg-purple-50/60 border border-purple-100 rounded-2xl p-4 text-center">
                <p class="text-2xl font-extrabold text-purple-600">${stats.total_quizzes_taken || 0}</p>
                <p class="text-[11px] font-semibold text-slate-500 mt-1">Quizzes Taken</p>
            </div>
            <div class="bg-amber-50/60 border border-amber-100 rounded-2xl p-4 text-center">
                <p class="text-2xl font-extrabold text-amber-600">${stats.average_score || 0}%</p>
                <p class="text-[11px] font-semibold text-slate-500 mt-1">Average Score</p>
            </div>
        </div>

        <!-- Enrolled Courses -->
        <div class="space-y-3 mb-6">
            <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <i class="fas fa-book-bookmark text-indigo-600"></i>
                Enrolled Courses (${enrolledCourses.length})
            </h4>
            <div class="space-y-3">
                ${enrolledCourses.map(course => `
                    <div class="p-4 bg-white border border-slate-200/80 rounded-2xl">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-bold text-slate-800 text-sm">${course.title}</p>
                                <p class="text-xs text-slate-400">Code: ${course.code} | Enrolled: ${course.enrolled_at}</p>
                            </div>
                            <span class="text-xs font-bold ${course.progress >= 100 ? 'text-emerald-600' : 'text-indigo-600'}">
                                ${course.progress}% Completed
                            </span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: ${course.progress}%"></div>
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

let currentEditButton = null;

function editStudent(button) {
    currentEditButton = button;
    
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
            showSuccess('Student information updated successfully!');
            closeEditModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showError(data.message || 'Failed to update student');
        }
    })
    .catch(() => {
        showError('An error occurred during student update');
    });
}
</script>
@endsection
