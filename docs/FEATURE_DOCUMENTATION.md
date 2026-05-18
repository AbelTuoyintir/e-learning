# Quiz App Feature Documentation

## 1. Product Overview
This platform is an e-learning and assessment system with two primary user roles:
- `Admin` (web guard): manages courses, modules, topics, quizzes, questions, and learning materials.
- `Student` (student guard): enrolls in courses, studies materials, takes quizzes, and tracks results.

---

## 2. Role-Based Feature Matrix

| Feature Area | Admin | Student | Status |
|---|---|---|---|
| Authentication | Admin login/logout | Student login/logout, forgot/reset password | Implemented |
| Student onboarding | Register students | N/A | Implemented |
| Course management | Create/update/delete/list/filter courses | Browse available courses | Implemented |
| Enrollment | N/A | Enroll in courses | Implemented |
| Module management | Create/update/delete modules | View enrolled course modules | Implemented |
| Topic management | Create/update/delete/order topics | View topics in enrolled courses | Implemented |
| Learning materials | Upload topic docs (PDF/PPTX), YouTube links | Read/view materials in protected reader | Implemented |
| Document reader | N/A | Zoom, highlight, read aloud, persistent highlights | Implemented |
| Quiz management | Create/update/delete quizzes | See available quizzes from enrolled courses | Implemented |
| Question bank | Add/edit/delete/import questions; per-quiz limits | N/A | Implemented |
| Quiz-taking | N/A | Take quiz, submit answers, see score/result | Implemented |
| Results history | N/A | Paginated result history + detailed view | Implemented |
| Notifications | N/A | In-app notifications + login alerts | Implemented |
| Email integrations | System events | Result mail + password reset mail | Implemented |

---

## 3. Core Features by Domain

## 3.1 Authentication & Access Control
- Separate guards for admin (`web`) and student (`student`).
- Student login with credential validation and login activity alerts.
- Student forgot/reset password flow with token support.
- Admin login flow with role check (`admin`).
- Route middleware segmentation:
  - `auth:web` for admin pages.
  - `auth:student` for student-only pages.
- Quiz/material access constrained by enrollment checks.

## 3.2 Student Lifecycle
- Student registration form and backend creation.
- Auto-generated student index number.
- Random password generation and credential delivery by email.
- Optional SMS sending logic included (Arkesel API integration path).

## 3.3 Course Management
- Admin can create/edit/delete courses.
- Course filtering/search endpoint for admin course table.
- Course images supported.
- Student can browse active courses and enroll.

## 3.4 Module Management
- Admin can create/update/delete modules linked to courses.
- Students can navigate enrolled course module structures.

## 3.5 Topic & Learning Material Management
- Admin can create/edit/delete topics with:
  - Title, order, status.
  - YouTube URL (validated to YouTube domains).
  - Document upload (PDF/PPTX, max 10MB).
- Topic document replacement on update.
- Safe file cleanup when replacing/deleting.

## 3.6 Student Material Consumption
- Enrolled students access course materials only.
- Protected read/view routes for materials (no direct public links).
- Embedded YouTube player in materials view (with external fallback link).
- Document reader page supports:
  - Zoom control (`50%` to `200%`, default `100%`).
  - Highlighter mode.
  - Clear highlights.
  - Read aloud / stop (Web Speech API).
  - PDF text extraction fallback for read-aloud.
  - Persistent highlights saved per student + document.

## 3.7 Quizzes
- Admin can create/update/delete quizzes with:
  - Difficulty.
  - Time limit + time per question.
  - Course/module/topic linkage.
  - Cover image.
  - Per-quiz question limit (`question_limit`).
- Quiz list includes question counts.

## 3.8 Question Bank
- Admin question CRUD per quiz.
- Bulk import via CSV/XLSX/XLS.
- Import validation for required columns and options.
- Correct option normalization supports:
  - `A/B/C/D`, `option_a/...`, `1/2/3/4`, and common word forms.
- Question bank hard cap enforcement per quiz for manual adds and imports.

## 3.9 Quiz Attempt & Scoring Engine
- Student quiz submission with:
  - Answer validation.
  - Per-question points support.
  - Total/percentage calculation.
  - Pass/fail determination.
  - Attempt numbering.
- Detailed answer payload persisted in result record.
- Result detail sanitization before rendering.

## 3.10 Results & Progress
- Latest result view immediately after submission.
- Result history page with pagination and summary statistics.
- Individual result detail pages.
- Cached result/stat aggregations for performance.

## 3.11 Notification & Messaging
- In-app student notifications:
  - Quiz completion outcome.
  - Enrollment success.
- Student login alert notifications.
- Student result emails.
- Password reset notifications.

---

## 4. Data Features
- Persistent `document_highlights` table for per-student highlight state.
- Quiz entities support scheduling/readiness metadata fields (`due_at`, `is_active`, etc. where present in model/migrations).
- Results store rich details arrays and completion metadata.

---

## 5. Security & Governance Features
- Enrollment checks before quiz/material access.
- Guard-based auth separation between admin and student.
- Protected streaming endpoints for learning documents.
- Server-side validation on all major create/update/import workflows.

---

## 6. Known Gaps / Technical Debt (Current Codebase)
- `README.md` currently has unresolved merge conflict markers and needs cleanup.
- Some route/controller consistency issues likely remain from iterative changes (example: routes reference methods not visible in current `StudentController` for transcript/certificate downloads).
- Admin routes are grouped under `prefix('admin')` but not uniformly protected by admin middleware in `web.php`; this should be hardened.
- Some controller/model naming inconsistencies exist (`Enrollment` vs `StudentCourses`) and should be standardized.
- Topic creation UI contains legacy JS hooks for dynamic blocks that are not always present in DOM (guarded in code, but should be cleaned).

---

## 7. Suggested Next Iteration
1. Add automated tests for:
- Enrollment-gated access to materials/quizzes.
- Question import with limit enforcement.
- Document reader highlight save/load APIs.
2. Introduce role middleware on all admin-prefixed routes.
3. Add API/resource docs for all student material reader endpoints.
4. Resolve naming consistency across enrollment models.
5. Clean and finalize `README.md`.

