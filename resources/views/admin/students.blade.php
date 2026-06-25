@extends('layouts.app')

@section('title', 'Manage Students')

@section('content')
<div class="container mx-auto px-6 py-8">

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Manage Students</h1>
            <p class="text-slate-600 mt-2">View and manage all registered students</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-semibold transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
         <a href="{{ route('admin.students') }}" class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-semibold transition">
            <i class="fas fa-plus mr-2"></i>Add Student
        </a>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="text-xl font-semibold text-slate-800">All Students ({{ $students->count() }})</h2>
        </div>
        <div class="bg-blue-300 shadow-sm shadow-md.rounded-lg.px-6.py-4.mb-6">
            <p class="text-slate-800 text-sm">Note: You can view, edit, or delete student records using the action buttons provided in the table below.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Phone</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Program</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Registered</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($students as $student)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-indigo-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-800">{{ $student->firstname }} {{ $student->lastname }}</p>
                                    <p class="text-sm text-slate-600">{{ $student->middlename }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-800">{{ $student->email }}</td>
                        <td class="px-6 py-4 text-slate-800">{{ $student->phone ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-slate-800">{{ $student->Program ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $student->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <button onclick="showStudentDetails(this)" 
                                        data-student-id="{{ $student->id }}"
                                        data-route="{{ route('student.details', ['student' => $student->id]) }}"
                                        class="text-indigo-600 hover:text-indigo-800 p-2 rounded-lg hover:bg-indigo-50">
                                    <i class="fas fa-eye"></i>
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
                                        class="text-slate-600 hover:text-indigo-600 p-2 rounded-lg hover:bg-indigo-50 transition">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <i class="fas fa-users text-4xl mb-4 text-slate-300"></i>
                            <p>No students registered yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- Student Details Modal -->
<div id="studentDetailsModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto relative">
            
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-user-graduate text-indigo-600"></i>
                    Student Details
                </h2>
                <button onclick="closeStudentModal()" class="text-slate-400 hover:text-red-500 transition text-2xl">
                    &times;
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6" id="studentDetailsContent">
                <!-- Loading State -->
                <div id="loadingState" class="text-center py-12">
                    <i class="fas fa-spinner fa-spin text-3xl text-indigo-600"></i>
                    <p class="mt-2 text-slate-500">Loading student details...</p>
                </div>
                
                <!-- Content will be injected here -->
                <div id="detailsContent" class="hidden"></div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div id="editStudentModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto relative">
            
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-user-edit text-indigo-600"></i>
                    Edit Student Details
                </h2>
                <button onclick="closeEditModal()" class="text-slate-400 hover:text-red-500 transition text-2xl">
                    &times;
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <form id="editStudentForm" onsubmit="submitEditForm(event)">
                    <input type="hidden" id="edit_student_id" name="student_id">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- First Name -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fas fa-user text-indigo-500 mr-1"></i> First Name *
                            </label>
                            <input type="text" id="edit_firstname" name="firstname" required
                                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        
                        <!-- Last Name -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fas fa-user text-indigo-500 mr-1"></i> Last Name *
                            </label>
                            <input type="text" id="edit_lastname" name="lastname" required
                                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fas fa-envelope text-indigo-500 mr-1"></i> Email *
                            </label>
                            <input type="email" id="edit_email" name="email" required
                                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        
                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fas fa-phone text-indigo-500 mr-1"></i> Phone
                            </label>
                            <input type="tel" id="edit_phone" name="phone"
                                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        
                        <!-- Program -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fas fa-graduation-cap text-indigo-500 mr-1"></i> Program
                            </label>
                            <select id="edit_program" name="program"
                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
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
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fas fa-toggle-on text-indigo-500 mr-1"></i> Status
                            </label>
                            <select id="edit_status" name="status"
                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                                <option value="graduated">Graduated</option>
                            </select>
                        </div>
                        
                        <!-- Registration Date (Read Only) -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fas fa-calendar-alt text-indigo-500 mr-1"></i> Registration Date
                            </label>
                            <input type="text" id="edit_registration_date" readonly disabled
                                   class="w-full px-4 py-2.5 bg-slate-100 border border-slate-300 rounded-lg text-slate-600">
                        </div>
                    </div>
                    
                    <!-- Password Change Section -->
                    <div class="mt-6 pt-4 border-t border-slate-200">
                        <h4 class="font-semibold text-slate-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-key text-indigo-500"></i>
                            Change Password (Optional)
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">New Password</label>
                                <input type="password" id="edit_password" name="password"
                                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                       placeholder="Leave blank to keep current">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Confirm Password</label>
                                <input type="password" id="edit_password_confirmation" name="password_confirmation"
                                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                       placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3 mt-6 pt-4 border-t border-slate-200">
                        <button type="button" onclick="closeEditModal()"
                                class="flex-1 px-4 py-2.5 bg-slate-100 border border-slate-300 rounded-lg text-slate-700 font-medium hover:bg-slate-200 transition">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-semibold hover:from-indigo-500 hover:to-purple-500 transition shadow-md">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Student Details Modal Functions
let currentStudentId = null;

// Ensure global function is available for inline onclick
window.showStudentDetails = function (button) {
    const studentId = button.getAttribute('data-student-id');
    const url = button.getAttribute('data-route');

    currentStudentId = studentId;

    const modal = document.getElementById('studentDetailsModal');
    const loadingState = document.getElementById('loadingState');
    const detailsContent = document.getElementById('detailsContent');

    // Show modal and loading state
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
            throw new Error('Failed to load student details');
        }
    })
    .catch(() => {
        loadingState.innerHTML = `
            <div class="text-center py-12">
                <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
                <p class="mt-2 text-red-600">Error loading student details</p>
                <button onclick="closeStudentModal()" class="mt-4 px-4 py-2 bg-slate-200 rounded-lg">Close</button>
            </div>
        `;
    });
}

