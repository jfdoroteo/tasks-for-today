<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTaskAccessFields extends Migration
{
    public function up()
    {
        if (! in_array('password_hash', $this->db->getFieldNames('users'), true)) {
            $this->forge->addColumn('users', [
                'password_hash' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
            ]);
        }

        if (! in_array('is_archived', $this->db->getFieldNames('tasks'), true)) {
            $this->forge->addColumn('tasks', [
                'is_archived' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => false,
                ],
            ]);
        }
    }

    public function down()
    {
        if (in_array('is_archived', $this->db->getFieldNames('tasks'), true)) {
            $this->forge->dropColumn('tasks', 'is_archived');
        }

        if (in_array('password_hash', $this->db->getFieldNames('users'), true)) {
            $this->forge->dropColumn('users', 'password_hash');
        }
    }
}
