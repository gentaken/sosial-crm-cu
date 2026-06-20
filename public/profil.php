<?php
// public/profil.php - Logika Detail Anggota
session_start();

require_once '../config/database.php';
require_once '../config/app.php';
require_once '../config/dictionary.php'; 

// PANGGIL MIDDLEWARE
require_once '../app/Helpers/auth.php';

if (!isset($_GET['no_ba'])) {
    header("Location: index.php");
    exit;
}

$no_ba = htmlspecialchars($_GET['no_ba']);
$data_core = [];
$error_msg = "";
$success_msg = "";

// ==============================================================================
// 0. HANDLE POST REQUEST (TAMBAH DATA LOKAL)
// ==============================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    // Action Tambah Keluarga
    if ($_POST['action'] == 'tambah_keluarga') {
        $nik = htmlspecialchars(trim($_POST['nik']));
        $nama_kel = htmlspecialchars(trim($_POST['nama']));
        $hubungan = htmlspecialchars(trim($_POST['hubungan']));
        $tempat_lahir = htmlspecialchars(trim($_POST['tempat_lahir']));
        $tgl_lahir = !empty($_POST['tgl_lahir']) ? $_POST['tgl_lahir'] : null;
        $agama = htmlspecialchars(trim($_POST['agama']));
        $pendidikan = htmlspecialchars(trim($_POST['pendidikan']));
        $no_telp_wa = htmlspecialchars(trim($_POST['no_telp_wa']));
        $pekerjaan = htmlspecialchars(trim($_POST['pekerjaan']));
        $nama_instansi = htmlspecialchars(trim($_POST['nama_instansi']));
        $jabatan = htmlspecialchars(trim($_POST['jabatan']));
        $penghasilan = !empty($_POST['penghasilan']) ? str_replace(['Rp', '.', ' '], '', $_POST['penghasilan']) : 0;

        try {
            $stmt_ins = $pdo_lokal->prepare("
                INSERT INTO t_keluarga (
                    no_ba_utama, nik, nama, hubungan, tempat_lahir, tgl_lahir, 
                    agama, pendidikan, no_telp_wa, pekerjaan, nama_instansi, jabatan, penghasilan_perbulan
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt_ins->execute([
                $no_ba, $nik, $nama_kel, $hubungan, $tempat_lahir, $tgl_lahir, 
                $agama, $pendidikan, $no_telp_wa, $pekerjaan, $nama_instansi, $jabatan, $penghasilan
            ]);
            $success_msg = "Data prospek/keluarga berhasil ditambahkan ke database lokal!";
        } catch (PDOException $e) {
            $error_msg = "Gagal menambah data keluarga: " . htmlspecialchars($e->getMessage());
        }
    }
    
    // Action Tambah Organisasi (Tab 6 - DSS Oriented)
    if ($_POST['action'] == 'tambah_organisasi') {
        $nama_org = htmlspecialchars(trim($_POST['nama_organisasi']));
        $id_kategori = (int) $_POST['id_kategori'];
        $id_jabatan = (int) $_POST['id_jabatan'];
        $id_wilayah = (int) $_POST['id_wilayah'];
        $tahun_mulai = (int) $_POST['tahun_mulai'];
        $tahun_selesai = htmlspecialchars(trim($_POST['tahun_selesai']));
        $keterangan = htmlspecialchars(trim($_POST['keterangan']));
        
        $file_sk_name = null;
        if (isset($_FILES['file_bukti_sk']) && $_FILES['file_bukti_sk']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['file_bukti_sk']['tmp_name'];
            $file_orig = $_FILES['file_bukti_sk']['name'];
            $ext = pathinfo($file_orig, PATHINFO_EXTENSION);
            
            $allowed_ext = ['pdf', 'jpg', 'jpeg', 'png'];
            if (in_array(strtolower($ext), $allowed_ext)) {
                $file_sk_name = "sk_" . $no_ba . "_" . time() . "." . $ext;
                $target_path = "../public/uploads/sk/" . $file_sk_name;
                
                if (!is_dir("../public/uploads/sk/")) { 
                    mkdir("../public/uploads/sk/", 0777, true); 
                }
                move_uploaded_file($file_tmp, $target_path);
            }
        }

        try {
            $stmt_org = $pdo_lokal->prepare("
                INSERT INTO t_organisasi (
                    no_ba, nama_organisasi, id_kategori, id_jabatan, 
                    id_wilayah, tahun_mulai, tahun_selesai, keterangan, file_bukti_sk
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt_org->execute([
                $no_ba, $nama_org, $id_kategori, $id_jabatan, 
                $id_wilayah, $tahun_mulai, $tahun_selesai, $keterangan, $file_sk_name
            ]);
            $success_msg = "Pengalaman organisasi berhasil disimpan!";
        } catch (PDOException $e) {
            $error_msg = "Gagal menambah data organisasi: " . htmlspecialchars($e->getMessage());
        }
    }

    // Action Tambah Diklat CU (Tab 7 - DSS Oriented)
    if ($_POST['action'] == 'tambah_diklat') {
        $id_diklat = (int) $_POST['id_diklat'];
        $tanggal_pelaksanaan = $_POST['tanggal_pelaksanaan'];
        $penyelenggara = htmlspecialchars(trim($_POST['penyelenggara']));
        
        $file_sertifikat_name = null;
        if (isset($_FILES['file_sertifikat']) && $_FILES['file_sertifikat']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['file_sertifikat']['tmp_name'];
            $file_orig = $_FILES['file_sertifikat']['name'];
            $ext = pathinfo($file_orig, PATHINFO_EXTENSION);
            
            $allowed_ext = ['pdf', 'jpg', 'jpeg', 'png'];
            if (in_array(strtolower($ext), $allowed_ext)) {
                $file_sertifikat_name = "sertifikat_" . $no_ba . "_" . time() . "." . $ext;
                $target_path = "../public/uploads/sertifikat/" . $file_sertifikat_name;
                
                if (!is_dir("../public/uploads/sertifikat/")) { 
                    mkdir("../public/uploads/sertifikat/", 0777, true); 
                }
                move_uploaded_file($file_tmp, $target_path);
            }
        }

        try {
            $stmt_diklat_ins = $pdo_lokal->prepare("
                INSERT INTO t_diklat_anggota (
                    no_ba, id_diklat, tanggal_pelaksanaan, penyelenggara, file_sertifikat
                ) VALUES (?, ?, ?, ?, ?)
            ");
            $stmt_diklat_ins->execute([
                $no_ba, $id_diklat, $tanggal_pelaksanaan, $penyelenggara, $file_sertifikat_name
            ]);
            $success_msg = "Riwayat Pendidikan/Diklat CU berhasil ditambahkan!";
        } catch (PDOException $e) {
            $error_msg = "Gagal menambah data diklat: " . htmlspecialchars($e->getMessage());
        }
    }
}

// ==============================================================================
// 1. PENARIKAN DATA MASTER DARI CORE (CBS)
// ==============================================================================
try {
    $stmt_core = $pdo_core->prepare("
        SELECT 
            a.No_BA, a.Kode_Cabang, c.Nama_Cabang, a.Kode_Jenis, a.Tgl_Masuk, a.Tgl_Penggunaan_Mobile, a.Status_Penggunaan_Mobile,
            a.No_KK, a.Jenis_ID, a.No_ID, a.Nama, a.Jns_Kelamin, a.Tempat_Lahir, a.Tgl_Lahir, 
            a.Agama, a.Status_Perkawinan, 
            a.Alamat, a.No, a.RT, a.RW, a.Kelurahan, a.Kecamatan, a.Kota, a.Kode_Pos, 
            a.No_Telp, a.No_HP, a.No_SMS_Gateway, a.Email, 
            a.Status_Tempat_Tinggal, a.Alamat_Tinggal, a.No_Tinggal, a.RT_Tinggal, a.RW_Tinggal, 
            a.Kelurahan_Tinggal, a.Kecamatan_Tinggal, a.Kota_Tinggal, a.Kode_Pos_Tinggal, 
            a.Pendidikan_Terakhir, a.Pekerjaan, a.Instansi, a.Alamat_Instansi, a.No_Telp_Instansi, 
            a.Tgl_Masuk_Karyawan, a.Divisi, a.Besar_Gaji, a.Sisa_Gaji, 
            a.Nama_Bank, a.No_Rekening_Bank, 
            a.Nama_Ahli_Waris1, a.Hubungan_Ahli_Waris1, a.Tempat_Lahir_Ahli_Waris1, a.Tgl_Lahir_Ahli_Waris1,
            a.Nama_Ahli_Waris2, a.Hubungan_Ahli_Waris2, a.Tempat_Lahir_Ahli_Waris2, a.Tgl_Lahir_Ahli_Waris2,
            a.Nama_Ahli_Waris3, a.Hubungan_Ahli_Waris3, a.Tempat_Lahir_Ahli_Waris3, a.Tgl_Lahir_Ahli_Waris3,
            a.Nama_Ahli_Waris4, a.Hubungan_Ahli_Waris4, a.Tempat_Lahir_Ahli_Waris4, a.Tgl_Lahir_Ahli_Waris4,
            a.Nama_Gadis_Ibu_Kandung, a.Status_Keanggotaan, 
            a.AA_Saldo_SP, a.AA_Saldo_SW, a.AA_Saldo_SS, a.AA_Saldo_DS
        FROM m_anggota a
        LEFT JOIN m_cabang c ON TRIM(a.Kode_Cabang) = TRIM(c.Kode_Cabang)
        WHERE TRIM(a.No_BA) = ?
    ");
    $stmt_core->execute([trim($no_ba)]);
    $data_core = $stmt_core->fetch(PDO::FETCH_ASSOC);

    if (!$data_core) {
        die("Data anggota tidak ditemukan di Core System.");
    }
} catch (PDOException $e) {
    die("Terjadi kesalahan sistem Core: " . htmlspecialchars($e->getMessage()));
}

// ==============================================================================
// 2. AUTO-DETECT & SYNC PEKERJAAN (Database Lokal)
// ==============================================================================
try {
    $stmt_cek = $pdo_lokal->prepare("SELECT * FROM t_pekerjaan WHERE no_ba = ? AND is_active = 1");
    $stmt_cek->execute([$no_ba]);
    $pekerjaan_aktif = $stmt_cek->fetch(PDO::FETCH_ASSOC);
    
    $core_job = $data_core['Pekerjaan'] ?: '-';
    $core_instansi = $data_core['Instansi'] ?: '-';
    $core_alamat_instansi = $data_core['Alamat_Instansi'] ?: '-';
    $core_divisi = $data_core['Divisi'] ?: '-';
    $core_gaji = (float) $data_core['Besar_Gaji'];

    $perlu_sinkronisasi = false;

    if (!$pekerjaan_aktif) {
        $perlu_sinkronisasi = true;
    } else {
        if (
            $pekerjaan_aktif['pekerjaan_baku'] !== $core_job ||
            $pekerjaan_aktif['nama_instansi'] !== $core_instansi ||
            $pekerjaan_aktif['alamat_instansi'] !== $core_alamat_instansi ||
            $pekerjaan_aktif['jabatan'] !== $core_divisi ||
            $pekerjaan_aktif['pendapatan_utama'] != $core_gaji
        ) {
            $perlu_sinkronisasi = true;
        }
    }

    if ($perlu_sinkronisasi) {
        $pdo_lokal->beginTransaction();
        
        $stmt_arsip = $pdo_lokal->prepare("UPDATE t_pekerjaan SET is_active = 0 WHERE no_ba = ?");
        $stmt_arsip->execute([$no_ba]);
        
        $bawa_pend_tambahan = $pekerjaan_aktif ? $pekerjaan_aktif['pendapatan_tambahan'] : 0;
        $bawa_biaya_hidup = $pekerjaan_aktif ? $pekerjaan_aktif['rincian_biaya_hidup'] : null;

        $stmt_insert = $pdo_lokal->prepare("
            INSERT INTO t_pekerjaan (
                no_ba, pekerjaan_baku, nama_instansi, alamat_instansi, jabatan, 
                pendapatan_utama, pendapatan_tambahan, rincian_biaya_hidup, is_active
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)
        ");
        $stmt_insert->execute([
            $no_ba, $core_job, $core_instansi, $core_alamat_instansi, $core_divisi, 
            $core_gaji, $bawa_pend_tambahan, $bawa_biaya_hidup
        ]);
        
        $pdo_lokal->commit();
        
        $stmt_cek->execute([$no_ba]);
        $pekerjaan_aktif = $stmt_cek->fetch(PDO::FETCH_ASSOC);
    }

    $stmt_histori = $pdo_lokal->prepare("SELECT * FROM t_pekerjaan WHERE no_ba = ? AND is_active = 0 ORDER BY created_at DESC");
    $stmt_histori->execute([$no_ba]);
    $pekerjaan_histori = $stmt_histori->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {}

// ==============================================================================
// 3. TARIK TRANSAKSI SIMPANAN KEANGGOTAAN DARI CORE
// ==============================================================================
$trx_anggota = [];
try {
    $stmt_tr_anggota = $pdo_core->prepare("
        SELECT * FROM (
            SELECT Tgl_Transaksi, Kode_Sandi, Jml_SP, Jml_SW, Jml_SS, Saldo_SP, Saldo_SW, Saldo_SS, Keterangan 
            FROM tr_anggota 
            WHERE TRIM(No_BA) = ? 
            ORDER BY Tgl_Transaksi DESC LIMIT 50
        ) AS T1 ORDER BY Tgl_Transaksi ASC
    ");
    $stmt_tr_anggota->execute([trim($no_ba)]);
    $trx_anggota = $stmt_tr_anggota->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {}

// ==============================================================================
// 4. TARIK REKENING SIMPANAN HARIAN
// ==============================================================================
$pure_simpanan_harian = [];
$sh_berjangka_display = [];
$trx_simpanan_harian = [];

try {
    $stmt_sh = $pdo_core->prepare("
        SELECT 
            sh.No_RekeningSH, sh.Tgl_Masuk_SH, sh.Kode_Golongan, g.Nama_Golongan, 
            sh.Saldo_Simpanan, sh.Status_Rekening, sh.Tgl_Keluar, sh.Alasan_Keluar, 
            sh.Status_Pengagunan, sh.Setoran_Awal, sh.Jangka_Waktu, sh.Tgl_Jatuh_tempo, 
            sh.Bunga_Yg_Berlaku, sh.Besar_Kewajiban_Simpanan
        FROM m_simpananharian sh
        LEFT JOIN m_golongansimpananharian g ON TRIM(sh.Kode_Golongan) = TRIM(g.Kode_Golongan)
        WHERE TRIM(sh.No_BA) = ?
    ");
    $stmt_sh->execute([trim($no_ba)]);
    $all_sh = $stmt_sh->fetchAll(PDO::FETCH_ASSOC);

    foreach ($all_sh as $sh) {
        $jt = $sh['Tgl_Jatuh_tempo'];
        $kewajiban = (float) $sh['Besar_Kewajiban_Simpanan'];
        $setoran_awal = (float) $sh['Setoran_Awal'];

        if ($jt === '1910-01-01' || $jt === '0000-00-00' || ($setoran_awal == 0 && $kewajiban == 0)) {
            $pure_simpanan_harian[] = $sh;
        } else {
            $sh_berjangka_display[] = $sh;
        }
    }

    if (!empty($all_sh)) {
        $stmt_tr_sh = $pdo_core->prepare("
            SELECT * FROM (
                SELECT Tgl_Transaksi, Kode_Sandi, Debit, Kredit, Saldo, Keterangan 
                FROM tr_simpananharian 
                WHERE No_RekeningSH = ? 
                ORDER BY Tgl_Transaksi DESC LIMIT 50
            ) AS T2 ORDER BY Tgl_Transaksi ASC
        ");
        foreach ($all_sh as $sh) {
            $stmt_tr_sh->execute([$sh['No_RekeningSH']]);
            $trx_simpanan_harian[$sh['No_RekeningSH']] = $stmt_tr_sh->fetchAll(PDO::FETCH_ASSOC);
        }
    }
} catch (PDOException $e) {}

// ==============================================================================
// 5. TARIK SIMPANAN BERJANGKA
// ==============================================================================
$data_simpanan_berjangka = [];
try {
    $stmt_sb = $pdo_core->prepare("
        SELECT 
            sb.No_SertifikatSB, sb.Jml_Simpanan, sb.Jangka_Waktu, sb.Suku_Bunga_Saat_Ini, 
            sb.Tgl_Mulai, sb.Status_Sertifikat, sb.Tgl_Pencairan, jsb.Jenis_Simpanan_Berjangka
        FROM m_simpananberjangka sb
        JOIN m_simpananharian sh ON TRIM(sb.No_RekeningSH) = TRIM(sh.No_RekeningSH)
        LEFT JOIN m_jenissimpananberjangka jsb ON TRIM(sb.Kode_Jenis) = TRIM(jsb.Kode_Jenis)
        WHERE TRIM(sh.No_BA) = ?
    ");
    $stmt_sb->execute([trim($no_ba)]);
    $data_simpanan_berjangka = $stmt_sb->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {}

// ==============================================================================
// 6. TARIK PINJAMAN & TRANSAKSI
// ==============================================================================
$data_pinjaman = [];
$trx_pinjaman = [];
try {
    $stmt_pj = $pdo_core->prepare("
        SELECT 
            p.No_Pinjaman, p.Tgl_Pinjam, p.Tujuan_Pinjaman, p.Suku_Bunga, p.Jangka_Waktu, 
            p.Status_Pinjaman, p.Saldo_Pinjaman, jp.Nama_Pinjaman AS Nama_Produk_Pinjaman
        FROM m_pinjaman p
        LEFT JOIN m_jenispinjaman jp ON TRIM(p.Jenis_Pinjaman) = TRIM(jp.Kode_Jenis)
        WHERE TRIM(p.No_BA) = ?
    ");
    $stmt_pj->execute([trim($no_ba)]);
    $data_pinjaman = $stmt_pj->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($data_pinjaman)) {
        $stmt_tr_pj = $pdo_core->prepare("
            SELECT * FROM (
                SELECT Tgl_Transaksi, Kode_Sandi, Angsuran, Bunga, Denda, Saldo, Keterangan 
                FROM tr_pinjaman 
                WHERE No_Pinjaman = ? 
                ORDER BY Tgl_Transaksi DESC LIMIT 50
            ) AS T3 ORDER BY Tgl_Transaksi ASC
        ");
        foreach ($data_pinjaman as $pj) {
            $stmt_tr_pj->execute([$pj['No_Pinjaman']]);
            $trx_pinjaman[$pj['No_Pinjaman']] = $stmt_tr_pj->fetchAll(PDO::FETCH_ASSOC);
        }
    }
} catch (PDOException $e) {}

// ==============================================================================
// 7. TARIK KELUARGA (TAB 4)
// ==============================================================================
$keluarga_core = [];
if (!empty($data_core['No_KK']) && $data_core['No_KK'] != '-' && $data_core['No_KK'] != '0') {
    try {
        $stmt_kel_core = $pdo_core->prepare("
            SELECT a.No_BA, a.No_ID AS NIK, a.Nama, a.Kode_Cabang, c.Nama_Cabang 
            FROM m_anggota a
            LEFT JOIN m_cabang c ON TRIM(a.Kode_Cabang) = TRIM(c.Kode_Cabang)
            WHERE a.No_KK = ? AND TRIM(a.No_BA) != ?
        ");
        $stmt_kel_core->execute([$data_core['No_KK'], trim($no_ba)]);
        $keluarga_core = $stmt_kel_core->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {}
}

$keluarga_lokal = [];
try {
    $stmt_kel_lokal = $pdo_lokal->prepare("SELECT k.* FROM t_keluarga k WHERE k.no_ba_utama = ? ORDER BY k.created_at DESC");
    $stmt_kel_lokal->execute([$no_ba]);
    $hasil_lokal = $stmt_kel_lokal->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($hasil_lokal)) {
        $stmt_cek_nik = $pdo_core->prepare("SELECT No_BA FROM m_anggota WHERE No_ID = ? LIMIT 1");
        foreach ($hasil_lokal as $kl) {
            $stmt_cek_nik->execute([$kl['nik']]);
            $core_match = $stmt_cek_nik->fetch(PDO::FETCH_ASSOC);
            
            $kl['is_anggota_core'] = false;
            $kl['core_no_ba'] = null;
            if ($core_match) {
                $kl['is_anggota_core'] = true;
                $kl['core_no_ba'] = $core_match['No_BA'];
            }
            $keluarga_lokal[] = $kl;
        }
    }
} catch (PDOException $e) {}

// ==============================================================================
// 8. DATA-READY DSS PORTOFOLIO ORGANISASI (TAB 6)
// ==============================================================================
$master_kategori = [];
$master_jabatan = [];
$master_wilayah = [];
$data_organisasi = [];
$skor_potensi = 0; 
$status_potensi = "Belum Ada Data";
$warna_potensi = "secondary";

try {
    $master_kategori = $pdo_lokal->query("SELECT * FROM m_kategori_org ORDER BY id_kategori ASC")->fetchAll(PDO::FETCH_ASSOC);
    $master_jabatan = $pdo_lokal->query("SELECT * FROM m_jabatan_org ORDER BY id_jabatan ASC")->fetchAll(PDO::FETCH_ASSOC);
    $master_wilayah = $pdo_lokal->query("SELECT * FROM m_tingkat_wilayah ORDER BY id_wilayah ASC")->fetchAll(PDO::FETCH_ASSOC);

    $stmt_org = $pdo_lokal->prepare("
        SELECT 
            t.id, t.nama_organisasi, t.tahun_mulai, t.tahun_selesai, t.keterangan, t.file_bukti_sk, t.is_verified,
            k.nama_kategori, k.bobot_nilai AS b_kat,
            j.nama_jabatan, j.bobot_nilai AS b_jab,
            w.nama_wilayah, w.bobot_nilai AS b_wil
        FROM t_organisasi t
        LEFT JOIN m_kategori_org k ON t.id_kategori = k.id_kategori
        LEFT JOIN m_jabatan_org j ON t.id_jabatan = j.id_jabatan
        LEFT JOIN m_tingkat_wilayah w ON t.id_wilayah = w.id_wilayah
        WHERE t.no_ba = ?
        ORDER BY t.tahun_mulai DESC
    ");
    $stmt_org->execute([$no_ba]);
    $data_organisasi = $stmt_org->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($data_organisasi)) {
        foreach ($data_organisasi as &$org) {
            $sub_total = ((int)$org['b_kat']) * ((int)$org['b_jab']) * ((int)$org['b_wil']);
            $org['skor_item'] = $sub_total;
            $skor_potensi += $sub_total;
        }
        unset($org);
    }

    if ($skor_potensi >= 60) { $status_potensi = "Sangat Unggul"; $warna_potensi = "success"; }
    elseif ($skor_potensi >= 20) { $status_potensi = "Potensial"; $warna_potensi = "primary"; }
    elseif ($skor_potensi > 0) { $status_potensi = "Kaderisasi Awal"; $warna_potensi = "warning"; }

} catch (PDOException $e) {
    $error_msg = "Sistem gagal memuat DSS Organisasi: " . htmlspecialchars($e->getMessage());
}

// ==============================================================================
// 9. DATA-READY DSS PENDIDIKAN / DIKLAT CU (TAB 7)
// ==============================================================================
$master_diklat = [];
$data_diklat = [];
$status_diklat = "Belum Memenuhi Syarat Minimal";
$warna_diklat = "danger";
$total_skor_diklat = 0;
$lulus_dikdas = false;

try {
    $master_diklat = $pdo_lokal->query("SELECT * FROM m_jenis_diklat ORDER BY kategori ASC, bobot_nilai ASC")->fetchAll(PDO::FETCH_ASSOC);

    $stmt_diklat = $pdo_lokal->prepare("
        SELECT 
            d.*, m.nama_diklat, m.kategori, m.bobot_nilai 
        FROM t_diklat_anggota d
        JOIN m_jenis_diklat m ON d.id_diklat = m.id_diklat
        WHERE d.no_ba = ? 
        ORDER BY d.tanggal_pelaksanaan DESC
    ");
    $stmt_diklat->execute([$no_ba]);
    $data_diklat = $stmt_diklat->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($data_diklat)) {
        foreach ($data_diklat as $diklat) {
            $total_skor_diklat += (int) $diklat['bobot_nilai'];
            if ($diklat['kategori'] === 'Wajib/Dasar') {
                $lulus_dikdas = true;
            }
        }
    }

    if ($lulus_dikdas) {
        $status_diklat = "Layak & Lulus Syarat Minimal";
        $warna_diklat = "success";
    }

} catch (PDOException $e) {
    $error_msg = "Sistem gagal memuat DSS Pendidikan/Diklat: " . htmlspecialchars($e->getMessage());
}

// Konfigurasi Halaman
$page_title = "Profil " . htmlspecialchars($data_core['Nama']);
$show_sidebar = true;
$active_menu = "pencarian"; 

function format_tgl_lahir($tgl) {
    if (empty($tgl) || $tgl == '0000-00-00' || $tgl == '1970-01-01' || $tgl == '1910-01-01') return '-';
    return date('d M Y', strtotime($tgl));
}

require_once '../app/Views/profil.php';
?>