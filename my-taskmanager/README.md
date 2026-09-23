# Personal Task Manager

A simple individual task manager built with Laravel and Blade for WST21-PM-2026-SF.

## Project Details

- **Project Code:** WST21-PM-2026-SF
- **Student Name:** [Your name]
- **Course & Year:** [Your course and year]
- **Database Used:** SQLite (Laravel-compatible; can be switched to MySQL in `.env`)

## Features

- Add Task
- View Tasks
- Edit Task
- Delete Task
- Update Status (Pending / Completed)

## Running Locally

1. Install PHP 8.2+, Composer, and SQLite.
2. Run `composer install`.
3. Copy `.env.example` to `.env` and run `php artisan key:generate`.
4. Run `php artisan migrate`.
5. Start the app with `php artisan serve`.
6. Open `http://localhost:8000`.
