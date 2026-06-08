<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Project Tracker</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .card-register { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .btn-custom { background-color: #4a90e2; color: #fff; border-radius: 20px; }
        .btn-custom:hover { background-color: #357abd; color: #fff; }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-register p-4">
                    <h3 class="text-center font-weight-bold mb-4">Create Account</h3>
                    <?php if(isset($validation)):?>
                        <div class="alert alert-danger">
                            <?= $validation->listErrors() ?>
                        </div>
                    <?php endif;?>
                    <form action="<?= base_url('register/process') ?>" method="post">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="nama" class="form-control rounded-pill" value="<?= set_value('nama') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email address</label>
                            <input type="email" name="email" class="form-control rounded-pill" value="<?= set_value('email') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control rounded-pill" required>
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" name="confpassword" class="form-control rounded-pill" required>
                        </div>
                        <button type="submit" class="btn btn-custom btn-block mt-4">Register</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="<?= base_url('login') ?>" class="text-muted">Already have an account? Login here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
