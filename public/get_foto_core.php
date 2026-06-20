<?php
// public/get_foto_core.php - Endpoint khusus merender Foto BLOB dari Core
session_start();
require_once '../config/database.php';

// Jika tidak ada No_BA yang dikirim, tampilkan placeholder
if (!isset($_GET['no_ba'])) {
    header("Content-Type: image/jpeg");
    readfile('assets/img/placeholder_foto.jpg');
    exit;
}

$no_ba = htmlspecialchars($_GET['no_ba']);

try {
    // Kita HANYA memanggil kolom Foto untuk menghemat memori
    $stmt = $pdo_core->prepare("SELECT Foto FROM m_anggota WHERE No_BA = ?");
    $stmt->execute([$no_ba]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && !empty($row['Foto'])) {
        // Outputkan BLOB sebagai gambar JPEG
        header("Content-Type: image/jpeg");
        echo $row['Foto'];
    } else {
        // Jika kolom Foto kosong di database, tampilkan placeholder
        header("Content-Type: image/jpeg");
        readfile('assets/img/placeholder_foto.jpg');
    }
} catch (PDOException $e) {
    // Jika terjadi error database, tetap tampilkan placeholder agar UI tidak rusak
    header("Content-Type: image/jpeg");
    readfile('assets/img/placeholder_foto.jpg');
}
?>