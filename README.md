# AI-Assisted Task Management System

## About Project

This is a premium, production-ready AI-Assisted Task Management System. Built using **Laravel 10+**, **Tailwind CSS**, and **MySQL**, it demonstrates advanced web application concepts, clean architecture, and modern AI integration.

### Core Architecture

This project is built utilizing robust software design patterns to ensure maintainability, testability, and decoupled logic:
- **Service Layer & Repository Pattern:** Business logic is abstracted into dedicated Services (e.g., `TaskService`, `AIService`), while data access is handled by Repositories (`TaskRepositoryInterface`, `Eloquent/TaskRepository`). Controllers only handle HTTP request validation and response formatting.
- **Form Request Validation Architecture:** Extensive use of strict, modular Form Requests (e.g., `UpdateProfileRequest`, `StoreTaskRequest`) completely separating validation rules from Controller logic.
- **Repository Caching:** Implements intelligent caching with tags and graceful degradation. `TaskRepository` caches list (`all()`) and single fetch (`find()`) queries. Write operations (`create`, `update`, `delete`, `updateStatus`) automatically invalidate the cache using localized tags to ensure fresh data delivery without performance penalties.
- **Fine-grained RBAC Policy System:** Resources are protected dynamically using Laravel Policies (`TaskPolicy`). Admins retain full CRUD privileges, while Users are restricted to updating statuses on assigned tasks only.
- **Sanctum-Protected REST APIs:** Cross-platform integration ready via `/api/tasks`.

### AI Integration (Gemini)

The system leverages the **Gemini API** for automated intelligent task insights:
- **Asynchronous Queue Workers:** The `ProcessAITaskSummary` job is dispatched onto a database queue when a task is created or its description significantly changes. 
- **Graceful Retries & Fallbacks:** The job utilizes timeouts, 3 backoff retries, and dedicated `failed()` hooks to insert a safe fallback string if the AI is unreachable, preventing UI breakage.
- **Smart UI Polling:** The task detail page dynamically detects if the `ai_summary` is null. It displays an animated loader and automatically polls for the generated content once the queue worker finishes.
- **Insight Extraction:** The `AIService` constructs a detailed prompt from the task title and description, instructing the Gemini model (`gemini-1.5-flash`) to return a strict JSON payload containing a concise summary and an AI-recommended priority level.

---

## Setup Instructions

### Requirements
- **Local Environment:** PHP >= 8.2, Composer, Node.js >= 18, NPM, MySQL >= 8.0, Redis.
- **Docker Environment (Recommended):** Docker Desktop (includes Docker Compose).

---

### Installation & Environment Setup

#### Option 1: Using Docker (Recommended via Laravel Sail)
1. **Clone the Repository & Navigate to the Project Root:**
   ```bash
   cd task-manager2.0
   ```
2. **Setup Environment File:**
   ```bash
   cp .env.example .env
   ```
3. **Install Composer Dependencies:**
   ```bash
   composer install
   ```
4. **Install NPM Dependencies:**
   ```bash
   npm install
   ```
5. **Start Docker Containers:**
   ```bash
   ./vendor/bin/sail up -d
   ```
6. **Generate Application Key:**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ```
7. **Run Database Setup & Migrations (with seeders):**
   ```bash
   ./vendor/bin/sail artisan migrate:fresh --seed
   ```
8. **Compile Frontend Assets:**
   ```bash
   ./vendor/bin/sail npm run build
   ```

#### Option 2: Running Locally (Without Docker)
1. **Setup Environment File:**
   ```bash
   cp .env.example .env
   ```
2. **Install Dependencies:**
   ```bash
   composer install
   ```
3. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```
4. **Database Configuration:**
   Configure your database credentials inside the `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=task_manager
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```
5. **Run Migrations & Seeders:**
   ```bash
   php artisan migrate:fresh --seed
   ```
6. **Install Frontend Assets & Compile:**
   ```bash
   npm install
   npm run build
   ```

---

### Running the Application

1. **Start the Development Server:**
   ```bash
   # Docker
   ./vendor/bin/sail up -d
   # Local
   php artisan serve
   ```
2. **Start the Queue Worker (Mandatory for AI Summary Processing):**
   ```bash
   # Docker
   ./vendor/bin/sail artisan queue:work
   # Local
   php artisan queue:work
   ```
3. **Access the Application:**
   Navigate to [http://127.0.0.1:8000](http://127.0.0.1:8000)
   - **Admin User:** `admin@example.com` / `password`
   - **Regular User:** `user@example.com` / `password`

---

### Running Tests
To verify all unit, feature, and authorization flows:
```bash
# Docker
./vendor/bin/sail artisan test
# Local
php artisan test
```

---

### Common Fixes

#### 1. Database Connection Refused
Ensure MySQL service is running. If running locally, check port `3306` inside `.env`. If running Docker, make sure you stop any conflicting local MySQL instance.

#### 2. Queue Jobs not processing
Make sure `php artisan queue:work` is actively running in the background, as AI summaries are queued asynchronously.

#### 3. Asset Styles not displaying
Compile the assets to ensure Tailwind utility styling is fully built:
```bash
npm run build
```
