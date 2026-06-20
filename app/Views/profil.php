<?php require_once '../app/Views/Layouts/header.php'; ?>
<?php require_once '../app/Views/Layouts/sidebar.php'; ?>

<main class="main-content p-3 p-md-4">
    
    <?php if ($success_msg): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $success_msg ?> <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error_msg ?> <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($data_core): ?>
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex align-items-center">
            <div class="flex-shrink-0 position-relative">
                <img src="get_foto_core.php?no_ba=<?= htmlspecialchars($data_core['No_BA']) ?>" alt="Foto Anggota" class="rounded-circle border" style="width: 70px; height: 70px; object-fit: cover; background-color:#e9ecef;">
                <?php if($data_core['Status_Keanggotaan'] == '0'): ?>
                    <span class="position-absolute bottom-0 start-100 translate-middle p-2 bg-success border border-light rounded-circle" title="Aktif"></span>
                <?php else: ?>
                    <span class="position-absolute bottom-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle" title="Keluar"></span>
                <?php endif; ?>
            </div>
            <div class="flex-grow-1 ms-3">
                <h5 class="mb-0 fw-bold"><?= htmlspecialchars($data_core['Nama']) ?></h5>
                <p class="mb-0 text-muted small">No. BA: <?= htmlspecialchars($data_core['No_BA']) ?> | NIK: <?= htmlspecialchars($data_core['No_ID']) ?></p>
                <p class="mb-0 text-muted small">
                    <i class="bi bi-geo-alt-fill text-danger"></i> <?= htmlspecialchars($data_core['Kota']) ?> | 
                    <i class="bi bi-shop text-primary ms-1"></i> Cabang: <?= htmlspecialchars($data_core['Nama_Cabang']) ?> (<?= htmlspecialchars($data_core['Kode_Cabang']) ?>)
                </p>
            </div>
            <div><a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a></div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white p-0 border-bottom">
            <ul class="nav nav-tabs px-3 pt-2" id="profilTabs" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-pribadi" type="button">1. Pribadi</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pekerjaan" type="button">2. Pekerjaan</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-portofolio" type="button">3. Portofolio</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-keluarga" type="button">4. Keluarga</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-kunjungan" type="button">5. Kunjungan</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-organisasi" type="button">6. Organisasi</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-diklat" type="button">7. Diklat CU</button></li>
            </ul>
        </div>
        
        <div class="card-body">
            <div class="tab-content">
                
                <div class="tab-pane fade show active" id="tab-pribadi" role="tabpanel">
                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2"><i class="bi bi-person-badge"></i> Identitas & Demografi</h6>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <p class="data-label">Nama Sesuai ID</p><p class="data-value"><?= htmlspecialchars($data_core['Nama']) ?></p>
                            <p class="data-label">Jenis ID & Nomor</p><p class="data-value"><?= get_dict_value($dict_jenis_id, $data_core['Jenis_ID']) ?> - <?= htmlspecialchars($data_core['No_ID']) ?></p>
                            <p class="data-label">No. Kartu Keluarga (KK)</p><p class="data-value"><?= htmlspecialchars($data_core['No_KK']) ?: '-' ?></p>
                            <p class="data-label">Nama Ibu Kandung</p><p class="data-value"><?= htmlspecialchars($data_core['Nama_Gadis_Ibu_Kandung']) ?: '<span class="text-muted fst-italic">Kosong</span>' ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="data-label">Tempat, Tanggal Lahir</p><p class="data-value"><?= htmlspecialchars($data_core['Tempat_Lahir']) ?>, <?= format_tgl_lahir($data_core['Tgl_Lahir']) ?></p>
                            <p class="data-label">Jenis Kelamin</p><p class="data-value"><?= get_dict_value($dict_jns_kelamin, $data_core['Jns_Kelamin']) ?></p>
                            <p class="data-label">Agama</p><p class="data-value"><?= get_dict_value($dict_agama, $data_core['Agama']) ?></p>
                            <p class="data-label">Pendidikan Terakhir</p><p class="data-value"><?= get_dict_value($dict_pendidikan, $data_core['Pendidikan_Terakhir']) ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="data-label">Status Perkawinan</p><p class="data-value"><?= get_dict_value($dict_status_nikah, $data_core['Status_Perkawinan']) ?></p>
                            <p class="data-label">Tanggal Masuk Anggota</p><p class="data-value"><?= date('d M Y', strtotime($data_core['Tgl_Masuk'])) ?></p>
                            <p class="data-label">Aplikasi Mobile CU</p>
                            <p class="data-value">
                                <?php if($data_core['Status_Penggunaan_Mobile'] == 1): ?>
                                    <span class="badge bg-success">Aktif (Sejak <?= format_tgl_lahir($data_core['Tgl_Penggunaan_Mobile']) ?>)</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Belum Menggunakan</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2"><i class="bi bi-geo-alt"></i> Alamat & Kontak</h6>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="data-label">Alamat Sesuai KTP</p>
                            <p class="data-value mb-2">
                                <?= htmlspecialchars($data_core['Alamat']) ?> No. <?= htmlspecialchars($data_core['No']) ?><br>
                                RT <?= htmlspecialchars($data_core['RT']) ?> / RW <?= htmlspecialchars($data_core['RW']) ?>, Kel. <?= htmlspecialchars($data_core['Kelurahan']) ?><br>
                                Kec. <?= htmlspecialchars($data_core['Kecamatan']) ?>, <?= htmlspecialchars($data_core['Kota']) ?> - <?= htmlspecialchars($data_core['Kode_Pos']) ?>
                            </p>
                            <p class="data-label">Status Tempat Tinggal</p>
                            <p class="data-value"><?= get_dict_value($dict_status_tinggal, $data_core['Status_Tempat_Tinggal']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="data-label">Alamat Domisili</p>
                            <p class="data-value mb-2">
                                <?= htmlspecialchars($data_core['Alamat_Tinggal']) ?> No. <?= htmlspecialchars($data_core['No_Tinggal']) ?><br>
                                RT <?= htmlspecialchars($data_core['RT_Tinggal']) ?> / RW <?= htmlspecialchars($data_core['RW_Tinggal']) ?>, Kel. <?= htmlspecialchars($data_core['Kelurahan_Tinggal']) ?><br>
                                Kec. <?= htmlspecialchars($data_core['Kecamatan_Tinggal']) ?>, <?= htmlspecialchars($data_core['Kota_Tinggal']) ?> - <?= htmlspecialchars($data_core['Kode_Pos_Tinggal']) ?>
                            </p>
                            <p class="data-label">Kontak & Telekomunikasi</p>
                            <p class="data-value">
                                <i class="bi bi-telephone text-secondary me-1"></i> Telp: <?= htmlspecialchars($data_core['No_Telp']) ?: '-' ?><br>
                                <i class="bi bi-phone text-secondary me-1"></i> HP/WA: <?= htmlspecialchars($data_core['No_HP']) ?: '-' ?> <br>
                                <i class="bi bi-envelope text-secondary me-1"></i> Email: <?= htmlspecialchars($data_core['Email']) ?: '-' ?>
                            </p>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2"><i class="bi bi-people"></i> Data Ahli Waris</h6>
                    <div class="row">
                        <?php 
                        $ada_ahli_waris = false;
                        for($i=1; $i<=4; $i++): 
                            $nama_aw = trim($data_core['Nama_Ahli_Waris'.$i]);
                            if(!empty($nama_aw) && $nama_aw != '-'):
                                $ada_ahli_waris = true;
                        ?>
                            <div class="col-md-6 mb-3">
                                <div class="p-3 border rounded shadow-sm bg-light">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($nama_aw) ?></h6>
                                        <span class="badge bg-secondary">Waris <?= $i ?></span>
                                    </div>
                                    <div class="small text-muted">
                                        <div class="row mb-1">
                                            <div class="col-4">Hubungan</div>
                                            <div class="col-8 fw-bold text-dark">: <?= htmlspecialchars($data_core['Hubungan_Ahli_Waris'.$i]) ?></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">TTL</div>
                                            <div class="col-8">: <?= htmlspecialchars($data_core['Tempat_Lahir_Ahli_Waris'.$i]) ?>, <?= format_tgl_lahir($data_core['Tgl_Lahir_Ahli_Waris'.$i]) ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endif; 
                        endfor; 
                        if(!$ada_ahli_waris):
                        ?>
                            <div class="col-12"><div class="alert alert-light text-center small text-muted border">Tidak ada data ahli waris yang terdaftar di Core System.</div></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-pekerjaan" role="tabpanel">
                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2"><i class="bi bi-briefcase-fill"></i> Data Pekerjaan & Arus Kas</h6>
                    <div class="alert alert-light border small text-muted mb-4">
                        <i class="bi bi-info-circle-fill me-2 fs-5 text-primary"></i> 
                        Atribut pekerjaan utama otomatis disinkronkan dari Core System CBS. Pembaruan rincian arus kas bulanan dapat dilakukan melalui menu form survei/kunjungan.
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <p class="data-label">Pekerjaan Utama (Sesuai Core)</p><p class="data-value"><?= htmlspecialchars($pekerjaan_aktif['pekerjaan_baku'] ?? '-') ?></p>
                            <p class="data-label">Instansi / Tempat Kerja</p><p class="data-value"><?= htmlspecialchars($pekerjaan_aktif['nama_instansi'] ?? '-') ?></p>
                            <p class="data-label">Divisi / Jabatan</p><p class="data-value"><?= htmlspecialchars($pekerjaan_aktif['jabatan'] ?? '-') ?></p>
                            <p class="data-label">Alamat Tempat Kerja / Instansi</p><p class="data-value"><?= htmlspecialchars($data_core['Alamat_Instansi'] ?: '-') ?></p>
                            <p class="data-label">No. Telepon Instansi</p><p class="data-value"><?= htmlspecialchars($data_core['No_Telp_Instansi'] ?: '-') ?></p>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border shadow-sm">
                                <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-calculator text-success me-1"></i> Rincian Arus Kas (Per Bulan)</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Pendapatan Utama (Gaji Core)</span>
                                    <span class="fw-bold text-dark currency-text">Rp <?= number_format($pekerjaan_aktif['pendapatan_utama'] ?? 0, 0, ',', '.') ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Pendapatan Tambahan / Usaha</span>
                                    <span class="fw-bold text-success currency-text">Rp <?= number_format($pekerjaan_aktif['pendapatan_tambahan'] ?? 0, 0, ',', '.') ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 border-top pt-2">
                                    <span class="fw-bold">Total Pendapatan Kotor</span>
                                    <?php $total_pendapatan = ($pekerjaan_aktif['pendapatan_utama'] ?? 0) + ($pekerjaan_aktif['pendapatan_tambahan'] ?? 0); ?>
                                    <span class="fw-bold text-primary currency-text">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></span>
                                </div>
                                <div class="d-flex justify-content-between mt-4 mb-2 border-top pt-3">
                                    <span class="text-muted">Estimasi Biaya Hidup & Angsuran</span>
                                    <span class="fw-bold text-danger currency-text">Rp <?= number_format($pekerjaan_aktif['rincian_biaya_hidup'] ?? 0, 0, ',', '.') ?></span>
                                </div>
                                <div class="d-flex justify-content-between border-top pt-2 bg-white p-2 rounded border">
                                    <span class="fw-bold text-uppercase" style="font-size: 0.9rem;">Sisa Pendapatan Bersih</span>
                                    <?php $sisa_pendapatan = $total_pendapatan - ($pekerjaan_aktif['rincian_biaya_hidup'] ?? 0); ?>
                                    <span class="fw-bold text-success currency-text">Rp <?= number_format($sisa_pendapatan, 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-secondary border-bottom pb-2"><i class="bi bi-clock-history"></i> Histori Pembaruan Data Pekerjaan</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover text-nowrap" style="font-size: 0.85rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal Sinkronisasi/Update</th><th>Pekerjaan Baku</th><th>Instansi</th><th>Jabatan</th><th class="text-end">Pendapatan Utama (Core)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($pekerjaan_histori)): foreach($pekerjaan_histori as $histori): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i:s', strtotime($histori['created_at'])) ?></td>
                                        <td><?= htmlspecialchars($histori['pekerjaan_baku']) ?: '-' ?></td>
                                        <td><?= htmlspecialchars($histori['nama_instansi']) ?: '-' ?></td>
                                        <td><?= htmlspecialchars($histori['jabatan']) ?: '-' ?></td>
                                        <td class="text-end currency-text">Rp <?= number_format($histori['pendapatan_utama'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr><td colspan="5" class="text-center text-muted py-3">Belum ada riwayat arsip perubahan pekerjaan di database lokal.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-portofolio" role="tabpanel">
                    <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 pb-2 border-bottom">
                        <ul class="nav nav-pills mb-2 mb-md-0" id="pills-tab-porto" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active px-4 rounded-pill me-2 fw-bold" id="pills-simpanan-tab" data-bs-toggle="pill" data-bs-target="#pills-simpanan" type="button" role="tab"><i class="bi bi-safe2-fill me-2"></i>Simpanan & Investasi</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link px-4 rounded-pill fw-bold text-danger border border-transparent" id="pills-pinjaman-tab" data-bs-toggle="pill" data-bs-target="#pills-pinjaman" type="button" role="tab"><i class="bi bi-cash-coin me-2"></i>Fasilitas Pinjaman</button>
                            </li>
                        </ul>
                        <div class="btn-group btn-group-sm shadow-sm" role="group" id="portoFilterBtn">
                            <input type="radio" class="btn-check filter-radio" name="portoFilter" id="filterAll" value="all" checked><label class="btn btn-outline-secondary" for="filterAll">Semua</label>
                            <input type="radio" class="btn-check filter-radio" name="portoFilter" id="filterAktif" value="aktif"><label class="btn btn-outline-success" for="filterAktif">Aktif</label>
                            <input type="radio" class="btn-check filter-radio" name="portoFilter" id="filterTutup" value="tutup"><label class="btn btn-outline-danger" for="filterTutup">Tutup / Lunas</label>
                        </div>
                    </div>

                    <div class="tab-content" id="pills-tabContent-porto">
                        <div class="tab-pane fade show active" id="pills-simpanan" role="tabpanel">
                            <div class="mb-2 px-1 text-primary small fw-bold text-uppercase"><i class="bi bi-shield-lock-fill me-1"></i> A. Ekuitas / Simpanan Keanggotaan</div>
                            <div class="accordion mb-4" id="accKeanggotaan">
                                <div class="accordion-item porto-item status-<?= $data_core['Status_Keanggotaan'] == '0' ? 'aktif' : 'tutup' ?> border-primary mb-3 shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                    <h2 class="accordion-header" id="headingAnggota">
                                        <button class="accordion-button bg-primary bg-opacity-10 text-primary fw-bold p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAnggota" aria-expanded="true">
                                            <div class="d-flex w-100 justify-content-between align-items-center me-3">
                                                <span><i class="bi bi-piggy-bank-fill me-2 fs-5"></i> Simpanan Keanggotaan</span>
                                                <span class="badge bg-primary text-white fs-6 shadow-sm">No. BA: <?= htmlspecialchars($data_core['No_BA']) ?></span>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapseAnggota" class="accordion-collapse collapse show" data-bs-parent="#accKeanggotaan">
                                        <div class="accordion-body p-0">
                                            <div class="table-responsive" style="max-height: 400px;">
                                                <table class="table table-sm table-hover table-striped mb-0 text-nowrap" style="font-size: 0.85rem;">
                                                    <thead class="table-light sticky-top">
                                                        <tr>
                                                            <th class="border-end">Tgl Transaksi</th><th>Sandi</th><th class="text-end border-start">In/Out SP</th><th class="text-end">In/Out SW</th><th class="text-end">In/Out SS</th><th class="text-end border-start border-end">Saldo SP</th><th class="text-end border-end">Saldo SW</th><th class="text-end">Saldo SS</th><th class="border-start">Keterangan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($trx_anggota)): foreach ($trx_anggota as $trx): ?>
                                                            <tr>
                                                                <td class="border-end"><?= date('d/m/Y', strtotime($trx['Tgl_Transaksi'])) ?></td>
                                                                <td><?= get_dict_value($dict_sandi_anggota, $trx['Kode_Sandi']) ?></td>
                                                                <td class="text-end currency-text border-start"><?= number_format($trx['Jml_SP'], 0, ',', '.') ?></td>
                                                                <td class="text-end currency-text"><?= number_format($trx['Jml_SW'], 0, ',', '.') ?></td>
                                                                <td class="text-end currency-text"><?= number_format($trx['Jml_SS'], 0, ',', '.') ?></td>
                                                                <td class="text-end fw-bold currency-text border-start border-end"><?= number_format($trx['Saldo_SP'], 0, ',', '.') ?></td>
                                                                <td class="text-end fw-bold currency-text border-end"><?= number_format($trx['Saldo_SW'], 0, ',', '.') ?></td>
                                                                <td class="text-end fw-bold currency-text"><?= number_format($trx['Saldo_SS'], 0, ',', '.') ?></td>
                                                                <td class="border-start"><?= htmlspecialchars($trx['Keterangan']) ?></td>
                                                            </tr>
                                                        <?php endforeach; else: ?>
                                                            <tr><td colspan="9" class="text-center text-muted py-3">Belum ada riwayat transaksi keanggotaan.</td></tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?php $total_saldo_keanggotaan = $data_core['AA_Saldo_SP'] + $data_core['AA_Saldo_SW'] + $data_core['AA_Saldo_SS']; ?>
                                            <div class="bg-light p-3 border-top d-flex justify-content-end align-items-center">
                                                <span class="text-muted small me-3 text-uppercase fw-bold">Total Saldo Keanggotaan:</span>
                                                <span class="fs-5 fw-bold text-success currency-text">Rp <?= number_format($total_saldo_keanggotaan, 0, ',', '.') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-2 px-1 text-secondary small fw-bold text-uppercase mt-4"><i class="bi bi-wallet2 me-1"></i> B. Simpanan Harian (Liquid/Murni)</div>
                            <div class="accordion mb-4" id="accHarian">
                                <?php if (!empty($pure_simpanan_harian)): foreach ($pure_simpanan_harian as $index => $sh): ?>
                                    <div class="accordion-item porto-item status-<?= $sh['Status_Rekening'] == '0' ? 'aktif' : 'tutup' ?> mb-3 shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed bg-white text-dark fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSH_<?= $index ?>">
                                                <i class="bi bi-credit-card-2-front me-2 fs-5 text-secondary"></i> <?= htmlspecialchars($sh['Nama_Golongan'] ?? 'Produk Tidak Ditemukan') ?> | Rek: <?= htmlspecialchars($sh['No_RekeningSH']) ?>
                                                <?= $sh['Status_Rekening'] == 0 ? '<span class="badge bg-success ms-3">Aktif</span>' : '<span class="badge bg-danger ms-3">Tutup</span>' ?>
                                            </button>
                                        </h2>
                                        <div id="collapseSH_<?= $index ?>" class="accordion-collapse collapse" data-bs-parent="#accHarian">
                                            <div class="accordion-body p-0">
                                                <div class="bg-light p-3 border-bottom row g-3 text-muted" style="font-size: 0.85rem;">
                                                    <div class="col-md-3"><span class="d-block fw-bold text-dark">Tgl Buka</span><span><?= format_tgl_lahir($sh['Tgl_Masuk_SH']) ?></span></div>
                                                    <div class="col-md-3"><span class="d-block fw-bold text-dark">Setoran Awal</span><span class="currency-text">Rp <?= number_format($sh['Setoran_Awal'], 0, ',', '.') ?></span></div>
                                                    <div class="col-md-3"><span class="d-block fw-bold text-dark">Bunga/Jasa</span><span><?= htmlspecialchars($sh['Bunga_Yg_Berlaku']) ?>%</span></div>
                                                    <div class="col-md-3"><span class="d-block fw-bold text-dark">Kewajiban Pokok</span><span class="currency-text">Rp <?= number_format($sh['Besar_Kewajiban_Simpanan'], 0, ',', '.') ?></span></div>
                                                </div>
                                                <div class="table-responsive" style="max-height: 400px;">
                                                    <table class="table table-sm table-hover table-striped mb-0 text-nowrap" style="font-size: 0.85rem;">
                                                        <thead class="table-light sticky-top">
                                                            <tr><th>Tgl Transaksi</th><th>Sandi</th><th>Keterangan</th><th class="text-end text-danger">Debit (Keluar)</th><th class="text-end text-success">Kredit (Masuk)</th><th class="text-end bg-light border-start">Saldo Akhir</th></tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $trx_sh = $trx_simpanan_harian[$sh['No_RekeningSH']] ?? []; if (!empty($trx_sh)): foreach ($trx_sh as $t): ?>
                                                                <tr>
                                                                    <td><?= date('d/m/Y H:i', strtotime($t['Tgl_Transaksi'])) ?></td>
                                                                    <td><?= get_dict_value($dict_sandi_simpanan, $t['Kode_Sandi']) ?></td>
                                                                    <td><?= htmlspecialchars($t['Keterangan']) ?: '-' ?></td>
                                                                    <td class="text-end currency-text text-danger"><?= number_format($t['Debit'], 0, ',', '.') ?></td>
                                                                    <td class="text-end currency-text text-success"><?= number_format($t['Kredit'], 0, ',', '.') ?></td>
                                                                    <td class="text-end fw-bold currency-text border-start"><?= number_format($t['Saldo'], 0, ',', '.') ?></td>
                                                                </tr>
                                                            <?php endforeach; else: ?>
                                                                <tr><td colspan="6" class="text-center text-muted py-3">Belum ada riwayat transaksi keuangan harian.</td></tr>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="bg-light p-3 border-top d-flex justify-content-end align-items-center">
                                                    <span class="text-muted small me-3 text-uppercase fw-bold">Saldo Buku Lokal:</span>
                                                    <span class="fs-5 fw-bold text-success currency-text">Rp <?= number_format($sh['Saldo_Simpanan'], 0, ',', '.') ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; endif; ?>
                            </div>

                            <div class="mb-2 px-1 text-warning small fw-bold text-uppercase mt-4"><i class="bi bi-award-fill me-1"></i> C. Simpanan Berjangka / Investasi</div>
                            <div class="accordion mb-4" id="accBerjangka">
                                <?php if (!empty($data_simpanan_berjangka)): foreach ($data_simpanan_berjangka as $index => $sb): ?>
                                    <div class="accordion-item porto-item status-<?= $sb['Status_Sertifikat'] == '0' ? 'aktif' : 'tutup' ?> mb-3 shadow-sm border-warning" style="border-radius: 8px; overflow: hidden;">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed bg-warning bg-opacity-10 text-dark fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSB_<?= $index ?>">
                                                <i class="bi bi-ticket-perforated-fill me-2 fs-5 text-warning"></i> <?= htmlspecialchars($sb['Jenis_Simpanan_Berjangka'] ?? 'Sertifikat Berjangka Murni') ?> | Sertifikat: <?= htmlspecialchars($sb['No_SertifikatSB']) ?>
                                                <?= $sb['Status_Sertifikat'] == 0 ? '<span class="badge bg-success ms-3">Aktif</span>' : '<span class="badge bg-secondary ms-3">Dicairkan</span>' ?>
                                            </button>
                                        </h2>
                                        <div id="collapseSB_<?= $index ?>" class="accordion-collapse collapse" data-bs-parent="#accBerjangka">
                                            <div class="accordion-body p-4 row g-3 bg-white">
                                                <?php $jatuh_tempo = strtotime("+" . $sb['Jangka_Waktu'] . " months", strtotime($sb['Tgl_Mulai'])); ?>
                                                <div class="col-md-3"><span class="text-muted small d-block mb-1">Nominal Pokok</span><span class="fs-5 fw-bold text-success currency-text">Rp <?= number_format($sb['Jml_Simpanan'], 0, ',', '.') ?></span></div>
                                                <div class="col-md-3"><span class="text-muted small d-block mb-1">Suku Bunga Kontrak</span><span class="fw-bold"><?= htmlspecialchars($sb['Suku_Bunga_Saat_Ini']) ?>% p.a</span></div>
                                                <div class="col-md-3"><span class="text-muted small d-block mb-1">Mulai Valuta</span><span class="fw-bold"><?= format_tgl_lahir($sb['Tgl_Mulai']) ?></span></div>
                                                <div class="col-md-3"><span class="text-muted small d-block mb-1">Jatuh Tempo (<?= $sb['Jangka_Waktu'] ?> Bln)</span><span class="fw-bold text-danger"><?= date('d M Y', $jatuh_tempo) ?></span></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; endif; ?>

                                <?php if (!empty($sh_berjangka_display)): foreach ($sh_berjangka_display as $index => $sh_b): ?>
                                    <div class="accordion-item porto-item status-<?= $sh_b['Status_Rekening'] == '0' ? 'aktif' : 'tutup' ?> mb-3 shadow-sm border-warning" style="border-radius: 8px; overflow: hidden;">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed bg-white text-dark fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSHB_<?= $index ?>">
                                                <i class="bi bi-calendar-range-fill me-2 fs-5 text-warning"></i> <?= htmlspecialchars($sh_b['Nama_Golongan']) ?> (Berjangka) | Rek: <?= htmlspecialchars($sh_b['No_RekeningSH']) ?>
                                                <?= $sh_b['Status_Rekening'] == 0 ? '<span class="badge bg-success ms-3">Aktif</span>' : '<span class="badge bg-secondary ms-3">Tutup</span>' ?>
                                            </button>
                                        </h2>
                                        <div id="collapseSHB_<?= $index ?>" class="accordion-collapse collapse" data-bs-parent="#accBerjangka">
                                            <div class="accordion-body p-0">
                                                <div class="p-3 bg-light row g-2 small border-bottom">
                                                    <div class="col-6 col-md-3"><strong>Tenor:</strong> <?= $sh_b['Jangka_Waktu'] ?> Bulan</div>
                                                    <div class="col-6 col-md-3"><strong>Jatuh Tempo:</strong> <span class="text-danger fw-bold"><?= format_tgl_lahir($sh_b['Tgl_Jatuh_tempo']) ?></span></div>
                                                    <div class="col-6 col-md-3"><strong>Bunga:</strong> <?= $sh_b['Bunga_Yg_Berlaku'] ?>%</div>
                                                    <div class="col-6 col-md-3"><strong>Agunan:</strong> <?= $sh_b['Status_Pengagunan'] == 1 ? 'Ya' : 'Tidak' ?></div>
                                                </div>
                                                <div class="table-responsive" style="max-height: 250px;">
                                                    <table class="table table-sm table-striped mb-0 text-nowrap" style="font-size: 0.8rem;">
                                                        <thead><tr class="table-secondary"><th>Tanggal</th><th>Sandi</th><th class="text-end">Debit</th><th class="text-end">Kredit</th><th class="text-end">Saldo</th></tr></thead>
                                                        <tbody>
                                                            <?php $trx_shb = $trx_simpanan_harian[$sh_b['No_RekeningSH']] ?? []; if(!empty($trx_shb)): foreach($trx_shb as $tb): ?>
                                                                <tr><td><?= date('d/m/Y', strtotime($tb['Tgl_Transaksi'])) ?></td><td><?= get_dict_value($dict_sandi_simpanan, $tb['Kode_Sandi']) ?></td><td class="text-end text-danger"><?= number_format($tb['Debit'], 0, ',', '.') ?></td><td class="text-end text-success"><?= number_format($tb['Kredit'], 0, ',', '.') ?></td><td class="text-end fw-bold"><?= number_format($tb['Saldo'], 0, ',', '.') ?></td></tr>
                                                            <?php endforeach; else: ?><tr><td colspan="5" class="text-center py-2 text-muted">Tidak ada mutasi.</td></tr><?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="p-2 text-end bg-light fw-bold pr-3">Subtotal Kapital: Rp <?= number_format($sh_b['Saldo_Simpanan'], 0, ',', '.') ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; endif; ?>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-pinjaman" role="tabpanel">
                            <div class="accordion mb-4" id="accordionPinjaman">
                                <?php if (!empty($data_pinjaman)): foreach ($data_pinjaman as $index => $pj): ?>
                                    <div class="accordion-item porto-item status-<?= $pj['Status_Pinjaman'] == '0' ? 'aktif' : 'tutup' ?> mb-3 shadow-sm border-danger" style="border-radius: 8px; overflow: hidden;">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed bg-danger bg-opacity-10 text-danger fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePJ_<?= $index ?>">
                                                <i class="bi bi-bank me-2 fs-5"></i> <?= htmlspecialchars($pj['Nama_Produk_Pinjaman'] ?? 'Pinjaman / Kredit') ?> | No. Kontrak: <?= htmlspecialchars($pj['No_Pinjaman']) ?>
                                                <?= $pj['Status_Pinjaman'] == 0 ? '<span class="badge bg-warning text-dark ms-3">Belum Lunas</span>' : '<span class="badge bg-success ms-3">Lunas</span>' ?>
                                            </button>
                                        </h2>
                                        <div id="collapsePJ_<?= $index ?>" class="accordion-collapse collapse" data-bs-parent="#accordionPinjaman">
                                            <div class="accordion-body p-0">
                                                <div class="bg-white p-3 border-bottom row g-3 text-muted" style="font-size: 0.85rem;">
                                                    <?php $jt_pinjaman = strtotime("+" . $pj['Jangka_Waktu'] . " months", strtotime($pj['Tgl_Pinjam'])); ?>
                                                    <div class="col-md-4"><span class="d-block fw-bold text-dark">Tgl Cair / Alokasi</span><span><?= format_tgl_lahir($pj['Tgl_Pinjam']) ?> (<?= htmlspecialchars($pj['Tujuan_Pinjaman'] ?: '-') ?>)</span></div>
                                                    <div class="col-md-4"><span class="d-block fw-bold text-dark">Suku Bunga Pinjaman</span><span><?= htmlspecialchars($pj['Suku_Bunga']) ?>% p.a Efektif</span></div>
                                                    <div class="col-md-4"><span class="d-block fw-bold text-dark">Tenor / Jatuh Tempo Matriks</span><span><?= htmlspecialchars($pj['Jangka_Waktu']) ?> Bulan (<span class="text-danger fw-bold"><?= date('d M Y', $jt_pinjaman) ?></span>)</span></div>
                                                </div>
                                                <div class="table-responsive" style="max-height: 400px;">
                                                    <table class="table table-sm table-hover table-striped mb-0 text-nowrap" style="font-size: 0.85rem;">
                                                        <thead class="table-light sticky-top">
                                                            <tr><th>Tgl Transaksi</th><th>Sandi</th><th class="text-end text-success border-start">Bayar Pokok</th><th class="text-end text-success">Bayar Bunga</th><th class="text-end text-danger">Denda</th><th class="text-end bg-light border-start">Sisa Pokok (O/S Saldo)</th><th class="bg-light border-start border-end">Keterangan</th></tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $trx_pj = $trx_pinjaman[$pj['No_Pinjaman']] ?? []; if (!empty($trx_pj)): foreach ($trx_pj as $tp): ?>
                                                                <tr>
                                                                    <td><?= date('d/m/Y H:i', strtotime($tp['Tgl_Transaksi'])) ?></td>
                                                                    <td><?= get_dict_value($dict_sandi_pinjaman, $tp['Kode_Sandi']) ?></td>
                                                                    <td class="text-end currency-text text-success border-start"><?= number_format($tp['Angsuran'], 0, ',', '.') ?></td>
                                                                    <td class="text-end currency-text text-success"><?= number_format($tp['Bunga'], 0, ',', '.') ?></td>
                                                                    <td class="text-end currency-text text-danger"><?= number_format($tp['Denda'], 0, ',', '.') ?></td>
                                                                    <td class="text-end fw-bold currency-text border-start"><?= number_format($tp['Saldo'], 0, ',', '.') ?></td>
                                                                    <td class="border-start border-end"><?= htmlspecialchars($tp['Keterangan']) ?: '-' ?></td>
                                                                </tr>
                                                            <?php endforeach; else: ?>
                                                                <tr><td colspan="7" class="text-center text-muted py-3">Belum ada riwayat angsuran/pembayaran kredit.</td></tr>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="bg-danger bg-opacity-10 p-3 border-top d-flex justify-content-end align-items-center">
                                                    <span class="text-danger small me-3 text-uppercase fw-bold">Sisa Outstanding Pinjaman:</span>
                                                    <span class="fs-5 fw-bold text-danger currency-text">Rp <?= number_format($pj['Saldo_Pinjaman'], 0, ',', '.') ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; else: ?>
                                    <div class="alert alert-light border shadow-sm text-center py-5">
                                        <i class="bi bi-journal-x fs-1 text-muted d-block mb-3"></i><h6 class="fw-bold text-secondary">Tidak Ada Fasilitas Kredit</h6>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-keluarga" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                        <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-diagram-3-fill"></i> Jaringan Keluarga & Prospek</h6>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKeluarga"><i class="bi bi-plus-lg"></i> Tambah Anggota (Prospek)</button>
                    </div>
                    <div class="mb-2 px-1 text-success small fw-bold text-uppercase mt-3"><i class="bi bi-person-check-fill me-1"></i> A. Keluarga (Anggota Koperasi Aktif)</div>
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                                <thead class="table-light"><tr><th>Nama Anggota</th><th>NIK Sesuai KTP</th><th>No. Buku Anggota (BA)</th><th>Cabang Terdaftar</th><th class="text-end">Aksi</th></tr></thead>
                                <tbody>
                                    <?php if (!empty($keluarga_core)): foreach ($keluarga_core as $kc): ?>
                                        <tr>
                                            <td class="fw-bold"><?= htmlspecialchars($kc['Nama']) ?></td><td><?= htmlspecialchars($kc['NIK']) ?></td>
                                            <td><span class="badge bg-primary text-white"><?= htmlspecialchars($kc['No_BA']) ?></span></td><td><?= htmlspecialchars($kc['Nama_Cabang']) ?></td>
                                            <td class="text-end"><a href="profil.php?no_ba=<?= urlencode($kc['No_BA']) ?>" class="btn btn-sm btn-outline-success" target="_blank"><i class="bi bi-eye"></i> Lihat Profil</a></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada anggota keluarga lain yang terdaftar dalam 1 Kartu Keluarga.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-2 px-1 text-warning small fw-bold text-uppercase mt-4"><i class="bi bi-person-lines-fill me-1"></i> B. Database Prospek Keluarga (Input Lokal)</div>
                    <div class="card shadow-sm border-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                                <thead class="table-light"><tr><th>Nama Lengkap</th><th>Hubungan</th><th>NIK KTP & TTL</th><th>Pekerjaan & Kontak</th><th class="text-end">Status / Aksi</th></tr></thead>
                                <tbody>
                                    <?php if (!empty($keluarga_lokal)): foreach ($keluarga_lokal as $kl): ?>
                                        <tr>
                                            <td class="fw-bold"><?= htmlspecialchars($kl['nama']) ?></td><td><span class="badge bg-secondary"><?= htmlspecialchars($kl['hubungan']) ?></span></td>
                                            <td><div class="small fw-bold text-dark"><?= htmlspecialchars($kl['nik']) ?></div><div class="small text-muted"><?= htmlspecialchars($kl['tempat_lahir']) ?>, <?= format_tgl_lahir($kl['tgl_lahir']) ?></div></td>
                                            <td><div class="small fw-bold text-dark"><?= htmlspecialchars($kl['pekerjaan']) ?><?= $kl['nama_instansi'] ? ' di '.$kl['nama_instansi'] : '' ?></div><div class="small text-muted"><i class="bi bi-telephone"></i> <?= htmlspecialchars($kl['no_telp_wa']) ?: '-' ?></div></td>
                                            <td class="text-end">
                                                <?php if ($kl['is_anggota_core']): ?>
                                                    <div class="mb-1"><span class="badge bg-success"><i class="bi bi-check-circle"></i> Nyangkut di Core</span></div>
                                                    <a href="profil.php?no_ba=<?= urlencode($kl['core_no_ba']) ?>" class="btn btn-sm btn-success" target="_blank">Lihat Profil No. BA: <?= htmlspecialchars($kl['core_no_ba']) ?></a>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Prospek / Non-Anggota</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data keluarga non-anggota (prospek) hasil survei.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-organisasi" role="tabpanel">
                    <div class="row mb-4 align-items-center">
                        <div class="col-md-7">
                            <h6 class="fw-bold text-primary mb-1"><i class="bi bi-diagram-2-fill"></i> Relasi Pengalaman Organisasi Calon Pengurus</h6>
                            <p class="text-muted small mb-0">Rancangan basis data relasional objektif terintegrasi bobot nilai untuk instrumen DSS.</p>
                        </div>
                        <div class="col-md-5 text-md-end mt-3 mt-md-0">
                            <button class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahOrganisasi">
                                <i class="bi bi-plus-circle-fill"></i> Tambah Portofolio Organisasi
                            </button>
                        </div>
                    </div>

                    <div class="card bg-<?= $warna_potensi ?> bg-opacity-10 border-<?= $warna_potensi ?> mb-4 shadow-sm">
                        <div class="card-body py-3 d-flex flex-wrap justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold text-<?= $warna_potensi ?> mb-1"><i class="bi bi-cpu-fill me-2"></i> Kualifikasi Kematangan Organisasi (Kriteria Kuantitatif SAW)</h6>
                                <div class="small text-muted">Matriks Hitung: $\sum (\text{Bobot Kategori} \times \text{Bobot Jabatan} \times \text{Bobot Wilayah})$ dari riwayat terverifikasi.</div>
                            </div>
                            <div class="text-md-end mt-2 mt-md-0">
                                <span class="badge bg-<?= $warna_potensi ?> text-white fs-6 px-3 py-2 border border-light shadow-sm">
                                    Status: <?= htmlspecialchars($status_potensi) ?> | Kumulatif Skor: <?= $skor_potensi ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle text-nowrap mb-0" style="font-size: 0.88rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Organisasi</th>
                                    <th>Kategori (Bobot)</th>
                                    <th>Jabatan (Bobot)</th>
                                    <th>Tingkat Wilayah (Bobot)</th>
                                    <th class="text-center">Masa Bakti</th>
                                    <th class="text-center">SK Bukti</th>
                                    <th class="text-center bg-light fw-bold text-primary border-start border-end">Sub-Skor DSS</th>
                                    <th class="text-center">Verifikasi Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data_organisasi)): foreach ($data_organisasi as $org): ?>
                                    <tr>
                                        <td class="fw-bold text-dark"><?= htmlspecialchars($org['nama_organisasi']) ?></td>
                                        <td><?= htmlspecialchars($org['nama_kategori']) ?> <span class="badge bg-light text-dark border">W:<?= $org['b_kat'] ?></span></td>
                                        <td class="fw-bold text-dark"><?= htmlspecialchars($org['nama_jabatan']) ?> <span class="badge bg-light text-primary border">W:<?= $org['b_jab'] ?></span></td>
                                        <td><?= htmlspecialchars($org['nama_wilayah']) ?> <span class="badge bg-light text-secondary border">W:<?= $org['b_wil'] ?></span></td>
                                        <td class="text-center"><?= htmlspecialchars($org['tahun_mulai']) ?> - <?= htmlspecialchars($org['tahun_selesai'] ?: 'Sekarang') ?></td>
                                        <td class="text-center">
                                            <?php if ($org['file_bukti_sk']): ?>
                                                <a href="uploads/sk/<?= $org['file_bukti_sk'] ?>" target="_blank" class="btn btn-xs btn-outline-secondary p-1 py-0" style="font-size:0.75rem;"><i class="bi bi-file-earmark-pdf-fill text-danger"></i> Dokumen SK</a>
                                            <?php else: ?>
                                                <span class="text-muted small fst-italic">Tidak Ada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center bg-light fw-bold text-primary border-start border-end fs-6"><?= $org['skor_item'] ?></td>
                                        <td class="text-center">
                                            <?php if ($org['is_verified'] == 1): ?>
                                                <span class="badge bg-success-subtle text-success border border-success px-2"><i class="bi bi-patch-check-fill"></i> Valid</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning-subtle text-warning border border-warning px-2"><i class="bi bi-hourglass-split"></i> Review</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr><td colspan="8" class="text-center text-muted py-4"><i class="bi bi-folder-x fs-3 d-block mb-2"></i>Belum ada portofolio keorganisasian yang tercatat di database lokal aplikasi ini.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-kunjungan" role="tabpanel"><p class="text-muted small">Kunjungan dimuat.</p></div>
                <div class="tab-pane fade" id="tab-diklat" role="tabpanel"><p class="text-muted small">Diklat dimuat.</p></div>

            </div>
        </div>
    </div>
    <?php endif; ?>
</main>

<div class="modal fade" id="modalTambahKeluarga" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white"><h5 class="modal-title"><i class="bi bi-person-plus-fill me-2"></i> Tambah Data Keluarga / Prospek</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <form action="profil.php?no_ba=<?= urlencode($no_ba) ?>" method="POST">
                <input type="hidden" name="action" value="tambah_keluarga">
                <div class="modal-body bg-light">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6"><label class="form-label fw-bold">NIK KTP <span class="text-danger">*</span></label><input type="text" class="form-control" name="nik" required maxlength="16"></div>
                        <div class="col-md-6"><label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label><input type="text" class="form-control" name="nama" required></div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6"><label class="form-label fw-bold">Hubungan Keluarga <span class="text-danger">*</span></label><select class="form-select" name="hubungan" required><option value="" selected disabled>Pilih...</option><option value="Suami">Suami</option><option value="Istri">Istri</option><option value="Anak">Anak</option><option value="Orang Tua">Orang Tua</option></select></div>
                        <div class="col-md-6"><label class="form-label fw-bold">Nomor Kontak</label><input type="text" class="form-control" name="no_telp_wa"></div>
                    </div>
                    <div class="row g-3 mb-2">
                        <div class="col-md-4"><label class="form-label">Tempat Lahir</label><input type="text" class="form-control" name="tempat_lahir"></div>
                        <div class="col-md-4"><label class="form-label">Tanggal Lahir</label><input type="date" class="form-control" name="tgl_lahir"></div>
                        <div class="col-md-4"><label class="form-label">Agama</label><input type="text" class="form-control" name="agama"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label">Pendidikan</label><input type="text" class="form-control" name="pendidikan"></div>
                        <div class="col-md-4"><label class="form-label">Pekerjaan</label><input type="text" class="form-control" name="pekerjaan"></div>
                        <div class="col-md-4"><label class="form-label">Estimasi Penghasilan</label><input type="text" class="form-control currency-input" name="penghasilan"></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan Prospek</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahOrganisasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-diagram-2-fill me-2"></i> Input Portofolio Kompetensi Organisasi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="profil.php?no_ba=<?= urlencode($no_ba) ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="tambah_organisasi">
                <div class="modal-body bg-light">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lembaga / Organisasi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_organisasi" required placeholder="Contoh: Koperasi Kredit Mandiri / Karang Taruna">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori Sektor (Master Kriteria) <span class="text-danger">*</span></label>
                        <select class="form-select" name="id_kategori" required>
                            <option value="" selected disabled>Pilih parameter...</option>
                            <?php foreach ($master_kategori as $kat): ?>
                                <option value="<?= $kat['id_kategori'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?> (Bobot: <?= $kat['bobot_nilai'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jabatan / Kedudukan Struktural <span class="text-danger">*</span></label>
                        <select class="form-select" name="id_jabatan" required>
                            <option value="" selected disabled>Pilih tingkatan jabatan...</option>
                            <?php foreach ($master_jabatan as $jab): ?>
                                <option value="<?= $jab['id_jabatan'] ?>"><?= htmlspecialchars($jab['nama_jabatan']) ?> (Bobot: <?= $jab['bobot_nilai'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Cakupan Wilayah Operasional <span class="text-danger">*</span></label>
                        <select class="form-select" name="id_wilayah" required>
                            <option value="" selected disabled>Pilih tingkat teritorial...</option>
                            <?php foreach ($master_wilayah as $wil): ?>
                                <option value="<?= $wil['id_wilayah'] ?>"><?= htmlspecialchars($wil['nama_wilayah']) ?> (Bobot: <?= $wil['bobot_nilai'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Tahun Mulai <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="tahun_mulai" required min="1950" max="<?= date('Y') ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Tahun Selesai</label>
                            <input type="text" class="form-control" name="tahun_selesai" placeholder="Misal: 2024 atau Sekarang">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Unggah Bukti Fisik SK Kelayakan (PDF/Gambar)</label>
                        <input type="file" class="form-control" name="file_bukti_sk" accept=".pdf,.png,.jpg,.jpeg">
                        <small class="text-muted">Opsional. Bukti dokumen otentik kepengurusan.</small>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Deskripsi Kerja / Catatan Prestasi</label>
                        <textarea class="form-control" name="keterangan" rows="2" placeholder="Tulis rincian singkat jika ada..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save-fill"></i> Simpan & Kalkulasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../app/Views/Layouts/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterRadios = document.querySelectorAll('.filter-radio');
    const portoItems = document.querySelectorAll('.porto-item');

    filterRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const filterValue = this.value;
            portoItems.forEach(item => {
                if (filterValue === 'all') { item.style.display = ''; } 
                else if (item.classList.contains('status-' + filterValue)) { item.style.display = ''; } 
                else { item.style.display = 'none'; }
            });
        });
    });

    const currencyInputs = document.querySelectorAll('.currency-input');
    currencyInputs.forEach(input => {
        input.addEventListener('keyup', function(e) {
            let value = this.value.replace(/[^,\d]/g, '').toString();
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            this.value = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        });
    });
});
</script>