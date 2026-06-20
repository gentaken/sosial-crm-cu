<?php
// config/database.php
date_default_timezone_set('Asia/Jakarta');

// Konfigurasi Mockup Database Core (CBS)
$host_core = 'sriya0323.cusawiran.net';
$db_core   = 'cuso_cusawiran';
$user_core = 'root';
$pass_core = 'swrdb@123456';

// Konfigurasi Database Lokal (CRM Sosial)
$host_lokal = 'db';
$db_lokal   = 'crm_anggota_cu';
$user_lokal = 'root';
$pass_lokal = 'dev_root_pass';

try {
    // Koneksi ke Core System (Hanya Baca)
    $pdo_core = new PDO("mysql:host=$host_core;dbname=$db_core", $user_core, $pass_core);
    $pdo_core->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Koneksi ke Database Lokal (Baca & Tulis)
    $pdo_lokal = new PDO("mysql:host=$host_lokal;dbname=$db_lokal", $user_lokal, $pass_lokal);
    $pdo_lokal->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage());
}
?>