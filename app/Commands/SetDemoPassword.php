<?php

namespace App\Commands;

use App\Models\UserModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SetDemoPassword extends BaseCommand
{
    protected $group = 'Tasks for Today';
    protected $name = 'tasks:password';
    protected $description = 'Generate a new password for an existing task-manager account.';
    protected $usage = 'tasks:password <username>';
    protected $arguments = [
        'username' => 'The account username whose password will be reset.',
    ];

    public function run(array $params)
    {
        $username = $params[0] ?? '';

        if ($username === '') {
            CLI::error('Provide a username, for example: php spark tasks:password johnhenrichdoroteo-ui');

            return EXIT_ERROR;
        }

        $model = new UserModel();
        $user = $model->where('username', $username)->first();

        if ($user === null) {
            CLI::error('That user account was not found.');

            return EXIT_ERROR;
        }

        $password = bin2hex(random_bytes(18));
        $saved = $model->update($user['id'], [
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        if (! $saved) {
            CLI::error('The password could not be updated.');

            return EXIT_ERROR;
        }

        CLI::write('Username: ' . $username);
        CLI::write('New password: ' . $password);
        CLI::write('Copy it now; it is shown only once and stored as a hash.');
        CLI::write('Do not publish this password or include it in screenshots.');

        return EXIT_SUCCESS;
    }
}
