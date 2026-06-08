<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\TaskModel;
use App\Models\CommentModel;
use App\Models\AttachmentModel;
use App\Models\TimeLogModel;

class TaskController extends BaseController
{
    private function checkProjectAccess($project_id)
    {
        $projectModel = new ProjectModel();
        $proj = $projectModel->find($project_id);
        if (!$proj) return false;

        if ($proj['user_id'] == session()->get('id')) {
            return $proj;
        }

        $memberModel = new \App\Models\ProjectMemberModel();
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
        $data['project'] = $this->checkProjectAccess($project_id);
        if(!$data['project']) {
            return redirect()->to('/projects')->with('error', 'Project not found or unauthorized');
        }

        $taskModel = new TaskModel();
        $keyword = $this->request->getGet('search');
        $priorityFilter = $this->request->getGet('priority');

        $query = $taskModel->where('project_id', $project_id);
        
        if (!empty($keyword)) {
            $query->like('judul_task', $keyword);
        }
        if (!empty($priorityFilter)) {
            $query->where('prioritas', $priorityFilter);
        }
        
        $tasks = $query->orderBy('deadline', 'ASC')->findAll();

        $data['tasks'] = [
            'to_do' => [],
            'in_progress' => [],
            'done' => []
        ];

        foreach($tasks as $t) {
            $data['tasks'][$t['status']][] = $t;
        }
        
        $data['search'] = $keyword;
        $data['priority'] = $priorityFilter;

        return view('tasks/kanban', $data);
    }

    public function store($project_id)
    {
        $proj = $this->checkProjectAccess($project_id);
        if(!$proj) return redirect()->to('/projects');

        $taskModel = new TaskModel();
        $data = [
            'project_id' => $project_id,
            'judul_task' => $this->request->getPost('judul_task'),
            'deskripsi'  => $this->request->getPost('deskripsi'),
            'prioritas'  => $this->request->getPost('prioritas'),
            'deadline'   => $this->request->getPost('deadline'),
            'status'     => 'to_do'
        ];
        $taskModel->save($data);

        return redirect()->to('/projects/'.$project_id.'/tasks')->with('success', 'Task added successfully.');
    }

    public function updateStatus($task_id, $status)
    {
        $taskModel = new TaskModel();
        $task = $taskModel->find($task_id);
        if($task) {
            $proj = $this->checkProjectAccess($task['project_id']);
            if($proj) {
                $taskModel->update($task_id, ['status' => $status]);
                return redirect()->to('/projects/'.$task['project_id'].'/tasks');
            }
        }
        return redirect()->to('/projects');
    }

    public function updateStatusAjax()
    {
        $task_id = $this->request->getPost('task_id');
        $status = $this->request->getPost('status');
        
        $taskModel = new TaskModel();
        $task = $taskModel->find($task_id);
        if($task) {
            $proj = $this->checkProjectAccess($task['project_id']);
            if($proj) {
                $taskModel->update($task_id, ['status' => $status]);
                return $this->response->setJSON(['success' => true]);
            }
        }
        return $this->response->setJSON(['success' => false], 403);
    }

    public function update($task_id)
    {
        $taskModel = new TaskModel();
        $task = $taskModel->find($task_id);
        if($task) {
            $proj = $this->checkProjectAccess($task['project_id']);
            if($proj) {
                $data = [
                    'judul_task' => $this->request->getPost('judul_task'),
                    'deskripsi'  => $this->request->getPost('deskripsi'),
                    'prioritas'  => $this->request->getPost('prioritas'),
                    'deadline'   => $this->request->getPost('deadline')
                ];
                $taskModel->update($task_id, $data);
                return redirect()->to('/projects/'.$task['project_id'].'/tasks')->with('success', 'Task updated successfully.');
            }
        }
        return redirect()->to('/projects');
    }

    public function delete($task_id)
    {
        $taskModel = new TaskModel();
        $task = $taskModel->find($task_id);
        if($task) {
            $proj = $this->checkProjectAccess($task['project_id']);
            if($proj) {
                $taskModel->delete($task_id);
                return redirect()->to('/projects/'.$task['project_id'].'/tasks')->with('success', 'Task deleted.');
            }
        }
        return redirect()->to('/projects');
    }

