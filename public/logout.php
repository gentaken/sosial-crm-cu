<?php
// public/logout.php - Modul Keluar Sistem
session_start();

// Hancurkan semua data session
$_SESSION = array();
session_destroy();

// Arahkan kembali ke halaman login
header("Location: login.php");
exit;
?>