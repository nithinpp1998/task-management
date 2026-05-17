# AI-Assisted Task Management System

This is a complete production-ready Laravel 10+ AI Assisted Task Management System built with Laravel Blade, Tailwind CSS, MySQL, Repository Pattern, Service Layer Architecture, Policies, REST APIs, Docker, and AI Integration.

## Architecture & Patterns Used

### Clean Architecture
The application strictly follows a clean layered architecture to ensure modularity, scalability, and maintainability.
- **Controllers**: Responsible only for handling HTTP requests, authorization, and delegating business logic to Services. Direct Eloquent calls are completely avoided here.
- **Service Layer**: Houses the core business logic, transaction handling, and coordinates between Repositories and external services (like the AI integration).
- **Repository Pattern**: Abstracts data access. The `TaskRepository` implements `TaskRepositoryInterface`, allowing flexible database querying, filtering, and pagination while keeping the Service layer decoupled from Eloquent.

### Design Patterns & Principles
- **Dependency Injection**: Used heavily across Controllers, Services, and Jobs to decouple classes and enhance testability.
- **SOLID Principles**: Adhered to (e.g., Single Responsibility Principle in Services/Jobs, Open/Closed through Interfaces).
- **Enums**: Strongly typed Enums (`TaskPriority`, `TaskStatus`, `AIPriority`) are used to manage finite states.
- **Policies**: Fine-grained authorization implemented via `TaskPolicy`.
- **Form Requests**: All validation logic is extracted to dedicated Form Request classes.
- **API Resources**: Transforms models into standardized JSON responses (`TaskResource`).

### AI Integration Flow
1. When a new Task is created, the `TaskService` delegates to the `TaskRepository`.
2. After successful persistence, a `ProcessAITaskSummary` Queue Job is dispatched to run asynchronously.
3. The Job uses the `AIService` to communicate with the AI model (mocked here but ready for OpenAI/Gemini SDK).
4. The generated summary and AI priority are then patched back to the task, with built-in retry logic and exception logging.

## Installation Guide (Docker/Sail)

This project is pre-configured with Laravel Sail (Docker), providing PHP 8.2+, MySQL, Redis, and Nginx.

1. Clone the repository and navigate to the project root:
   ```bash
   cd task-manager2.0
   ```
2. Copy the environment file and install dependencies:
   ```bash
   cp .env.example .env
   composer install
   npm install
   ```
3. Start the Docker containers using Sail:
   ```bash
   ./vendor/bin/sail up -d
   ```
4. Generate the application key and run database migrations/seeders:
   ```bash
   ./vendor/bin/sail artisan key:generate
   ./vendor/bin/sail artisan migrate:fresh --seed
   ```
5. Compile frontend assets:
   ```bash
   ./vendor/bin/sail npm run build
   ```

## Running the Queue (For AI Features)

The AI summary generation happens asynchronously using a Queue Worker. To start it, run:
```bash
./vendor/bin/sail artisan queue:work
```

## Testing

The application includes robust Unit and Feature tests.
To run the test suite:
```bash
./vendor/bin/sail artisan test
```

## Role System

The database seeder automatically generates two default accounts:
1. **Admin** (`admin@example.com` / `password`): Full access to create, update, delete, and view all tasks. Access to user management.
2. **User** (`user@example.com` / `password`): Can only view their assigned tasks and update the task status.

## REST APIs

The application exposes fully authenticated REST APIs (using Sanctum). Note: A Bearer token is required.

- `GET /api/tasks` - List all tasks (with filters & pagination)
- `POST /api/tasks` - Create a new task (Admin only)
- `PATCH /api/tasks/{id}/status` - Update task status
- `GET /api/tasks/{id}/ai-summary` - Retrieve AI-generated summary
