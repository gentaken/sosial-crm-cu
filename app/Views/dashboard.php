<?php require_once '../app/Views/Layouts/header.php'; ?>
<?php require_once '../app/Views/Layouts/sidebar.php'; ?>

<main class="main-content p-3 p-md-4">
    
    <?php if (isset($_GET['error']) && $_GET['error'] == 'access_denied'): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
            <i class="bi bi-shield-lock-fill me-2"></i> Akses Ditolak! Anda tidak memiliki hak otorisasi untuk membuka halaman tersebut.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Beranda Dashboard</h4>
        <div class="text-muted small"><?= date('l, d F Y') ?></div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Anggota CRM</h6>
                            <h3 class="fw-bold mb-0 text-primary"><?= number_format($stat_total_anggota, 0, ',', '.') ?></h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded"><i class="bi bi-people text-primary fs-4"></i></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Dikunjungi Bulan Ini</h6>
                            <h3 class="fw-bold mb-0 text-success"><?= $stat_dikunjungi_bulan_ini ?></h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded"><i class="bi bi-check2-circle text-success fs-4"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Target Kunjungan</h6>
                            <h3 class="fw-bold mb-0 text-warning"><?= $stat_target_kunjungan ?></h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded"><i class="bi bi-bullseye text-warning fs-4"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-primary bg-primary bg-opacity-10 border-0 shadow-sm d-flex align-items-center" role="alert">
        <i class="bi bi-emoji-smile fs-4 text-primary me-3"></i>
        <div>
            <strong>Selamat datang kembali, <?= htmlspecialchars($_SESSION['nama_lengkap']) ?>!</strong><br>
            <span class="small text-muted">Role Anda saat ini adalah: <span class="fw-bold"><?= htmlspecialchars($_SESSION['user_type']) ?></span>. Jangan lupa merencanakan kunjungan hari ini.</span>
        </div>
    </div>

</main>

<?php require_once '../app/Views/Layouts/footer.php'; ?>