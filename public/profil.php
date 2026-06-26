<?php
// public/profil.php - Logika Detail Anggota, DMS, LOS, & Narasi Pendampingan
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
    
    // Action Tambah Profiling & Survei Holistik
    if ($_POST['action'] == 'tambah_profiling') {
        try {
            $pdo_lokal->beginTransaction();

            $stmt_master = $pdo_lokal->prepare("
                INSERT INTO t_survei_master (no_ba, tgl_survei, nama_petugas, kondisi_rumah_aset, keharmonisan_keluarga, relasi_sosial_warga, kesimpulan_rekomendasi) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt_master->execute([
                $no_ba, $_POST['tgl_survei'], htmlspecialchars(trim($_POST['nama_petugas'])),
                htmlspecialchars(trim($_POST['kondisi_rumah_aset'])), htmlspecialchars(trim($_POST['keharmonisan_keluarga'])),
                htmlspecialchars(trim($_POST['relasi_sosial_warga'])), htmlspecialchars(trim($_POST['rekomendasi_petugas']))
            ]);
            $id_survei = $pdo_lokal->lastInsertId();

            if (isset($_POST['aset_nama']) && is_array($_POST['aset_nama'])) {
                $stmt_aset = $pdo_lokal->prepare("INSERT INTO t_survei_aset (id_survei, entitas, kategori_aset, nama_aset, kondisi_deskripsi, nilai_pasar) VALUES (?, ?, ?, ?, ?, ?)");
                for ($i = 0; $i < count($_POST['aset_nama']); $i++) {
                    if (!empty($_POST['aset_nama'][$i])) {
                        $nilai = (float)str_replace(['Rp', '.', ' '], '', $_POST['aset_nilai'][$i]);
                        $stmt_aset->execute([$id_survei, $_POST['aset_entitas'][$i], $_POST['aset_kategori'][$i], htmlspecialchars($_POST['aset_nama'][$i]), htmlspecialchars($_POST['aset_kondisi'][$i]), $nilai]);
                    }
                }
            }

            if (isset($_POST['hutang_sumber']) && is_array($_POST['hutang_sumber'])) {
                $stmt_hutang = $pdo_lokal->prepare("INSERT INTO t_survei_hutang (id_survei, entitas, sumber_kreditur, tujuan_penggunaan, sisa_outstanding, angsuran_perbulan) VALUES (?, ?, ?, ?, ?, ?)");
                for ($i = 0; $i < count($_POST['hutang_sumber']); $i++) {
                    if (!empty($_POST['hutang_sumber'][$i])) {
                        $sisa = (float)str_replace(['Rp', '.', ' '], '', $_POST['hutang_sisa'][$i]);
                        $angsuran = (float)str_replace(['Rp', '.', ' '], '', $_POST['hutang_angsuran'][$i]);
                        $stmt_hutang->execute([$id_survei, $_POST['hutang_entitas'][$i], htmlspecialchars($_POST['hutang_sumber'][$i]), htmlspecialchars($_POST['hutang_tujuan'][$i]), $sisa, $angsuran]);
                    }
                }
            }

            if (isset($_POST['cf_nama']) && is_array($_POST['cf_nama'])) {
                $stmt_cf = $pdo_lokal->prepare("INSERT INTO t_survei_cashflow (id_survei, tipe_cf, entitas, nama_item, nominal_bulanan) VALUES (?, ?, ?, ?, ?)");
                for ($i = 0; $i < count($_POST['cf_nama']); $i++) {
                    if (!empty($_POST['cf_nama'][$i])) {
                        $nominal = (float)str_replace(['Rp', '.', ' '], '', $_POST['cf_nominal'][$i]);
                        $stmt_cf->execute([$id_survei, $_POST['cf_tipe'][$i], $_POST['cf_entitas'][$i], htmlspecialchars($_POST['cf_nama'][$i]), $nominal]);
                    }
                }
            }

            if (isset($_POST['usaha_kategori']) && is_array($_POST['usaha_kategori'])) {
                $stmt_usaha = $pdo_lokal->prepare("INSERT INTO t_survei_usaha (id_survei, kategori_pekerjaan, deskripsi_skala, tgl_mulai_siklus, treatment_kegiatan, estimasi_modal_hpp, estimasi_tgl_panen, estimasi_pendapatan_kotor) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                for ($i = 0; $i < count($_POST['usaha_kategori']); $i++) {
                    if (!empty($_POST['usaha_kategori'][$i])) {
                        $modal = (float)str_replace(['Rp', '.', ' '], '', $_POST['usaha_modal'][$i]);
                        $pendapatan = (float)str_replace(['Rp', '.', ' '], '', $_POST['usaha_pendapatan'][$i]);
                        $tgl_mulai = !empty($_POST['usaha_tgl_mulai'][$i]) ? $_POST['usaha_tgl_mulai'][$i] : null;
                        $tgl_panen = !empty($_POST['usaha_tgl_panen'][$i]) ? $_POST['usaha_tgl_panen'][$i] : null;
                        $stmt_usaha->execute([$id_survei, htmlspecialchars($_POST['usaha_kategori'][$i]), htmlspecialchars($_POST['usaha_deskripsi'][$i]), $tgl_mulai, htmlspecialchars($_POST['usaha_treatment'][$i]), $modal, $tgl_panen, $pendapatan]);
                    }
                }
            }

            if (isset($_POST['rab_item']) && is_array($_POST['rab_item'])) {
                $stmt_rab = $pdo_lokal->prepare("INSERT INTO t_survei_rab (id_survei, entitas, item_rencana, estimasi_biaya) VALUES (?, ?, ?, ?)");
                for ($i = 0; $i < count($_POST['rab_item']); $i++) {
                    if (!empty($_POST['rab_item'][$i])) {
                        $biaya = (float)str_replace(['Rp', '.', ' '], '', $_POST['rab_biaya'][$i]);
                        $stmt_rab->execute([$id_survei, $_POST['rab_entitas'][$i], htmlspecialchars($_POST['rab_item'][$i]), $biaya]);
                    }
                }
            }

            $punya_bpjs = $_POST['kes_bpjs'] ?? 'Tidak';
            $punya_jiwa = $_POST['kes_jiwa'] ?? 'Tidak';
            $premi = !empty($_POST['kes_premi']) ? (float)str_replace(['Rp', '.', ' '], '', $_POST['kes_premi']) : 0;
            $up = !empty($_POST['kes_up']) ? (float)str_replace(['Rp', '.', ' '], '', $_POST['kes_up']) : 0;
            $kronis = htmlspecialchars(trim($_POST['kes_kronis']));
            
            $analisis_coverage = "Coverage Standar.";
            if ($punya_bpjs == 'Tidak' && !empty($kronis)) { $analisis_coverage = "RISIKO TINGGI: Terdapat riwayat penyakit kronis namun tidak memiliki jaminan kesehatan (BPJS)."; } 
            elseif ($punya_bpjs == 'Ya' && !empty($kronis)) { $analisis_coverage = "Risiko Terukur: Penyakit kronis tercover BPJS."; }
            if ($punya_jiwa == 'Ya' && $up > 0) { $analisis_coverage .= " Memiliki Asuransi Jiwa tambahan."; }

            $stmt_kes = $pdo_lokal->prepare("INSERT INTO t_survei_kesehatan (id_survei, punya_bpjs, kelas_bpjs_asuransi, premi_perbulan, punya_asuransi_jiwa, up_jiwa, riwayat_penyakit_umum, riwayat_penyakit_kronis, analisis_coverage) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_kes->execute([$id_survei, $punya_bpjs, htmlspecialchars($_POST['kes_kelas']), $premi, $punya_jiwa, $up, htmlspecialchars(trim($_POST['kes_umum'])), $kronis, $analisis_coverage]);

            $pdo_lokal->commit();
            $success_msg = "Data Profiling dan Evaluasi berhasil direkam!";
        } catch (PDOException $e) {
            $pdo_lokal->rollBack();
            $error_msg = "Gagal menyimpan laporan profiling: " . htmlspecialchars($e->getMessage());
        }
    }

    // Action Tambah Organisasi (Tab 6)
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
                if (!is_dir("../public/uploads/sk/")) { mkdir("../public/uploads/sk/", 0777, true); }
                move_uploaded_file($file_tmp, $target_path);
            }
        }

        try {
            $stmt_org = $pdo_lokal->prepare("INSERT INTO t_organisasi (no_ba, nama_organisasi, id_kategori, id_jabatan, id_wilayah, tahun_mulai, tahun_selesai, keterangan, file_bukti_sk) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_org->execute([$no_ba, $nama_org, $id_kategori, $id_jabatan, $id_wilayah, $tahun_mulai, $tahun_selesai, $keterangan, $file_sk_name]);
            $success_msg = "Pengalaman organisasi berhasil disimpan!";
        } catch (PDOException $e) { $error_msg = "Gagal menambah data organisasi: " . htmlspecialchars($e->getMessage()); }
    }

    // Action Tambah Diklat CU (Tab 7)
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
                if (!is_dir("../public/uploads/sertifikat/")) { mkdir("../public/uploads/sertifikat/", 0777, true); }
                move_uploaded_file($file_tmp, $target_path);
            }
        }

        try {
            $stmt_diklat_ins = $pdo_lokal->prepare("INSERT INTO t_diklat_anggota (no_ba, id_diklat, tanggal_pelaksanaan, penyelenggara, file_sertifikat) VALUES (?, ?, ?, ?, ?)");
            $stmt_diklat_ins->execute([$no_ba, $id_diklat, $tanggal_pelaksanaan, $penyelenggara, $file_sertifikat_name]);
            $success_msg = "Riwayat Pendidikan/Diklat CU berhasil ditambahkan!";
        } catch (PDOException $e) { $error_msg = "Gagal menambah data diklat: " . htmlspecialchars($e->getMessage()); }
    }

    // Action Tambah Dokumen DMS (Tab 8)
    if ($_POST['action'] == 'tambah_dokumen') {
        $kategori_dokumen = htmlspecialchars(trim($_POST['kategori_dokumen']));
        $keterangan = htmlspecialchars(trim($_POST['keterangan']));
        
        $nama_file_doc = null;
        if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['file_dokumen']['tmp_name'];
            $file_orig = $_FILES['file_dokumen']['name'];
            $ext = pathinfo($file_orig, PATHINFO_EXTENSION);
            
            $allowed_ext = ['pdf', 'jpg', 'jpeg', 'png'];
            if (in_array(strtolower($ext), $allowed_ext)) {
                $nama_file_doc = "doc_" . $no_ba . "_" . time() . "." . $ext;
                $target_path = "../public/uploads/dokumen/" . $nama_file_doc;
                if (!is_dir("../public/uploads/dokumen/")) { mkdir("../public/uploads/dokumen/", 0777, true); }
                if (move_uploaded_file($file_tmp, $target_path)) {
                    try {
                        $stmt_doc = $pdo_lokal->prepare("INSERT INTO t_dokumen_anggota (no_ba, kategori_dokumen, nama_file, keterangan) VALUES (?, ?, ?, ?)");
                        $stmt_doc->execute([$no_ba, $kategori_dokumen, $nama_file_doc, $keterangan]);
                        $success_msg = "Dokumen/Arsip digital berhasil diunggah ke sistem!";
                    } catch (PDOException $e) { $error_msg = "Gagal menyimpan data dokumen: " . htmlspecialchars($e->getMessage()); }
                }
            } else { $error_msg = "Format file tidak didukung."; }
        }
    }
}

