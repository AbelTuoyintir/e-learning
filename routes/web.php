<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('home');
});

// Route::get('/', [QuizController::class, 'index'])->name('admin.dashboard');
// Admin Authentication Routes (using web guard)

Route::middleware('auth:web')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/students', function () {
        $students = \App\Models\Student::all();
        return view('admin.students', compact('students'));
    })->name('students.index');
});

Route::middleware('guest:web')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login');
    Route::post('/admin/login/submit', [AuthController::class, 'adminLoginSubmit'])->name('admin.login.submit');
});

// Student Authentication Routes
Route::get('/student/login', [AuthController::class, 'login'])->name('login');
Route::post('/student/login/submit', [AuthController::class, 'studentLogin'])->name('student.login.submit');

// Forgot Password Routes for Students
Route::get('/student/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('student.forgot.password');
Route::post('/student/forgot-password', [AuthController::class, 'sendResetLink'])->name('student.forgot.password.submit');
Route::get('/student/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('student.reset.password');
Route::post('/student/reset-password', [AuthController::class, 'resetPassword'])->name('student.reset.password.submit');

Route::middleware('guest:student')->group(function () {
    // Public student routes
    Route::get('/students/courses',[App\Http\Controllers\CourseController::class, 'courseReg'])->name('students.courses');
    Route::get('/students/registeration', [App\Http\Controllers\UserController::class, 'regstu'])->name('admin.students');
    Route::post('/students/registeration', [App\Http\Controllers\UserController::class, 'store'])->name('students.store');
});

// Authenticated Student Routes
Route::middleware('auth:student')->group(function () {
    Route::get('/students/dashboard',[StudentController::class, 'dashboard'])->name('students.dashboard');
     Route::get('/students/courses',[App\Http\Controllers\CourseController::class, 'courseReg'])->name('students.courses');
    Route::get('/students/registeration', [App\Http\Controllers\UserController::class, 'regstu'])->name('admin.students');
    Route::post('/students/registeration', [App\Http\Controllers\UserController::class, 'store'])->name('students.store');
    Route::post('/students/course-enrollment',[App\Http\Controllers\CourseController::class, 'enroll'])->name('student.enroll');
    Route::get('/students/enrolled-courses', [App\Http\Controllers\CourseController::class, 'enrolledCourses'])->name('students.enrolledcourses');
    Route::get('/students/enrolled-courses/{course}/materials', [App\Http\Controllers\CourseController::class, 'getMaterials'])->name('students.course.materials');
      Route::get('/student/course/{course}/materials', [CourseController::class, 'getMaterials'])
        ->name('students.course.materials');
    Route::get('/students/enrolled-courses/{course}/quizzes', [App\Http\Controllers\CourseController::class, 'getQuizzes'])->name('students.course.quizzes');
    Route::get('/students/scores',function(){
        return view('students.scores');
    })->name('students.scores');
    Route::get('/download/transcript', [StudentController::class, 'downloadTranscript'])->name('download.transcript');
    Route::get('/download/certificate/{course}', [StudentController::class, 'downloadCertificate'])->name('download.certificate');

       // Quiz taking flow
    Route::get('quizzes', [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quiz/{quiz}', [StudentController::class, 'showQuiz'])->name('quiz.start');

    Route::post('/quiz/{quiz}/submit', [StudentController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/{quiz}/results', [StudentController::class, 'results'])->name('quiz.results');

    // Quiz history and progress
    Route::get('/results', [StudentController::class, 'resultsIndex'])->name('results.index');
    Route::get('/results/{result}', [StudentController::class, 'resultShow'])->name('results.show');
});

// Admin Logout
Route::post('/admin/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

// Student Logout
Route::post('/student/logout', [AuthController::class, 'studentLogout'])->name('student.logout');

// Route::get('/', function () {
//     return view('dashboard');
// })->name('admin.dashboard');
//admin routes with middleware

Route::prefix('admin')->group(function () {
Route::get('quizzes', [QuizController::class, 'index'])->name('quizzes.index');
Route::get('quiz/create', [QuizController::class, 'create'])->name('quizzes.create');
Route::post('quiz', [QuizController::class, 'store'])->name('quizzes.store');
Route::get('quiz/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
Route::put('quiz/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
Route::delete('quiz/{id}', [QuizController::class, 'destroy'])->name('quizzes.destroy');

Route::get('course', [App\Http\Controllers\CourseController::class, 'index'])->name('courses.index');
Route::post('course', [App\Http\Controllers\CourseController::class, 'store'])->name('courses.store');
Route::get('/courses/filter-search', [CourseController::class, 'filterAndSearch'])->name('courses.filterSearch');
Route::post('course/modules', [App\Http\Controllers\ModuleController::class, 'store'])->name('module.store');
Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
Route::get('courses/{course}/show', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/{course}/modules', [CourseController::class, 'modules'])->name('courses.modules');
Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
Route::post('/courses/{course}/modules/store', [ModuleController::class, 'store'])
    ->name('modules.store');
Route::post('/courses/{course}/modules/store', [ModuleController::class, 'store'])
     ->name('modules.store');

Route::put('/modules/{module}', [ModuleController::class, 'update'])
     ->name('modules.update');
Route::delete('/modules/{module}', [App\Http\Controllers\ModuleController::class, 'destroy'])->name('modules.destroy');
Route::get('/modules/{module}/topics', [TopicController::class, 'index'])->name('topics.index');

   Route::get('/topics/create/{module}', [TopicController::class, 'create'])
    ->name('admin.topics.create');
    Route::post('/topics', [TopicController::class, 'store'])->name('admin.topics.store');
    Route::get('/topics/{topic}', [TopicController::class, 'show'])->name('admin.topics.show');
    Route::get('/topics/{topic}/edit', [TopicController::class, 'edit'])->name('admin.topics.edit');
    Route::put('/topics/{topic}', [TopicController::class, 'update'])->name('admin.topics.update');
    Route::delete('/topics/{topic}', [TopicController::class, 'destroy'])->name('admin.topics.destroy');
    Route::get('/modules/{module}/topics', [TopicController::class, 'byModule'])->name('admin.modules.topics');
    Route::post('/topics/order', [TopicController::class, 'updateOrder'])->name('admin.topics.order');
    route::get('/topics/back', [TopicController::class, 'back'])->name('admin.topics.back');


// Incorrect - duplicate quiz parameter
Route::post('quiz/{quiz}/questions/import', [QuestionController::class, 'import'])->name('questions.import');

// Corrected routes
Route::prefix('quiz/{quiz}')->group(function () {
    Route::get('questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::post('questions/import', [QuestionController::class, 'import'])->name('questions.import');
    Route::get('questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
});
});

// Dashboard - List available quizzes
    // Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');



// Route::get('results', [ResultController::class, 'index'])->name('results.index');
// Route::get('results/{id}', [ResultController::class, 'show'])->name('results.show');
// Route::delete('results/{id}', [ResultController::class, 'destroy'])->name('results.destroy');


