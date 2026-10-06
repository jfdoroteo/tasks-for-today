<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

class DemoPasswordSeeder extends Seeder
{
    public function run(): void
    {
        $users = $this->db->table('users')->select('id, password_hash')->get()->getResultArray();
        $count = 0;

        foreach ($users as $user) {
            if (! empty($user['password_hash'])) {
                continue;
            }

            // Existing accounts receive an unknown random password until one is set privately.
            $randomPassword = bin2hex(random_bytes(18));
            $this->db->table('users')->where('id', $user['id'])->update([
                'password_hash' => password_hash($randomPassword, PASSWORD_DEFAULT),
            ]);
            $count++;
        }

        CLI::write("Added password hashes to {$count} existing user accounts.");
        CLI::write('Run php spark tasks:password <username> to generate a password you can use to sign in.');
    }
}
