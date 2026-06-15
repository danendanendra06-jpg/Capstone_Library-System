<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Dashboard') ?> - Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f4f7fe; 
            color: #2b3674;
        }
        
        /* Sidebar Styling */
        .sidebar { 
            min-height: 100vh; 
            background: #111c43; 
            color: #ffffff;
            box-shadow: 4px 0 20px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .sidebar .logo-area {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        
        .sidebar nav a { 
            color: #a3aed1; 
            text-decoration: none; 
            padding: 12px 20px; 
            display: flex; 
            align-items: center;
            border-radius: 12px; 
            margin: 0 15px 8px 15px; 
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .sidebar nav a i {
            font-size: 1.2rem;
            margin-right: 15px;
            transition: all 0.3s ease;
        }
        
        .sidebar nav a:hover { 
            background: rgba(255, 255, 255, 0.05); 
            color: #ffffff; 
            transform: translateX(5px);
        }
        
        .sidebar nav a.active { 
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); 
            color: white; 
            box-shadow: 0 4px 10px rgba(0, 242, 254, 0.3);
        }

        .sidebar nav a.text-danger:hover {
            background: rgba(220, 53, 69, 0.1);
            color: #ff4d4d !important;
        }
        
        /* Main Content Area */
        .content-area { 
            flex-grow: 1; 
            padding: 30px; 
            overflow-y: auto;
            height: 100vh;
        }
        
        /* Top Navbar */
        .navbar-custom { 
            background: rgba(255, 255, 255, 0.7); 
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,.02); 
            border-radius: 16px;
            padding: 15px 25px;
            border: 1px solid rgba(255,255,255,0.8);
        }
        
        .navbar-custom .h1 {
            color: #2b3674;
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .user-profile-badge {
            background: #ffffff;
            padding: 8px 15px;
            border-radius: 50px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        /* Custom Cards for general pages */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,.03);
        }
    </style>
</head>
<body class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar" style="width: 260px;">
        <div class="logo-area">
            <h3 class="mb-0 fw-bold d-flex align-items-center justify-content-center text-white">
                <i class="bi bi-book-half me-2 text-info"></i> LibSys
            </h3>
            <span class="badge bg-info bg-opacity-25 text-info mt-2 px-3 rounded-pill">Admin Portal</span>
        </div>
        <nav>
            <a href="<?= base_url('dashboard') ?>" class="<?= (current_url(true)->getSegment(1) == 'dashboard') ? 'active' : '' ?>">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <p class="text-uppercase text-muted px-4 mt-4 mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Management</p>
            <a href="<?= base_url('books') ?>" class="<?= (current_url(true)->getSegment(1) == 'books') ? 'active' : '' ?>">
                <i class="bi bi-journal-richtext"></i> Books Catalog
            </a>
            <a href="<?= base_url('categories') ?>" class="<?= (current_url(true)->getSegment(1) == 'categories') ? 'active' : '' ?>">
                <i class="bi bi-tags-fill"></i> Categories
            </a>
            <a href="<?= base_url('users') ?>" class="<?= (current_url(true)->getSegment(1) == 'users') ? 'active' : '' ?>">
                <i class="bi bi-people-fill"></i> Members
            </a>
            <p class="text-uppercase text-muted px-4 mt-4 mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Circulation</p>
                <li class="nav-item mb-1">
                    <a href="<?= base_url('borrows') ?>" class="nav-link <?= (url_is('borrows*') && !url_is('borrows/returns*')) ? 'active bg-info text-white' : 'text-white-50' ?>">
                        <i class="bi bi-arrow-left-right me-2"></i> Borrows
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="<?= base_url('borrows/returns') ?>" class="nav-link <?= url_is('borrows/returns*') ? 'active bg-info text-white' : 'text-white-50' ?>">
                        <i class="bi bi-box-arrow-in-down-left me-2"></i> Returns
                    </a>
                </li>
            <a href="<?= base_url('fines') ?>" class="<?= (current_url(true)->getSegment(1) == 'fines') ? 'active' : '' ?>">
                <i class="bi bi-wallet2"></i> Fines
            </a>
            <div class="mt-5 mb-3 px-3">
                <a href="<?= base_url('logout') ?>" class="text-danger bg-danger bg-opacity-10 justify-content-center py-2">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="content-area">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-custom mb-4">
            <div class="container-fluid px-2">
                <span class="navbar-brand mb-0 h1"><?= esc($title ?? 'Dashboard') ?></span>
                <div class="d-flex align-items-center">
                    <div class="user-profile-badge">
                        <div class="user-avatar">
                            <?= strtoupper(substr(session()->get('username') ?? 'A', 0, 1)) ?>
                        </div>
                        <div class="d-flex flex-column lh-1">
                            <span><?= esc(session()->get('username')) ?></span>
                            <small class="text-muted" style="font-size: 0.75rem;"><?= esc(session()->get('role')) ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div><?= session()->getFlashdata('success') ?></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div><?= session()->getFlashdata('error') ?></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
