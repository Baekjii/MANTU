<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="top-header">
    <div class="header-title-wrapper">
        <h1 class="header-title">All Projects</h1>
    </div>
    <div class="header-actions">
        <a href="<?= base_url('projects/create') ?>" class="btn-primary-custom" style="text-decoration:none;">
            <i class="fas fa-plus"></i> New Project
        </a>
    </div>
</div>

<div class="table-card">
    <div class="table-header-custom">
        <h3 class="table-title">Your Projects</h3>
    </div>
    
    <table class="table custom-table table-borderless">
        <thead>
            <tr>
                <th>Project Name</th>
                <th>Description</th>
                <th>Tasks</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($projects)): ?>
            <tr>
                <td colspan="5" class="text-center text-muted py-4">No projects found. Click "New Project" to create one.</td>
            </tr>
            <?php else: ?>
                <?php foreach($projects as $p): ?>
                <tr>
                    <td>
                        <div class="task-title"><?= esc($p['nama_project']) ?></div>
                    </td>
                    <td>
                        <div class="text-muted" style="font-size: 0.85rem; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?= esc($p['deskripsi']) ?>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-info"><?= $p['task_count'] ?> Tasks</span>
                    </td>
                    <td>
                        <div class="text-muted small"><?= date('M d, Y', strtotime($p['created_at'])) ?></div>
                    </td>
                    <td>
                        <a href="<?= base_url('projects/'.$p['id'].'/tasks') ?>" class="btn btn-sm btn-outline-primary mr-2">View Board</a>
                        <button type="button" class="btn btn-sm btn-outline-secondary mr-2 btn-edit-project" data-toggle="modal" data-target="#editProjectModal" data-id="<?= $p['id'] ?>" data-name="<?= esc($p['nama_project']) ?>" data-desc="<?= esc($p['deskripsi']) ?>"><i class="fas fa-edit"></i></button>
                        <a href="<?= base_url('projects/delete/'.$p['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this project? All related tasks will be deleted as well.')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Edit Project Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="" method="post" id="editProjectForm" class="modal-content" style="border-radius: 12px; border:none;">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title font-weight-bold">Edit Project</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body pt-0">
        <div class="form-group">
            <label class="font-weight-bold small text-muted">Project Name</label>
            <input type="text" name="nama_project" id="edit_nama_project" class="form-control" required style="border-radius:8px;">
        </div>
        <div class="form-group">
            <label class="font-weight-bold small text-muted">Description</label>
            <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="3" style="border-radius:8px;"></textarea>
        </div>
      </div>
      <div class="modal-footer border-top-0">
        <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius:8px;">Cancel</button>
        <button type="submit" class="btn-primary-custom">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $('.btn-edit-project').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var desc = $(this).data('desc');
        
        $('#edit_nama_project').val(name);
        $('#edit_deskripsi').val(desc);
        $('#editProjectForm').attr('action', '<?= base_url('projects/update') ?>/' + id);
    });
</script>
<?= $this->endSection() ?>