function renderStudentDetails(data) {
    const student = data.student;
    const enrolledCourses = data.enrolled_courses;
    const paymentHistory = data.payment_history;
    const recentResults = data.recent_results;
    const stats = data.statistics;
    
    const html = `
        <!-- Profile Header -->
        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-200">
            <img src="${student.avatar}" alt="${student.fullname}" class="w-20 h-20 rounded-full object-cover border-4 border-indigo-200">
            <div>
                <h3 class="text-2xl font-bold text-slate-800">${student.fullname}</h3>
                <p class="text-slate-500">${student.email}</p>
                <p class="text-slate-500 text-sm">${student.phone}</p>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-2 py-0.5 ${student.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'} text-xs rounded-full">
                        ${student.status === 'active' ? 'Active' : 'Inactive'}
                    </span>
                    <span class="text-xs text-slate-400">Registered: ${student.registration_date}</span>
                </div>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-indigo-50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-indigo-600">${stats.total_courses}</p>
                <p class="text-xs text-slate-600">Courses Enrolled</p>
            </div>
            <div class="bg-green-50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-green-600">${stats.completed_courses}</p>
                <p class="text-xs text-slate-600">Courses Completed</p>
            </div>
            <div class="bg-purple-50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-purple-600">${stats.total_quizzes_taken}</p>
                <p class="text-xs text-slate-600">Quizzes Taken</p>
            </div>
            <div class="bg-yellow-50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-yellow-600">${stats.average_score}%</p>
                <p class="text-xs text-slate-600">Average Score</p>
            </div>
        </div>
        
        <!-- Program Info -->
        <div class="bg-slate-50 rounded-xl p-4 mb-6">
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-graduation-cap text-indigo-600"></i>
                <h4 class="font-semibold text-slate-700">Program Information</h4>
            </div>
            <p class="text-slate-700">${student.program}</p>
        </div>
        
        <!-- Enrolled Courses -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-semibold text-slate-700 flex items-center gap-2">
                    <i class="fas fa-book-open text-indigo-600"></i>
                    Enrolled Courses (${enrolledCourses.length})
                </h4>
            </div>
            <div class="space-y-3">
                ${enrolledCourses.map(course => `
                    <div class="border border-slate-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-semibold text-slate-800">${course.title}</p>
                                <p class="text-xs text-slate-500">Code: ${course.code} | Enrolled: ${course.enrolled_at}</p>
                            </div>
                            <span class="text-sm font-semibold ${course.progress >= 100 ? 'text-green-600' : 'text-indigo-600'}">
                                ${course.progress}% Complete
                            </span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: ${course.progress}%"></div>
                        </div>
                        <p class="text-xs text-slate-500 mt-2">${course.completed_quizzes}/${course.total_quizzes} quizzes completed</p>
                    </div>
                `).join('')}
                ${enrolledCourses.length === 0 ? '<p class="text-slate-500 text-center py-4">No courses enrolled yet.</p>' : ''}
            </div>
        </div>
        
        <!-- Payment History -->
        <div class="mb-6">
            <h4 class="font-semibold text-slate-700 mb-3 flex items-center gap-2">
                <i class="fas fa-credit-card text-indigo-600"></i>
                Payment History
            </h4>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="text-left py-2 px-3">Date</th>
                            <th class="text-left py-2 px-3">Reference</th>
                            <th class="text-right py-2 px-3">Amount</th>
                            <th class="text-center py-2 px-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${paymentHistory.map(payment => `
                            <tr class="border-b border-slate-100">
                                <td class="py-2 px-3 text-slate-600">${payment.date}</td>
                                <td class="py-2 px-3 text-slate-600 text-xs">${payment.reference}</td>
                                <td class="py-2 px-3 text-right font-medium">₵${payment.amount}</td>
                                <td class="py-2 px-3 text-center">
                                    <span class="px-2 py-0.5 ${payment.status === 'successful' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'} text-xs rounded-full">
                                        ${payment.status}
                                    </span>
                                </td>
                            </tr>
                        `).join('')}
                        ${paymentHistory.length === 0 ? '<tr><td colspan="4" class="text-center py-4 text-slate-500">No payment records found.</td></tr>' : ''}
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Recent Results -->
        <div class="mb-6">
            <h4 class="font-semibold text-slate-700 mb-3 flex items-center gap-2">
                <i class="fas fa-chart-line text-indigo-600"></i>
                Recent Quiz Results
            </h4>
            <div class="space-y-2">
                ${recentResults.map(result => `
                    <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                        <div>
                            <p class="font-medium text-slate-700">${result.quiz_title}</p>
                            <p class="text-xs text-slate-500">${result.completed_at}</p>
                        </div>
                        <div class="text-right">
                            <span class="font-bold ${result.passed ? 'text-green-600' : 'text-red-600'}">${result.score}%</span>
                            <span class="ml-2 ${result.passed ? 'text-green-500' : 'text-red-500'}">
                                <i class="fas ${result.passed ? 'fa-check-circle' : 'fa-times-circle'}"></i>
                            </span>
                        </div>
                    </div>
                `).join('')}
                ${recentResults.length === 0 ? '<p class="text-slate-500 text-center py-4">No quiz attempts yet.</p>' : ''}
            </div>
        </div>
        
        <!-- Total Paid Summary -->
        <div class="bg-indigo-50 rounded-xl p-4">
            <div class="flex justify-between items-center">
                <span class="font-semibold text-slate-700">Total Amount Paid:</span>
                <span class="text-2xl font-bold text-indigo-600">₵${stats.total_paid}</span>
            </div>
        </div>
    `;
    
    document.getElementById('detailsContent').innerHTML = html;
}

