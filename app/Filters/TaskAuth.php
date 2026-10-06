<?php

namespace App\Filters;

use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class TaskAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userId = session()->get('task_user_id');
        $user = is_int($userId) && $userId > 0 ? (new UserModel())->find($userId) : null;

        if ($user !== null && ! empty($user['password_hash'])) {
            return null;
        }

        session()->remove(['task_user_id', 'task_user_name']);

        return redirect()->to(site_url('login'))
            ->with('auth_notice', 'Sign in to manage tasks. The task lists are still open to everyone.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader('Cache-Control', 'no-store, max-age=0');
        $response->setHeader('Pragma', 'no-cache');
    }
}
