<?php

namespace App\Http\Controllers;   // ✅ This must be the very first line (after <?php)

use App\Models\Course;
use App\Models\Module;
use App\Models\Enrollment;
use App\Models\Notification;
use App\Models\TopicContent;
use App\Models\Topic;
use App\Models\DocumentHighlight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CourseController extends Controller
{
    //
    public function index(){
        $courses= \App\Models\Course::where('status','active')->paginate(5);
        $totalCourses = \App\Models\Course::count();
        $totalPublishedCourses = \App\Models\Course::where('status', 'active')->count();
        $modules = \App\Models\Module::with('course')->latest()->get();
        $cour = Course::all();
        return view('course.courses',[
            'courses'=>$courses,
            'totalCourses'=>$totalCourses,
            'totalPublishedCourses'=>$totalPublishedCourses,
            'modules'=>$modules,
            'cour'=>$cour,
        ]);
    }

   public function store(Request $request)
{
    
    try {
        // Validate and store course data
        $validated = $request->validate([
            'title' => 'required|string|max:255', // Fixed: changed from 'title' to 'name'
            'description' => 'nullable|string',
            'instructor' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'duration' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload if image is provided
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('course_images', 'public');
            $validated['image'] = $path;
        }

        $validated['price'] = isset($validated['price']) ? (float) $validated['price'] : 0;

        // Create the course
        $course = \App\Models\Course::create($validated);

        return redirect()->back()->with('success', 'Course created successfully!');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to create course: ' . $e->getMessage())->withInput();
    }
}

public function filterAndSearch(Request $request)
{
    $query = \App\Models\Course::query();

    // Search by title/description
    if ($request->search) {
        $query->where(function($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
        });
    }

    // Filter by category
    if ($request->category && $request->category !== 'All Categories') {
        $query->where('category', $request->category);
    }

    // Filter by status
    if ($request->status && $request->status !== 'All Status') {
        $query->where('status', $request->status);
    }

    $courses = $query->latest()->get();

    return response()->json($courses);
}

public function courseReg(){
    $courses = \App\Models\Course::where('status','active')->paginate(10);
    return view('students.studcourses', [
        'courses'=> $courses,
    ]);
}



public function enroll(Request $request)
{
    try {
        \Log::info('Enrollment process started.', ['user_id' => auth()->id(), 'input' => $request->all()]);

        // Get the authenticated user
        $user = auth()->user();

        // Check if user is authenticated
        if (!$user) {
            // Use a direct URL instead of named route to avoid issues
            return redirect('/login')->with('error', 'Please log in to enroll in courses.');
        }

        $courseId = $request->input('course_id');

        // Validate course_id
        $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);

        // Find the course
        $course = Course::find($courseId);

        if (!$course) {
            return back()->with('error', 'Course not found.');
        }

        // Check if already enrolled
        if ($user->enrollments()->where('course_id', $courseId)->exists()) {
            return back()->with('info', 'You are already enrolled in this course.');
        }

        // Paid courses go through checkout.
        if ((float) $course->price > 0) {
            return redirect()->route('students.courses.checkout', $course->id);
        }

        // Free course: auto-enroll.
        $enrollment = $this->createEnrollment($user->id, $course, 'free');

        \Log::info('Enrollment successful.', [
            'user_id' => $user->id,
            'course_id' => $courseId,
            'enrollment_id' => $enrollment->id
        ]);

        // Create notification for successful enrollment
        $course = Course::find($courseId);
        Notification::create([
            'student_id' => $user->id,
            'title' => 'Course Enrollment Successful!',
            'message' => "You have successfully enrolled in '{$course->title}'. Start exploring the course materials and quizzes.",
            'type' => 'success',
            'is_read' => false,
        ]);

        return back()->with([
            'success' => 'Successfully enrolled in the course!',
            'enrolled_course_id' => $course->id,
        ]);

    } catch (\Exception $e) {
        \Log::error('Error during course enrollment.', [
            'error' => $e->getMessage(),
            'user_id' => auth()->id(),
            'course_id' => $request->input('course_id')
        ]);

        return back()->with('error', 'An error occurred during enrollment. Please try again.');
    }
}

public function checkout(Course $course)
{
    $user = auth()->user();

    if (! $user) {
        return redirect()->route('login')->with('error', 'Please log in to continue.');
    }

    if ((float) $course->price <= 0) {
        return redirect()->route('students.courses')
            ->with('info', 'This course is free. You can enroll directly.');
    }

    if ($user->enrollments()->where('course_id', $course->id)->exists()) {
        return redirect()->route('students.enrolledcourses')
            ->with('info', 'You already own this course.');
    }

    return view('students.course-checkout', compact('course'));
}

public function completePurchase(Request $request, Course $course)
{
    // Legacy endpoint: create a pending enrollment and redirect to Paystack
    return $this->initializePaystackPayment($request, $course);
}

public function initializePaystackPayment(Request $request, Course $course)
{
    $user = auth()->user();

    if (! $user) {
        return redirect()->route('login')->with('error', 'Please log in to continue.');
    }

    if ((float) $course->price <= 0) {
        return redirect()->route('students.courses')
            ->with('info', 'This course is free. Please use enroll.');
    }

    if ($user->enrollments()->where('course_id', $course->id)->whereIn('payment_status', ['pending', 'paid'])->exists()) {
        return redirect()->route('students.enrolledcourses')
            ->with('info', 'You already own this course (or it is being processed).');
    }

    // Reference used to reconcile in webhook
    $reference = 'PAY-'.Str::upper(Str::random(10));

    // Create pending enrollment (DO NOT mark paid here)
    $enrollment = $this->createEnrollment($user->id, $course, 'pending', $reference);

    // Redirect to Paystack checkout
    $amountKobo = (int) round(((float) $course->price) * 100);

    $paystackUrl = rtrim(config('services.paystack.url'), '/');
    $publicKey = config('services.paystack.public_key');

    // If keys are missing, fail loudly
    if (! $publicKey) {
        return back()->with('error', 'Paystack is not configured (missing PAYSTACK_PUBLIC_KEY).');
    }

    // Paystack redirect uses a standard host; we create a transaction server-side via Paystack initialize.
    // For simplicity, we call Paystack initialize endpoint and then redirect to the returned authorization url.
    $endpoint = $paystackUrl.'/transaction/initialize';

    $payload = [
        'email' => $user->email,
        'amount' => $amountKobo,
        'reference' => $reference,
        'currency' => 'GHS',
        'callback_url' => route('paystack.webhook'),
        'metadata' => [
            'student_id' => $user->id,
            'course_id' => $course->id,
            'enrollment_id' => $enrollment->id,
        ],
    ];

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.config('services.paystack.secret_key'),
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $raw = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($raw === false || $httpCode >= 400) {
        \Log::error('Paystack initialize failed', [
            'http_code' => $httpCode,
            'error' => $err,
            'response' => $raw,
        ]);
        return back()->with('error', 'Payment initialization failed. Please try again.');
    }

    $response = json_decode($raw, true);

    if (! is_array($response) || empty($response['status']) || $response['status'] !== true || empty($response['data']['authorization_url'])) {
        \Log::error('Paystack initialize unexpected response', ['response' => $response]);
        return back()->with('error', 'Payment initialization failed. Please try again.');
    }

    return redirect()->away($response['data']['authorization_url']);
}


    // Show edit form
    public function edit(Course $course)
    {
        $course =Course::find($course->id);
        return view('course.edit', compact('course'));
    }

    // Update course
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructor' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'duration' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_image' => 'nullable|boolean',
        ]);

        if ($request->boolean('remove_image') && $course->image) {
            if (Storage::disk('public')->exists($course->image)) {
                Storage::disk('public')->delete($course->image);
            }
            $validated['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($course->image && Storage::disk('public')->exists($course->image)) {
                Storage::disk('public')->delete($course->image);
            }
            $validated['image'] = $request->file('image')->store('course_images', 'public');
        }

        $validated['price'] = isset($validated['price']) ? (float) $validated['price'] : (float) ($course->price ?? 0);

        $course->update($validated);

        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully!');
    }

    // Show modules for a course
    public function modules(Course $course)
    {
        $modules = $course->modules()->paginate(10); // paginate 10 per page
        // dd($modules);
        return view('course.module', compact('course', 'modules'));
    }

    public function show(Course $course){
        $course->load('modules.topics');
        return view('course.courseDetails', compact('course'));
    }

    // Delete course
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully!');
    }

    // Get enrolled courses for the authenticated student
   public function enrolledCourses()
    {
        $user = Auth::user();

        // Debug step 1: Check user
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Debug step 2: Check relationship
        $enrolledCourses = $user->enrollments()
            ->with('course')
            ->get();

        return view('students.enrolledcourse', compact('enrolledCourses'));
    }
    // Get materials for a specific course
    // `public function getMaterials(Course $course)
    // {
    //     // Check if the user is enrolled in the course
    //     $user = auth()->user();
    //     if (!$user->enrollments()->where('course_id', $course->id)->exists()) {
    //         return redirect()->route('students.enrolledcourses')->with('error', 'You are not enrolled in this course.');
    //     }

    //     return view('students.materials', compact('course'));
    // }`

    // Get quizzes for a specific course
 public function getQuizzes(Course $course)
{
    $user = auth()->user();

    // Check if user is enrolled in the course
    if (!$user->enrollments()->where('course_id', $course->id)->exists()) {
        return redirect()->route('students.enrolledcourses')
                        ->with('error', 'You are not enrolled in this course.');
    }

    try {
        // Simple version without attempts
        $quizzes = $course->quizzes()
            ->withCount('questions')
            ->orderBy('due_at')
            ->get();

        return view('students.getquiz', compact('course', 'quizzes'));

    } catch (\Exception $e) {
        \Log::error('Error loading quizzes: ' . $e->getMessage());

        return back()->with('error', 'Failed to load quizzes: ' . $e->getMessage());
    }
}

