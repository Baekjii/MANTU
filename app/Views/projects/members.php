<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="top-header">
    <div class="header-title-wrapper">
        <h1 class="header-title">Team Members</h1>
        <span class="header-badge"><?= esc($project['nama_project']) ?></span>
    </div>
    <div class="header-actions">
        <a href="<?= base_url('projects/'.$project['id'].'/tasks') ?>" class="btn btn-outline-secondary mr-2" style="border-radius: 8px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Board
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('success')):?>
    <div class="alert alert-success border-0 shadow-sm" style="border-radius: 8px;"><?= session()->getFlashdata('success') ?></div>
<?php endif;?>
<?php if(session()->getFlashdata('error')):?>
    <div class="alert alert-danger border-0 shadow-sm" style="border-radius: 8px;"><?= session()->getFlashdata('error') ?></div>
<?php endif;?>

<!-- Invite Member Card -->
<div class="table-card mb-4">
    <div class="table-header-custom">
        <h3 class="table-title"><i class="fas fa-user-plus mr-2" style="color: #1a73e8;"></i>Invite Anggota Baru</h3>
    </div>
    <div style="padding: 20px 25px;">
        <form action="<?= base_url('projects/'.$project['id'].'/members/add') ?>" method="post">
            <div class="row align-items-end">
                <div class="col-md-5 form-group mb-0">
                    <label class="font-weight-bold small text-muted">Email User</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email user terdaftar..." required style="border-radius: 8px;">
                </div>
                <div class="col-md-3 form-group mb-0">
                    <label class="font-weight-bold small text-muted">Role</label>
                    <select name="role" class="form-control" style="border-radius: 8px;">
                        <option value="member">Member</option>
                        <option value="viewer">Viewer</option>
                        <option value="owner">Owner</option>
                    </select>
                </div>
                <div class="col-md-4 form-group mb-0">
                    <button type="submit" class="btn-primary-custom btn-block" style="padding: 10px 20px;">
                        <i class="fas fa-paper-plane mr-1"></i> Kirim Undangan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Members List -->
<div class="table-card">
    <div class="table-header-custom">
        <h3 class="table-title">Daftar Anggota (<?= count($members) ?>)</h3>
    </div>
    
    <table class="table custom-table table-borderless">
        <thead>
            <tr>
                <th>Anggota</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Bergabung</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($members)): ?>
            <tr>
                <td colspan="6" class="text-center text-muted py-4">
                    <i class="fas fa-users" style="font-size: 2rem; display: block; margin-bottom: 10px; color: #cbd5e0;"></i>
                    Belum ada anggota tambahan. Invite seseorang untuk berkolaborasi!
                </td>
            </tr>
            <?php else: ?>
                <?php foreach($members as $m): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: #1a73e8; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; margin-right: 12px;">
                                <?= strtoupper(substr($m['nama'], 0, 1)) ?>
                            </div>
                            <div class="task-title"><?= esc($m['nama']) ?></div>
                        </div>
                    </td>
                    <td class="text-muted small"><?= esc($m['email']) ?></td>
                    <td>
                        <?php if($m['id'] > 0 && $m['user_id'] != $project['user_id']): ?>
                            <form action="<?= base_url('members/update-role/'.$m['id']) ?>" method="post" class="d-inline">
                                <select name="role" class="form-control form-control-sm d-inline-block" style="width: 110px; border-radius: 6px;" onchange="this.form.submit()">
                                    <option value="owner" <?= $m['role'] == 'owner' ? 'selected' : '' ?>>Owner</option>
                                    <option value="member" <?= $m['role'] == 'member' ? 'selected' : '' ?>>Member</option>
                                    <option value="viewer" <?= $m['role'] == 'viewer' ? 'selected' : '' ?>>Viewer</option>
                                </select>
                            </form>
                        <?php else: ?>
                            <span class="badge badge-primary" style="background: #e2e8f0; color: #4a5568;">Owner</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($m['status'] == 'pending'): ?>
                            <span class="badge badge-warning" style="background: #fefcbf; color: #b7791f;">Pending</span>
                        <?php else: ?>
                            <span class="badge badge-success" style="background: #c6f6d5; color: #22543d;">Accepted</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-muted small"><?= date('d M Y', strtotime($m['created_at'])) ?></td>
                    <td>
                        <?php if($m['id'] > 0 && $m['user_id'] != $project['user_id']): ?>
                            <a href="<?= base_url('members/remove/'.$m['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus anggota ini dari proyek?')">
                                <i class="fas fa-user-minus"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