window.closeStudentModal = function closeStudentModal() {
    const modal = document.getElementById('studentDetailsModal');
    modal.classList.add('hidden');
    currentStudentId = null;
    
    // Reset content
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('detailsContent').classList.add('hidden');
    document.getElementById('detailsContent').innerHTML = '';
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeStudentModal();
    }
});

// Close modal when clicking outside
document.getElementById('studentDetailsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStudentModal();
    }
});

let currentEditButton = null;


function editStudent(button) {
    currentEditButton = button;
    
    // Get student data from button attributes
    const studentId = button.getAttribute('data-student-id');
    const firstName = button.getAttribute('data-student-firstname');
    const lastName = button.getAttribute('data-student-lastname');
    const email = button.getAttribute('data-student-email');
    const phone = button.getAttribute('data-student-phone');
    const program = button.getAttribute('data-student-program');
    const status = button.getAttribute('data-student-status');
    
    // Populate the form
    document.getElementById('edit_student_id').value = studentId;
    document.getElementById('edit_firstname').value = firstName;
    document.getElementById('edit_lastname').value = lastName;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_phone').value = phone || '';
    document.getElementById('edit_program').value = program || '';
    document.getElementById('edit_status').value = status || 'active';
    
    // Clear password fields
    document.getElementById('edit_password').value = '';
    document.getElementById('edit_password_confirmation').value = '';
    
    // Show modal
    const modal = document.getElementById('editStudentModal');
    modal.classList.remove('hidden');
}