// App\Http\Controllers\CourseController.php

public function getMaterials(Course $course)
{
    $user = auth()->user();

    // Check if user is enrolled in the course
    if (!$user->enrollments()->where('course_id', $course->id)->exists()) {
        return redirect()->route('students.enrolledcourses')
                        ->with('error', 'You are not enrolled in this course.');
    }

    // Eager load all necessary relationships with their nested relationships
    $course->load([
        'modules' => function ($query) {
            $query->orderBy('order')
                ->where('is_active', true);
        },
        'modules.topics' => function ($query) {
            $query->orderBy('order')
                ->where('is_active', true);
        },
        'modules.topics.contents',
        'modules.topics.quiz.questions'
    ]);

    // Module progression gating: module N is unlocked only if module (N-1)'s FINAL quiz is passed.
    // Assumption: each module has one or more quizzes of type='final'. If any of them passed, module unlocks.
    $modules = $course->modules ?? collect();
    $modulesByOrder = $modules->values();

    $unlockedModuleIds = [];
    if ($modulesByOrder->isNotEmpty()) {
        // First module always unlocked
        $unlockedModuleIds[] = (int) $modulesByOrder[0]->id;
    }

    // Find all final quizzes for modules
    $finalQuizzesByModuleId = \App\Models\Quiz::query()
        ->where('course_id', $course->id)
        ->where('type', 'final')
        ->whereNotNull('module_id')
        ->get()
        ->groupBy(fn($q) => (int) $q->module_id);

    for ($i = 1; $i < $modulesByOrder->count(); $i++) {
        $prevModuleId = (int) $modulesByOrder[$i - 1]->id;
        $currModuleId = (int) $modulesByOrder[$i]->id;

        $finalQuizzes = $finalQuizzesByModuleId->get($prevModuleId, collect());

        // If previous module has no FINAL quizzes, treat it as passed (keeps backward compatibility)
        if ($finalQuizzes->isEmpty()) {
            $unlockedModuleIds[] = $currModuleId;
            continue;
        }

        $finalQuizIds = $finalQuizzes->pluck('id')->all();

        $prevModulePassed = \App\Models\Result::query()
            ->where('student_id', $user->id)
            ->whereIn('quiz_id', $finalQuizIds)
            ->where('passed', 1)
            ->exists();

        if ($prevModulePassed) {
            $unlockedModuleIds[] = $currModuleId;
        }
    }

    $lockedModuleIds = array_values(array_diff($modulesByOrder->pluck('id')->map(fn($m) => (int) $m)->all(), $unlockedModuleIds));

    return view('students.materials', compact('course', 'lockedModuleIds', 'unlockedModuleIds'));
}


