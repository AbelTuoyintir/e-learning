# TODO - Dynamic Quizzes & Module Progression

## Step 1: Verify current DB schema
- Inspect existing migrations for quizzes and quiz_attempts tables to avoid duplicate columns.

## Step 2: Add new migrations
- Create migration(s) to add/ensure: `type`, `question_distribution`, and defaults for `passing_score` on `quizzes`.
- Create migration to add `question_ids` (json) on `quiz_attempts`.

## Step 3: Implement dynamic question fetching + attempt locking
- Update `StudentController@showQuiz`:
  - If quiz has `question_distribution`, fetch questions per distribution.
  - Create a `QuizAttempt` at start and store selected `question_ids`.
  - Render only locked question set.
- Update `StudentController@submit`:
  - Score only locked question set.
  - Use strict passing logic for final quizzes (>= passing_score, default 65).

## Step 4: Implement strict module progression
- Update `CourseController@getMaterials`:
  - Lock module N unless module N-1 final quiz is passed.
  - Pass locked/unlocked flags to `students/materials.blade.php`.

## Step 5: Admin quiz creation/edit UI + controller
- Update `QuizController@store` and `@update` validation/saving for new fields.
- Update admin quiz blade templates to allow:
  - quiz type (practice/final)
  - passing score (default 65)
  - question distribution builder (topic + counts)

## Step 6: Student materials UI
- Update `resources/views/students/materials.blade.php`:
  - visually disable locked modules.
  - display unlock requirement banner.

## Step 7: Tests / manual verification
- Manual cases:
  - Course with 2 modules.
  - Module 1 final quiz failing keeps Module 2 locked.
  - Retake and passing unlocks Module 2.
  - Refresh during quiz doesn’t change locked questions.
  - Confirm distribution counts per topic match admin config.