    // ========================================
    // TASK DETAIL (Comments, Attachments, Time Logs)
    // ========================================

    public function detail($task_id)
    {
        $taskModel = new TaskModel();
        $task = $taskModel->find($task_id);
        if (!$task) {
            return redirect()->to('/projects')->with('error', 'Task not found');
        }

        $project = $this->checkProjectAccess($task['project_id']);
        if (!$project) {
            return redirect()->to('/projects')->with('error', 'Unauthorized');
        }

        $commentModel = new CommentModel();
        $attachmentModel = new AttachmentModel();
        $timeLogModel = new TimeLogModel();

        $data = [
            'task'        => $task,
            'project'     => $project,
            'comments'    => $commentModel->getCommentsByTask($task_id),
            'attachments' => $attachmentModel->getAttachmentsByTask($task_id),
            'timelogs'    => $timeLogModel->getLogsByTask($task_id),
            'total_durasi'=> $timeLogModel->where('task_id', $task_id)->selectSum('durasi_menit')->first()['durasi_menit'] ?? 0,
        ];

        return view('tasks/detail', $data);
    }

    // --- Comments ---
    public function storeComment($task_id)
    {
        $taskModel = new TaskModel();
        $task = $taskModel->find($task_id);
        if (!$task) return redirect()->to('/projects');

        $proj = $this->checkProjectAccess($task['project_id']);
        if (!$proj) return redirect()->to('/projects');

        $commentModel = new CommentModel();
        $commentModel->save([
            'task_id'  => $task_id,
            'user_id'  => session()->get('id'),
            'komentar' => $this->request->getPost('komentar'),
        ]);

        return redirect()->to('/tasks/' . $task_id . '#comments')->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function deleteComment($id)
    {
        $commentModel = new CommentModel();
        $comment = $commentModel->find($id);
        if (!$comment) return redirect()->to('/projects');

        // Hanya user yang membuat komentar yang bisa menghapus
        if ($comment['user_id'] != session()->get('id')) {
            return redirect()->to('/tasks/' . $comment['task_id'])->with('error', 'Unauthorized');
        }

        $commentModel->delete($id);
        return redirect()->to('/tasks/' . $comment['task_id'] . '#comments')->with('success', 'Komentar berhasil dihapus.');
    }

    // --- Attachments ---
    public function storeAttachment($task_id)
    {
        $taskModel = new TaskModel();
        $task = $taskModel->find($task_id);
        if (!$task) return redirect()->to('/projects');

        $proj = $this->checkProjectAccess($task['project_id']);
        if (!$proj) return redirect()->to('/projects');

        $files = $this->request->getFileMultiple('file_attachments');
        $attachmentModel = new AttachmentModel();
        $uploaded = 0;

        if ($files) {
            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/attachments', $newName);

                    $attachmentModel->save([
                        'task_id'     => $task_id,
                        'user_id'     => session()->get('id'),
                        'nama_file'   => $file->getClientName(),
                        'path_file'   => 'uploads/attachments/' . $newName,
                        'tipe_file'   => $file->getClientMimeType(),
                        'ukuran_file' => $file->getSize(),
                    ]);
                    $uploaded++;
                }
            }
        }

        if ($uploaded > 0) {
            return redirect()->to('/tasks/' . $task_id . '#attachments')->with('success', $uploaded . ' lampiran berhasil ditambahkan.');
        }