public function readMaterial(Course $course, TopicContent $content)
{
    $user = auth()->user();

    if (! $user->enrollments()->where('course_id', $course->id)->exists()) {
        return redirect()->route('students.enrolledcourses')
            ->with('error', 'You are not enrolled in this course.');
    }

    $contentBelongsToCourse = $content->topic
        && $content->topic->module
        && (int) $content->topic->module->course_id === (int) $course->id;

    if (! $contentBelongsToCourse) {
        abort(403, 'Unauthorized material access.');
    }

    if (! $content->file_path) {
        abort(404, 'Material file not found.');
    }

    return $this->renderDocumentReader(
        $course,
        $content->file_name ?: 'Course Material',
        route('students.course.materials.view', [$course->id, $content->id]),
        $content->file_name,
        $content->type,
        route('students.course.materials.highlights.get', [$course->id, $content->id]),
        route('students.course.materials.highlights.save', [$course->id, $content->id])
    );
}

public function readTopicDocument(Course $course, Topic $topic)
{
    $user = auth()->user();

    if (! $user->enrollments()->where('course_id', $course->id)->exists()) {
        return redirect()->route('students.enrolledcourses')
            ->with('error', 'You are not enrolled in this course.');
    }

    $topicBelongsToCourse = $topic->module && (int) $topic->module->course_id === (int) $course->id;

    if (! $topicBelongsToCourse) {
        abort(403, 'Unauthorized material access.');
    }

    if (! $topic->file_path) {
        abort(404, 'Document not found.');
    }

    return $this->renderDocumentReader(
        $course,
        $topic->file_name ?: 'Topic Document',
        route('students.course.topics.document.view', [$course->id, $topic->id]),
        $topic->file_name,
        'document',
        route('students.course.topics.document.highlights.get', [$course->id, $topic->id]),
        route('students.course.topics.document.highlights.save', [$course->id, $topic->id])
    );
}

