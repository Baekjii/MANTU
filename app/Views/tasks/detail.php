<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    .detail-header { margin-bottom: 30px; }
    .detail-header h2 { font-weight: 700; margin: 0; }
    .detail-header .breadcrumb-custom { font-size: 0.85rem; color: #718096; margin-bottom: 8px; }
    .detail-header .breadcrumb-custom a { color: #3182ce; text-decoration: none; }
    .detail-header .breadcrumb-custom a:hover { text-decoration: underline; }

    .task-meta { display: flex; gap: 20px; flex-wrap: wrap; margin-top: 15px; }
    .task-meta-item { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: #4a5568; }
    .task-meta-item i { color: #718096; width: 16px; text-align: center; }

    /* Tabs */
    .detail-tabs { border-bottom: 2px solid #edf2f7; margin-bottom: 25px; display: flex; gap: 0; }
    .detail-tab { padding: 12px 24px; font-weight: 600; font-size: 0.9rem; color: #718096; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.2s; background: none; border-top: none; border-left: none; border-right: none; }
    .detail-tab:hover { color: #2d3748; }
    .detail-tab.active { color: #1a73e8; border-bottom-color: #1a73e8; }
    .detail-tab .tab-count { background: #edf2f7; color: #4a5568; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem; margin-left: 6px; }
    .detail-tab.active .tab-count { background: #ebf5ff; color: #1a73e8; }

    .tab-content-panel { display: none; }
    .tab-content-panel.active { display: block; }

    /* Comments */
    .comment-item { display: flex; gap: 12px; padding: 15px 0; border-bottom: 1px solid #f0f0f0; }
    .comment-item:last-child { border-bottom: none; }
    .comment-avatar { width: 36px; height: 36px; border-radius: 50%; background: #1a73e8; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; flex-shrink: 0; }
    .comment-body { flex: 1; }
    .comment-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
    .comment-author { font-weight: 600; font-size: 0.9rem; color: #1a202c; }
    .comment-time { font-size: 0.75rem; color: #a0aec0; }
    .comment-text { font-size: 0.9rem; color: #4a5568; line-height: 1.5; }

    /* Attachments */
    .attachment-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #f8fafc; border-radius: 8px; margin-bottom: 10px; border: 1px solid #edf2f7; }
    .attachment-info { display: flex; align-items: center; gap: 12px; }
    .attachment-icon { width: 40px; height: 40px; background: #ebf5ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #1a73e8; font-size: 1.1rem; }
    .attachment-name { font-weight: 600; font-size: 0.85rem; color: #1a202c; }
    .attachment-meta { font-size: 0.75rem; color: #a0aec0; }
    .attachment-actions { display: flex; gap: 8px; }

    /* Time Logs */
    .timelog-summary { display: flex; gap: 20px; margin-bottom: 20px; }
    .timelog-stat { background: #f8fafc; border: 1px solid #edf2f7; border-radius: 10px; padding: 15px 20px; flex: 1; text-align: center; }
    .timelog-stat-value { font-size: 1.5rem; font-weight: 700; color: #1a73e8; }
    .timelog-stat-label { font-size: 0.75rem; color: #718096; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }

    .section-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid #edf2f7; padding: 25px; }
</style>

<div class="detail-header">
    <div class="breadcrumb-custom">
        <a href="<?= base_url('projects') ?>">Dashboard</a> /
        <a href="<?= base_url('projects/'.$project['id'].'/tasks') ?>"><?= esc($project['nama_project']) ?></a> /
        Detail Task
    </div>
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h2><?= esc($task['judul_task']) ?></h2>
            <div class="task-meta">
                <div class="task-meta-item">
                    <i class="fas fa-flag"></i>
                    <span class="badge badge-priority-<?= $task['prioritas'] ?? 'mid' ?>" style="padding: 4px 10px; border-radius: 4px; font-size: 0.75rem;
                        <?php
                            $bgColors = ['high' => 'background:#fc8181;color:#fff;', 'mid' => 'background:#f6e05e;color:#744210;', 'low' => 'background:#a0aec0;color:#fff;'];
                            echo $bgColors[$task['prioritas']] ?? $bgColors['mid'];
                        ?>
                    "><?= ucfirst($task['prioritas']) ?></span>
                </div>
                <div class="task-meta-item">
                    <i class="fas fa-circle" style="font-size: 8px; color: <?= $task['status'] == 'done' ? '#38a169' : ($task['status'] == 'in_progress' ? '#3182ce' : '#a0aec0') ?>;"></i>
                    <?= ucfirst(str_replace('_', ' ', $task['status'])) ?>
                </div>
                <div class="task-meta-item">
                    <i class="far fa-calendar-alt"></i>
                    <?= $task['deadline'] ? date('d M Y', strtotime($task['deadline'])) : '-' ?>
                </div>
            </div>
            <?php if(!empty($task['deskripsi'])): ?>
                <p class="mt-3 text-muted" style="font-size: 0.9rem;"><?= esc($task['deskripsi']) ?></p>
            <?php endif; ?>
        </div>
        <a href="<?= base_url('projects/'.$project['id'].'/tasks') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('success')):?>
    <div class="alert alert-success border-0 shadow-sm" style="border-radius: 8px;"><?= session()->getFlashdata('success') ?></div>
<?php endif;?>
<?php if(session()->getFlashdata('error')):?>
    <div class="alert alert-danger border-0 shadow-sm" style="border-radius: 8px;"><?= session()->getFlashdata('error') ?></div>
<?php endif;?>

<!-- Tabs -->
<div class="detail-tabs">
    <button class="detail-tab active" data-tab="comments">
        <i class="fas fa-comments"></i> Komentar <span class="tab-count"><?= count($comments) ?></span>
    </button>
    <button class="detail-tab" data-tab="attachments">
        <i class="fas fa-paperclip"></i> Lampiran <span class="tab-count"><?= count($attachments) ?></span>
    </button>
    <button class="detail-tab" data-tab="timelogs">
        <i class="fas fa-clock"></i> Time Log <span class="tab-count"><?= count($timelogs) ?></span>
    </button>
</div>

<!-- TAB: Comments -->
<div class="tab-content-panel active" id="tab-comments">
    <div class="section-card">
        <form action="<?= base_url('tasks/'.$task['id'].'/comments') ?>" method="post" class="mb-4">
            <div class="form-group mb-2">
                <textarea name="komentar" class="form-control" rows="3" placeholder="Tulis komentar..." required style="border-radius: 8px; border: 1px solid #e2e8f0;"></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm" style="border-radius: 6px;"><i class="fas fa-paper-plane"></i> Kirim</button>
        </form>

        <div class="comments-list">
            <?php if(empty($comments)): ?>
                <p class="text-muted text-center py-4"><i class="far fa-comment-dots" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>Belum ada komentar.</p>
            <?php else: ?>
                <?php foreach($comments as $c): ?>
                <div class="comment-item">
                    <div class="comment-avatar"><?= strtoupper(substr($c['nama'], 0, 1)) ?></div>
                    <div class="comment-body">
                        <div class="comment-header">
                            <span class="comment-author"><?= esc($c['nama']) ?></span>
                            <div>
                                <span class="comment-time"><?= date('d M Y H:i', strtotime($c['created_at'])) ?></span>
                                <?php if($c['user_id'] == session()->get('id')): ?>
                                    <a href="<?= base_url('comments/delete/'.$c['id']) ?>" class="text-danger ml-2" onclick="return confirm('Hapus komentar ini?')" title="Hapus"><i class="fas fa-trash-alt" style="font-size: 0.75rem;"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="comment-text"><?= nl2br(esc($c['komentar'])) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- TAB: Attachments -->
<div class="tab-content-panel" id="tab-attachments">
    <div class="section-card">
        <form action="<?= base_url('tasks/'.$task['id'].'/attachments') ?>" method="post" enctype="multipart/form-data" class="mb-4">
            <div class="d-flex align-items-center gap-3" style="gap: 10px;">
                <div class="custom-file" style="flex: 1;">
                    <input type="file" name="file_attachments[]" class="custom-file-input" id="fileInput" multiple required>
                    <label class="custom-file-label" for="fileInput" style="border-radius: 8px;">Pilih file...</label>
                </div>
                <button type="submit" class="btn btn-primary btn-sm ml-2" style="border-radius: 6px; white-space: nowrap;"><i class="fas fa-upload"></i> Upload</button>
            </div>
        </form>

        <form id="formRenameAttachment" method="post" style="display:none;">
            <input type="hidden" name="new_name" id="inputNewName">
        </form>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="m-0 font-weight-bold text-secondary">Daftar Lampiran</h6>
            <input type="text" id="searchAttachment" class="form-control form-control-sm" placeholder="Cari nama lampiran..." style="width: 250px; border-radius: 8px;">
        </div>

        <div class="attachments-list">
            <?php if(empty($attachments)): ?>
                <p class="text-muted text-center py-4"><i class="fas fa-paperclip" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>Belum ada lampiran.</p>
            <?php else: ?>
                <?php foreach($attachments as $a): ?>
                <div class="attachment-item">
                    <div class="attachment-info">
                        <div class="attachment-icon">
                            <?php
                                $ext = pathinfo($a['nama_file'], PATHINFO_EXTENSION);
                                $iconMap = ['pdf' => 'fa-file-pdf', 'doc' => 'fa-file-word', 'docx' => 'fa-file-word', 'xls' => 'fa-file-excel', 'xlsx' => 'fa-file-excel', 'png' => 'fa-file-image', 'jpg' => 'fa-file-image', 'jpeg' => 'fa-file-image', 'gif' => 'fa-file-image', 'zip' => 'fa-file-archive', 'rar' => 'fa-file-archive'];
                                $icon = $iconMap[$ext] ?? 'fa-file';
                            ?>
                            <i class="fas <?= $icon ?>"></i>
                        </div>
                        <div>
                            <div class="attachment-name"><?= esc($a['nama_file']) ?></div>
                            <div class="attachment-meta">
                                <?= number_format($a['ukuran_file'] / 1024, 1) ?> KB
                                · Diunggah oleh <?= esc($a['uploaded_by']) ?>
                                · <?= date('d M Y', strtotime($a['created_at'])) ?>
                            </div>
                        </div>
                    </div>
                    <div class="attachment-actions">
                        <a href="<?= base_url('attachments/inline/'.$a['id']) ?>" target="_blank" class="btn btn-sm btn-outline-info" title="Lihat/Preview"><i class="fas fa-eye"></i></a>
                        <a href="<?= base_url('attachments/download/'.$a['id']) ?>" class="btn btn-sm btn-outline-primary" title="Download"><i class="fas fa-download"></i></a>
                        <button type="button" class="btn btn-sm btn-outline-warning btn-rename" data-id="<?= $a['id'] ?>" data-name="<?= esc($a['nama_file']) ?>" title="Ganti Nama"><i class="fas fa-edit"></i></button>
                        <a href="<?= base_url('attachments/delete/'.$a['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus lampiran ini?')" title="Hapus"><i class="fas fa-trash"></i></a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- TAB: Time Logs -->
<div class="tab-content-panel" id="tab-timelogs">
    <div class="timelog-summary">
        <div class="timelog-stat">
            <div class="timelog-stat-value"><?= count($timelogs) ?></div>
            <div class="timelog-stat-label">Total Entries</div>
        </div>
        <div class="timelog-stat">
            <div class="timelog-stat-value">
                <?php
                    $totalMin = intval($total_durasi);
                    $hours = floor($totalMin / 60);
                    $mins = $totalMin % 60;
                    echo $hours . 'j ' . $mins . 'm';
                ?>
            </div>
            <div class="timelog-stat-label">Total Durasi</div>
        </div>
    </div>

    <div class="section-card">
        <h6 class="font-weight-bold mb-3"><i class="fas fa-plus-circle text-primary"></i> Catat Waktu Baru</h6>
        <form action="<?= base_url('tasks/'.$task['id'].'/timelogs') ?>" method="post">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label class="small font-weight-bold text-muted">Waktu Mulai</label>
                    <input type="datetime-local" name="waktu_mulai" class="form-control" required style="border-radius: 8px;">
                </div>
                <div class="col-md-4 form-group">
                    <label class="small font-weight-bold text-muted">Waktu Selesai</label>
                    <input type="datetime-local" name="waktu_selesai" class="form-control" required style="border-radius: 8px;">
                </div>
                <div class="col-md-4 form-group">
                    <label class="small font-weight-bold text-muted">Catatan</label>
                    <input type="text" name="catatan" class="form-control" placeholder="Apa yang dikerjakan?" style="border-radius: 8px;">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm" style="border-radius: 6px;"><i class="fas fa-save"></i> Simpan</button>
        </form>

        <hr class="my-4">

        <?php if(empty($timelogs)): ?>
            <p class="text-muted text-center py-3"><i class="far fa-clock" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>Belum ada catatan waktu.</p>
        <?php else: ?>
        <table class="table custom-table table-borderless">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Durasi</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($timelogs as $tl): ?>
                <tr>
                    <td><strong><?= esc($tl['nama']) ?></strong></td>
                    <td class="small"><?= date('d/m/Y H:i', strtotime($tl['waktu_mulai'])) ?></td>
                    <td class="small"><?= $tl['waktu_selesai'] ? date('d/m/Y H:i', strtotime($tl['waktu_selesai'])) : '-' ?></td>
                    <td>
                        <?php
                            $h = floor($tl['durasi_menit'] / 60);
                            $m = $tl['durasi_menit'] % 60;
                            echo ($h > 0 ? $h.'j ' : '') . $m.'m';
                        ?>
                    </td>
                    <td class="small text-muted"><?= esc($tl['catatan'] ?? '-') ?></td>
                    <td>
                        <a href="<?= base_url('timelogs/delete/'.$tl['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus log ini?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Tab switching
    document.querySelectorAll('.detail-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            // Remove active from all tabs
            document.querySelectorAll('.detail-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content-panel').forEach(p => p.classList.remove('active'));

            // Activate clicked tab
            this.classList.add('active');
            var target = this.getAttribute('data-tab');
            document.getElementById('tab-' + target).classList.add('active');
        });
    });

    // Activate tab from URL hash
    var hash = window.location.hash.replace('#', '');
    if (hash && document.querySelector('[data-tab="' + hash + '"]')) {
        document.querySelector('[data-tab="' + hash + '"]').click();
    }

    // Custom file input label for multiple files
    document.getElementById('fileInput')?.addEventListener('change', function() {
        var files = this.files;
        var fileName = 'Pilih file...';
        if (files.length > 1) {
            fileName = files.length + ' file terpilih';
        } else if (files.length === 1) {
            fileName = files[0].name;
        }
        this.nextElementSibling.textContent = fileName;
    });

    // Rename attachment prompt
    document.querySelectorAll('.btn-rename').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var oldName = this.getAttribute('data-name');
            var newName = prompt('Masukkan nama baru untuk lampiran ini (tanpa ekstensi tidak apa-apa):', oldName);
            
            if (newName !== null && newName.trim() !== '') {
                document.getElementById('inputNewName').value = newName.trim();
                var form = document.getElementById('formRenameAttachment');
                form.action = '<?= base_url("attachments/rename/") ?>' + id;
                form.submit();
            }
        });
    });

    // Search attachment
    document.getElementById('searchAttachment')?.addEventListener('input', function() {
        var query = this.value.toLowerCase();
        document.querySelectorAll('.attachment-item').forEach(function(item) {
            var name = item.querySelector('.attachment-name').textContent.toLowerCase();
            if (name.includes(query)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>
<?= $this->endSection() ?>
