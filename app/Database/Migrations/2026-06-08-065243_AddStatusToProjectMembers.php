<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToProjectMembers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('project_members', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'accepted', 'declined'],
                'default'    => 'pending',
                'after'      => 'role'
            ]
        ]);
        
        // Asumsi data yang sudah ada sebelum fitur ini dibuat adalah 'accepted'
        $db = \Config\Database::connect();
        $db->table('project_members')->update(['status' => 'accepted']);
    }

    public function down()
    {
        $this->forge->dropColumn('project_members', 'status');
    }
}
