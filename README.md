# Tasks for Today

A CodeIgniter 4 task manager for IT0049 TSA2. The original pixel-art pages remain, with task creation, editing, archiving, and session-based sign-in added. The Welcome page shows today's active tasks; the Task List shows all active tasks.

## Requirements

- PHP 8.2 or newer with the extensions required by CodeIgniter 4
- Composer
- MySQL or MariaDB (XAMPP works locally)

## Set up locally

1. Open a terminal in this project folder and run `composer install`.
2. Copy the included `env` template to `.env`. Set `CI_ENVIRONMENT = development`, `app.baseURL = 'http://localhost:8080/'`, and your MySQL connection details. Do not commit `.env`.
3. Start MySQL. For a **new, empty** database only, import `database/tasks_for_today.sql`. It creates the database, tables, nine sample tasks, and one demo user. Do not re-import it over a database containing your own tasks.
4. If you already have the TSA1 database, **skip the SQL import**. Instead run `php spark migrate` to add `users.password_hash` and `tasks.is_archived` without replacing existing records. Then run `php spark db:seed DemoPasswordSeeder` to give accounts with no password an unknown random password hash.
5. Run `php spark tasks:password johnhenrichdoroteo-ui` to generate a new sign-in password for the demo username. Copy the printed password privately; the database stores only its hash. This also works after a fresh SQL import. Never put the password in Git, screenshots, or the README.
6. Run `php spark serve` from the project root and open `http://localhost:8080/`. If port 8080 is occupied, use `php spark serve --port 8081` and change `app.baseURL` to `http://localhost:8081/`.

The app uses the `Asia/Manila` timezone for its definition of today. The sample SQL uses MySQL's `CURDATE()`; matching the MySQL timezone keeps sample task dates aligned with the app.

## Pages and access

| URL | Access | Purpose |
| --- | --- | --- |
| `/` | Public | Today's active tasks |
| `/tasks` | Public | All active tasks |
| `/profile` | Public | Demo user profile |
| `/about` | Public | Project information |
| `/login` | Public | Sign-in form |
| `/tasks/new` | Signed in | New task form |
| `/tasks/{id}/edit` | Signed in | Edit an active task |

The create, update, archive, and logout actions use POST forms. The task routes use an authentication filter, and POST requests use CSRF protection. After a successful login, the session identifies the user; logout ends that session. Archived tasks remain in the database with `is_archived = 1` but disappear from the Welcome page and public Task List.

## MVC structure

- `app/Config/Routes.php` maps URLs to controller methods and applies the task authentication filter.
- `app/Controllers/Auth.php` handles sign-in and sign-out; `app/Filters/TaskAuth.php` guards task-changing pages.
- `app/Controllers/Home.php` and `app/Controllers/Tasks.php` prepare task data for the views and perform task actions.
- `app/Models/TaskModel.php` and `app/Models/UserModel.php` define their database tables and writable fields.
- `app/Views/` contains the pages and forms; `public/assets/css/style.css` contains the shared styling.
- `app/Database/Migrations/` upgrades an existing TSA1 database. `database/tasks_for_today.sql` is for a fresh database only.

## Test the activity

1. While signed out, open `/tasks/new` or a task edit URL. You should return to `/login`.
2. Sign in with the demo username and your privately generated password. Create a task. Try an empty title or date to see validation messages, then submit valid values.
3. Edit that task and confirm its new values appear in `/tasks`.
4. Archive it and confirm it disappears from `/tasks` while its database row remains marked `is_archived = 1`.
5. Log out and confirm a protected URL redirects to `/login` again. The four original pages should remain public throughout.

For automated checks, run `php -d extension=sqlite3 vendor/bin/phpunit --no-coverage` if your CLI PHP has SQLite available. These tests use an isolated in-memory database and do not alter your MySQL data.

## Submission note

The repository includes a fresh-database SQL export and a migration for existing installations. A public GitHub repository and a working hosted PHP/MySQL deployment must still be provided separately for submission; a GitHub link alone does not run the application.
