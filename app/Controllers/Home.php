<?php

namespace App\Controllers;

use App\Models\TaskModel;
use CodeIgniter\I18n\Time;

class Home extends BaseController
{
    public function index(): string
    {
        $today = Time::today('Asia/Manila');
        $taskModel = new TaskModel();

        $data = [
            'title'      => 'Today',
            'activePage' => 'today',
            'todayLabel' => $today->format('l, F j, Y'),
            'tasks'      => $taskModel
                ->where('task_date', $today->format('Y-m-d'))
                ->where('is_archived', 0)
                ->orderBy('id', 'ASC')
                ->findAll(),
        ];

        return view('layout/header', $data)
            . view('pages/welcome', $data)
            . view('layout/footer');
    }

    public function about(): string
    {
        $data = [
            'title'      => 'About',
            'activePage' => 'about',
        ];

        return view('layout/header', $data)
            . view('pages/about')
            . view('layout/footer');
    }
}
