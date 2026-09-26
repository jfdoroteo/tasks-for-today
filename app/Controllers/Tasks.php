<?php

namespace App\Controllers;

use App\Models\TaskModel;

class Tasks extends BaseController
{
    public function index(): string
    {
        $taskModel = new TaskModel();

        $data = [
            'title'      => 'All Tasks',
            'activePage' => 'tasks',
            'tasks'      => $taskModel
                ->orderBy('task_date', 'ASC')
                ->orderBy('id', 'ASC')
                ->findAll(),
        ];

        return view('layout/header', $data)
            . view('tasks/index', $data)
            . view('layout/footer');
    }
}
