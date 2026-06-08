<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAssignedToTasks extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tasks', [
            'assigned_to' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'project_id'],
        ]);

        // Tambah foreign key assigned_to -> users.id
        $this->forge->addForeignKey('assigned_to', 'users', 'id', 'SET NULL', 'CASCADE', 'tasks_assigned_to_foreign');
        // Note: addForeignKey setelah addColumn tidak langsung jalan di CI4,
        // jadi kita gunakan raw query
        $this->db->query('ALTER TABLE tasks ADD CONSTRAINT tasks_assigned_to_foreign FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE tasks DROP FOREIGN KEY tasks_assigned_to_foreign');
        $this->forge->dropColumn('tasks', 'assigned_to');
    }
}
