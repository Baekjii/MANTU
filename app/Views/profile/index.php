<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="top-header">
    <div class="header-title-wrapper">
        <h1 class="header-title">My Profile</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card" style="border-radius: 12px; border: 1px solid #edf2f7; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
            <div class="card-body p-4">
                <?php if(session()->getFlashdata('success')):?>
                    <div class="alert alert-success border-0 shadow-sm" style="border-radius: 8px;"><?= session()->getFlashdata('success') ?></div>
                <?php endif;?>

                <form action="<?= base_url('profile/update') ?>" method="post">
                    <div class="form-group">
                        <label class="font-weight-bold small text-muted">Full Name</label>
                        <input type="text" name="nama" class="form-control" value="<?= esc($user['nama']) ?>" required style="border-radius:8px;">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold small text-muted">Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required style="border-radius:8px;">
                    </div>
                    <hr>
                    <h6 class="mb-3 text-muted">Change Password <small>(leave blank to keep current)</small></h6>
                    <div class="form-group">
                        <label class="font-weight-bold small text-muted">New Password</label>
                        <input type="password" name="password" class="form-control" style="border-radius:8px;">
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn-primary-custom">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
