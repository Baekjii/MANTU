<?php

namespace App\Models;

use CodeIgniter\Model;

class TimeLogModel extends Model
{
    protected $table            = 'time_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['task_id', 'user_id', 'waktu_mulai', 'waktu_selesai', 'durasi_menit', 'catatan'];

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
     * Ambil semua time logs dari sebuah task beserta data user-nya
     */
    public function getLogsByTask(int $taskId): array
    {
        return $this->select('time_logs.*, users.nama')
                    ->join('users', 'users.id = time_logs.user_id')
                    ->where('time_logs.task_id', $taskId)
                    ->orderBy('time_logs.waktu_mulai', 'DESC')
                    ->findAll();
    }

    /**
     * Hitung total durasi (menit) yang dihabiskan user tertentu di sebuah project
     */
    public function getTotalDurasiByProject(int $projectId, ?int $userId = null): int
    {
        $builder = $this->select('SUM(time_logs.durasi_menit) as total')
                        ->join('tasks', 'tasks.id = time_logs.task_id')
                        ->where('tasks.project_id', $projectId);

        if ($userId !== null) {
            $builder->where('time_logs.user_id', $userId);
        }

        $result = $builder->first();
        return (int) ($result['total'] ?? 0);
    }
}
