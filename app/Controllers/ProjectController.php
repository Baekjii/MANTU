<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\TaskModel;

class ProjectController extends BaseController
{
    public function index()
    {
        $projectModel = new ProjectModel();
        $memberModel = new \App\Models\ProjectMemberModel();
        
        $ownedProjects = $projectModel->where('user_id', session()->get('id'))->findAll();
        // Karena `getProjectsByUser` me-return array yang sedikit berbeda dari findAll(), kita format ulang agar konsisten
        $memberProjectsRaw = $memberModel->getProjectsByUser(session()->get('id'));
        $memberProjects = array_map(function($p) {
            return [
                'id' => $p['project_id'],
                'user_id' => null, // bukan owner
                'nama_project' => $p['nama_project'],
                'deskripsi' => $p['deskripsi']
            ];
        }, $memberProjectsRaw);
        
        // Gabungkan projects
        $data['projects'] = array_merge($ownedProjects, $memberProjects);
        
        // Ambil pending invitations
        $data['pending_invitations'] = $memberModel->getPendingInvitations(session()->get('id'));

        // stats overall
        $data['stats'] = [
            'total' => 0,
            'done' => 0,
            'done_today' => 0,
            'in_progress' => 0,
            'overdue' => 0
        ];
        
        $db = \Config\Database::connect();
        
        // Get tasks from owned projects and member projects
        $projectIds = array_column($data['projects'], 'id');
        
        if (empty($projectIds)) {
            $data['all_tasks'] = [];
        } else {
            $builder = $db->table('tasks');
            $builder->select('tasks.*, projects.nama_project');
            $builder->join('projects', 'projects.id = tasks.project_id');
            $builder->whereIn('projects.id', $projectIds);
            $builder->where('tasks.deleted_at IS NULL');
            $builder->where('projects.deleted_at IS NULL');
            
            $keyword = $this->request->getGet('search');
            if(!empty($keyword)) {
                $builder->like('tasks.judul_task', $keyword);
            }
            
            $builder->orderBy('tasks.deadline', 'ASC');
            $data['all_tasks'] = $builder->get()->getResultArray();
        }
        
        foreach($data['all_tasks'] as $t) {
            $data['stats']['total']++;
            if($t['status'] == 'done') {
                $data['stats']['done']++;
                if(substr($t['updated_at'], 0, 10) == date('Y-m-d')) {
                    $data['stats']['done_today']++;
                }
            }
            if($t['status'] == 'in_progress') $data['stats']['in_progress']++;
            if($t['deadline'] < date('Y-m-d') && $t['status'] != 'done') $data['stats']['overdue']++;
        }
        
        $data['search'] = $keyword ?? '';
        
        return view('projects/index', $data);
    }

    public function all()
    {
        $projectModel = new ProjectModel();
        $memberModel = new \App\Models\ProjectMemberModel();
        
        $ownedProjects = $projectModel->where('user_id', session()->get('id'))->findAll();
        $memberProjectsRaw = $memberModel->getProjectsByUser(session()->get('id'));
        $memberProjects = array_map(function($p) {
            return [
                'id' => $p['project_id'],
                'user_id' => null, // bukan owner
                'nama_project' => $p['nama_project'],
                'deskripsi' => $p['deskripsi'],
                'is_member' => true,
                'role' => $p['role'],
                'owner_name' => $p['owner_name'],
                'created_at' => $p['created_at']
            ];
        }, $memberProjectsRaw);
        
        $data['projects'] = array_merge($ownedProjects, $memberProjects);
        
        // Count tasks for each project
        $db = \Config\Database::connect();
        foreach($data['projects'] as &$p) {
            $p['task_count'] = $db->table('tasks')
                                  ->where('project_id', $p['id'])
                                  ->where('deleted_at IS NULL')
                                  ->countAllResults();
        }

        return view('projects/all', $data);
    }

    public function create()
    {
        return view('projects/create');
    }

    public function store()
    {
        $model = new ProjectModel();
        $data = [
            'user_id' => session()->get('id'),
            'nama_project' => $this->request->getPost('nama_project'),
            'deskripsi' => $this->request->getPost('deskripsi')
        ];
        $model->save($data);
        return redirect()->to('/projects')->with('success', 'Project created successfully');
    }
    
    public function update($id)
    {
        $model = new ProjectModel();
        $proj = $model->where('id', $id)->where('user_id', session()->get('id'))->first();
        if ($proj) {
            $data = [
                'nama_project' => $this->request->getPost('nama_project'),
                'deskripsi' => $this->request->getPost('deskripsi')
            ];
            $model->update($id, $data);
            return redirect()->to('/projects/all')->with('success', 'Project updated successfully');
        }
        return redirect()->to('/projects/all');
    }

    public function delete($id)
    {
        $model = new ProjectModel();
        $proj = $model->where('id', $id)->where('user_id', session()->get('id'))->first();
        if($proj) {
            $model->delete($id);
        }
        return redirect()->to('/projects');
    }

    public function storeTaskGlobal()
    {
        $project_id = $this->request->getPost('project_id');
        $projectModel = new ProjectModel();
        $proj = $projectModel->where('id', $project_id)->where('user_id', session()->get('id'))->first();
        if(!$proj) return redirect()->to('/projects');

        $taskModel = new \App\Models\TaskModel();
        $data = [
            'project_id' => $project_id,
            'judul_task' => $this->request->getPost('judul_task'),
            'deskripsi'  => $this->request->getPost('deskripsi'),
            'prioritas'  => $this->request->getPost('prioritas'),
            'deadline'   => $this->request->getPost('deadline'),
            'status'     => 'to_do'
        ];
        $taskModel->save($data);

        return redirect()->to('/projects')->with('success', 'Task added successfully.');
    }
}
