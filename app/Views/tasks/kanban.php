<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    .kanban-col { background-color: #e2e8f0; border-radius: 10px; padding: 15px; min-height: 70vh; }
    .task-card { border-radius: 8px; border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 15px; }
    .overdue { border: 1px solid #e53e3e; }
    .badge-priority-high { background-color: #fc8181; color: white; }
    .badge-priority-mid { background-color: #f6e05e; color: #744210; }
    .badge-priority-low { background-color: #a0aec0; color: white; }
</style>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><?= esc($project['nama_project']) ?></h2>
            <p class="text-muted"><?= esc($project['deskripsi']) ?></p>
        </div>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addTaskModal"><i class="fas fa-plus"></i> Add Task</button>
    </div>

    <!-- Search & Filter -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form action="" method="get" class="form-inline">
                <input type="text" name="search" class="form-control mr-2" placeholder="Search task..." value="<?= esc($search ?? '') ?>">
                <select name="priority" class="form-control mr-2">
                    <option value="">All Priorities</option>
                    <option value="high" <?= (isset($priority) && $priority == 'high') ? 'selected' : '' ?>>High</option>
                    <option value="mid" <?= (isset($priority) && $priority == 'mid') ? 'selected' : '' ?>>Medium</option>
                    <option value="low" <?= (isset($priority) && $priority == 'low') ? 'selected' : '' ?>>Low</option>
                </select>
                <button type="submit" class="btn btn-secondary">Filter</button>
                <a href="<?= current_url() ?>" class="btn btn-light ml-2">Reset</a>
            </form>
        </div>
    </div>

    <?php if(session()->getFlashdata('success')):?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif;?>

    <div class="row">
        <!-- TO DO Column -->
        <div class="col-md-4">
            <div class="kanban-col">
                <h5 class="mb-3">To Do <span class="badge badge-secondary" id="count-to_do"><?= count($tasks['to_do']) ?></span></h5>
                <div class="task-list" id="list-to_do" data-status="to_do" style="min-height: 50vh;">
                <?php foreach($tasks['to_do'] as $t): 
                    $isOverdue = ($t['deadline'] < date('Y-m-d'));
                ?>
                <div class="card task-card <?= $isOverdue ? 'overdue' : '' ?>" data-id="<?= $t['id'] ?>">
                    <div class="card-body">
                        <?php if($isOverdue): ?><span class="badge badge-danger mb-2">Overdue!</span><?php endif; ?>
                        <span class="badge badge-priority-<?= $t['prioritas'] ?> float-right"><?= ucfirst($t['prioritas']) ?></span>
                        <h6 class="card-title mt-2"><?= esc($t['judul_task']) ?></h6>
                        <p class="card-text text-muted small"><?= esc($t['deskripsi']) ?></p>
                        <p class="mb-2 small"><i class="far fa-calendar-alt"></i> <?= $t['deadline'] ?></p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="<?= base_url('tasks/'.$t['id']) ?>" class="btn btn-sm btn-outline-info" title="Detail"><i class="fas fa-info-circle"></i></a>
                            <div>
                                <button class="btn btn-sm btn-outline-secondary btn-edit-task" data-id="<?= $t['id'] ?>" data-judul="<?= esc($t['judul_task']) ?>" data-desc="<?= esc($t['deskripsi']) ?>" data-prio="<?= $t['prioritas'] ?>" data-deadline="<?= $t['deadline'] ?>" data-toggle="modal" data-target="#editTaskModal"><i class="fas fa-edit"></i></button>
                                <a href="<?= base_url('tasks/delete/'.$t['id']) ?>" class="text-danger ml-2" onclick="return confirm('Delete this task?')"><i class="fas fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- IN PROGRESS Column -->
        <div class="col-md-4">
            <div class="kanban-col">
                <h5 class="mb-3">In Progress <span class="badge badge-secondary" id="count-in_progress"><?= count($tasks['in_progress']) ?></span></h5>
                <div class="task-list" id="list-in_progress" data-status="in_progress" style="min-height: 50vh;">
                <?php foreach($tasks['in_progress'] as $t): 
                    $isOverdue = ($t['deadline'] < date('Y-m-d'));
                ?>
                <div class="card task-card <?= $isOverdue ? 'overdue' : '' ?>" data-id="<?= $t['id'] ?>">
                    <div class="card-body">
                        <?php if($isOverdue): ?><span class="badge badge-danger mb-2">Overdue!</span><?php endif; ?>
                        <span class="badge badge-priority-<?= $t['prioritas'] ?> float-right"><?= ucfirst($t['prioritas']) ?></span>
                        <h6 class="card-title mt-2"><?= esc($t['judul_task']) ?></h6>
                        <p class="card-text text-muted small"><?= esc($t['deskripsi']) ?></p>
                        <p class="mb-2 small"><i class="far fa-calendar-alt"></i> <?= $t['deadline'] ?></p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="<?= base_url('tasks/'.$t['id']) ?>" class="btn btn-sm btn-outline-info" title="Detail"><i class="fas fa-info-circle"></i></a>
                            <div>
                                <button class="btn btn-sm btn-outline-secondary btn-edit-task" data-id="<?= $t['id'] ?>" data-judul="<?= esc($t['judul_task']) ?>" data-desc="<?= esc($t['deskripsi']) ?>" data-prio="<?= $t['prioritas'] ?>" data-deadline="<?= $t['deadline'] ?>" data-toggle="modal" data-target="#editTaskModal"><i class="fas fa-edit"></i></button>
                                <a href="<?= base_url('tasks/delete/'.$t['id']) ?>" class="text-danger ml-2" onclick="return confirm('Delete this task?')"><i class="fas fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- DONE Column -->
        <div class="col-md-4">
            <div class="kanban-col">
                <h5 class="mb-3">Done <span class="badge badge-secondary" id="count-done"><?= count($tasks['done']) ?></span></h5>
                <div class="task-list" id="list-done" data-status="done" style="min-height: 50vh;">
                <?php foreach($tasks['done'] as $t): ?>
                <div class="card task-card" style="opacity: 0.7;" data-id="<?= $t['id'] ?>">
                    <div class="card-body">
                        <span class="badge badge-success float-right">Done</span>
                        <h6 class="card-title mt-2"><s><?= esc($t['judul_task']) ?></s></h6>
                        <p class="card-text text-muted small"><?= esc($t['deskripsi']) ?></p>
                        <p class="mb-2 small"><i class="far fa-calendar-alt"></i> <?= $t['deadline'] ?></p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="<?= base_url('tasks/'.$t['id']) ?>" class="btn btn-sm btn-outline-info" title="Detail"><i class="fas fa-info-circle"></i></a>
                            <div>
                                <button class="btn btn-sm btn-outline-secondary btn-edit-task" data-id="<?= $t['id'] ?>" data-judul="<?= esc($t['judul_task']) ?>" data-desc="<?= esc($t['deskripsi']) ?>" data-prio="<?= $t['prioritas'] ?>" data-deadline="<?= $t['deadline'] ?>" data-toggle="modal" data-target="#editTaskModal"><i class="fas fa-edit"></i></button>
                                <a href="<?= base_url('tasks/delete/'.$t['id']) ?>" class="text-danger ml-2" onclick="return confirm('Delete this task?')"><i class="fas fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="<?= base_url('projects/'.$project['id'].'/tasks/store') ?>" method="post" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Task</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label>Task Title</label>
            <input type="text" name="judul_task" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label>Priority</label>
            <select name="prioritas" class="form-control">
                <option value="low">Low</option>
                <option value="mid" selected>Medium</option>
                <option value="high">High</option>
            </select>
        </div>
        <div class="form-group">
            <label>Deadline</label>
            <input type="date" name="deadline" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save Task</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="" method="post" id="editTaskForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Task</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label>Task Title</label>
            <input type="text" name="judul_task" id="edit_judul_task" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="deskripsi" id="edit_deskripsi_task" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label>Priority</label>
            <select name="prioritas" id="edit_prioritas" class="form-control">
                <option value="low">Low</option>
                <option value="mid">Medium</option>
                <option value="high">High</option>
            </select>
        </div>
        <div class="form-group">
            <label>Deadline</label>
            <input type="date" name="deadline" id="edit_deadline" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // Edit Modal Logic
    $('.btn-edit-task').click(function() {
        var id = $(this).data('id');
        $('#edit_judul_task').val($(this).data('judul'));
        $('#edit_deskripsi_task').val($(this).data('desc'));
        $('#edit_prioritas').val($(this).data('prio'));
        $('#edit_deadline').val($(this).data('deadline'));
        $('#editTaskForm').attr('action', '<?= base_url('tasks/update') ?>/' + id);
    });

    // Drag and Drop Logic
    var drake_options = {
        group: 'shared',
        animation: 150,
        ghostClass: 'bg-light',
        onEnd: function (evt) {
            var itemEl = evt.item;
            var taskId = $(itemEl).data('id');
            var toList = evt.to;
            var newStatus = $(toList).data('status');
            var oldStatus = $(evt.from).data('status');

            if(newStatus !== oldStatus) {
                // Update badges visually
                $('#count-' + oldStatus).text($('#list-' + oldStatus).children('.task-card').length);
                $('#count-' + newStatus).text($('#list-' + newStatus).children('.task-card').length);

                // Send AJAX request to update status
                $.ajax({
                    url: '<?= base_url('tasks/update_status_ajax') ?>',
                    type: 'POST',
                    data: {
                        task_id: taskId,
                        status: newStatus
                    },
                    success: function(res) {
                        if(newStatus == 'done') {
                            $(itemEl).css('opacity', '0.7');
                        } else {
                            $(itemEl).css('opacity', '1');
                        }
                    }
                });
            }
        }
    };

    Sortable.create(document.getElementById('list-to_do'), drake_options);
    Sortable.create(document.getElementById('list-in_progress'), drake_options);
    Sortable.create(document.getElementById('list-done'), drake_options);
</script>
<?= $this->endSection() ?>
