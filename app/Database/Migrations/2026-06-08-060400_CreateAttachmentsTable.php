<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAttachmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'task_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'user_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nama_file'   => ['type' => 'VARCHAR', 'constraint' => '255'],
            'path_file'   => ['type' => 'VARCHAR', 'constraint' => '500'],
            'tipe_file'   => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'ukuran_file' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0, 'comment' => 'Ukuran dalam bytes'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('task_id', 'tasks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('attachments');
    }

    public function down()
    {
        $this->forge->dropTable('attachments');
    }
}
