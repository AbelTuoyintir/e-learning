# AI-Powered Learning Management and Examination Preparation System

Build a modern AI-powered Learning Management System (LMS) that helps students learn, practice, assess themselves, and prepare for examinations. The platform should combine AI tutoring, intelligent learning tracking, module-based assessments, and academic performance monitoring.

## Core Features

### AI Learning Assistant
- OpenAI GPT models as primary provider.
- Ollama Cloud Service as fallback.
- AI Tutor for academic assistance, concept explanation, and mistake analysis.

### Course & Module Management
- Structured courses with multiple modules and topics.
- Independent study support for modules.

### Learning Progress Tracking
- Topic status marking (Not Started, In Progress, Completed).
- Visual progress dashboards.

### Assessment Engine
- Randomized question selection (Rule 1 & 2).
- Automatic grading for objective questions.
- Pass/fail determination based on configurable thresholds.

### Module Attempt Policy
- Maximum of 4 attempts per module.
- Mandatory module content retake required after 4 failures.

### Analytics & Records
- Detailed student academic records and performance trends.
- Admin analytics for course completion and AI usage.

## Setup

1. Clone the repository.
2. Run `composer install` and `npm install`.
3. Configure your `.env` file, including `OPENAI_API_KEY`.
4. Run migrations: `php artisan migrate`.
5. Start the server: `php artisan serve`.