// ==============================================================================
// 1. PENARIKAN DATA MASTER DARI CORE (CBS)
// ==============================================================================
try {
    $stmt_core = $pdo_core->prepare("SELECT a.*, c.Nama_Cabang FROM m_anggota a LEFT JOIN m_cabang c ON TRIM(a.Kode_Cabang) = TRIM(c.Kode_Cabang) WHERE TRIM(a.No_BA) = ?");
    $stmt_core->execute([trim($no_ba)]);
    $data_core = $stmt_core->fetch(PDO::FETCH_ASSOC);
    if (!$data_core) die("Data anggota tidak ditemukan di Core System.");
} catch (PDOException $e) { die("Terjadi kesalahan sistem Core: " . htmlspecialchars($e->getMessage())); }

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
        if ($pekerjaan_aktif['pekerjaan_baku'] !== $core_job || $pekerjaan_aktif['nama_instansi'] !== $core_instansi ||
            $pekerjaan_aktif['alamat_instansi'] !== $core_alamat_instansi || $pekerjaan_aktif['jabatan'] !== $core_divisi ||
            $pekerjaan_aktif['pendapatan_utama'] != $core_gaji) {
            $perlu_sinkronisasi = true;
        }
    }

    if ($perlu_sinkronisasi) {
        $pdo_lokal->beginTransaction();
        $stmt_arsip = $pdo_lokal->prepare("UPDATE t_pekerjaan SET is_active = 0 WHERE no_ba = ?");
        $stmt_arsip->execute([$no_ba]);
        $bawa_pend_tambahan = $pekerjaan_aktif ? $pekerjaan_aktif['pendapatan_tambahan'] : 0;
        $bawa_biaya_hidup = $pekerjaan_aktif ? $pekerjaan_aktif['rincian_biaya_hidup'] : null;

        $stmt_insert = $pdo_lokal->prepare("INSERT INTO t_pekerjaan (no_ba, pekerjaan_baku, nama_instansi, alamat_instansi, jabatan, pendapatan_utama, pendapatan_tambahan, rincian_biaya_hidup, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)");
        $stmt_insert->execute([$no_ba, $core_job, $core_instansi, $core_alamat_instansi, $core_divisi, $core_gaji, $bawa_pend_tambahan, $bawa_biaya_hidup]);
        $pdo_lokal->commit();
        $stmt_cek->execute([$no_ba]);
        $pekerjaan_aktif = $stmt_cek->fetch(PDO::FETCH_ASSOC);
    }

    $stmt_histori = $pdo_lokal->prepare("SELECT * FROM t_pekerjaan WHERE no_ba = ? AND is_active = 0 ORDER BY created_at DESC");
    $stmt_histori->execute([$no_ba]);
    $pekerjaan_histori = $stmt_histori->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {}

