<?php
// config/dictionary.php - Kamus Pemetaan Kode Angka Core System (CBS)

$dict_agama = [
    '0' => 'Islam', '1' => 'Katolik', '2' => 'Kristen Protestan', 
    '3' => 'Hindu', '4' => 'Budha', '5' => 'Konguchu', 
    '6' => 'Aliran Kepercayaan', '7' => 'Organisasi'
];

$dict_pendidikan = [
    '0' => 'Tidak/Belum Sekolah', '1' => 'SD', '2' => 'SMP', 
    '3' => 'SMU/SMA', '4' => 'STM', '5' => 'SMK/SMEA/SMIP/SPG', 
    '6' => 'Diploma 1', '7' => 'Diploma 3', '8' => 'Sarjana/Diploma 4', 
    '9' => 'Magister/S2', '10' => 'Doktoral/S3'
];

$dict_jns_kelamin = [
    '0' => 'Laki-laki', '1' => 'Perempuan', '2' => 'Organisasi'
];

$dict_status_nikah = [
    '0' => 'Kawin', '1' => 'Belum Kawin', '2' => 'Cerai Hidup', '3' => 'Cerai Mati'
];

$dict_jenis_id = [
    '0' => 'NIK', '1' => 'KTP', '2' => 'SIM', '3' => 'Paspor', 
    '4' => 'Badan Hukum', '5' => 'Akta Kelahiran'
];

$dict_status_tinggal = [
    '0' => 'Milik Sendiri', '1' => 'Sewa', '2' => 'Menumpang', '3' => 'Ikut Orang Tua'
];

// KAMUS SANDI TRANSAKSI SIMPANAN KEANGGOTAAN (tr_anggota)
$dict_sandi_anggota = [
    '01' => 'SETORAN TUNAI', '02' => 'PENARIKAN TUNAI', '03' => 'KOREKSI LEBIH TARIK TUNAI',
    '04' => 'KOREKSI LEBIH SETOR TUNAI', '05' => 'SETORAN TUNAI PENYESUAIAN',
    '06' => 'SETORAN PEMINDAHBUKUAN', '07' => 'TRANSFER BANK', '08' => 'PEMBAGIAN SHU', '09' => 'PENUTUPAN ANGGOTA TUNAI',
    '10' => 'PENARIKAN NON TUNAI', '11' => 'KOR. LEBIH TARIK PEMINDAHBUKUAN',
    '12' => 'KOR. LEBIH SETOR PEMINDAHBUKUAN', '13' => 'PENUTUPAN ANGGOTA NON TUNAI',
    '14' => 'SET PENYESUAIAN PEMINDAHBUKUAN', '15' => 'PEMB. TUNAI DENDA KURANG',
    '16' => 'PEMB. DENDA KURANG NON TUNAI', '17' => 'POTONGAN GAJI'
];

// KAMUS SANDI TRANSAKSI SIMPANAN HARIAN (tr_simpananharian)
$dict_sandi_simpanan = [
    '01' => 'SET TUNAI', '02' => 'JASA SIMPANAN HARIAN', '03' => 'PENARIKAN TUNAI',
    '04' => 'JASA SIMPANAN BERJANGKA', '06' => 'ADMINISTRASI', '08' => 'TARIK UNTUK PEMINDAHBUKUAN',
    '10' => 'KOR. LEBIH SETOR TUNAI', '11' => 'KOR. LEBIH TARIK TUNAI',
    '12' => 'PENUTUPAN REKENING TUNAI', '13' => 'TRANSFER BANK',
    '14' => 'KOR. LEBIH SETOR PEMINDAHBUKUAN', '15' => 'KOR. LEBIH TARIK PEMINDAHBUKUAN',
    '16' => 'SETORAN PEMINDAHBUKUAN', '17' => 'PENUTUPAN REKENING NON TUNAI'
];

// KAMUS SANDI TRANSAKSI PINJAMAN (tr_pinjaman)
$dict_sandi_pinjaman = [
    '00' => 'PENCAIRAN PINJAMAN', '01' => 'SETORAN TUNAI', '03' => 'PEMB. TUNAI JASA KURANG', 
    '04' => 'KOREKSI LEBIH SETOR TUNAI', '05' => 'KOREKSI KURANG SETOR TUNAI', '06' => 'TRANSFER BANK', 
    '07' => 'SETORAN PEMINDAHBUKUAN', '08' => 'PELUNASAN TUNAI PINJAMAN', '09' => 'KOREKSI LEBIH SETOR PEMINDAHBUKUAN',
    '10' => 'KOREKSI KURANG SETOR PEMINDAHBUKUAN', '11' => 'PEMB. JASA KURANG NON TUNAI',
    '12' => 'PELUNASAN PINJAMAN NON TUNAI', '14' => 'POTONGAN GAJI', '15' => 'JUDI'
];

// Fungsi bantuan untuk mencegah error jika kode tidak ditemukan di kamus
function get_dict_value($array, $key, $default = '-') {
    if ($key === null || $key === '') return $default;
    return isset($array[$key]) ? $array[$key] : $key; 
}
?>