<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\TopicController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', [QuizController::class, 'index'])->name('admin.dashboard');
Route::get('/', function () {
    return view('dashboad');
})->name('admin.dashboard');
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
Route::prefix('admin')->group(function () {
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
});




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

// Dashboard - List available quizzes
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');

    // Quiz taking flow
    Route::get('/quiz/{quiz}', [StudentController::class, 'showQuiz'])->name('quiz.start');

    Route::post('/quiz/{quiz}/submit', [StudentController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/{quiz}/results', [StudentController::class, 'results'])->name('quiz.results');

    // Quiz history and progress
    Route::get('/results', [StudentController::class, 'resultsIndex'])->name('results.index');
    Route::get('/results/{result}', [StudentController::class, 'resultShow'])->name('results.show');

// Route::get('results', [ResultController::class, 'index'])->name('results.index');
// Route::get('results/{id}', [ResultController::class, 'show'])->name('results.show');
// Route::delete('results/{id}', [ResultController::class, 'destroy'])->name('results.destroy');

//students routes
Route::get('/students/dashboard',function(){
    return view('students.dashboard');
})->name('students.dashboard');



Route::get('/students/courses',[App\Http\Controllers\CourseController::class, 'courseReg'])->name('students.courses');
Route::post('/students/course-enrollment',[App\Http\Controllers\CourseController::class, 'enroll'])->name('student.enroll');
Route::get('/students/enrolled-courses', [App\Http\Controllers\CourseController::class, 'enrolledCourses'])->name('students.enrolledcourses');
Route::get('/students/enrolled-courses/{course}/materials', [App\Http\Controllers\CourseController::class, 'getMaterials'])->name('students.course.materials');
Route::get('/students/enrolled-courses/{course}/quizzes', [App\Http\Controllers\CourseController::class, 'getQuizzes'])->name('students.course.quizzes');
Route::post('/student/login/submit', [App\Http\Controllers\AuthController::class, 'studentLogin'])->name('student.login.submit');
Route::get('/student/login', [App\Http\Controllers\AuthController::class, 'login'])->name('student.login');
Route::post('/student/logout', [App\Http\Controllers\AuthController::class, 'studentLogout'])->name('student.logout');
Route::get('/students/scores',function(){
    return view('students.scores');
})->name('students.scores');
// Route::get('/students/profile',function(){
//     return view('students.profile');
// })->name('students.profile');

Route::get('/students/registeration', [App\Http\Controllers\UserController::class, 'regstu'])->name('admin.students');
Route::post('/students/registeration', [App\Http\Controllers\UserController::class, 'store'])->name('students.store');