// ==============================================================================
// 3. TARIK TRANSAKSI SIMPANAN, BERJANGKA & PINJAMAN (CORE)
// ==============================================================================
$trx_anggota = []; $pure_simpanan_harian = []; $sh_berjangka_display = []; $trx_simpanan_harian = [];
$all_sh = []; $data_simpanan_berjangka = []; $data_pinjaman = []; $trx_pinjaman = [];

try {
    $stmt_tr_anggota = $pdo_core->prepare("SELECT * FROM (SELECT Tgl_Transaksi, Kode_Sandi, Jml_SP, Jml_SW, Jml_SS, Saldo_SP, Saldo_SW, Saldo_SS, Keterangan FROM tr_anggota WHERE TRIM(No_BA) = ? ORDER BY Tgl_Transaksi DESC LIMIT 50) AS T1 ORDER BY Tgl_Transaksi ASC");
    $stmt_tr_anggota->execute([trim($no_ba)]); $trx_anggota = $stmt_tr_anggota->fetchAll(PDO::FETCH_ASSOC);

    $stmt_sh = $pdo_core->prepare("SELECT sh.*, g.Nama_Golongan FROM m_simpananharian sh LEFT JOIN m_golongansimpananharian g ON TRIM(sh.Kode_Golongan) = TRIM(g.Kode_Golongan) WHERE TRIM(sh.No_BA) = ?");
    $stmt_sh->execute([trim($no_ba)]); $all_sh = $stmt_sh->fetchAll(PDO::FETCH_ASSOC);

    $stmt_sb = $pdo_core->prepare("SELECT sb.*, jsb.Jenis_Simpanan_Berjangka FROM m_simpananberjangka sb JOIN m_simpananharian sh ON TRIM(sb.No_RekeningSH) = TRIM(sh.No_RekeningSH) LEFT JOIN m_jenissimpananberjangka jsb ON TRIM(sb.Kode_Jenis) = TRIM(jsb.Kode_Jenis) WHERE TRIM(sh.No_BA) = ?");
    $stmt_sb->execute([trim($no_ba)]); $data_simpanan_berjangka = $stmt_sb->fetchAll(PDO::FETCH_ASSOC);

    $stmt_pj = $pdo_core->prepare("SELECT p.*, jp.Nama_Pinjaman AS Nama_Produk_Pinjaman FROM m_pinjaman p LEFT JOIN m_jenispinjaman jp ON TRIM(p.Jenis_Pinjaman) = TRIM(jp.Kode_Jenis) WHERE TRIM(p.No_BA) = ?");
    $stmt_pj->execute([trim($no_ba)]); $data_pinjaman = $stmt_pj->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($data_pinjaman)) {
        $stmt_tr_pj = $pdo_core->prepare("SELECT * FROM (SELECT Tgl_Transaksi, Kode_Sandi, Angsuran, Bunga, Denda, Saldo, Keterangan FROM tr_pinjaman WHERE No_Pinjaman = ? ORDER BY Tgl_Transaksi DESC LIMIT 50) AS T3 ORDER BY Tgl_Transaksi ASC");
        foreach ($data_pinjaman as $pj) { $stmt_tr_pj->execute([$pj['No_Pinjaman']]); $trx_pinjaman[$pj['No_Pinjaman']] = $stmt_tr_pj->fetchAll(PDO::FETCH_ASSOC); }
    }
} catch (PDOException $e) {}

