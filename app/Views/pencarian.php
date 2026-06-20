<?php require_once '../app/Views/Layouts/header.php'; ?>
<?php require_once '../app/Views/Layouts/sidebar.php'; ?>

<main class="main-content p-3 p-md-4">
    <div class="row justify-content-center mt-3">
        <div class="col-md-10 col-lg-8">
            
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="text-center mb-4 text-primary fw-bold">Pencarian Anggota</h4>
                    
                    <form action="index.php" method="GET">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control form-control-lg" name="search_query" placeholder="Masukkan No. BA atau Nama..." required value="<?= isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : '' ?>">
                            <button class="btn btn-primary px-4" type="submit">Cari</button>
                        </div>
                    </form>

                    <?php if ($error_msg): ?>
                        <div class="alert alert-warning"><?= $error_msg ?></div>
                    <?php endif; ?>

                    <?php if (!empty($hasil_pencarian)): ?>
                        <div class="list-group mt-3">
                            <?php foreach ($hasil_pencarian as $row): ?>
                                <a href="profil.php?no_ba=<?= urlencode($row['No_BA']) ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1 fw-bold"><?= htmlspecialchars($row['Nama']) ?></h5>
                                        <small class="text-muted">BA: <?= htmlspecialchars($row['No_BA']) ?></small>
                                    </div>
                                    <p class="mb-1 text-secondary"><small><?= htmlspecialchars($row['Alamat']) ?></small></p>
                                    <small>Tgl Lahir: <?= date('d M Y', strtotime($row['Tgl_Lahir'])) ?></small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
            
            <div class="text-center mt-4 text-muted small">
                &copy; <?= date('Y') ?> <?= htmlspecialchars($app_config['nama_app']) ?> | <?= htmlspecialchars($app_config['alamat_cu']) ?>
            </div>
            
        </div>
    </div>
</main>

<?php require_once '../app/Views/Layouts/footer.php'; ?>