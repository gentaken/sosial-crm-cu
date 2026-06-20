<?php
// public/pengaturan.php - Controller Pengaturan Instansi (White-Label)
session_start();

require_once '../config/database.php';
require_once '../config/app.php';

// PANGGIL MIDDLEWARE (Menendang user yang belum login)
require_once '../app/Helpers/auth.php';

// PROTEKSI KHUSUS ADMIN (Menendang user yang bukan Admin ke dashboard)
require_admin();

$success_msg = "";
$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_pengaturan') {
    try {
        $nama_app = htmlspecialchars($_POST['nama_app']);
        $nama_cu = htmlspecialchars($_POST['nama_cu']);
        $alamat_cu = htmlspecialchars($_POST['alamat_cu']);
        $warna_primer = htmlspecialchars($_POST['warna_primer']);
        $warna_hover = htmlspecialchars($_POST['warna_hover']);

        // Logika Upload Logo Aplikasi
        $logo_app_path = $app_config['logo_app'];
        if (isset($_FILES['logo_app']) && $_FILES['logo_app']['error'] == 0) {
            $ext = pathinfo($_FILES['logo_app']['name'], PATHINFO_EXTENSION);
            $target = 'assets/img/logo_app_custom.' . $ext;
            if (move_uploaded_file($_FILES['logo_app']['tmp_name'], $target)) {
                $logo_app_path = $target;
            }
        }

        // Logika Upload Logo Koperasi/CU
        $logo_cu_path = $app_config['logo_cu'];
        if (isset($_FILES['logo_cu']) && $_FILES['logo_cu']['error'] == 0) {
            $ext = pathinfo($_FILES['logo_cu']['name'], PATHINFO_EXTENSION);
            $target = 'assets/img/logo_cu_custom.' . $ext;
            if (move_uploaded_file($_FILES['logo_cu']['tmp_name'], $target)) {
                $logo_cu_path = $target;
            }
        }

        // Eksekusi Update ke Database Lokal
        $stmt = $pdo_lokal->prepare("
            UPDATE sys_pengaturan 
            SET nama_app = ?, nama_cu = ?, alamat_cu = ?, warna_primer = ?, warna_hover = ?, logo_app = ?, logo_cu = ? 
            WHERE id = 1
        ");
        $stmt->execute([$nama_app, $nama_cu, $alamat_cu, $warna_primer, $warna_hover, $logo_app_path, $logo_cu_path]);
        
        $success_msg = "Pengaturan berhasil diperbarui! Perubahan tema telah diterapkan ke seluruh aplikasi.";
        
        // Tarik ulang konfigurasi terbaru agar langsung terlihat perubahannya di halaman ini
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
        }

    } catch (PDOException $e) {
        $error_msg = "Gagal memperbarui pengaturan: " . htmlspecialchars($e->getMessage());
    }
}

// Konfigurasi Halaman untuk Layout
$page_title = "Pengaturan Instansi";
$show_sidebar = true;
$active_menu = "pengaturan";

// Memanggil View untuk menampilkan UI ke layar
require_once '../app/Views/pengaturan.php';
?>