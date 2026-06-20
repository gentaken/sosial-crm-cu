<?php
// app/Helpers/auth.php - Middleware Keamanan & Pengecekan Sesi

// Pastikan session sudah berjalan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['user_name']) || empty($_SESSION['user_name'])) {
    // Jika belum, tendang ke halaman login
    header("Location: login.php");
    exit;
}

// Fungsi bantuan untuk mengecek apakah user saat ini adalah Admin
function is_admin() {
    // Asumsi User_Type '1' atau 'Admin' di database Core Anda (sesuaikan nantinya)
    return (isset($_SESSION['user_type']) && (strtolower($_SESSION['user_type']) === 'admin' || $_SESSION['user_type'] == '1'));
}

// Fungsi proteksi khusus halaman Admin
function require_admin() {
    if (!is_admin()) {
        // Jika bukan admin tapi mencoba akses halaman admin, tendang ke dashboard
        header("Location: dashboard.php?error=access_denied");
        exit;
    }
}
?>