public function getMaterialHighlights(Course $course, TopicContent $content)
{
    $studentId = $this->authorizeMaterialHighlightAccess($course, $content);

    $record = DocumentHighlight::where([
        'student_id' => $studentId,
        'course_id' => $course->id,
        'target_type' => 'topic_content',
        'target_id' => $content->id,
    ])->first();

    return response()->json([
        'highlights' => $record?->highlights ?? [],
    ]);
}

public function saveMaterialHighlights(Request $request, Course $course, TopicContent $content)
{
    $studentId = $this->authorizeMaterialHighlightAccess($course, $content);
    $payload = $this->validateHighlightsPayload($request);

    DocumentHighlight::updateOrCreate(
        [
            'student_id' => $studentId,
            'course_id' => $course->id,
            'target_type' => 'topic_content',
            'target_id' => $content->id,
        ],
        [
            'highlights' => $payload['highlights'] ?? [],
        ]
    );

    return response()->json(['saved' => true]);
}

public function getTopicDocumentHighlights(Course $course, Topic $topic)
{
    $studentId = $this->authorizeTopicDocumentHighlightAccess($course, $topic);

    $record = DocumentHighlight::where([
        'student_id' => $studentId,
        'course_id' => $course->id,
        'target_type' => 'topic_document',
        'target_id' => $topic->id,
    ])->first();

    return response()->json([
        'highlights' => $record?->highlights ?? [],
    ]);
}

