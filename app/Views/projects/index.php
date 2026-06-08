<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Header -->
<div class="top-header">
    <div class="header-title-wrapper">
        <h1 class="header-title">Overview</h1>
        <span class="header-badge">Academic Project 2024</span>
    </div>
    <div class="header-actions">
        <form action="" method="get" class="m-0 d-flex">
            <input type="text" name="search" class="search-input" placeholder="Search tasks..." value="<?= esc($search ?? '') ?>">
            <button type="button" class="btn-primary-custom" data-toggle="modal" data-target="#addTaskModalGlobal">
                <i class="fas fa-plus"></i> New Task
            </button>
        </form>
    </div>
</div>

<?php if(session()->getFlashdata('success')):?>
    <div class="alert alert-success border-0 shadow-sm" style="border-radius: 8px;"><?= session()->getFlashdata('success') ?></div>
<?php endif;?>
<?php if(session()->getFlashdata('error')):?>
    <div class="alert alert-danger border-0 shadow-sm" style="border-radius: 8px;"><?= session()->getFlashdata('error') ?></div>
<?php endif;?>

<!-- Pending Invitations -->
<?php if(!empty($pending_invitations)): ?>
<div class="alert alert-warning border-0 shadow-sm mb-4" style="border-radius: 12px; background: #fffaf0; border-left: 4px solid #ed8936 !important;">
    <h5 class="font-weight-bold" style="color: #c05621;"><i class="fas fa-envelope-open-text mr-2"></i> Undangan Project Baru</h5>
    <p class="mb-3 text-dark">Anda memiliki <strong><?= count($pending_invitations) ?></strong> undangan untuk bergabung ke dalam project.</p>
    
    <div class="d-flex flex-column" style="gap: 10px;">
        <?php foreach($pending_invitations as $inv): ?>
        <div class="d-flex align-items-center justify-content-between p-3" style="background: #fff; border-radius: 8px; border: 1px solid #feebc8;">
            <div>
                <strong><?= esc($inv['nama_project']) ?></strong>
                <div class="small text-muted">Diundang oleh: <?= esc($inv['owner_name']) ?> (sebagai <?= ucfirst($inv['role']) ?>)</div>
            </div>
            <div class="d-flex" style="gap: 10px;">
                <form action="<?= base_url('invitations/accept/'.$inv['id']) ?>" method="post">
                    <button type="submit" class="btn btn-sm btn-success" style="border-radius: 6px;"><i class="fas fa-check"></i> Terima</button>
                </form>
                <form action="<?= base_url('invitations/decline/'.$inv['id']) ?>" method="post">
                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 6px;"><i class="fas fa-times"></i> Tolak</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Stat Cards -->
<div class="row">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-title">Total Tasks</div>
            <div class="stat-value"><?= $stats['total'] ?></div>
            <div class="stat-subtitle text-muted-custom">Across all projects</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-title">In Progress</div>
            <div class="stat-value"><?= $stats['in_progress'] ?></div>
            <div class="progress-micro">
                <div class="bar" style="width: <?= $stats['total'] > 0 ? ($stats['in_progress'] / $stats['total']) * 100 : 0 ?>%"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-title">Completed</div>
            <div class="stat-value"><?= $stats['done'] ?></div>
            <div class="stat-subtitle text-muted-custom"><?= $stats['done_today'] ?> tasks today</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-overdue">
            <div class="stat-title">Overdue</div>
            <div class="stat-value"><?= $stats['overdue'] ?></div>
            <div class="stat-subtitle">Action Required</div>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="table-card">
    <div class="table-header-custom">
        <h3 class="table-title">Recent Task Activities</h3>
        <div class="table-controls">
            <button>Filter</button>
            <button>Sort</button>
        </div>
    </div>
    
    <table class="table custom-table table-borderless">
        <thead>
            <tr>
                <th>Task Title</th>
                <th>Project</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Deadline</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($all_tasks)): ?>
            <tr>
                <td colspan="6" class="text-center text-muted py-4">No tasks found. Click "+ New Task" to create one.</td>
            </tr>
            <?php else: ?>
                <?php foreach($all_tasks as $t): 
                    $isOverdue = ($t['deadline'] < date('Y-m-d') && $t['status'] != 'done');
                    
                    $statusClass = '';
                    $statusText = '';
                    if($isOverdue) {
                        $statusClass = 'status-overdue';
                        $statusText = 'OVERDUE';
                    } else if($t['status'] == 'to_do') {
                        $statusClass = 'status-todo';
                        $statusText = 'PENDING';
                    } else if($t['status'] == 'in_progress') {
                        $statusClass = 'status-inprogress';
                        $statusText = 'IN PROGRESS';
                    } else if($t['status'] == 'done') {
                        $statusClass = 'status-done';
                        $statusText = 'DONE';
                    }

                    $priorityClass = 'priority-'.$t['prioritas'];
                ?>
                <tr class="<?= $isOverdue ? 'row-overdue' : '' ?>">
                    <td>
                        <div class="task-title"><?= esc($t['judul_task']) ?></div>
                    </td>
                    <td>
                        <div class="task-project"><?= esc(strlen($t['nama_project']) > 15 ? substr($t['nama_project'],0,15).'...' : $t['nama_project']) ?></div>
                    </td>
                    <td>
                        <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                    </td>
                    <td class="<?= $priorityClass ?>"><?= ucfirst($t['prioritas']) ?></td>
                    <td>
                        <div class="task-deadline"><?= date('M d, Y', strtotime($t['deadline'])) ?></div>
                    </td>
                    <td>
                        <a href="<?= base_url('projects/'.$t['project_id'].'/tasks') ?>" class="action-link">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Create Global -->
<div class="modal fade" id="addTaskModalGlobal" tabindex="-1">
  <div class="modal-dialog">
    <form action="<?= base_url('projects/store_task_global') ?>" method="post" class="modal-content" style="border-radius: 12px; border:none;">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title font-weight-bold">New Task</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body pt-0">
        <?php if(empty($projects)): ?>
            <div class="alert alert-warning">You need to create a Project first before adding a task.</div>
            <a href="<?= base_url('projects/create') ?>" class="btn btn-primary btn-block">Create Project</a>
        <?php else: ?>
            <div class="form-group">
                <label class="font-weight-bold small text-muted">Select Project</label>
                <select name="project_id" class="form-control" required style="border-radius:8px;">
                    <?php foreach($projects as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= esc($p['nama_project']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="font-weight-bold small text-muted">Task Title</label>
                <input type="text" name="judul_task" class="form-control" required style="border-radius:8px;">
            </div>
            <div class="form-group">
                <label class="font-weight-bold small text-muted">Description</label>
                <textarea name="deskripsi" class="form-control" rows="3" style="border-radius:8px;"></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold small text-muted">Priority</label>
                    <select name="prioritas" class="form-control" style="border-radius:8px;">
                        <option value="low">Low</option>
                        <option value="mid" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold small text-muted">Deadline</label>
                    <input type="date" name="deadline" class="form-control" required style="border-radius:8px;">
                </div>
            </div>
        <?php endif; ?>
      </div>
      <div class="modal-footer border-top-0">
        <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius:8px;">Cancel</button>
        <?php if(!empty($projects)): ?>
        <button type="submit" class="btn-primary-custom">Save Task</button>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
