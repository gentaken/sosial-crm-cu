<?php
// config/app.php - Konfigurasi Parameter Aplikasi & Instansi Dinamis

// Pastikan koneksi database sudah dipanggil sebelum file ini
if (!isset($pdo_lokal)) {
    die("Error: Koneksi database lokal belum diinisialisasi.");
}

$app_config = [];

try {
    // Menarik pengaturan dari database lokal (ID = 1 karena hanya ada 1 baris setting global)
    $stmt_setting = $pdo_lokal->prepare("SELECT * FROM sys_pengaturan WHERE id = 1");
    $stmt_setting->execute();
    $setting = $stmt_setting->fetch(PDO::FETCH_ASSOC);

    if ($setting) {
        $app_config = [
            'nama_app'      => $setting['nama_app'],
            'nama_cu'       => $setting['nama_cu'],
            'alamat_cu'     => $setting['alamat_cu'],
            'warna_primer'  => $setting['warna_primer'],
            'warna_hover'   => $setting['warna_hover'],
            'logo_app'      => $setting['logo_app'],
            'logo_cu'       => $setting['logo_cu'],
        ];
    } else {
        // Fallback jika tabel kosong
        die("Error: Data pengaturan sistem tidak ditemukan. Silakan isi tabel sys_pengaturan.");
    }

} catch (PDOException $e) {
    die("Error mengambil pengaturan sistem: " . htmlspecialchars($e->getMessage()));
}
?>