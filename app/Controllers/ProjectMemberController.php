<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\ProjectMemberModel;
use App\Models\UserModel;

class ProjectMemberController extends BaseController
{
    private function checkProjectAccess($project_id)
    {
        $projectModel = new ProjectModel();
        $proj = $projectModel->find($project_id);
        if (!$proj) return false;

        if ($proj['user_id'] == session()->get('id')) {
            return $proj;
        }

        $memberModel = new ProjectMemberModel();
        $member = $memberModel->where('project_id', $project_id)
                              ->where('user_id', session()->get('id'))
                              ->where('status', 'accepted')
                              ->first();
        
        if ($member) {
            return $proj;
        }

        return false;
    }

    public function index($project_id)
    {
        $project = $this->checkProjectAccess($project_id);
        if (!$project) {
            return redirect()->to('/projects')->with('error', 'Project not found or unauthorized');
        }

        $memberModel = new ProjectMemberModel();
        $members = $memberModel->getMembersByProject($project_id);

        $userModel = new UserModel();
        $owner = $userModel->find($project['user_id']);
        
        $ownerMember = [
            'id' => 0, 
            'project_id' => $project_id,
            'user_id' => $owner['id'],
            'role' => 'owner',
            'status' => 'accepted',
            'created_at' => $project['created_at'] ?? date('Y-m-d H:i:s'),
            'nama' => $owner['nama'] . ' (Project Owner)',
            'email' => $owner['email']
        ];
        
        array_unshift($members, $ownerMember);

        return view('projects/members', [
            'project' => $project,
            'members' => $members,
        ]);
    }

    public function add($project_id)
    {
        $projectModel = new ProjectModel();
        $project = $projectModel->where('id', $project_id)->where('user_id', session()->get('id'))->first();
        if (!$project) {
            return redirect()->to('/projects')->with('error', 'Project not found');
        }

        $email = $this->request->getPost('email');
        $role  = $this->request->getPost('role') ?? 'member';

        // Cari user berdasarkan email
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();
        if (!$user) {
            return redirect()->to('/projects/' . $project_id . '/members')->with('error', 'User dengan email "' . $email . '" tidak ditemukan.');
        }

        // Cek apakah sudah menjadi member
        $memberModel = new ProjectMemberModel();
        $existing = $memberModel->where('project_id', $project_id)->where('user_id', $user['id'])->first();
        if ($existing) {
            return redirect()->to('/projects/' . $project_id . '/members')->with('error', 'User sudah menjadi anggota proyek ini.');
        }

        $memberModel->save([
            'project_id' => $project_id,
            'user_id'    => $user['id'],
            'role'       => $role,
            'status'     => 'pending',
        ]);

        return redirect()->to('/projects/' . $project_id . '/members')->with('success', 'Undangan berhasil dikirim ke anggota.');
    }

    public function remove($id)
    {
        $memberModel = new ProjectMemberModel();
        $member = $memberModel->find($id);
        if (!$member) {
            return redirect()->to('/projects')->with('error', 'Member not found');
        }

        // Pastikan yang menghapus adalah pemilik project
        $projectModel = new ProjectModel();
        $project = $projectModel->where('id', $member['project_id'])->where('user_id', session()->get('id'))->first();
        if (!$project) {
            return redirect()->to('/projects')->with('error', 'Unauthorized');
        }

        $memberModel->delete($id);
        return redirect()->to('/projects/' . $member['project_id'] . '/members')->with('success', 'Anggota berhasil dihapus.');
    }

    public function updateRole($id)
    {
        $memberModel = new ProjectMemberModel();
        $member = $memberModel->find($id);
        if (!$member) {
            return redirect()->to('/projects')->with('error', 'Member not found');
        }

        $projectModel = new ProjectModel();
        $project = $projectModel->where('id', $member['project_id'])->where('user_id', session()->get('id'))->first();
        if (!$project) {
            return redirect()->to('/projects')->with('error', 'Unauthorized');
        }

        $role = $this->request->getPost('role');
        $memberModel->update($id, ['role' => $role]);

        return redirect()->to('/projects/' . $member['project_id'] . '/members')->with('success', 'Role berhasil diubah.');
    }

    public function acceptInvite($id)
    {
        $memberModel = new ProjectMemberModel();
        $member = $memberModel->find($id);
        if (!$member || $member['user_id'] != session()->get('id')) {
            return redirect()->to('/projects')->with('error', 'Undangan tidak ditemukan atau Anda tidak berhak.');
        }

        $memberModel->update($id, ['status' => 'accepted']);
        return redirect()->to('/projects')->with('success', 'Anda telah menerima undangan project.');
    }

    public function declineInvite($id)
    {
        $memberModel = new ProjectMemberModel();
        $member = $memberModel->find($id);
        if (!$member || $member['user_id'] != session()->get('id')) {
            return redirect()->to('/projects')->with('error', 'Undangan tidak ditemukan atau Anda tidak berhak.');
        }

        // Jika ditolak, kita hapus saja datanya agar tidak nyangkut
        $memberModel->delete($id);
        return redirect()->to('/projects')->with('success', 'Anda telah menolak undangan project.');
    }
}
