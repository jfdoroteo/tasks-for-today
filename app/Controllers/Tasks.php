<?php

namespace App\Controllers;

use App\Models\TaskModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\I18n\Time;

class Tasks extends BaseController
{
    public function index(): string
    {
        $taskModel = new TaskModel();

        $data = [
            'title'      => 'All Tasks',
            'activePage' => 'tasks',
            'tasks'      => $taskModel
                ->where('is_archived', 0)
                ->orderBy('task_date', 'ASC')
                ->orderBy('id', 'ASC')
                ->findAll(),
        ];

        return view('layout/header', $data)
            . view('tasks/index', $data)
            . view('layout/footer');
    }

    public function new(): string
    {
        return $this->taskForm(null);
    }

    public function create(): RedirectResponse
    {
        $data = $this->formData();

        if (! $this->validateData($data, $this->rules())) {
            return redirect()->to(site_url('tasks/new'))
                ->with('task_form_values', $data)
                ->with('task_form_errors', $this->validator->getErrors());
        }

        $data['created_at'] = Time::now('Asia/Manila')->format('Y-m-d H:i:s');

        try {
            $saved = (new TaskModel())->insert($data);
        } catch (DatabaseException $exception) {
            log_message('error', 'Task insert failed: {message}', ['message' => $exception->getMessage()]);
            $saved = false;
        }

        if ($saved === false) {
            return redirect()->to(site_url('tasks/new'))
                ->with('task_form_values', $this->formData())
                ->with('task_form_error', 'The task could not be saved. Please try again.');
        }

        return redirect()->to(site_url('tasks'))->with('notice', 'Task added.');
    }

    public function edit(int $id): string
    {
        return $this->taskForm($this->activeTask($id));
    }

    public function update(int $id): RedirectResponse
    {
        $this->activeTask($id);
        $data = $this->formData();

        if (! $this->validateData($data, $this->rules())) {
            return redirect()->to(site_url("tasks/{$id}/edit"))
                ->with('task_form_values', $data)
                ->with('task_form_errors', $this->validator->getErrors());
        }

        try {
            $saved = (new TaskModel())->update($id, $data);
        } catch (DatabaseException $exception) {
            log_message('error', 'Task update failed: {message}', ['message' => $exception->getMessage()]);
            $saved = false;
        }

        if (! $saved) {
            return redirect()->to(site_url("tasks/{$id}/edit"))
                ->with('task_form_values', $data)
                ->with('task_form_error', 'The task could not be updated. Please try again.');
        }

        return redirect()->to(site_url('tasks'))->with('notice', 'Task updated.');
    }

    public function archive(int $id): RedirectResponse
    {
        $this->activeTask($id);

        try {
            $saved = (new TaskModel())->update($id, ['is_archived' => 1]);
        } catch (DatabaseException $exception) {
            log_message('error', 'Task archive failed: {message}', ['message' => $exception->getMessage()]);
            $saved = false;
        }

        return redirect()->to(site_url('tasks'))
            ->with($saved ? 'notice' : 'task_error', $saved ? 'Task archived.' : 'The task could not be archived.');
    }

    private function taskForm(?array $task): string
    {
        $editing = $task !== null;
        $data = [
            'title'      => $editing ? 'Edit task' : 'New task',
            'activePage' => 'tasks',
            'task'       => $task,
            'today'      => Time::today('Asia/Manila')->format('Y-m-d'),
        ];

        return view('layout/header', $data)
            . view('tasks/form', $data)
            . view('layout/footer');
    }

    private function activeTask(int $id): array
    {
        $task = (new TaskModel())->where('id', $id)->where('is_archived', 0)->first();

        if ($task === null) {
            throw PageNotFoundException::forPageNotFound('Task not found.');
        }

        return $task;
    }

    private function formData(): array
    {
        $data = [];

        foreach (['title', 'task_date', 'status'] as $field) {
            $value = $this->request->getPost($field);
            $data[$field] = is_string($value) ? trim($value) : '';
        }

        return $data;
    }

    private function rules(): array
    {
        return [
            'title'     => 'required|min_length[2]|max_length[150]',
            'task_date' => 'required|valid_date[Y-m-d]',
            'status'    => 'required|in_list[pending,in_progress,completed]',
        ];
    }
}