public function saveTopicDocumentHighlights(Request $request, Course $course, Topic $topic)
{
    $studentId = $this->authorizeTopicDocumentHighlightAccess($course, $topic);
    $payload = $this->validateHighlightsPayload($request);

    DocumentHighlight::updateOrCreate(
        [
            'student_id' => $studentId,
            'course_id' => $course->id,
            'target_type' => 'topic_document',
            'target_id' => $topic->id,
        ],
        [
            'highlights' => $payload['highlights'] ?? [],
        ]
    );

    return response()->json(['saved' => true]);
}

public function viewMaterial(Course $course, TopicContent $content)
{
    $user = auth()->user();

    if (! $user->enrollments()->where('course_id', $course->id)->exists()) {
        return redirect()->route('students.enrolledcourses')
            ->with('error', 'You are not enrolled in this course.');
    }

    // Ensure the requested content belongs to this course.
    $contentBelongsToCourse = $content->topic
        && $content->topic->module
        && (int) $content->topic->module->course_id === (int) $course->id;

    if (! $contentBelongsToCourse) {
        abort(403, 'Unauthorized material access.');
    }

    if (! $content->file_path) {
        abort(404, 'Material file not found.');
    }

    return $this->streamMaterialFile(
        $content->file_path,
        $content->file_name ?: basename($content->file_path)
    );
}

public function viewTopicDocument(Course $course, Topic $topic)
{
    $user = auth()->user();

    if (! $user->enrollments()->where('course_id', $course->id)->exists()) {
        return redirect()->route('students.enrolledcourses')
            ->with('error', 'You are not enrolled in this course.');
    }

    $topicBelongsToCourse = $topic->module && (int) $topic->module->course_id === (int) $course->id;

    if (! $topicBelongsToCourse) {
        abort(403, 'Unauthorized material access.');
    }

    if (! $topic->file_path) {
        abort(404, 'Document not found.');
    }

    return $this->streamMaterialFile(
        $topic->file_path,
        $topic->file_name ?: basename($topic->file_path)
    );
}

private function streamMaterialFile(string $relativePath, string $safeName)
{
    $disk = null;
    if (Storage::disk('local')->exists($relativePath)) {
        $disk = 'local';
    } elseif (Storage::disk('public')->exists($relativePath)) {
        $disk = 'public';
    }

    if (! $disk) {
        abort(404, 'Material file not found.');
    }

    $mimeType = Storage::disk($disk)->mimeType($relativePath) ?? 'application/octet-stream';
    $size = Storage::disk($disk)->size($relativePath);
    $stream = Storage::disk($disk)->readStream($relativePath);

    if (! $stream) {
        abort(500, 'Unable to open material file.');
    }

    return response()->stream(function () use ($stream) {
        fpassthru($stream);
        fclose($stream);
    }, 200, [
        'Content-Type' => $mimeType,
        'Content-Length' => (string) $size,
        'Content-Disposition' => 'inline; filename="'.addslashes($safeName).'"',
        'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
        'Pragma' => 'no-cache',
        'Expires' => '0',
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'SAMEORIGIN',
    ]);
}

private function renderDocumentReader(
    Course $course,
    string $title,
    string $sourceUrl,
    ?string $fileName = null,
    ?string $type = null,
    ?string $highlightLoadUrl = null,
    ?string $highlightSaveUrl = null
)
{
    $extension = strtolower(pathinfo($fileName ?? '', PATHINFO_EXTENSION));
    $isPdf = $extension === 'pdf' || $type === 'pdf';

    return view('students.document-reader', [
        'course' => $course,
        'documentTitle' => $title,
        'sourceUrl' => $sourceUrl,
        'isPdf' => $isPdf,
        'highlightLoadUrl' => $highlightLoadUrl,
        'highlightSaveUrl' => $highlightSaveUrl,
    ]);
}

