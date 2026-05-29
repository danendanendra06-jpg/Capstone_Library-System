<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Dashboard') ?> - Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #343a40; color: white; }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 10px 15px; display: block; border-radius: 4px; margin-bottom: 5px; }
        .sidebar a:hover, .sidebar a.active { background: #495057; color: white; }
        .content-area { flex-grow: 1; padding: 20px; }
        .navbar-custom { background: white; box-shadow: 0 2px 4px rgba(0,0,0,.08); }
    </style>
</head>
<body class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3" style="width: 250px;">
        <h4 class="text-center mb-4"><i class="bi bi-book"></i> LibSys</h4>
        <hr>
        <nav>
            <a href="<?= base_url('dashboard') ?>" class="<?= (current_url(true)->getSegment(1) == 'dashboard') ? 'active' : '' ?>"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
            <a href="<?= base_url('books') ?>" class="<?= (current_url(true)->getSegment(1) == 'books') ? 'active' : '' ?>"><i class="bi bi-journal-text me-2"></i> Books</a>
            <a href="<?= base_url('categories') ?>" class="<?= (current_url(true)->getSegment(1) == 'categories') ? 'active' : '' ?>"><i class="bi bi-tags me-2"></i> Categories</a>
            <a href="<?= base_url('users') ?>" class="<?= (current_url(true)->getSegment(1) == 'users') ? 'active' : '' ?>"><i class="bi bi-people me-2"></i> Members</a>
            <a href="<?= base_url('transactions') ?>" class="<?= (current_url(true)->getSegment(1) == 'transactions') ? 'active' : '' ?>"><i class="bi bi-arrow-left-right me-2"></i> Transactions</a>
            <a href="<?= base_url('fines') ?>" class="<?= (current_url(true)->getSegment(1) == 'fines') ? 'active' : '' ?>"><i class="bi bi-cash-coin me-2"></i> Fines</a>
            <hr>
            <a href="<?= base_url('logout') ?>" class="text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="content-area">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light navbar-custom rounded mb-4 px-3">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1"><?= esc($title ?? 'Dashboard') ?></span>
                <div class="d-flex align-items-center">
                    <span class="me-3">Welcome, <strong><?= esc(session()->get('username')) ?></strong> (<?= esc(session()->get('role')) ?>)</span>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
