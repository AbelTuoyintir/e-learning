# TODO

## Learning History insert failure (Unknown column activity_id)
- [x] Fix mismatch between `learning_history` migration/model/controller payload.
  - Current migration columns: `id`, `student_id`, `activity_type`, `description`, `related_id`, `related_type`, `metadata`, `timestamps`
  - Fixed code inserts: use `related_id`/`related_type` instead of `activity_id`.
- [x] Decide one approach: Option B (recommended)
- [x] Implement code changes.
- [x] Run migration if needed (only if the `learning_history` table was not created yet).

- [x] Run a quick quiz submission test to verify learning history row is created.