private function validateHighlightsPayload(Request $request): array
{
    return $request->validate([
        'highlights' => 'nullable|array|max:1000',
        'highlights.*.left' => 'required|numeric|min:0|max:100',
        'highlights.*.top' => 'required|numeric|min:0|max:100',
        'highlights.*.width' => 'required|numeric|min:0|max:100',
        'highlights.*.height' => 'required|numeric|min:0|max:100',
    ]);
}

private function authorizeMaterialHighlightAccess(Course $course, TopicContent $content): int
{
    $user = auth()->user();
    if (! $user || ! $user->enrollments()->where('course_id', $course->id)->exists()) {
        abort(403, 'You are not enrolled in this course.');
    }

    $contentBelongsToCourse = $content->topic
        && $content->topic->module
        && (int) $content->topic->module->course_id === (int) $course->id;

    if (! $contentBelongsToCourse) {
        abort(403, 'Unauthorized material access.');
    }

    return (int) $user->id;
}

private function authorizeTopicDocumentHighlightAccess(Course $course, Topic $topic): int
{
    $user = auth()->user();
    if (! $user || ! $user->enrollments()->where('course_id', $course->id)->exists()) {
        abort(403, 'You are not enrolled in this course.');
    }

    $topicBelongsToCourse = $topic->module && (int) $topic->module->course_id === (int) $course->id;
    if (! $topicBelongsToCourse) {
        abort(403, 'Unauthorized material access.');
    }

    return (int) $user->id;
}

// In your CourseController.php

public function studentQuizzes()
{
    $user = auth()->user();

    // Get enrolled courses
    $enrolledCourses = $user->enrollments()
        ->with('course')
        ->get();

    // If you want to show quizzes from enrolled courses
    $quizzes = collect();

    foreach ($enrolledCourses as $enrollment) {
        $courseQuizzes = $enrollment->course->quizzes ?? collect();
        $quizzes = $quizzes->merge($courseQuizzes);
    }

    return view('students.getquiz', compact('quizzes', 'enrolledCourses'));
}

// Alternative: All quizzes from enrolled courses with more details
public function allQuizzes()
{
    $user = auth()->user();

    // Get enrolled course IDs
    $enrolledCourseIds = $user->enrollments()->pluck('course_id');

    // Get quizzes from enrolled courses
    $quizzes = \App\Models\Quiz::whereIn('course_id', $enrolledCourseIds)
        ->with(['course', 'module', 'questions'])
        ->where('is_active', true)
        ->orderBy('due_at', 'asc')
        ->get()
        ->map(function($quiz) use ($user) {
            // Add attempts count for this user
            $quiz->attempts_count = $quiz->attempts()->where('user_id', $user->id)->count();
            $quiz->latest_attempt = $quiz->attempts()
                ->where('user_id', $user->id)
                ->latest()
                ->first();
            return $quiz;
        });

    return view('students.all-quizzes', compact('quizzes'));
}

private function createEnrollment(int $studentId, Course $course, string $paymentStatus, ?string $reference = null): Enrollment
{
    $payload = [
        'student_id' => $studentId,
        'course_id' => $course->id,
        'enrolled_at' => now(),
    ];

    if ($paymentStatus === 'paid') {
        $payload['price_paid'] = (float) $course->price;
        $payload['payment_status'] = 'paid';
        $payload['payment_reference'] = $reference ?: ('PAY-'.Str::upper(Str::random(10)));
        $payload['purchased_at'] = now();
    } elseif ($paymentStatus === 'pending') {
        $payload['price_paid'] = (float) $course->price;
        $payload['payment_status'] = 'pending';
        $payload['payment_reference'] = $reference ?: ('PAY-'.Str::upper(Str::random(10)));
        // purchased_at gets set when webhook confirms
        $payload['purchased_at'] = null;
    } else {
        $payload['price_paid'] = 0;
        $payload['payment_status'] = 'free';
        $payload['purchased_at'] = now();
        $payload['payment_reference'] = null;
    }


    return Enrollment::create($payload);
}


}
