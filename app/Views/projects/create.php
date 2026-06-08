<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="top-header">
    <div class="header-title-wrapper">
        <h1 class="header-title">Create New Project</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card" style="border-radius: 12px; border: 1px solid #edf2f7; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
            <div class="card-body p-4">
                <form action="<?= base_url('projects/store') ?>" method="post">
                    <div class="form-group">
                        <label class="font-weight-bold small text-muted">Project Name</label>
                        <input type="text" name="nama_project" class="form-control" required style="border-radius:8px;">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold small text-muted">Description</label>
                        <textarea name="deskripsi" class="form-control" rows="4" style="border-radius:8px;"></textarea>
                    </div>
                    <div class="mt-4 d-flex">
                        <button type="submit" class="btn-primary-custom mr-2">Save Project</button>
                        <a href="<?= base_url('projects') ?>" class="btn btn-light" style="border-radius:8px; padding: 8px 20px;">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
