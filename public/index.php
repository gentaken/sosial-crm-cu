<?php
// public/index.php - Halaman Utama / Pencarian Anggota
session_start();

require_once '../config/database.php';
require_once '../config/app.php';

// PANGGIL MIDDLEWARE AUTENTIKASI
require_once '../app/Helpers/auth.php';

$results = [];
$keyword_asli = "";
$pesan_pencarian = "";

if (isset($_GET['q'])) {
    $keyword_asli = trim($_GET['q']);
    
    if ($keyword_asli !== '') {
        try {
            // Kita bungkus keyword dengan % untuk pencarian parsial (fleksibel)
            $search_term = "%" . $keyword_asli . "%";
            
            // Query perbaikan: 
            // 1. Menggunakan TRIM() pada No_BA untuk menghilangkan spasi tersembunyi di database Core
            // 2. Mencari di Nama, No_BA, dan NIK (No_ID)
            // 3. Menambahkan pencarian Exact (persis sama) khusus untuk No_BA
            $stmt = $pdo_core->prepare("
                SELECT 
                    a.No_BA, 
                    a.No_ID, 
                    a.Nama, 
                    c.Nama_Cabang, 
                    a.Status_Keanggotaan, 
                    a.Kota
                FROM m_anggota a
                LEFT JOIN m_cabang c ON TRIM(a.Kode_Cabang) = TRIM(c.Kode_Cabang)
                WHERE 
                    a.Nama LIKE :kw 
                    OR TRIM(a.No_BA) LIKE :kw 
                    OR TRIM(a.No_BA) = :exact
                    OR a.No_ID LIKE :kw
                ORDER BY a.Nama ASC
                LIMIT 50
            ");
            
            $stmt->execute([
                'kw' => $search_term,
                'exact' => $keyword_asli
            ]);
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($results)) {
                $pesan_pencarian = "Anggota dengan kata kunci <strong>'" . htmlspecialchars($keyword_asli) . "'</strong> tidak ditemukan atau sudah tidak aktif.";
            }

        } catch (PDOException $e) {
            die("Kesalahan query pencarian: " . htmlspecialchars($e->getMessage()));
        }
    }
}

$page_title = "Pencarian Anggota";
$show_sidebar = true;
$active_menu = "pencarian";

require_once '../app/Views/Layouts/header.php';
require_once '../app/Views/Layouts/sidebar.php';
?>

<main class="main-content p-3 p-md-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-primary"><i class="bi bi-search me-2"></i> Direktori Anggota</h4>
            <p class="text-muted small mb-0">Cari berdasarkan Nama, NIK, atau No. BA</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <form action="index.php" method="GET" class="row g-2 align-items-center justify-content-center">
                <div class="col-md-8">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" name="q" placeholder="Masukkan No. BA (misal: 0.08.06646), NIK, atau Nama..." value="<?= htmlspecialchars($keyword_asli) ?>" required autofocus>
                        <button class="btn btn-primary px-4 fw-bold" type="submit">Cari Anggota</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if ($keyword_asli !== ''): ?>
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">
                    Hasil Pencarian: <span class="text-primary"><?= count($results) ?> Data Ditemukan</span>
                </h6>
            </div>
            
            <?php if (!empty($results)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="50">#</th>
                                <th>Nama Anggota</th>
                                <th>No. BA & NIK</th>
                                <th>Cabang & Kota</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($results as $row): ?>
                                <tr>
                                    <td class="text-center text-muted"><?= $no++ ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="get_foto_core.php?no_ba=<?= urlencode($row['No_BA']) ?>" alt="Foto" class="rounded-circle border" style="width: 40px; height: 40px; object-fit: cover; background-color:#f8f9fa;">
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="mb-0 fw-bold text-dark"><?= htmlspecialchars($row['Nama']) ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-primary"><?= htmlspecialchars($row['No_BA']) ?></div>
                                        <div class="small text-muted">NIK: <?= htmlspecialchars($row['No_ID']) ?></div>
                                    </td>
                                    <td>
                                        <div><?= htmlspecialchars($row['Nama_Cabang']) ?: '-' ?></div>
                                        <div class="small text-muted"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($row['Kota']) ?: '-' ?></div>
                                    </td>
                                    <td>
                                        <?php if ($row['Status_Keanggotaan'] == '0'): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i> Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1"><i class="bi bi-x-circle-fill me-1"></i> Keluar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="profil.php?no_ba=<?= urlencode($row['No_BA']) ?>" class="btn btn-sm btn-primary shadow-sm">
                                            Buka Profil <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="card-body text-center py-5">
                    <i class="bi bi-search fs-1 text-muted mb-3 d-block"></i>
                    <h5 class="fw-bold text-dark">Data Tidak Ditemukan</h5>
                    <p class="text-muted mb-0"><?= $pesan_pencarian ?></p>
                    <p class="small text-muted mt-2">Pastikan penulisan No. BA (termasuk titik) atau ejaan nama sudah benar.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</main>

<?php require_once '../app/Views/Layouts/footer.php'; ?>