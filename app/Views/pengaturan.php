<?php require_once '../app/Views/Layouts/header.php'; ?>
<?php require_once '../app/Views/Layouts/sidebar.php'; ?>

<main class="main-content p-3 p-md-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="bi bi-gear-fill text-primary me-2"></i>Pengaturan Instansi</h4>
    </div>

    <?php if ($error_msg): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <?= $error_msg ?> <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($success_msg): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= $success_msg ?> 
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="pengaturan.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update_pengaturan">
        
        <div class="row g-4">
            <div class="col-12 col-lg-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-bold mb-0 text-secondary"><i class="bi bi-building me-2"></i>Profil Koperasi / CU</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Aplikasi (CRM)</label>
                            <input type="text" class="form-control" name="nama_app" value="<?= htmlspecialchars($app_config['nama_app']) ?>" required>
                            <small class="text-muted">Nama ini akan muncul di sudut kiri atas layar.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Koperasi / CU</label>
                            <input type="text" class="form-control" name="nama_cu" value="<?= htmlspecialchars($app_config['nama_cu']) ?>" required>
                            <small class="text-muted">Nama instansi resmi.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Alamat Resmi Instansi</label>
                            <textarea class="form-control" name="alamat_cu" rows="3" required><?= htmlspecialchars($app_config['alamat_cu']) ?></textarea>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Logo Aplikasi (Kotak/Ikon)</label>
                                <div class="d-flex align-items-center mb-2">
                                    <img src="<?= htmlspecialchars($app_config['logo_app']) ?>" alt="Logo App Saat Ini" height="40" class="me-3 rounded bg-light p-1 border">
                                    <input type="file" class="form-control form-control-sm" name="logo_app" accept="image/png, image/jpeg">
                                </div>
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Logo Utama Instansi</label>
                                <div class="d-flex align-items-center mb-2">
                                    <img src="<?= htmlspecialchars($app_config['logo_cu']) ?>" alt="Logo CU Saat Ini" height="40" class="me-3 rounded bg-light p-1 border">
                                    <input type="file" class="form-control form-control-sm" name="logo_cu" accept="image/png, image/jpeg">
                                </div>
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-bold mb-0 text-secondary"><i class="bi bi-palette-fill me-2"></i>Tema Warna Aplikasi</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-light border small text-muted mb-4">
                            Pilih warna identitas utama instansi Anda. Sistem akan menyesuaikan tombol, menu aktif, dan sorotan teks di seluruh aplikasi secara otomatis.
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Warna Utama (Primary Color)</label>
                            <div class="d-flex align-items-center">
                                <input type="color" class="form-control form-control-color border-0 p-0 shadow-sm me-3" style="width: 50px; height: 50px; cursor: pointer;" name="warna_primer" value="<?= htmlspecialchars($app_config['warna_primer']) ?>" required>
                                <input type="text" class="form-control form-control-sm text-uppercase" value="<?= htmlspecialchars($app_config['warna_primer']) ?>" disabled style="width: 100px;">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Warna Sorotan (Hover/Active Color)</label>
                            <div class="d-flex align-items-center">
                                <input type="color" class="form-control form-control-color border-0 p-0 shadow-sm me-3" style="width: 50px; height: 50px; cursor: pointer;" name="warna_hover" value="<?= htmlspecialchars($app_config['warna_hover']) ?>" required>
                                <input type="text" class="form-control form-control-sm text-uppercase" value="<?= htmlspecialchars($app_config['warna_hover']) ?>" disabled style="width: 100px;">
                            </div>
                            <small class="text-muted mt-2 d-block">Disarankan menggunakan warna yang sedikit lebih gelap atau lebih terang dari Warna Utama.</small>
                        </div>
                        
                    </div>
                    <div class="card-footer bg-white py-3 text-end border-top-0">
                        <button type="submit" class="btn btn-primary px-4 fw-bold"><i class="bi bi-save me-2"></i>Simpan Pengaturan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<?php require_once '../app/Views/Layouts/footer.php'; ?>