        return redirect()->to('/tasks/' . $task_id . '#attachments')->with('error', 'Gagal mengupload lampiran.');
    }

    public function inlineAttachment($id)
    {
        $attachmentModel = new AttachmentModel();
        $attachment = $attachmentModel->find($id);
        if (!$attachment) return redirect()->to('/projects');

        $filePath = FCPATH . $attachment['path_file'];
        if (file_exists($filePath)) {
            return $this->response->setHeader('Content-Type', $attachment['tipe_file'])
                                  ->setHeader('Content-Disposition', 'inline; filename="' . $attachment['nama_file'] . '"')
                                  ->setBody(file_get_contents($filePath));
        }

        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

    public function renameAttachment($id)
    {
        $attachmentModel = new AttachmentModel();
        $attachment = $attachmentModel->find($id);
        if (!$attachment) return redirect()->to('/projects');

        $taskModel = new TaskModel();
        $task = $taskModel->find($attachment['task_id']);
        if (!$this->checkProjectAccess($task['project_id'])) {
            return redirect()->to('/projects');
        }

        $newName = $this->request->getPost('new_name');
        if (!empty($newName)) {
            // Keep the original extension if the user didn't provide one
            $ext = pathinfo($attachment['nama_file'], PATHINFO_EXTENSION);
            if (!preg_match("/\.$ext$/i", $newName)) {
                $newName .= '.' . $ext;
            }

            $attachmentModel->update($id, ['nama_file' => $newName]);
            return redirect()->to('/tasks/' . $task['id'] . '#attachments')->with('success', 'Nama lampiran berhasil diubah.');
        }

        return redirect()->to('/tasks/' . $task['id'] . '#attachments')->with('error', 'Nama lampiran tidak boleh kosong.');
    }

    public function deleteAttachment($id)
    {
        $attachmentModel = new AttachmentModel();
        $attachment = $attachmentModel->find($id);
        if (!$attachment) return redirect()->to('/projects');

        // Hapus file fisik
        $filePath = FCPATH . $attachment['path_file'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $task_id = $attachment['task_id'];
        $attachmentModel->delete($id);
        return redirect()->to('/tasks/' . $task_id . '#attachments')->with('success', 'Lampiran berhasil dihapus.');
    }

    public function downloadAttachment($id)
    {
        $attachmentModel = new AttachmentModel();
        $attachment = $attachmentModel->find($id);
        if (!$attachment) return redirect()->to('/projects');

        $filePath = FCPATH . $attachment['path_file'];
        if (file_exists($filePath)) {
            return $this->response->download($filePath, null)->setFileName($attachment['nama_file']);
        }

        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

    // --- Time Logs ---
    public function storeTimeLog($task_id)
    {
        $taskModel = new TaskModel();
        $task = $taskModel->find($task_id);
        if (!$task) return redirect()->to('/projects');

        $proj = $this->checkProjectAccess($task['project_id']);
        if (!$proj) return redirect()->to('/projects');

        $waktuMulai = $this->request->getPost('waktu_mulai');
        $waktuSelesai = $this->request->getPost('waktu_selesai');
        
        // Hitung durasi dalam menit
        $durasi = 0;
        if ($waktuMulai && $waktuSelesai) {
            $start = new \DateTime($waktuMulai);
            $end = new \DateTime($waktuSelesai);
            
            if ($end < $start) {
                return redirect()->to('/tasks/' . $task_id . '#timelogs')->with('error', 'Waktu selesai tidak boleh lebih awal dari waktu mulai.');
            }
            
            $diff = $start->diff($end);
            $durasi = ($diff->h * 60) + $diff->i + ($diff->days * 24 * 60);
        }

        $timeLogModel = new TimeLogModel();
        $timeLogModel->save([
            'task_id'       => $task_id,
            'user_id'       => session()->get('id'),
            'waktu_mulai'   => $waktuMulai,
            'waktu_selesai' => $waktuSelesai,
            'durasi_menit'  => $durasi,
            'catatan'       => $this->request->getPost('catatan'),
        ]);

        return redirect()->to('/tasks/' . $task_id . '#timelogs')->with('success', 'Waktu kerja berhasil dicatat.');
    }

    public function deleteTimeLog($id)
    {
        $timeLogModel = new TimeLogModel();
        $log = $timeLogModel->find($id);
        if (!$log) return redirect()->to('/projects');

        $task_id = $log['task_id'];
        $timeLogModel->delete($id);
        return redirect()->to('/tasks/' . $task_id . '#timelogs')->with('success', 'Log waktu berhasil dihapus.');
    }
}
