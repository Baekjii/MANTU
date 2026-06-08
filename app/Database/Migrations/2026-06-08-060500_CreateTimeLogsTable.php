<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTimeLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'task_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'user_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'waktu_mulai'   => ['type' => 'DATETIME'],
            'waktu_selesai' => ['type' => 'DATETIME', 'null' => true],
            'durasi_menit'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
            'catatan'       => ['type' => 'VARCHAR', 'constraint' => '500', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('task_id', 'tasks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('time_logs');
    }

    public function down()
    {
        $this->forge->dropTable('time_logs');
    }
}
