<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTasksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'project_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'judul_task'  => ['type' => 'VARCHAR', 'constraint' => '255'],
            'deskripsi'   => ['type' => 'TEXT', 'null' => true],
            'status'      => ['type' => 'ENUM', 'constraint' => ['to_do', 'in_progress', 'done'], 'default' => 'to_do'],
            'prioritas'   => ['type' => 'ENUM', 'constraint' => ['low', 'mid', 'high'], 'default' => 'mid'],
            'deadline'    => ['type' => 'DATE', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('project_id', 'projects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tasks');
    }

    public function down()
    {
        $this->forge->dropTable('tasks');
    }
}
