# Tasks for Today

A small CodeIgniter 4 task-management application for IT0049 TSA1. The Welcome page filters tasks to the current date; the Task List shows all records. The Profile page reads one demo user, and the About page identifies the developer.

## Requirements

- PHP 8.2 or newer with the extensions required by CodeIgniter 4
- Composer
- MySQL or MariaDB (XAMPP works locally)

## Set up locally

1. Open a terminal in this project folder and run `composer install`.
2. Copy the included `env` template to `.env`. Set `CI_ENVIRONMENT = development`, `app.baseURL = 'http://localhost:8080/'`, and your MySQL connection details. The `.env` file is intentionally ignored by Git.
3. Start MySQL and import `database/tasks_for_today.sql` into an empty database. The script creates the `tasks_for_today` database, both tables, nine tasks, and one demo user. Task dates are relative to the day you import it.
4. Run `php spark serve` from the project root (it uses port 8080 by default).
5. Open `http://localhost:8080/`.

The application uses the `Asia/Manila` timezone for its definition of today. Keep the MySQL server on the same timezone when importing the sample SQL so its `CURDATE()` values match the app's date.

## Pages

| URL | Purpose |
| --- | --- |
| `/` | Today's tasks only |
| `/tasks` | All tasks, ordered by date |
| `/profile` | The single demo user |
| `/about` | Project and developer information |

## How the data flows

`TaskModel` and `UserModel` represent the database tables. The controllers use Model methods to retrieve records and pass them to views. `Home::index()` filters `task_date` to today's date; `Tasks::index()` retrieves the complete ordered list. This assessment is read-only: forms, editing, deletion, and uploads are not required.

The database script is for an empty database. Do not re-import it over existing tables.
