<?php
// public/dashboard.php
session_start();
require_once '../config/database.php';
require_once '../config/app.php';

// 1. PANGGIL MIDDLEWARE (Otomatis menendang user jika belum login)
require_once '../app/Helpers/auth.php';

// Variabel Data Dummy (Nanti ditarik dari DB)
$stat_total_anggota = 1250;
$stat_dikunjungi_bulan_ini = 45;
$stat_target_kunjungan = 100;

// Konfigurasi Halaman untuk Layout
$page_title = "Beranda Dashboard";
$show_sidebar = true;
$active_menu = "dashboard"; // Untuk menandai menu yang menyala di sidebar

// 2. PANGGIL TAMPILAN VIEW
require_once '../app/Views/dashboard.php';
?>