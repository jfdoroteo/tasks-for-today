<?php

use App\Models\TaskModel;
use App\Models\UserModel;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/** @internal */
final class TaskWorkflowTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    private BaseConnection $testDb;
    private int $userId;
    private int $taskId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testDb = db_connect('tests');
        $this->testDb->query('CREATE TABLE db_users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            full_name TEXT NOT NULL,
            email TEXT NOT NULL,
            password_hash TEXT,
            created_at TEXT NOT NULL
        )');
        $this->testDb->query('CREATE TABLE db_tasks (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            status TEXT NOT NULL,
            task_date TEXT NOT NULL,
            created_at TEXT NOT NULL,
            is_archived INTEGER NOT NULL DEFAULT 0
        )');

        $this->userId = (int) (new UserModel())->insert([
            'username'      => 'demo_user',
            'full_name'     => 'Demo User',
            'email'         => 'demo@example.test',
            'password_hash' => password_hash('TestPassword123!', PASSWORD_DEFAULT),
            'created_at'    => '2026-10-05 10:00:00',
        ]);
        $this->taskId = (int) (new TaskModel())->insert([
            'title'      => 'Original task',
            'status'     => 'pending',
            'task_date'  => date('Y-m-d'),
            'created_at' => '2026-10-05 10:00:00',
        ]);
    }

    protected function tearDown(): void
    {
        $this->testDb->query('DROP TABLE db_tasks');
        $this->testDb->query('DROP TABLE db_users');

        parent::tearDown();
    }

    public function testPublicPagesRemainReadableWithoutLogin(): void
    {
        foreach (['/', '/tasks', '/profile', '/about', '/login'] as $path) {
            $this->withSession([])->get($path)->assertOK();
        }
    }

    public function testGuestsCannotOpenOrSubmitManagementActions(): void
    {
        $this->withSession([])->get('/tasks/new')->assertRedirectTo(site_url('login'));
        $this->withSession([])->get("/tasks/{$this->taskId}/edit")->assertRedirectTo(site_url('login'));

        $this->withSession([])->post('/tasks', [
            'title'      => 'Unauthorized task',
            'task_date'  => '2026-10-06',
            'status'     => 'pending',
            csrf_token() => csrf_hash(),
        ])->assertRedirectTo(site_url('login'));

        $this->withSession([])->post("/tasks/{$this->taskId}/archive", [
            csrf_token() => csrf_hash(),
        ])->assertRedirectTo(site_url('login'));

        $this->assertSame(1, (new TaskModel())->countAllResults());
        $this->assertSame(0, (int) (new TaskModel())->find($this->taskId)['is_archived']);
    }

    public function testCorrectPasswordSignsInAndWrongPasswordDoesNot(): void
    {
        $this->withSession([])->post('/login', [
            'username'   => 'demo_user',
            'password'   => 'wrong-password',
            csrf_token() => csrf_hash(),
        ])->assertRedirectTo(site_url('login'));
        $this->assertArrayNotHasKey('task_user_id', $_SESSION);

        $this->withSession([])->post('/login', [
            'username'   => 'demo_user',
            'password'   => 'TestPassword123!',
            csrf_token() => csrf_hash(),
        ])->assertRedirectTo(site_url('tasks'));
        $this->assertSame($this->userId, (int) ($_SESSION['task_user_id'] ?? 0));
        $this->withSession($_SESSION)->get('/tasks/new')->assertOK();
    }

    public function testInvalidTaskIsRejectedAndValidTaskCanBeEdited(): void
    {
        $this->postAsUser('/tasks', [
            'title'     => '',
            'task_date' => '',
            'status'    => 'pending',
        ])->assertRedirectTo(site_url('tasks/new'));
        $this->assertSame(1, (new TaskModel())->countAllResults());

        $this->postAsUser('/tasks', [
            'title'       => 'Prepare report',
            'task_date'   => '2026-10-06',
            'status'      => 'in_progress',
            'is_archived' => 1,
        ])->assertRedirectTo(site_url('tasks'));

        $newTask = (new TaskModel())->where('title', 'Prepare report')->first();
        $this->assertNotNull($newTask);
        $this->assertSame(0, (int) $newTask['is_archived']);

        $this->postAsUser('/tasks/' . $newTask['id'], [
            'title'     => 'Prepare final report',
            'task_date' => '2026-10-07',
            'status'    => 'completed',
        ])->assertRedirectTo(site_url('tasks'));

        $updated = (new TaskModel())->find($newTask['id']);
        $this->assertSame('Prepare final report', $updated['title']);
        $this->assertSame('completed', $updated['status']);
    }

    public function testArchiveKeepsTheRowButRemovesItFromPublicLists(): void
    {
        $this->postAsUser("/tasks/{$this->taskId}/archive", [])
            ->assertRedirectTo(site_url('tasks'));

        $model = new TaskModel();
        $this->assertSame(1, $model->countAllResults());
        $this->assertSame(1, (int) $model->find($this->taskId)['is_archived']);
        $this->assertStringNotContainsString('Original task', $this->withSession([])->get('/tasks')->getBody());
        $this->assertStringNotContainsString('Original task', $this->withSession([])->get('/')->getBody());
    }

    public function testLogoutEndsManagementAccess(): void
    {
        $this->withSession([
            'task_user_id'   => $this->userId,
            'task_user_name' => 'Demo User',
        ])->post('/logout', [csrf_token() => csrf_hash()])->assertRedirectTo(site_url('login'));

        $this->assertArrayNotHasKey('task_user_id', $_SESSION);
        $this->withSession([])->get('/tasks/new')->assertRedirectTo(site_url('login'));
    }

    private function postAsUser(string $path, array $data)
    {
        $data[csrf_token()] = csrf_hash();

        return $this->withSession([
            'task_user_id'   => $this->userId,
            'task_user_name' => 'Demo User',
        ])->post($path, $data);
    }
}
