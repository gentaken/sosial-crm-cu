<?php
// public/login.php - Modul Otentikasi Terintegrasi Core System (CBS)
session_start();

// Jika user sudah login, langsung lempar ke dashboard
if (isset($_SESSION['user_name'])) {
    header("Location: dashboard.php");
    exit;
}

require_once '../config/database.php';
require_once '../config/app.php';

$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        try {
            // Kueri validasi langsung ke database Core (m_user) menggunakan AES_DECRYPT
            $stmt = $pdo_core->prepare("
                SELECT User_Name, Nama_Lengkap, User_Type 
                FROM m_user 
                WHERE User_Name = ? 
                AND AES_DECRYPT(User_Password, 'CUSO') = ?
                AND Status_User = 'Aktif' -- Asumsi ada field status, sesuaikan jika berbeda
            ");
            $stmt->execute([$username, $password]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Berhasil Login! Buat sesi
                $_SESSION['user_name'] = $user['User_Name'];
                $_SESSION['nama_lengkap'] = $user['Nama_Lengkap'];
                $_SESSION['user_type'] = $user['User_Type']; // Untuk Role-Based Access Control
                
                header("Location: dashboard.php");
                exit;
            } else {
                $error_msg = "Username atau Password salah, atau akun tidak aktif.";
            }
        } catch (PDOException $e) {
            $error_msg = "Terjadi kesalahan otentikasi: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $error_msg = "Harap isi Username dan Password.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | <?= htmlspecialchars($app_config['nama_app']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bs-primary: <?= $app_config['warna_primer'] ?>;
        }
        body { background-color: #f8f9fa; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .text-primary { color: <?= $app_config['warna_primer'] ?> !important; }
        .btn-primary { background-color: <?= $app_config['warna_primer'] ?>; border-color: <?= $app_config['warna_primer'] ?>; }
        .btn-primary:hover { background-color: <?= $app_config['warna_hover'] ?>; border-color: <?= $app_config['warna_hover'] ?>; }
        .login-card { width: 100%; max-width: 400px; border-radius: 15px; overflow: hidden; }
    </style>
</head>
<body>

<div class="container px-3">
    <div class="card shadow-lg border-0 login-card mx-auto">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <img src="<?= htmlspecialchars($app_config['logo_app']) ?>" alt="Logo App" height="60" class="mb-3">
                <h4 class="fw-bold text-primary mb-1"><?= htmlspecialchars($app_config['nama_app']) ?></h4>
                <p class="text-muted small"><?= htmlspecialchars($app_config['nama_cu']) ?></p>
            </div>

            <?php if ($error_msg): ?>
                <div class="alert alert-danger py-2 small text-center"><?= $error_msg ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Username (CBS)</label>
                    <input type="text" class="form-control form-control-lg fs-6" name="username" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Password</label>
                    <input type="password" class="form-control form-control-lg fs-6" name="password" required>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg fs-6 fw-bold">Masuk Sistem</button>
                </div>
            </form>
            
            <div class="text-center mt-4">
                <small class="text-muted">Akses ini terintegrasi dengan Core Banking System.</small>
            </div>
        </div>
    </div>
</div>

</body>
</html>