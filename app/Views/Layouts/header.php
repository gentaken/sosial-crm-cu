<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' | ' : '' ?><?= htmlspecialchars($app_config['nama_app']) ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        /* CSS Dinamis dari Database */
        :root {
            --bs-primary: <?= $app_config['warna_primer'] ?>;
        }
        .text-primary { color: <?= $app_config['warna_primer'] ?> !important; }
        .bg-primary { background-color: <?= $app_config['warna_primer'] ?> !important; }
        .btn-primary { background-color: <?= $app_config['warna_primer'] ?>; border-color: <?= $app_config['warna_primer'] ?>; }
        .btn-primary:hover { background-color: <?= $app_config['warna_hover'] ?>; border-color: <?= $app_config['warna_hover'] ?>; }
        
        /* Layout Global (Agar Sidebar bisa berpadu dengan konten) */
        body { min-height: 100vh; display: flex; flex-direction: column; background-color: #f8f9fa; }
        .app-container { display: flex; flex: 1; }
        .main-content { flex: 1; width: 100%; }
        
        /* Sidebar Styling */
        .sidebar { width: 250px; background-color: #ffffff; border-right: 1px solid #dee2e6; }
        .sidebar .nav-link { color: #495057; padding: 0.8rem 1rem; border-radius: 0.375rem; margin-bottom: 0.2rem; display: flex; align-items: center; }
        .sidebar .nav-link:hover { background-color: #f8f9fa; }
        .sidebar .nav-link.active { background-color: <?= $app_config['warna_primer'] ?>; color: #ffffff; }
        .sidebar .nav-link i { margin-right: 10px; font-size: 1.2rem; }

        @media (min-width: 992px) {
            .app-container { overflow: hidden; }
            .sidebar-offcanvas { position: static !important; transform: none !important; visibility: visible !important; height: calc(100vh - 60px); overflow-y: auto; }
            .offcanvas-header { display: none; }
        }
        
        /* Utilitas Teks Profil */
        .currency-text { font-family: 'Courier New', Courier, monospace; font-weight: bold; }
        .data-label { font-size: 0.80rem; color: #6c757d; margin-bottom: 0; text-transform: uppercase; letter-spacing: 0.5px;}
        .data-value { font-weight: 500; margin-bottom: 15px; font-size: 0.95rem; }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white shadow-sm sticky-top" style="height: 60px;">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <?php if(isset($show_sidebar) && $show_sidebar): ?>
            <button class="btn border-0 d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                <i class="bi bi-list fs-4"></i>
            </button>
            <?php endif; ?>
            
            <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
                <img src="<?= htmlspecialchars($app_config['logo_app']) ?>" alt="Logo App" width="30" height="30" class="d-inline-block align-text-top me-2">
                <span class="fw-bold text-primary d-none d-sm-inline"><?= htmlspecialchars($app_config['nama_app']) ?></span>
            </a>
        </div>
        
        <div class="d-flex align-items-center">
            <span class="text-muted small me-3 d-none d-md-block"><?= htmlspecialchars($app_config['nama_cu']) ?></span>
            <span class="fw-bold small me-2 text-primary"><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></span>
            <div class="dropdown">
                <img src="assets/img/icon_user.png" alt="User" class="rounded-circle border dropdown-toggle bg-light" width="35" height="35" style="object-fit: cover; cursor: pointer;" data-bs-toggle="dropdown">
                
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Keluar</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="<?= (isset($show_sidebar) && $show_sidebar) ? 'app-container' : 'container mt-4 mb-5' ?>">