<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectMemberModel extends Model
{
    protected $table            = 'project_members';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['project_id', 'user_id', 'role', 'status'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Ambil semua member dari sebuah project beserta data user-nya
     */
    public function getMembersByProject(int $projectId): array
    {
        return $this->select('project_members.*, users.nama, users.email')
                    ->join('users', 'users.id = project_members.user_id')
                    ->where('project_members.project_id', $projectId)
                    ->findAll();
    }

    /**
     * Ambil semua project yang diikuti oleh seorang user (yang sudah di-accept)
     */
    public function getProjectsByUser(int $userId): array
    {
        return $this->select('project_members.*, projects.nama_project, projects.deskripsi, users.nama as owner_name')
                    ->join('projects', 'projects.id = project_members.project_id')
                    ->join('users', 'users.id = projects.user_id', 'left')
                    ->where('project_members.user_id', $userId)
                    ->where('project_members.status', 'accepted')
                    ->where('projects.deleted_at IS NULL')
                    ->findAll();
    }

    /**
     * Ambil daftar undangan (pending) untuk seorang user
     */
    public function getPendingInvitations(int $userId): array
    {
        return $this->select('project_members.*, projects.nama_project, users.nama as owner_name')
                    ->join('projects', 'projects.id = project_members.project_id')
                    ->join('users', 'users.id = projects.user_id')
                    ->where('project_members.user_id', $userId)
                    ->where('project_members.status', 'pending')
                    ->where('projects.deleted_at IS NULL')
                    ->findAll();
    }
}