window.closeEditModal = function closeEditModal() {
    const modal = document.getElementById('editStudentModal');
    modal.classList.add('hidden');
    currentEditButton = null;
}

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
    
    // Validate passwords match if provided
    if (formData.password && formData.password !== formData.password_confirmation) {
        Swal.fire({
            icon: 'error',
            title: 'Password Mismatch',
            text: 'New password and confirmation do not match.',
            confirmButtonColor: '#6366f1'
        });
        return;
    }
    
    // Show loading state
    Swal.fire({
        title: 'Updating...',
        text: 'Please wait while we update the student information.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Send update request
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
            // Update the button attributes with new data
            if (currentEditButton) {
                currentEditButton.setAttribute('data-student-firstname', formData.firstname);
                currentEditButton.setAttribute('data-student-lastname', formData.lastname);
                currentEditButton.setAttribute('data-student-email', formData.email);
                currentEditButton.setAttribute('data-student-phone', formData.phone || '');
                currentEditButton.setAttribute('data-student-program', formData.program || '');
                currentEditButton.setAttribute('data-student-status', formData.status);
                currentEditButton.setAttribute('data-student-name', formData.firstname + ' ' + formData.lastname);
            }
            
            // Update the table row if it exists
            updateTableRow(studentId, formData);
            
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: 'Student information has been updated successfully.',
                confirmButtonColor: '#6366f1',
                timer: 2000
            });
            
            // Close modal after success
            setTimeout(() => {
                closeEditModal();
            }, 1500);
        } else {
            throw new Error(data.message || 'Failed to update student');
        }
    })
    .catch(error => {
        console.error('Update error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Update Failed',
            text: error.message || 'Something went wrong. Please try again.',
            confirmButtonColor: '#dc2626'
        });
    });
}

function updateTableRow(studentId, formData) {
    // Find the row containing this student
    const rows = document.querySelectorAll('tr');
    for (let row of rows) {
        if (row.innerHTML.includes(`data-student-id="${studentId}"`) || 
            row.querySelector(`button[data-student-id="${studentId}"]`)) {
            
            // Update name cell
            const nameCell = row.querySelector('td:first-child, .student-name');
            if (nameCell) {
                nameCell.textContent = formData.firstname + ' ' + formData.lastname;
            }
            
            // Update email cell
            const emailCell = row.querySelector('td:nth-child(2), .student-email');
            if (emailCell) {
                emailCell.textContent = formData.email;
            }
            
            // Update status badge if exists
            const statusBadge = row.querySelector('.status-badge');
            if (statusBadge) {
                statusBadge.textContent = formData.status;
                statusBadge.className = `status-badge px-2 py-1 rounded-full text-xs font-semibold ${
                    formData.status === 'active' ? 'bg-green-100 text-green-700' : 
                    formData.status === 'inactive' ? 'bg-red-100 text-red-700' : 
                    'bg-yellow-100 text-yellow-700'
                }`;
            }
            
            break;
        }
    }
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
    }
});

// Close modal when clicking outside
document.getElementById('editStudentModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script>
@endsection
