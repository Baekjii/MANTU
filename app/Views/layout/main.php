<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MANTU - Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            height: 100vh;
            background-color: #1a1f36;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
            z-index: 1000;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            padding: 0 25px;
            margin-bottom: 40px;
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .sidebar-brand .logo-icon {
            background-color: #2b6cb0;
            color: #fff;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            margin-right: 12px;
            font-size: 18px;
        }

        .sidebar-menu-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6b7280;
            margin: 20px 25px 10px;
            font-weight: 600;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav li {
            margin-bottom: 5px;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 10px 25px;
            color: #a0aec0;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-nav a:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .sidebar-nav a.active {
            color: #fff;
            background-color: #2b6cb0;
            border-left: 3px solid #63b3ed;
            margin: 0 15px;
            padding: 10px 10px;
            border-radius: 8px;
            border-left: none;
            /* override */
        }

        /* Fix active state margin hack */
        .sidebar-nav li.active-item {
            padding: 0 15px;
        }

        .sidebar-nav li.active-item a {
            color: #fff;
            background-color: #1a73e8;
            /* Blue exact from image */
            border-radius: 8px;
            padding: 10px 15px;
            margin: 0;
        }

        .project-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 12px;
        }

        .dot-green {
            background-color: #38a169;
        }

        .dot-blue {
            background-color: #3182ce;
        }

        .dot-yellow {
            background-color: #ecc94b;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 20px 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background-color: #2d3748;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 12px;
            font-size: 14px;
        }

        .user-info {
            line-height: 1.2;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #fff;
        }

        .user-email {
            font-size: 0.75rem;
            color: #a0aec0;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 30px 40px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Header */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header-title-wrapper {
            display: flex;
            align-items: center;
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            margin-right: 15px;
        }

        .header-badge {
            background-color: #e2e8f0;
            color: #4a5568;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .header-actions {
            display: flex;
            align-items: center;
        }

        .search-input {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 15px;
            background-color: #fff;
            width: 250px;
            margin-right: 15px;
            font-size: 0.9rem;
            outline: none;
        }

        .search-input:focus {
            border-color: #1a73e8;
        }

        .btn-primary-custom {
            background-color: #1a73e8;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: background 0.2s;
        }

        .btn-primary-custom:hover {
            background-color: #1557b0;
            color: #fff;
        }

        .btn-primary-custom i {
            margin-right: 8px;
        }

        /* Stat Cards */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            border: 1px solid #edf2f7;
            height: 100%;
        }

        .stat-card.stat-overdue {
            background-color: #fff5f5;
            border-color: #fed7d7;
        }

        .stat-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #718096;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        .stat-overdue .stat-title {
            color: #c53030;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 5px;
            line-height: 1;
        }

        .stat-overdue .stat-value {
            color: #c53030;
        }

        .stat-subtitle {
            font-size: 0.75rem;
            font-weight: 500;
        }

        .text-green {
            color: #38a169;
        }

        .text-red {
            color: #e53e3e;
        }

        .text-muted-custom {
            color: #a0aec0;
        }

        .progress-micro {
            height: 4px;
            background-color: #edf2f7;
            border-radius: 2px;
            margin-top: 15px;
            overflow: hidden;
        }

        .progress-micro .bar {
            height: 100%;
            background-color: #3182ce;
        }

        /* Table Card */
        .table-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            border: 1px solid #edf2f7;
            margin-top: 30px;
            overflow: hidden;
            flex-grow: 1;
        }

        .table-header-custom {
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #edf2f7;
        }

        .table-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            color: #1a202c;
        }

        .table-controls button {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 5px 15px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #4a5568;
            margin-left: 10px;
            transition: all 0.2s;
        }

        .table-controls button:hover {
            background: #f7fafc;
        }

        .custom-table {
            width: 100%;
            margin: 0;
        }

        .custom-table th {
            background-color: #f8fafc;
            color: #718096;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            padding: 15px 25px;
            border-bottom: 1px solid #edf2f7;
            border-top: none;
        }

        .custom-table td {
            padding: 15px 25px;
            vertical-align: middle;
            border-bottom: 1px solid #edf2f7;
            color: #2d3748;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .row-overdue {
            background-color: #fff5f5 !important;
        }

        /* Badges & Pills */
        .status-badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
            letter-spacing: 0.5px;
        }

        .status-todo {
            background-color: #edf2f7;
            color: #4a5568;
        }

        .status-inprogress {
            background-color: #ebf8ff;
            color: #3182ce;
        }

        .status-done {
            background-color: #f0fff4;
            color: #38a169;
        }

        .status-overdue {
            background-color: #feb2b2;
            color: #c53030;
        }

        .priority-high {
            color: #e53e3e;
            font-weight: 600;
        }

        .priority-mid {
            color: #d69e2e;
            font-weight: 600;
        }

        .priority-low {
            color: #a0aec0;
            font-weight: 600;
        }

        .action-link {
            color: #3182ce;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.85rem;
        }

        .action-link:hover {
            text-decoration: underline;
            color: #2b6cb0;
        }

        .task-title {
            font-weight: 600;
            color: #1a202c;
            margin: 0;
        }

        .task-project {
            color: #718096;
            font-size: 0.85rem;
        }

        .task-deadline {
            color: #718096;
            font-size: 0.85rem;
        }

        .row-overdue .task-deadline {
            color: #e53e3e;
            font-weight: 600;
        }

        /* Footer */
        .app-footer {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            color: #a0aec0;
            font-weight: 500;
        }

        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #38a169;
            border-radius: 50%;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <?php
        $currentUri = uri_string();
        
        $projectModel = new \App\Models\ProjectModel();
        $memberModel = new \App\Models\ProjectMemberModel();
        
        $ownedProjects = $projectModel->where('user_id', session()->get('id'))->findAll();
        $memberProjectsRaw = $memberModel->getProjectsByUser(session()->get('id'));
        $memberProjects = array_map(function($p) {
            return [
                'id' => $p['project_id'],
                'user_id' => null, 
                'nama_project' => $p['nama_project'],
                'deskripsi' => $p['deskripsi'],
                'created_at' => $p['created_at']
            ];
        }, $memberProjectsRaw);
        
        $globalProjects = array_merge($ownedProjects, $memberProjects);
    ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="logo-icon">M</div>
            MANTU
        </div>

        <div class="sidebar-menu-title">Menu</div>
        <ul class="sidebar-nav">
            <li class="<?= (empty($currentUri) || $currentUri == 'projects') ? 'active-item' : '' ?>"><a href="<?= base_url('projects') ?>">Dashboard</a></li>
            <li class="<?= ($currentUri == 'projects/all') ? 'active-item' : '' ?>"><a href="<?= base_url('projects/all') ?>">All Projects</a></li>
        </ul>

        <div class="sidebar-menu-title mt-4">Team</div>
        <ul class="sidebar-nav">
            <?php if (isset($globalProjects) && !empty($globalProjects)): ?>
                <?php foreach (array_slice($globalProjects, 0, 5) as $p):
                    $isMembersActive = (strpos($currentUri, 'projects/'.$p['id'].'/members') !== false) ? 'active-item' : '';
                ?>
                <li class="<?= $isMembersActive ?>"><a href="<?= base_url('projects/'.$p['id'].'/members') ?>">
                    <i class="fas fa-users mr-2" style="font-size: 0.8rem; width: 16px;"></i>
                    <?= esc(strlen($p['nama_project']) > 12 ? substr($p['nama_project'], 0, 12) . '...' : $p['nama_project']) ?>
                </a></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li><a href="#" class="text-muted"><small>No projects yet</small></a></li>
            <?php endif; ?>
        </ul>

        <div class="sidebar-menu-title mt-4">Projects</div>
        <ul class="sidebar-nav">
            <?php
            $colors = ['green', 'blue', 'yellow'];
            $i = 0;
            if (isset($globalProjects) && !empty($globalProjects)):
                foreach (array_slice($globalProjects, 0, 5) as $p):
                    $color = $colors[$i % 3];
                    $isActive = (strpos($currentUri, 'projects/'.$p['id'].'/tasks') !== false) ? 'active-item' : '';
                    ?>
                    <li class="<?= $isActive ?>"><a href="<?= base_url('projects/'.$p['id'].'/tasks') ?>">
                            <div class="project-dot dot-<?= $color ?>"></div>
                            <?= esc(strlen($p['nama_project']) > 15 ? substr($p['nama_project'], 0, 15) . '...' : $p['nama_project']) ?>
                        </a></li>
                    <?php
                    $i++;
                endforeach;
            endif;
            ?>
        </ul>

        <div class="sidebar-footer">
            <div class="user-avatar">
                <?php
                $name = session()->get('nama');
                $initials = strtoupper(substr($name, 0, 1) . (strpos($name, ' ') ? substr($name, strpos($name, ' ') + 1, 1) : ''));
                echo $initials;
                ?>
            </div>
            <div class="user-info" style="flex:1; overflow: hidden;">
                <div class="user-name" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding-right: 5px;"><?= esc(session()->get('nama')) ?></div>
            </div>
            <a href="<?= base_url('profile') ?>" class="text-light" title="Profile Settings" style="padding: 5px;"><i class="fas fa-cog"></i></a>
            <a href="<?= base_url('logout') ?>" class="text-danger" title="Logout" style="padding: 5px;"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <?= $this->renderSection('content') ?>

        <div class="app-footer">
            <div>
                <span class="status-dot"></span> Server: Connected <span class="mx-3">Last Sync: Just now</span>
            </div>
            <div>
                v2.4.1-Stable
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>