// ==============================================================================
// 7. TARIK KELUARGA & 9. ORGANISASI & 10. DIKLAT & 11. DMS
// ==============================================================================
$keluarga_core = []; $keluarga_lokal = []; $data_organisasi = []; $data_diklat = []; $data_dokumen = [];
$master_kategori = []; $master_jabatan = []; $master_wilayah = []; $master_diklat = [];
$skor_potensi = 0; $status_potensi = "Belum Ada Data"; $warna_potensi = "secondary";
$status_diklat = "Belum Memenuhi Syarat Minimal"; $warna_diklat = "danger"; $total_skor_diklat = 0; $lulus_dikdas = false;

try {
    $stmt_kel_lokal = $pdo_lokal->prepare("SELECT * FROM t_keluarga WHERE no_ba_utama = ? ORDER BY created_at DESC");
    $stmt_kel_lokal->execute([$no_ba]); $keluarga_lokal = $stmt_kel_lokal->fetchAll(PDO::FETCH_ASSOC);

    $master_kategori = $pdo_lokal->query("SELECT * FROM m_kategori_org ORDER BY id_kategori ASC")->fetchAll(PDO::FETCH_ASSOC);
    $master_jabatan = $pdo_lokal->query("SELECT * FROM m_jabatan_org ORDER BY id_jabatan ASC")->fetchAll(PDO::FETCH_ASSOC);
    $master_wilayah = $pdo_lokal->query("SELECT * FROM m_tingkat_wilayah ORDER BY id_wilayah ASC")->fetchAll(PDO::FETCH_ASSOC);

    $stmt_org = $pdo_lokal->prepare("SELECT t.id, t.nama_organisasi, t.tahun_mulai, t.tahun_selesai, t.keterangan, t.file_bukti_sk, t.is_verified, k.nama_kategori, k.bobot_nilai AS b_kat, j.nama_jabatan, j.bobot_nilai AS b_jab, w.nama_wilayah, w.bobot_nilai AS b_wil FROM t_organisasi t LEFT JOIN m_kategori_org k ON t.id_kategori = k.id_kategori LEFT JOIN m_jabatan_org j ON t.id_jabatan = j.id_jabatan LEFT JOIN m_tingkat_wilayah w ON t.id_wilayah = w.id_wilayah WHERE t.no_ba = ? ORDER BY t.tahun_mulai DESC");
    $stmt_org->execute([$no_ba]); $data_organisasi = $stmt_org->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($data_organisasi)) {
        foreach ($data_organisasi as &$org) { $org['skor_item'] = ((int)$org['b_kat']) * ((int)$org['b_jab']) * ((int)$org['b_wil']); $skor_potensi += $org['skor_item']; }
        unset($org);
    }
    if ($skor_potensi >= 60) { $status_potensi = "Sangat Unggul"; $warna_potensi = "success"; } elseif ($skor_potensi >= 20) { $status_potensi = "Potensial"; $warna_potensi = "primary"; } elseif ($skor_potensi > 0) { $status_potensi = "Kaderisasi Awal"; $warna_potensi = "warning"; }

    $master_diklat = $pdo_lokal->query("SELECT * FROM m_jenis_diklat ORDER BY kategori ASC, bobot_nilai ASC")->fetchAll(PDO::FETCH_ASSOC);
    $stmt_diklat = $pdo_lokal->prepare("SELECT d.*, m.nama_diklat, m.kategori, m.bobot_nilai FROM t_diklat_anggota d JOIN m_jenis_diklat m ON d.id_diklat = m.id_diklat WHERE d.no_ba = ? ORDER BY d.tanggal_pelaksanaan DESC");
    $stmt_diklat->execute([$no_ba]); $data_diklat = $stmt_diklat->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($data_diklat)) {
        foreach ($data_diklat as $diklat) { $total_skor_diklat += (int) $diklat['bobot_nilai']; if ($diklat['kategori'] === 'Wajib/Dasar') { $lulus_dikdas = true; } }
    }
    if ($lulus_dikdas) { $status_diklat = "Layak & Lulus Syarat Minimal"; $warna_diklat = "success"; }

    $stmt_doc_get = $pdo_lokal->prepare("SELECT * FROM t_dokumen_anggota WHERE no_ba = ? ORDER BY tgl_upload DESC");
    $stmt_doc_get->execute([$no_ba]); $data_dokumen = $stmt_doc_get->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {}

