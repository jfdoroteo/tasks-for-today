<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    public function login(): string
    {
        $data = [
            'title'      => 'Sign in',
            'activePage' => 'login',
        ];

        return view('layout/header', $data)
            . view('auth/login')
            . view('layout/footer');
    }

    public function attempt(): RedirectResponse
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $username = is_string($username) ? trim($username) : '';
        $password = is_string($password) ? $password : '';

        if (! $this->validateData(
            ['username' => $username, 'password' => $password],
            ['username' => 'required|max_length[50]', 'password' => 'required|max_length[255]']
        )) {
            return redirect()->to(site_url('login'))
                ->with('auth_error', 'Enter your username and password.')
                ->with('login_username', $username);
        }

        $user = (new UserModel())->where('username', $username)->first();

        if ($user === null || empty($user['password_hash']) || ! password_verify($password, $user['password_hash'])) {
            return redirect()->to(site_url('login'))
                ->with('auth_error', 'The username or password is incorrect.')
                ->with('login_username', $username);
        }

        $session = session();
        $session->regenerate(true);
        $session->set([
            'task_user_id'   => (int) $user['id'],
            'task_user_name' => $user['full_name'],
        ]);

        return redirect()->to(site_url('tasks'))->with('notice', 'Signed in. You can now manage tasks.');
    }

    public function logout(): RedirectResponse
    {
        $session = session();
        $session->remove(['task_user_id', 'task_user_name']);
        $session->destroy();

        return redirect()->to(site_url('login'));
    }
}
