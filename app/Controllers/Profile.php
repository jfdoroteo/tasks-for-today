<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    public function index(): string
    {
        $userModel = new UserModel();

        $data = [
            'title'      => 'Profile',
            'activePage' => 'profile',
            'user'       => $userModel->first(),
        ];

        return view('layout/header', $data)
            . view('profile/index', $data)
            . view('layout/footer');
    }
}