// ==============================================================================
// 8. TARIK DATA SURVEI PROFILING (TAB 5)
// ==============================================================================
$data_survei_master = []; $detail_aset = []; $detail_hutang = []; $detail_cf = []; $detail_usaha = []; $detail_rab = []; $detail_kesehatan = [];
try {
    $stmt_master = $pdo_lokal->prepare("SELECT * FROM t_survei_master WHERE no_ba = ? ORDER BY tgl_survei DESC");
    $stmt_master->execute([$no_ba]);
    $data_survei_master = $stmt_master->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($data_survei_master)) {
        $id_survei_latest = $data_survei_master[0]['id_survei'];
        $detail_aset = $pdo_lokal->query("SELECT * FROM t_survei_aset WHERE id_survei = $id_survei_latest")->fetchAll(PDO::FETCH_ASSOC);
        $detail_hutang = $pdo_lokal->query("SELECT * FROM t_survei_hutang WHERE id_survei = $id_survei_latest")->fetchAll(PDO::FETCH_ASSOC);
        $detail_cf = $pdo_lokal->query("SELECT * FROM t_survei_cashflow WHERE id_survei = $id_survei_latest")->fetchAll(PDO::FETCH_ASSOC);
        $detail_usaha = $pdo_lokal->query("SELECT * FROM t_survei_usaha WHERE id_survei = $id_survei_latest")->fetchAll(PDO::FETCH_ASSOC);
        $detail_rab = $pdo_lokal->query("SELECT * FROM t_survei_rab WHERE id_survei = $id_survei_latest")->fetchAll(PDO::FETCH_ASSOC);
        $detail_kesehatan = $pdo_lokal->query("SELECT * FROM t_survei_kesehatan WHERE id_survei = $id_survei_latest LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {}

// ==============================================================================
// 12. ANALISIS KEUANGAN, RAPOR ANGGOTA & NARASI PENDAMPINGAN (FULL FIX)
// ==============================================================================
$rapor = [
    'total_aset_core' => 0, 'total_hutang_core' => 0,
    'total_aset_keluarga' => 0, 'total_aset_usaha' => 0,
    'total_hutang_keluarga' => 0, 'total_hutang_usaha' => 0,
    'grand_total_aset' => 0, 'grand_total_hutang' => 0,
    'der' => 0, 'der_status' => '', 'der_color' => '',
    'total_pemasukan_cf' => 0, 'total_pengeluaran_cf' => 0, 'surplus_defisit' => 0,
    'total_rab_biaya' => 0,
    'rpc_status' => '', 'rpc_color' => '',
    'badges' => [],
    'narasi_profiling' => '',
    'rekomendasi_pendampingan' => []
];

// A. Hitung Ekuitas & Kewajiban dari Core System
$rapor['total_aset_core'] += ((float)$data_core['AA_Saldo_SP'] + (float)$data_core['AA_Saldo_SW'] + (float)$data_core['AA_Saldo_SS']);
foreach($all_sh as $sh) { $rapor['total_aset_core'] += (float)$sh['Saldo_Simpanan']; }
foreach($data_simpanan_berjangka as $sb) { $rapor['total_aset_core'] += (float)$sb['Jml_Simpanan']; }
foreach($data_pinjaman as $pj) { if($pj['Status_Pinjaman'] == '0') { $rapor['total_hutang_core'] += (float)$pj['Saldo_Pinjaman']; } }

// B. Kalkulasi Variabel Form Profiling Dinamis (Lokal)
if (!empty($data_survei_master)) {
    foreach($detail_aset as $ast) { $ast['entitas'] == 'Keluarga' ? $rapor['total_aset_keluarga'] += (float)$ast['nilai_pasar'] : $rapor['total_aset_usaha'] += (float)$ast['nilai_pasar']; }
    foreach($detail_hutang as $htg) { $htg['entitas'] == 'Keluarga' ? $rapor['total_hutang_keluarga'] += (float)$htg['sisa_outstanding'] : $rapor['total_hutang_usaha'] += (float)$htg['sisa_outstanding']; }
    foreach($detail_cf as $cf) { $cf['tipe_cf'] == 'Pemasukan' ? $rapor['total_pemasukan_cf'] += (float)$cf['nominal_bulanan'] : $rapor['total_pengeluaran_cf'] += (float)$cf['nominal_bulanan']; }
    foreach($detail_rab as $rb) { $rapor['total_rab_biaya'] += (float)$rb['estimasi_biaya']; }

    $rapor['surplus_defisit'] = $rapor['total_pemasukan_cf'] - $rapor['total_pengeluaran_cf'];

    // RPC (Repayment Capacity) Status
    if ($rapor['total_pemasukan_cf'] > 0) {
        $rasio_surplus = ($rapor['surplus_defisit'] / $rapor['total_pemasukan_cf']) * 100;
        if ($rasio_surplus >= 20) { $rapor['rpc_status'] = 'Sangat Sehat (Surplus Kapasitas Baik)'; $rapor['rpc_color'] = 'success'; }
        elseif ($rasio_surplus > 0) { $rapor['rpc_status'] = 'Cukup (Surplus Tipis)'; $rapor['rpc_color'] = 'primary'; }
        else { $rapor['rpc_status'] = 'Defisit (Risiko Gagal Bayar Tinggi)'; $rapor['rpc_color'] = 'danger'; }
    } else {
        $rapor['rpc_status'] = 'Tidak Teridentifikasi Pemasukan di Arus Kas'; $rapor['rpc_color'] = 'danger';
    }
} else {
    $rapor['rpc_status'] = 'Belum Ada Data Survei Lapangan'; $rapor['rpc_color'] = 'secondary';
}

// C. Konsolidasi Final Executive Summary (DER & Total Harta)
$rapor['grand_total_aset'] = $rapor['total_aset_core'] + $rapor['total_aset_keluarga'] + $rapor['total_aset_usaha'];
$rapor['grand_total_hutang'] = $rapor['total_hutang_core'] + $rapor['total_hutang_keluarga'] + $rapor['total_hutang_usaha'];

if ($rapor['grand_total_aset'] > 0) { $rapor['der'] = ($rapor['grand_total_hutang'] / $rapor['grand_total_aset']) * 100; } 
else { $rapor['der'] = $rapor['grand_total_hutang'] > 0 ? 100 : 0; }

if ($rapor['der'] < 50) { $rapor['der_status'] = 'Sehat (Risiko Rendah)'; $rapor['der_color'] = 'success'; }
elseif ($rapor['der'] <= 80) { $rapor['der_status'] = 'Waspada (Risiko Menengah)'; $rapor['der_color'] = 'warning'; }
else { $rapor['der_status'] = 'Berisiko Tinggi (Overleverage)'; $rapor['der_color'] = 'danger'; }

// D. Algoritma Badges Rekomendasi (Dashboard Executive)
if (!empty($data_survei_master)) {
    if ($rapor['der'] < 50 && $rapor['surplus_defisit'] > 0) {
        $rapor['badges'][] = ['bg' => 'success', 'icon' => 'bi-star-fill', 'text' => 'Karakter & Kapasitas Baik'];
        $rapor['badges'][] = ['bg' => 'primary', 'icon' => 'bi-graph-up-arrow', 'text' => 'Potensi Peningkatan Kredit'];
    }
    if ($rapor['der'] > 80 || $rapor['surplus_defisit'] < 0) {
        $rapor['badges'][] = ['bg' => 'danger', 'icon' => 'bi-exclamation-triangle-fill', 'text' => 'Risiko NPL Tinggi (Butuh Restrukturisasi)'];
    }
    if ($detail_kesehatan && strpos($detail_kesehatan['analisis_coverage'], 'RISIKO TINGGI') !== false) {
        $rapor['badges'][] = ['bg' => 'warning text-dark', 'icon' => 'bi-heart-pulse-fill', 'text' => 'Rentan Risiko Medis (Tidak Punya BPJS)'];
    }
} else {
    $rapor['badges'][] = ['bg' => 'secondary', 'icon' => 'bi-question-circle', 'text' => 'Data Appraisal Kosong'];
}

// E. GENERATOR NARASI PROFILING & REKOMENDASI PENDAMPINGAN (MENTORSHIP)
$umur_anggota = date_diff(date_create($data_core['Tgl_Lahir']), date_create('today'))->y;
$jml_keluarga = count($keluarga_lokal) + count($keluarga_core);
$net_worth = ($rapor['total_aset_keluarga'] + $rapor['total_aset_usaha']) - ($rapor['total_hutang_keluarga'] + $rapor['total_hutang_usaha']);

if (!empty($data_survei_master)) {
    $pekerjaan_teks = $data_core['Pekerjaan'] ?: 'Pekerjaan Mandiri';
    $kondisi_sosial = htmlspecialchars($data_survei_master[0]['keharmonisan_keluarga']) . " dengan aktivitas sosial " . htmlspecialchars($data_survei_master[0]['relasi_sosial_warga']);
    
    $usaha_teks = "";
    if (count($detail_usaha) > 0) {
        $arr_usaha = [];
        foreach($detail_usaha as $u) { $arr_usaha[] = $u['deskripsi_skala']; }
        $usaha_teks = " Anggota juga menjalankan siklus usaha/pekerjaan berupa " . implode(", ", $arr_usaha) . ".";
    }

    // Bangun Paragraf Narasi
    $rapor['narasi_profiling'] = "Anggota atas nama <strong>" . htmlspecialchars($data_core['Nama']) . "</strong>, saat ini berusia $umur_anggota tahun dan bekerja di sektor $pekerjaan_teks." . $usaha_teks . " Beliau tercatat memiliki $jml_keluarga tanggungan/anggota keluarga. Dari kacamata sosial, profil anggota dikategorikan $kondisi_sosial. Secara ekonomi, total arus kas masuk per bulan mencapai Rp " . number_format($rapor['total_pemasukan_cf'],0,',','.') . " dengan beban pengeluaran Rp " . number_format($rapor['total_pengeluaran_cf'],0,',','.') . ", menyisakan surplus bersih sebesar Rp " . number_format($rapor['surplus_defisit'],0,',','.') . "/bulan. Total kekayaan bersih (Net Worth riil) di luar Core tercatat senilai Rp " . number_format($net_worth,0,',','.') . ". Ke depannya, anggota merencanakan beberapa harapan/RAB yang membutuhkan proyeksi dana sekitar Rp " . number_format($rapor['total_rab_biaya'],0,',','.') . ".";

    // Susun Poin Rekomendasi
    if (count($detail_usaha) > 0) {
        $rapor['rekomendasi_pendampingan'][] = ['aspek' => 'Peningkatan Usaha & Produksi', 'icon' => 'bi-graph-up-arrow', 'color' => 'success', 'saran' => 'Disarankan untuk mendampingi anggota dalam melakukan diversifikasi komoditas atau memperbaiki SOP pada siklus usahanya. Penguatan pencatatan HPP dan penjadwalan panen perlu disupervisi agar margin laba bisa ditingkatkan.'];
    } else {
        $rapor['rekomendasi_pendampingan'][] = ['aspek' => 'Penciptaan Pendapatan Baru', 'icon' => 'bi-lightbulb', 'color' => 'warning', 'saran' => 'Anggota saat ini hanya mengandalkan pendapatan tetap. Perlu didorong melalui diklat kewirausahaan CU untuk memulai usaha sampingan berskala mikro demi menambah keran penghasilan keluarga.'];
    }

    if ($detail_kesehatan && $detail_kesehatan['punya_bpjs'] == 'Tidak') {
        $rapor['rekomendasi_pendampingan'][] = ['aspek' => 'Jaring Pengaman Sosial (Kesehatan)', 'icon' => 'bi-heart-pulse', 'color' => 'danger', 'saran' => 'Prioritas Utama: Edukasi dan bantu anggota untuk segera mendaftar BPJS Kesehatan. Ketiadaan jaminan ini sangat berisiko menghancurkan ekonomi keluarga jika terjadi sakit.'];
    }

    if ($rapor['total_rab_biaya'] > 0) {
        $max_angsuran = $rapor['surplus_defisit'] > 0 ? $rapor['surplus_defisit'] * 0.40 : 0;
        $aset_agunan = 0;
        foreach($detail_aset as $ast) { if(in_array($ast['kategori_aset'], ['Aset Tetap (Tanah/Rumah)', 'Kendaraan', 'Peralatan/Mesin'])) { $aset_agunan += (float)$ast['nilai_pasar']; } }
        $max_plafon = $aset_agunan * 0.70;

        $saran_kredit = "Anggota memiliki target RAB Rp " . number_format($rapor['total_rab_biaya'],0,',','.') . ". ";
        if ($rapor['surplus_defisit'] > 0 && $max_angsuran > 0) {
            $saran_kredit .= "Jika difasilitasi produk pinjaman CU, <strong>batas aman angsuran maksimal adalah Rp " . number_format($max_angsuran,0,',','.') . "/bulan</strong> (40% dari surplus kas). Berdasarkan nilai aset riil yang dijaminkan, <strong>rekomendasi maksimal plafon kredit adalah Rp " . number_format($max_plafon,0,',','.') . "</strong>.";
        } else {
            $saran_kredit .= "TIDAK DIREKOMENDASIKAN mengambil pinjaman saat ini karena arus kas mengalami defisit atau tidak ada surplus yang mencukupi untuk angsuran.";
        }
        $rapor['rekomendasi_pendampingan'][] = ['aspek' => 'Analisis Kapasitas Pinjaman & Pencapaian RAB', 'icon' => 'bi-safe', 'color' => 'primary', 'saran' => $saran_kredit];
    }
} else {
    $rapor['narasi_profiling'] = "Belum ada data survei atau profiling lapangan yang direkam untuk anggota ini. Silakan agendakan kunjungan pendampingan pertama untuk membuka kunci analisis data.";
}

// Konfigurasi Halaman
$page_title = "Profil " . htmlspecialchars($data_core['Nama']);
$show_sidebar = true;
$active_menu = "pencarian"; 

function format_tgl_lahir($tgl) {
    if (empty($tgl) || $tgl == '0000-00-00' || $tgl == '1970-01-01' || $tgl == '1910-01-01') return '-';
    return date('d M Y', strtotime($tgl));
}

// LEMPAR SEMUA VARIABEL KE VIEW
require_once '../app/Views/profil.php';
?>