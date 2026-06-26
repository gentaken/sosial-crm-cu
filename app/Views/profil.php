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
            <div>
                <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4 border-top border-4 border-primary">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-speedometer2 me-2"></i> Executive Summary: Rapor Kelayakan Finansial</h6>
            <span class="badge bg-light text-dark border">Consolidated LOS Version</span>
        </div>
        <div class="card-body bg-light">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="p-3 bg-white border rounded h-100 shadow-sm">
                        <span class="d-block small fw-bold text-muted mb-2 text-uppercase">Posisi Net Worth Konsolidasi</span>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Total Harta (Core+Lokal)</span>
                                <span class="fw-bold text-success">Rp <?= number_format($rapor['grand_total_aset'],0,',','.') ?></span>
                            </div>
                            <div class="progress" style="height: 6px;"><div class="progress-bar bg-success" style="width: 100%"></div></div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Total Kewajiban/Hutang</span>
                                <span class="fw-bold text-danger">Rp <?= number_format($rapor['grand_total_hutang'],0,',','.') ?></span>
                            </div>
                            <?php $pct_hutang = ($rapor['grand_total_aset'] > 0) ? min(100, ($rapor['grand_total_hutang'] / $rapor['grand_total_aset']) * 100) : 0; ?>
                            <div class="progress" style="height: 6px;"><div class="progress-bar bg-danger" style="width: <?= $pct_hutang ?>%"></div></div>
                        </div>
                        <div class="pt-2 border-top">
                            <span class="small text-muted d-block">Kekayaan Bersih Riil:</span>
                            <span class="fs-5 fw-bold <?= ($rapor['grand_total_aset'] - $rapor['grand_total_hutang']) >= 0 ? 'text-success' : 'text-danger' ?>">
                                Rp <?= number_format($rapor['grand_total_aset'] - $rapor['grand_total_hutang'],0,',','.') ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-3 bg-white border rounded h-100 shadow-sm">
                        <span class="d-block small fw-bold text-muted mb-3 text-uppercase">Matriks & Leverage Rasio</span>
                        <div class="mb-3">
                            <span class="d-block small text-dark mb-1">Debt to Equity Ratio (DER)</span>
                            <div class="d-flex align-items-center">
                                <span class="fs-4 fw-bold text-<?= $rapor['der_color'] ?> me-2"><?= number_format($rapor['der'], 1, ',', '.') ?>%</span>
                                <span class="badge bg-<?= $rapor['der_color'] ?> bg-opacity-10 text-<?= $rapor['der_color'] ?> border border-<?= $rapor['der_color'] ?>"><?= $rapor['der_status'] ?></span>
                            </div>
                        </div>
                        <div>
                            <span class="d-block small text-dark mb-1">Repayment Capacity (Sisa Cashflow)</span>
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-bold text-<?= $rapor['rpc_color'] ?> me-2">
                                    <?= $rapor['surplus_defisit'] >= 0 ? '+' : '' ?>Rp <?= number_format($rapor['surplus_defisit'],0,',','.') ?> /bln
                                </span>
                            </div>
                            <span class="small text-muted" style="font-size:0.75rem;"><i class="bi bi-info-circle me-1"></i><?= $rapor['rpc_status'] ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-3 bg-white border rounded h-100 shadow-sm d-flex flex-column justify-content-center">
                        <span class="d-block small fw-bold text-muted mb-3 text-uppercase text-center">Rekomendasi Dokumen Survei</span>
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <?php foreach($rapor['badges'] as $badge): ?>
                                <span class="badge <?= $badge['bg'] ?> p-2 fs-6 shadow-sm">
                                    <i class="bi <?= $badge['icon'] ?> me-1"></i> <?= $badge['text'] ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white p-0 border-bottom">
            <ul class="nav nav-tabs px-3 pt-2" id="profilTabs" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-pribadi" type="button">1. Pribadi</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pekerjaan" type="button">2. Pekerjaan</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-portofolio" type="button">3. Portofolio</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-keluarga" type="button">4. Keluarga</button></li>
                <li class="nav-item"><button class="nav-link fw-bold text-success" data-bs-toggle="tab" data-bs-target="#tab-kunjungan" type="button">5. Survei & Appraisal LOS</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-organisasi" type="button">6. Organisasi</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-diklat" type="button">7. Diklat CU</button></li>
                <li class="nav-item"><button class="nav-link fw-bold text-primary" data-bs-toggle="tab" data-bs-target="#tab-dokumen" type="button">8. Arsip Digital</button></li>
            </ul>
        </div>
        
        <div class="card-body">
            <div class="tab-content">
                
                <div class="tab-pane fade show active" id="tab-pribadi" role="tabpanel">
                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2"><i class="bi bi-person-badge"></i> Identitas & Demografi</h6>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <p class="data-label">Nama Sesuai ID</p>
                            <p class="data-value"><?= htmlspecialchars($data_core['Nama']) ?></p>
                            <p class="data-label">Jenis ID & Nomor</p>
                            <p class="data-value"><?= get_dict_value($dict_jenis_id, $data_core['Jenis_ID']) ?> - <?= htmlspecialchars($data_core['No_ID']) ?></p>
                            <p class="data-label">No. Kartu Keluarga (KK)</p>
                            <p class="data-value"><?= htmlspecialchars($data_core['No_KK']) ?: '-' ?></p>
                            <p class="data-label">Nama Ibu Kandung</p>
                            <p class="data-value"><?= htmlspecialchars($data_core['Nama_Gadis_Ibu_Kandung']) ?: '<span class="text-muted fst-italic">Kosong</span>' ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="data-label">Tempat, Tanggal Lahir</p>
                            <p class="data-value"><?= htmlspecialchars($data_core['Tempat_Lahir']) ?>, <?= format_tgl_lahir($data_core['Tgl_Lahir']) ?></p>
                            <p class="data-label">Jenis Kelamin</p>
                            <p class="data-value"><?= get_dict_value($dict_jns_kelamin, $data_core['Jns_Kelamin']) ?></p>
                            <p class="data-label">Agama</p>
                            <p class="data-value"><?= get_dict_value($dict_agama, $data_core['Agama']) ?></p>
                            <p class="data-label">Pendidikan Terakhir</p>
                            <p class="data-value"><?= get_dict_value($dict_pendidikan, $data_core['Pendidikan_Terakhir']) ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="data-label">Status Perkawinan</p>
                            <p class="data-value"><?= get_dict_value($dict_status_nikah, $data_core['Status_Perkawinan']) ?></p>
                            <p class="data-label">Tanggal Masuk Anggota</p>
                            <p class="data-value"><?= date('d M Y', strtotime($data_core['Tgl_Masuk'])) ?></p>
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
                            if(!empty($nama_aw) && $nama_aw != '-'): $ada_ahli_waris = true;
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
                            <p class="data-label">Pekerjaan Utama (Sesuai Core)</p>
                            <p class="data-value"><?= htmlspecialchars($pekerjaan_aktif['pekerjaan_baku'] ?? '-') ?></p>
                            <p class="data-label">Instansi / Tempat Kerja</p>
                            <p class="data-value"><?= htmlspecialchars($pekerjaan_aktif['nama_instansi'] ?? '-') ?></p>
                            <p class="data-label">Divisi / Jabatan</p>
                            <p class="data-value"><?= htmlspecialchars($pekerjaan_aktif['jabatan'] ?? '-') ?></p>
                            <p class="data-label">Alamat Tempat Kerja / Instansi</p>
                            <p class="data-value"><?= htmlspecialchars($data_core['Alamat_Instansi'] ?: '-') ?></p>
                            <p class="data-label">No. Telepon Instansi</p>
                            <p class="data-value"><?= htmlspecialchars($data_core['No_Telp_Instansi'] ?: '-') ?></p>
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
                                    <th>Tanggal Sinkronisasi/Update</th>
                                    <th>Pekerjaan Baku</th>
                                    <th>Instansi</th>
                                    <th>Jabatan</th>
                                    <th class="text-end">Pendapatan Utama (Core)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($pekerjaan_histori)): ?>
                                    <?php foreach($pekerjaan_histori as $histori): ?>
                                        <tr>
                                            <td><?= date('d/m/Y H:i:s', strtotime($histori['created_at'])) ?></td>
                                            <td><?= htmlspecialchars($histori['pekerjaan_baku']) ?: '-' ?></td>
                                            <td><?= htmlspecialchars($histori['nama_instansi']) ?: '-' ?></td>
                                            <td><?= htmlspecialchars($histori['jabatan']) ?: '-' ?></td>
                                            <td class="text-end currency-text">Rp <?= number_format($histori['pendapatan_utama'], 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center text-muted py-3">Belum ada riwayat arsip perubahan pekerjaan di database lokal.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-portofolio" role="tabpanel">
                    <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 pb-2 border-bottom">
                        <ul class="nav nav-pills mb-2 mb-md-0" id="pills-tab-porto" role="tablist">
                            <li class="nav-item"><button class="nav-link active px-4 rounded-pill me-2 fw-bold" id="pills-simpanan-tab" data-bs-toggle="pill" data-bs-target="#pills-simpanan" type="button" role="tab"><i class="bi bi-safe2-fill me-2"></i>Simpanan & Investasi</button></li>
                            <li class="nav-item"><button class="nav-link px-4 rounded-pill fw-bold text-danger border border-transparent" id="pills-pinjaman-tab" data-bs-toggle="pill" data-bs-target="#pills-pinjaman" type="button" role="tab"><i class="bi bi-cash-coin me-2"></i>Fasilitas Pinjaman</button></li>
                        </ul>
                        <div class="btn-group btn-group-sm shadow-sm" role="group" id="portoFilterBtn">
                            <input type="radio" class="btn-check filter-radio" name="portoFilter" id="filterAll" value="all" checked>
                            <label class="btn btn-outline-secondary" for="filterAll">Semua</label>
                            <input type="radio" class="btn-check filter-radio" name="portoFilter" id="filterAktif" value="aktif">
                            <label class="btn btn-outline-success" for="filterAktif">Aktif</label>
                            <input type="radio" class="btn-check filter-radio" name="portoFilter" id="filterTutup" value="tutup">
                            <label class="btn btn-outline-danger" for="filterTutup">Tutup / Lunas</label>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pills-simpanan" role="tabpanel">
                            <div class="mb-2 px-1 text-primary small fw-bold text-uppercase"><i class="bi bi-shield-lock-fill me-1"></i> A. Ekuitas / Simpanan Keanggotaan</div>
                            <div class="accordion mb-4">
                                <div class="accordion-item porto-item status-<?= $data_core['Status_Keanggotaan'] == '0' ? 'aktif' : 'tutup' ?> border-primary mb-3 shadow-sm">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button bg-primary bg-opacity-10 text-primary fw-bold p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAnggota">
                                            <div class="d-flex w-100 justify-content-between align-items-center me-3">
                                                <span><i class="bi bi-piggy-bank-fill me-2 fs-5"></i> Simpanan Keanggotaan</span>
                                                <span class="badge bg-primary text-white fs-6 shadow-sm">No. BA: <?= htmlspecialchars($data_core['No_BA']) ?></span>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapseAnggota" class="accordion-collapse collapse show">
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
                                            <div class="bg-light p-3 border-top d-flex justify-content-end align-items-center">
                                                <span class="text-muted small me-3 text-uppercase fw-bold">Total Saldo:</span>
                                                <span class="fs-5 fw-bold text-success">Rp <?= number_format($rapor['total_aset_core'], 0, ',', '.') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-pinjaman" role="tabpanel">
                            <div class="accordion mb-4">
                                <?php if (!empty($data_pinjaman)): foreach ($data_pinjaman as $index => $pj): ?>
                                    <div class="accordion-item porto-item status-<?= $pj['Status_Pinjaman'] == '0' ? 'aktif' : 'tutup' ?> mb-3 shadow-sm border-danger">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed bg-danger bg-opacity-10 text-danger fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePJ_<?= $index ?>">
                                                <i class="bi bi-bank me-2 fs-5"></i> <?= htmlspecialchars($pj['Nama_Produk_Pinjaman']) ?> | No. Kontrak: <?= htmlspecialchars($pj['No_Pinjaman']) ?>
                                                <?= $pj['Status_Pinjaman'] == 0 ? '<span class="badge bg-warning text-dark ms-3">Belum Lunas</span>' : '<span class="badge bg-success ms-3">Lunas</span>' ?>
                                            </button>
                                        </h2>
                                        <div id="collapsePJ_<?= $index ?>" class="accordion-collapse collapse">
                                            <div class="accordion-body p-0">
                                                <div class="bg-white p-3 border-bottom row g-3 text-muted small">
                                                    <div class="col-md-4"><strong>Tgl Cair:</strong> <?= format_tgl_lahir($pj['Tgl_Pinjam']) ?> (<?= htmlspecialchars($pj['Tujuan_Pinjaman']) ?>)</div>
                                                    <div class="col-md-4"><strong>Suku Bunga:</strong> <?= htmlspecialchars($pj['Suku_Bunga']) ?>% p.a</div>
                                                    <div class="col-md-4"><strong>Tenor:</strong> <?= htmlspecialchars($pj['Jangka_Waktu']) ?> Bulan</div>
                                                </div>
                                                <div class="table-responsive" style="max-height: 300px;">
                                                    <table class="table table-sm table-striped mb-0 text-nowrap" style="font-size:0.85rem;">
                                                        <thead class="table-light">
                                                            <tr><th>Tgl</th><th>Sandi</th><th class="text-end text-success">Pokok</th><th class="text-end text-success">Bunga</th><th class="text-end text-danger">Denda</th><th class="text-end bg-light border-start">Sisa Pokok</th></tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $trx_pj = $trx_pinjaman[$pj['No_Pinjaman']] ?? []; foreach ($trx_pj as $tp): ?>
                                                                <tr>
                                                                    <td><?= date('d/m/Y', strtotime($tp['Tgl_Transaksi'])) ?></td>
                                                                    <td><?= get_dict_value($dict_sandi_pinjaman, $tp['Kode_Sandi']) ?></td>
                                                                    <td class="text-end"><?= number_format($tp['Angsuran'],0,',','.') ?></td>
                                                                    <td class="text-end"><?= number_format($tp['Bunga'],0,',','.') ?></td>
                                                                    <td class="text-end text-danger"><?= number_format($tp['Denda'],0,',','.') ?></td>
                                                                    <td class="text-end fw-bold bg-light"><?= number_format($tp['Saldo'],0,',','.') ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; else: ?>
                                    <div class="alert alert-light border text-center py-4">Tidak ada fasilitas pinjaman di Core system.</div>
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
                    <div class="mb-2 px-1 text-success small fw-bold text-uppercase"><i class="bi bi-person-check-fill me-1"></i> A. Keluarga Terdaftar Core System</div>
                    <div class="card shadow-sm border-0 mb-4">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light"><tr><th>Nama</th><th>NIK</th><th>No. BA</th><th>Cabang</th><th class="text-end">Aksi</th></tr></thead>
                            <tbody>
                                <?php if (!empty($keluarga_core)): foreach ($keluarga_core as $kc): ?>
                                    <tr><td class="fw-bold"><?= htmlspecialchars($kc['Nama']) ?></td><td><?= htmlspecialchars($kc['NIK']) ?></td><td><span class="badge bg-primary"><?= htmlspecialchars($kc['No_BA']) ?></span></td><td><?= htmlspecialchars($kc['Nama_Cabang']) ?></td><td class="text-end"><a href="profil.php?no_ba=<?= urlencode($kc['No_BA']) ?>" class="btn btn-xs btn-outline-success" target="_blank">Lihat Profil</a></td></tr>
                                <?php endforeach; else: ?><tr><td colspan="5" class="text-center text-muted">Tidak ada anggota keluarga terdeteksi via 1 KK di Core.</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-kunjungan" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                        <div>
                            <h6 class="fw-bold mb-0 text-success"><i class="bi bi-calculator-fill"></i> Lembar Appraisal Finansial & Dokumen Survei Lapangan</h6>
                            <p class="text-muted small mb-0">Platform otomasi analisis kelayakan, aktuaris kesehatan, dan RAB Multi-Usaha.</p>
                        </div>
                        <button class="btn btn-sm btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahProfiling">
                            <i class="bi bi-journal-plus"></i> Buat Laporan Survei/Appraisal Baru
                        </button>
                    </div>

                    <?php if (!empty($data_survei_master)): $m_latest = $data_survei_master[0]; ?>
                        <div class="alert alert-dark border py-2 d-flex justify-content-between align-items-center">
                            <span><strong>Hasil Survei Terakhir:</strong> <?= date('d M Y', strtotime($m_latest['tgl_survei'])) ?></span>
                            <span class="badge bg-secondary">Surveyor: <?= htmlspecialchars($m_latest['nama_petugas']) ?></span>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-primary bg-opacity-10 py-2 fw-bold text-primary small text-uppercase"><i class="bi bi-house-fill me-1"></i> A. Posisi Neraca Rumah Tangga / Keluarga</div>
                                    <div class="card-body small">
                                        <h6 class="fw-bold text-success mb-2">Harta / Aset Keluarga</h6>
                                        <ul class="list-group list-group-flush mb-3">
                                            <?php $has_fam_aset = false; foreach($detail_aset as $ast): if($ast['entitas']=='Keluarga'): $has_fam_aset=true; ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-start py-1">
                                                    <div><strong><?= htmlspecialchars($ast['nama_aset']) ?></strong> <span class="text-muted d-block" style="font-size:0.75rem;"><?= htmlspecialchars($ast['kondisi_deskripsi']) ?> (<?= $ast['kategori_aset'] ?>)</span></div>
                                                    <span class="fw-bold text-dark">Rp <?= number_format($ast['nilai_pasar'],0,',','.') ?></span>
                                                </li>
                                            <?php endif; endforeach; if(!$has_fam_aset): ?><li class="list-group-item text-muted fst-italic text-center">Tidak ada aset keluarga luar sistem</li><?php endif; ?>
                                        </ul>
                                        <h6 class="fw-bold text-danger mb-2">Kewajiban Konsumtif (Luar CU)</h6>
                                        <ul class="list-group list-group-flush">
                                            <?php $has_fam_htg = false; foreach($detail_hutang as $htg): if($htg['entitas']=='Keluarga'): $has_fam_htg=true; ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-start py-1">
                                                    <div><strong><?= htmlspecialchars($htg['sumber_kreditur']) ?></strong> <span class="text-muted d-block" style="font-size:0.75rem;">Keperluan: <?= htmlspecialchars($htg['tujuan_penggunaan']) ?> | Cicilan: Rp <?= number_format($htg['angsuran_perbulan'],0,',','.') ?>/bln</span></div>
                                                    <span class="fw-bold text-danger">Rp <?= number_format($htg['sisa_outstanding'],0,',','.') ?></span>
                                                </li>
                                            <?php endif; endforeach; if(!$has_fam_htg): ?><li class="list-group-item text-muted fst-italic text-center">Bebas hutang keluarga luar</li><?php endif; ?>
                                        </ul>
                                        <div class="bg-light p-2 rounded mt-3 d-flex justify-content-between fw-bold border">
                                            <span>Subtotal Bersih Keluarga:</span>
                                            <span class="text-primary">Rp <?= number_format($rapor['total_aset_keluarga'] - $rapor['total_hutang_keluarga'], 0, ',', '.') ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-success bg-opacity-10 py-2 fw-bold text-success small text-uppercase"><i class="bi bi-briefcase-fill me-1"></i> B. Posisi Neraca Produktif / Sektor Usaha</div>
                                    <div class="card-body small">
                                        <h6 class="fw-bold text-success mb-2">Harta / Komoditas Usaha</h6>
                                        <ul class="list-group list-group-flush mb-3">
                                            <?php $has_biz_aset = false; foreach($detail_aset as $ast): if($ast['entitas']=='Usaha'): $has_biz_aset=true; ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-start py-1">
                                                    <div><strong><?= htmlspecialchars($ast['nama_aset']) ?></strong> <span class="text-muted d-block" style="font-size:0.75rem;"><?= htmlspecialchars($ast['kondisi_deskripsi']) ?> (<?= $ast['kategori_aset'] ?>)</span></div>
                                                    <span class="fw-bold text-dark">Rp <?= number_format($ast['nilai_pasar'],0,',','.') ?></span>
                                                </li>
                                            <?php endif; endforeach; if(!$has_biz_aset): ?><li class="list-group-item text-muted fst-italic text-center">Tidak ada aset usaha produktif yang tercatat</li><?php endif; ?>
                                        </ul>
                                        <h6 class="fw-bold text-danger mb-2">Hutang Modal Kerja Pihak Ketiga</h6>
                                        <ul class="list-group list-group-flush">
                                            <?php $has_biz_htg = false; foreach($detail_hutang as $htg): if($htg['entitas']=='Usaha'): $has_biz_htg=true; ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-start py-1">
                                                    <div><strong><?= htmlspecialchars($htg['sumber_kreditur']) ?></strong> <span class="text-muted d-block" style="font-size:0.75rem;">Keperluan: <?= htmlspecialchars($htg['tujuan_penggunaan']) ?> | Cicilan: Rp <?= number_format($htg['angsuran_perbulan'],0,',','.') ?>/bln</span></div>
                                                    <span class="fw-bold text-danger">Rp <?= number_format($htg['sisa_outstanding'],0,',','.') ?></span>
                                                </li>
                                            <?php endif; endforeach; if(!$has_biz_htg): ?><li class="list-group-item text-muted fst-italic text-center">Bebas beban hutang modal usaha luar</li><?php endif; ?>
                                        </ul>
                                        <div class="bg-light p-2 rounded mt-3 d-flex justify-content-between fw-bold border">
                                            <span>Subtotal Bersih Usaha:</span>
                                            <span class="text-success">Rp <?= number_format($rapor['total_aset_usaha'] - $rapor['total_hutang_usaha'], 0, ',', '.') ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-calendar-event-fill text-warning me-1"></i> Detail Riwayat Siklus & Model Diversifikasi Usaha</h6>
                        <div class="row g-3 mb-4">
                            <?php if(!empty($detail_usaha)): foreach($detail_usaha as $ush): ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="p-3 bg-white border rounded shadow-sm position-relative">
                                        <span class="position-absolute top-0 end-0 mt-2 me-2 badge bg-secondary"><?= htmlspecialchars($ush['kategori_pekerjaan']) ?></span>
                                        <h6 class="fw-bold text-dark mb-1 mb-2 pr-5"><?= htmlspecialchars($ush['deskripsi_skala']) ?></h6>
                                        <div class="small text-muted mb-2">
                                            <div class="d-flex justify-content-between mb-1"><span>Mulai Siklus:</span> <span class="text-dark fw-bold"><?= format_tgl_lahir($ush['tgl_mulai_siklus']) ?></span></div>
                                            <div class="d-flex justify-content-between mb-1"><span>Perkiraan Panen:</span> <span class="text-danger fw-bold"><?= format_tgl_lahir($ush['estimasi_tgl_panen']) ?></span></div>
                                            <div class="border-top pt-1 mt-1"><strong>SOP / Treatment:</strong> <br><span style="font-size:0.8rem;"><?= nl2br(htmlspecialchars($ush['treatment_kegiatan'])) ?: '-' ?></span></div>
                                        </div>
                                        <div class="border-top pt-2 d-flex justify-content-between align-items-center small">
                                            <div><span class="d-block text-muted" style="font-size:0.7rem;">Estimasi HPP</span><strong class="text-danger">Rp <?= number_format($ush['estimasi_modal_hpp'],0,',','.') ?></strong></div>
                                            <div class="text-end"><span class="d-block text-muted" style="font-size:0.7rem;">Proyeksi Omzet</span><strong class="text-success">Rp <?= number_format($ush['estimasi_pendapatan_kotor'],0,',','.') ?></strong></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; else: ?>
                                <div class="col-12"><div class="alert alert-light border text-center small text-muted">Tidak ada siklus komoditas pertanian/peternakan/perdagangan yang tercatat.</div></div>
                            <?php endif; ?>
                        </div>

                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-arrow-left-right text-primary me-1"></i> Rekapitulasi Aliran Kas Konsolidasi (Bulanan)</h6>
                        <div class="table-responsive shadow-sm border rounded bg-white mb-4">
                            <table class="table table-bordered mb-0 small table-sm">
                                <thead class="table-light text-center">
                                    <tr><th>Arus</th><th>Entitas</th><th>Keterangan Item Arus Kas</th><th class="text-end" width="25%">Nominal Bulanan</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach($detail_cf as $cf): ?>
                                        <tr>
                                            <td class="text-center fw-bold text-<?= $cf['tipe_cf']=='Pemasukan'?'success':'danger' ?>"><?= $cf['tipe_cf'] ?></td>
                                            <td class="text-center"><span class="badge bg-<?= $cf['entitas']=='Keluarga'?'primary':'success' ?>"><?= $cf['entitas'] ?></span></td>
                                            <td><?= htmlspecialchars($cf['nama_item']) ?></td>
                                            <td class="text-end fw-bold text-dark">Rp <?= number_format($cf['nominal_bulanan'],0,',','.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-light fw-bold">
                                    <tr><td colspan="3" class="text-end text-success">Total Pemasukan Konsolidasi:</td><td class="text-end text-success">Rp <?= number_format($rapor['total_pemasukan_cf'],0,',','.') ?></td></tr>
                                    <tr><td colspan="3" class="text-end text-danger">Total Pengeluaran & Angsuran:</td><td class="text-end text-danger">Rp <?= number_format($rapor['total_pengeluaran_cf'],0,',','.') ?></td></tr>
                                    <tr class="<?= $rapor['surplus_defisit'] >= 0 ? 'table-success' : 'table-danger' ?>"><td colspan="3" class="text-end text-uppercase">Surplus/Defisit Bersih Riil:</td><td class="text-end fs-6">Rp <?= number_format($rapor['surplus_defisit'],0,',','.') ?></td></tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-white shadow-sm h-100 small">
                                    <h6 class="fw-bold text-danger border-bottom pb-2"><i class="bi bi-heart-pulse-fill me-1"></i> Manajemen Risiko Aktuaria Kesehatan</h6>
                                    <?php if ($detail_kesehatan): ?>
                                        <div class="mb-1"><strong>Jaminan Kesehatan (BPJS):</strong> <?= $detail_kesehatan['punya_bpjs'] ?> (Kelas: <?= htmlspecialchars($detail_kesehatan['kelas_bpjs_asuransi']) ?: '-' ?>, Premi: Rp <?= number_format($detail_kesehatan['premi_perbulan'],0,',','.') ?>/bln)</div>
                                        <div class="mb-2"><strong>Asuransi Jiwa Tambahan:</strong> <?= $detail_kesehatan['punya_asuransi_jiwa'] ?> (Nilai UP: <span class="text-success fw-bold">Rp <?= number_format($detail_kesehatan['up_jiwa'],0,',','.') ?></span>)</div>
                                        <div class="p-2 bg-light border rounded mb-2">
                                            <div class="mb-1"><strong>Riwayat Sakit Umum (Rata-rata Usia):</strong> <?= htmlspecialchars($detail_kesehatan['riwayat_penyakit_umum']) ?: '-' ?></div>
                                            <div class="mb-0"><strong>Riwayat Penyakit Kronis/Kritis:</strong> <span class="text-danger fw-bold"><?= htmlspecialchars($detail_kesehatan['riwayat_penyakit_kronis']) ?: 'Tidak Ada' ?></span></div>
                                        </div>
                                        <div class="alert alert-warning mb-0 border-start border-4 py-1 px-2 fw-bold" style="font-size:0.8rem;"><i class="bi bi-shield-exclamation me-1"></i> Kesimpulan Aktuaris: <?= htmlspecialchars($detail_kesehatan['analisis_coverage']) ?></div>
                                    <?php else: ?><div class="text-muted fst-italic text-center py-4">Data asuransi belum terisi.</div><?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-white shadow-sm h-100 small">
                                    <h6 class="fw-bold text-primary border-bottom pb-2"><i class="bi bi-folder-check me-1"></i> Rencana & Kuantifikasi Biaya (RAB Rencana Masa Depan)</h6>
                                    <ul class="list-group list-group-flush mb-0">
                                        <?php $tot_rab = 0; if(!empty($detail_rab)): foreach($detail_rab as $rb): $tot_rab += (float)$rb['estimasi_biaya']; ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center p-1">
                                                <div><strong><?= htmlspecialchars($rb['item_rencana']) ?></strong> <span class="badge bg-light text-dark border p-1" style="font-size:0.65rem;"><?= $rb['entitas'] ?></span></div>
                                                <span class="fw-bold text-primary">Rp <?= number_format($rb['estimasi_biaya'],0,',','.') ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center bg-light fw-bold p-2 border-top mt-2"><span>Total Plafon Kebutuhan Modal (RAB):</span><span class="text-primary fs-6">Rp <?= number_format($tot_rab,0,',','.') ?></span></li>
                                        <?php else: ?><li class="list-group-item text-muted text-center py-4">Belum ada poin rencana anggaran biaya (RAB) yang dicatat.</li><?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning mb-0 border-start border-4 small mt-4 shadow-sm bg-white">
                            <h6 class="fw-bold mb-1"><i class="bi bi-chat-left-text-fill"></i> Narasi Sosial & Rekomendasi Tertulis Petugas Lapangan</h6>
                            <p class="mb-2 text-muted"><strong>Kondisi Fisik Rumah:</strong> <?= nl2br(htmlspecialchars($m_latest['kondisi_rumah_aset'])) ?: '-' ?></p>
                            <p class="mb-2 text-muted"><strong>Kondisi Gejala Sosial & Warga:</strong> Keluarga: <?= htmlspecialchars($m_latest['keharmonisan_keluarga']) ?> | Relasi Warga: <?= htmlspecialchars($m_latest['relasi_sosial_warga']) ?></p>
                            <div class="p-2 border rounded bg-light text-dark fw-bold"><i class="bi bi-lightbulb-fill text-warning me-1"></i> Rekomendasi/Langkah Intervensi LOS: <?= nl2br(htmlspecialchars($m_latest['kesimpulan_rekomendasi'])) ?></div>
                        </div>

                    <?php else: ?>
                        <div class="alert alert-light border shadow-sm py-5 text-center">
                            <i class="bi bi-clipboard2-x fs-1 d-block mb-3 text-secondary opacity-50"></i>
                            <h5 class="fw-bold">Belum Ada Riwayat Kunjungan / Lembar Appraisal</h5>
                            <p class="mb-0 text-muted small">Anggota ini belum memiliki data survei terstruktur. Silakan tambahkan instrumen appraisal baru.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tab-pane fade" id="tab-organisasi" role="tabpanel">
                    <div class="row mb-4 align-items-center">
                        <div class="col-md-7">
                            <h6 class="fw-bold text-primary mb-1"><i class="bi bi-diagram-2-fill"></i> Relasi Pengalaman Organisasi Calon Pengurus</h6>
                            <p class="text-muted small mb-0">Rancangan basis data relasional objektif terintegrasi bobot nilai untuk instrumen DSS.</p>
                        </div>
                        <div class="col-md-5 text-md-end mt-3 mt-md-0"><button class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahOrganisasi"><i class="bi bi-plus-circle-fill"></i> Tambah Portofolio Organisasi</button></div>
                    </div>
                    <div class="card bg-<?= $warna_potensi ?> bg-opacity-10 border-<?= $warna_potensi ?> mb-4 shadow-sm">
                        <div class="card-body py-3 d-flex flex-wrap justify-content-between align-items-center">
                            <div><h6 class="fw-bold text-<?= $warna_potensi ?> mb-1"><i class="bi bi-cpu-fill me-2"></i> Kualifikasi Kematangan Organisasi (Kriteria Kuantitatif SAW)</h6><div class="small text-muted">Matriks Hitung: Skor bobot Kategori, Jabatan, & Wilayah dari riwayat.</div></div>
                            <div class="text-md-end mt-2 mt-md-0"><span class="badge bg-<?= $warna_potensi ?> text-white fs-6 px-3 py-2 border border-light shadow-sm">Status: <?= htmlspecialchars($status_potensi) ?> | Kumulatif Skor: <?= $skor_potensi ?></span></div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle text-nowrap mb-0 small">
                            <thead class="table-light"><tr><th>Nama Organisasi</th><th>Kategori</th><th>Jabatan</th><th>Wilayah</th><th class="text-center">Masa Bakti</th><th class="text-center">SK Bukti</th><th class="text-center text-primary border-start border-end">Sub-Skor DSS</th><th class="text-center">Status</th></tr></thead>
                            <tbody>
                                <?php if (!empty($data_organisasi)): foreach ($data_organisasi as $org): ?>
                                    <tr><td class="fw-bold"><?= htmlspecialchars($org['nama_organisasi']) ?></td><td><?= htmlspecialchars($org['nama_kategori']) ?></td><td class="fw-bold"><?= htmlspecialchars($org['nama_jabatan']) ?></td><td><?= htmlspecialchars($org['nama_wilayah']) ?></td><td class="text-center"><?= htmlspecialchars($org['tahun_mulai']) ?> - <?= htmlspecialchars($org['tahun_selesai'] ?: 'Sekarang') ?></td><td class="text-center"><?= $org['file_bukti_sk'] ? '<span class="badge bg-danger">PDF SK</span>':'<span class="text-muted">-</span>' ?></td><td class="text-center bg-light fw-bold text-primary fs-6"><?= $org['skor_item'] ?></td><td class="text-center"><?= $org['is_verified'] == 1 ? '<span class="badge bg-success">Valid</span>':'<span class="badge bg-warning">Review</span>' ?></td></tr>
                                <?php endforeach; else: ?><tr><td colspan="8" class="text-center py-3 text-muted">Belum ada portofolio organisasi lokal.</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-diklat" role="tabpanel">
                    <div class="row mb-4 align-items-center">
                        <div class="col-md-7">
                            <h6 class="fw-bold text-primary mb-1"><i class="bi bi-mortarboard-fill"></i> Riwayat Pendidikan & Pelatihan CU</h6>
                            <p class="text-muted small mb-0">Track record literasi dan kaderisasi keanggotaan (Minimum Requirement DSS).</p>
                        </div>
                        <div class="col-md-5 text-md-end mt-3 mt-md-0"><button class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahDiklat"><i class="bi bi-plus-circle-fill"></i> Tambah Riwayat Diklat</button></div>
                    </div>
                    <div class="card bg-<?= $warna_diklat ?> bg-opacity-10 border-<?= $warna_diklat ?> mb-4 shadow-sm">
                        <div class="card-body py-3 d-flex flex-wrap justify-content-between align-items-center">
                            <div><h6 class="fw-bold text-<?= $warna_diklat ?> mb-1"><i class="bi bi-shield-check me-2"></i> Parameter Filter Rekrutmen (Syarat Mutlak)</h6><div class="small text-muted">Anggota diwajibkan lulus setidaknya <strong>Pendidikan Dasar CU (4 Kuadran)</strong>.</div></div>
                            <div class="text-md-end mt-2 mt-md-0"><?= $lulus_dikdas ? '<span class="badge bg-success fs-6 p-2">Lulus Syarat Minimal (Skor: '.$total_skor_diklat.')</span>' : '<span class="badge bg-danger fs-6 p-2">Belum Lulus Syarat Minimal</span>' ?></div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle text-nowrap mb-0 small">
                            <thead class="table-light"><tr><th>Nama Modul</th><th>Kategori</th><th>Tgl Pelaksanaan</th><th>Penyelenggara</th><th class="text-center">Sertifikat</th><th class="text-center text-primary border-start">Bobot</th></tr></thead>
                            <tbody>
                                <?php if (!empty($data_diklat)): foreach ($data_diklat as $diklat): ?>
                                    <tr><td class="fw-bold"><?= htmlspecialchars($diklat['nama_diklat']) ?></td><td><?= $diklat['kategori'] ?></td><td><?= date('d M Y', strtotime($diklat['tanggal_pelaksanaan'])) ?></td><td><?= htmlspecialchars($diklat['penyelenggara']) ?></td><td class="text-center"><?= $diklat['file_sertifikat'] ? '<span class="badge bg-success">Ada</span>':'<span class="text-muted">-</span>' ?></td><td class="text-center bg-light fw-bold text-primary">+<?= $diklat['bobot_nilai'] ?></td></tr>
                                <?php endforeach; else: ?><tr><td colspan="6" class="text-center py-4 text-muted">Belum ada riwayat pendidikan CU.</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-dokumen" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                        <div>
                            <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-folder2-open"></i> Arsip Digital & Dokumen Anggota</h6>
                            <p class="text-muted small mb-0">Manajemen dokumen paperless (KTP, KK, Foto Rumah/Aset, Formulir).</p>
                        </div>
                        <button class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahDokumen"><i class="bi bi-cloud-upload-fill"></i> Upload Dokumen Baru</button>
                    </div>
                    <?php if (!empty($data_dokumen)): ?>
                        <div class="row g-3">
                            <?php foreach ($data_dokumen as $doc): ?>
                                <div class="col-md-4">
                                    <div class="card shadow-sm border-0">
                                        <div class="card-body p-3 small">
                                            <span class="badge bg-primary mb-1"><?= htmlspecialchars($doc['kategori_dokumen']) ?></span>
                                            <h6 class="fw-bold text-truncate mb-0"><?= htmlspecialchars($doc['keterangan'] ?: 'File Dokumen') ?></h6>
                                            <span class="small text-muted d-block mb-2"><?= date('d M Y', strtotime($doc['tgl_upload'])) ?></span>
                                            <a href="uploads/dokumen/<?= $doc['nama_file'] ?>" target="_blank" class="btn btn-xs btn-outline-secondary w-100"><i class="bi bi-eye"></i> Lihat Berkas</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?><div class="alert alert-light text-center py-4 text-muted">Arsip digital masih kosong.</div><?php endif; ?>
                </div>

            </div>
        </div>
    </div>
    <?php endif; ?>
</main>


<div class="modal fade" id="modalTambahProfiling" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-house-door-fill me-2"></i> Form Instrumen Kunjungan & Lembar Appraisal LOS</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="profil.php?no_ba=<?= urlencode($no_ba) ?>" method="POST">
                <input type="hidden" name="action" value="tambah_profiling">
                
                <div class="modal-body p-0 bg-light">
                    <ul class="nav nav-tabs nav-fill bg-white border-bottom pt-2 px-2" id="surveiTabs" role="tablist">
                        <li class="nav-item"><button class="nav-link active fw-bold small" data-bs-toggle="tab" data-bs-target="#sm-umum" type="button">1. Profil & Kesehatan</button></li>
                        <li class="nav-item"><button class="nav-link fw-bold text-primary small" data-bs-toggle="tab" data-bs-target="#sm-aset" type="button">2. Appraisal Aset (+)</button></li>
                        <li class="nav-item"><button class="nav-link fw-bold text-danger small" data-bs-toggle="tab" data-bs-target="#sm-hutang" type="button">3. Beban Hutang Luar (+)</button></li>
                        <li class="nav-item"><button class="nav-link fw-bold text-success small" data-bs-toggle="tab" data-bs-target="#sm-cashflow" type="button">4. Aliran Arus Kas (+)</button></li>
                        <li class="nav-item"><button class="nav-link fw-bold text-warning small" data-bs-toggle="tab" data-bs-target="#sm-usaha" type="button">5. Siklus Multi-Usaha (+)</button></li>
                        <li class="nav-item"><button class="nav-link fw-bold text-info small" data-bs-toggle="tab" data-bs-target="#sm-rab" type="button">6. Poin RAB Rencana (+)</button></li>
                    </ul>

                    <div class="tab-content p-4" style="max-height: 60vh; overflow-y: auto;">
                        
                        <div class="tab-pane fade show active" id="sm-umum">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6"><label class="form-label small fw-bold">Tgl Survei *</label><input type="date" class="form-control" name="tgl_survei" value="<?= date('Y-m-d') ?>" required></div>
                                <div class="col-md-6"><label class="form-label small fw-bold">Nama Surveyor / Petugas Lapangan *</label><input type="text" class="form-control" name="nama_petugas" required placeholder="Ketik nama pemeriksa"></div>
                            </div>
                            <div class="mb-3"><label class="form-label small fw-bold">Kondisi Kelayakan Rumah Tinggal & Atribut Fisik</label><textarea class="form-control" name="kondisi_rumah_aset" rows="2" placeholder="Deskripsikan luas lahan, dinding, atap, status milik sendiri/sewa..."></textarea></div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6"><label class="form-label small fw-bold">Keharmonisan Keluarga</label><select class="form-select" name="keharmonisan_keluarga"><option value="Sangat Harmonis">Sangat Harmonis</option><option value="Cukup Harmonis" selected>Cukup Harmonis</option><option value="Rentan Konflik / Retak">Rentan Konflik / Retak</option></select></div>
                                <div class="col-md-6"><label class="form-label small fw-bold">Relasi Sosial Masyarakat & Kegiatan Warga</label><input type="text" class="form-control" name="relasi_sosial_warga" placeholder="Contoh: Pengurus RT, anggota tahlilan, warga biasa"></div>
                            </div>
                            
                            <div class="p-3 border rounded bg-white mt-4">
                                <h6 class="fw-bold text-danger border-bottom pb-2 mb-2"><i class="bi bi-heart-pulse"></i> Parameter Analisis Risiko Kesehatan Bulanan</h6>
                                <div class="row g-3 mb-2">
                                    <div class="col-md-4"><label class="form-label small fw-bold">Memiliki BPJS / Asuransi Sehat</label><select class="form-select" name="kes_bpjs"><option value="Ya" selected>Ya</option><option value="Tidak">Tidak</option></select></div>
                                    <div class="col-md-4"><label class="form-label small fw-bold">Kelas BPJS / Jenis Produk</label><input type="text" class="form-control" name="kes_kelas" placeholder="Contoh: Kelas 2 / Mandiri Inhealth"></div>
                                    <div class="col-md-4"><label class="form-label small fw-bold">Beban Premi Bulanan</label><div class="input-group"><span class="input-group-text">Rp</span><input type="text" class="form-control currency-input" name="kes_premi" placeholder="0"></div></div>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-4"><label class="form-label small fw-bold">Memiliki Asuransi Jiwa Tambahan</label><select class="form-select" name="kes_jiwa"><option value="Ya">Ya (Nilai Plus Kredit)</option><option value="Tidak" selected>Tidak</option></select></div>
                                    <div class="col-md-8"><label class="form-label small fw-bold">Nilai Uang Pertanggungan (UP) Jiwa</label><div class="input-group"><span class="input-group-text">Rp</span><input type="text" class="form-control currency-input" name="kes_up" placeholder="0"></div></div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6"><label class="form-label small fw-bold">Kelompok Penyakit Umum (Rata-rata Usia / Ringan)</label><input type="text" class="form-control" name="kes_umum" placeholder="Contoh: Flu, maag, pusing musiman"></div>
                                    <div class="col-md-6"><label class="form-label small fw-bold text-danger">Kelompok Penyakit Kronis / Kritis (Beban Finansial Tinggi)</label><input type="text" class="form-control border-danger" name="kes_kronis" placeholder="Contoh: Diabetes, Jantung, Stroke, Asma Menahun (Kosongkan jika sehat)"></div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="sm-aset">
                            <div class="alert alert-primary py-2 small mb-3"><i class="bi bi-info-circle-fill"></i> Tambah semua kepemilikan aset riil anggota satu per satu untuk menyusun laporan posisi Neraca secara otomatis.</div>
                            <div id="wrapper-aset">
                                <div class="row g-2 mb-2 p-2 border rounded bg-white item-row-aset">
                                    <div class="col-md-3"><label class="form-label small text-muted mb-1">Entitas Pemilik</label><select class="form-select form-select-sm" name="aset_entitas[]"><option value="Keluarga">Keluarga (Konsumtif)</option><option value="Usaha">Usaha (Produktif/Modal)</option></select></div>
                                    <div class="col-md-3"><label class="form-label small text-muted mb-1">Kategori Harta</label><select class="form-select form-select-sm" name="aset_kategori[]"><option value="Aset Lancar (Kas/Tabungan)">Aset Lancar (Kas/Tabungan Bank Luar)</option><option value="Aset Tetap (Tanah/Rumah)">Aset Tetap (Tanah/Rumah)</option><option value="Kendaraan">Kendaraan (Motor/Mobil)</option><option value="Peralatan/Mesin">Peralatan/Mesin Kerja</option><option value="Stok Barang/Ternak">Stok Barang / Hewan Ternak</option></select></div>
                                    <div class="col-md-3"><label class="form-label small text-muted mb-1">Nama Barang / Judul Aset</label><input type="text" class="form-control form-control-sm" name="aset_nama[]" placeholder="Contoh: Motor Honda Beat 2022"></div>
                                    <div class="col-md-2"><label class="form-label small text-muted mb-1">Nilai Taksasi Pasar</label><input type="text" class="form-control form-control-sm currency-input input-hitung-aset" name="aset_nilai[]" placeholder="0"></div>
                                    <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-sm btn-danger w-100 btn-remove-row" style="display:none;"><i class="bi bi-trash"></i></button></div>
                                    <div class="col-12 mt-1"><input type="text" class="form-control form-control-sm" name="aset_kondisi[]" placeholder="Tulis deskripsi detail menceritakan kondisi fisik & bukti kepemilikan aset..."></div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="btn-tambah-aset"><i class="bi bi-plus-circle"></i> Tambah Baris Aset Baru</button>
                        </div>

                        <div class="tab-pane fade" id="sm-hutang">
                            <div class="alert alert-danger py-2 small mb-3"><i class="bi bi-exclamation-triangle-fill"></i> Catat seluruh beban pinjaman anggota di tempat luar (Bank, Leasing, Pegadaian, Pinjol) sebagai pengurang likuiditas kredit.</div>
                            <div id="wrapper-hutang">
                                <div class="row g-2 mb-2 p-2 border rounded bg-white item-row-hutang">
                                    <div class="col-md-2"><label class="form-label small text-muted mb-1">Jenis Sifat</label><select class="form-select form-select-sm" name="hutang_entitas[]"><option value="Keluarga">Hutang Konsumtif</option><option value="Usaha">Hutang Produktif Usaha</option></select></div>
                                    <div class="col-md-3"><label class="form-label small text-muted mb-1">Nama Kreditur Lembaga Luar</label><input type="text" class="form-control form-control-sm" name="hutang_sumber[]" placeholder="Contoh: Bank BRI / FIF Leasing"></div>
                                    <div class="col-md-3"><label class="form-label small text-muted mb-1">Tujuan Penggunaan Dana</label><input type="text" class="form-control form-control-sm" name="hutang_tujuan[]" placeholder="Contoh: Sisa Kredit Motor / Modal Pupuk"></div>
                                    <div class="col-md-2"><label class="form-label small text-muted mb-1">Sisa O/S Pokok</label><input type="text" class="form-control form-control-sm currency-input" name="hutang_sisa[]" placeholder="0"></div>
                                    <div class="col-md-1"><label class="form-label small text-muted mb-1">Cicilan / Bln</label><input type="text" class="form-control form-control-sm currency-input" name="hutang_angsuran[]" placeholder="0"></div>
                                    <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-sm btn-danger w-100 btn-remove-row" style="display:none;"><i class="bi bi-trash"></i></button></div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="btn-tambah-hutang"><i class="bi bi-plus-circle"></i> Tambah Baris Hutang Luar</button>
                        </div>

                        <div class="tab-pane fade" id="sm-cashflow">
                            <div class="alert alert-success py-2 small mb-3"><i class="bi bi-cash-stack"></i> Rincian Arus Kas Bulanan Tetap. Pisahkan item pendapatan (Gaji/Omzet) dengan pengeluaran (Biaya Makan/Operasional Usaha) untuk mengukur kapasitas laba bersih.</div>
                            <div id="wrapper-cf">
                                <div class="row g-2 mb-2 p-2 border rounded bg-white item-row-cf">
                                    <div class="col-md-2"><label class="form-label small text-muted mb-1">Jenis Aliran</label><select class="form-select form-select-sm" name="cf_tipe[]"><option value="Pemasukan">Pemasukan (In)</option><option value="Pengeluaran">Pengeluaran (Out)</option></select></div>
                                    <div class="col-md-2"><label class="form-label small text-muted mb-1">Entitas Kantong</label><select class="form-select form-select-sm" name="cf_entitas[]"><option value="Keluarga">Dompet Keluarga</option><option value="Usaha">Operasional Usaha</option></select></div>
                                    <div class="col-md-5"><label class="form-label small text-muted mb-1">Nama Item Komponen Arus Kas</label><input type="text" class="form-control form-control-sm" name="cf_nama[]" placeholder="Contoh: Gaji Pokok, Hasil Dagang Sembako, Biaya Sekolah Anak, Belanja Bahan Baku"></div>
                                    <div class="col-md-2"><label class="form-label small text-muted mb-1">Nominal per Bulan</label><input type="text" class="form-control form-control-sm currency-input" name="cf_nominal[]" placeholder="0"></div>
                                    <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-sm btn-danger w-100 btn-remove-row" style="display:none;"><i class="bi bi-trash"></i></button></div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-success mt-2" id="btn-tambah-cf"><i class="bi bi-plus-circle"></i> Tambah Item Komponen Kas</button>
                        </div>

                        <div class="tab-pane fade" id="sm-usaha">
                            <div class="alert alert-warning py-2 small mb-3 text-dark"><i class="bi bi-brightness-high-fill"></i> Modul Manajemen Siklus Usaha: Cocok untuk pemetaan komoditas musiman (Petani Bawang, Peternak Sapi, Pedagang, Karyawan Kontrak) guna memprediksi modal HPP dan waktu jatuh tempo panen.</div>
                            <div id="wrapper-usaha">
                                <div class="row g-2 mb-3 p-3 border rounded bg-white item-row-usaha">
                                    <div class="col-md-3"><label class="form-label small text-muted mb-1">Kategori Kluster Usaha</label><select class="form-select form-select-sm" name="usaha_kategori[]"><option value="Petani">Sektor Agraria / Petani</option><option value="Peternak">Sektor Peternakan</option><option value="Pedagang">Sektor Perdagangan / Jasa</option><option value="Karyawan">Sektor Karyawan / Buruh</option></select></div>
                                    <div class="col-md-6"><label class="form-label small text-muted mb-1">Skala Cakupan / Deskripsi Luas Lahan / Komoditas</label><input type="text" class="form-control form-control-sm" name="usaha_deskripsi[]" placeholder="Contoh: Bertani Bawang Merah Lahan 5000m2 / Penggemukan 5 Ekor Sapi"></div>
                                    <div class="col-md-2"><label class="form-label small text-muted mb-1">Mulai Tanam/Kerja</label><input type="date" class="form-control form-control-sm" name="usaha_tgl_mulai[]"></div>
                                    <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-sm btn-danger w-100 btn-remove-row" style="display:none;"><i class="bi bi-trash"></i></button></div>
                                    
                                    <div class="col-md-12 mt-1"><input type="text" class="form-control form-control-sm" name="usaha_treatment[]" placeholder="Ketik rincian intervensi / treatment berkala (Contoh: Pola pupuk urea tahap 1 & 2, suntik vaksin berkala, kulakan grosir rutin)..."></div>
                                    
                                    <div class="col-md-4 mt-2"><label class="form-label small text-muted mb-0" style="font-size:0.75rem;">Total Kebutuhan Modal Kerja (HPP)</label><div class="input-group input-group-sm"><span class="input-group-text">Rp</span><input type="text" class="form-control currency-input" name="usaha_modal[]" placeholder="0"></div></div>
                                    <div class="col-md-4 mt-2"><label class="form-label small text-muted mb-0" style="font-size:0.75rem;">Estimasi Perkiraan Waktu Panen</label><input type="date" class="form-control form-control-sm" name="usaha_tgl_panen[]"></div>
                                    <div class="col-md-4 mt-2"><label class="form-label small text-muted mb-0" style="font-size:0.75rem;">Estimasi Pendapatan Kotor (Omzet Panen)</label><div class="input-group input-group-sm"><span class="input-group-text">Rp</span><input type="text" class="form-control currency-input" name="usaha_pendapatan[]" placeholder="0"></div></div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-warning text-dark mt-1" id="btn-tambah-usaha"><i class="bi bi-plus-circle"></i> Tambah Kluster Siklus Kerja Baru</button>
                        </div>

                        <div class="tab-pane fade" id="sm-rab">
                            <div class="alert alert-info py-2 small mb-3"><i class="bi bi-file-earmark-spreadsheet"></i> Kuantifikasi Harapan Anggota (RAB). Masukkan rencana berbiaya baik untuk renovasi rumah, modal ekspansi usaha, maupun dana pendidikan anak.</div>
                            <div id="wrapper-rab">
                                <div class="row g-2 mb-2 p-2 border rounded bg-white item-row-rab">
                                    <div class="col-md-3"><label class="form-label small text-muted mb-1">Sifat Keperluan</label><select class="form-select form-select-sm" name="rab_entitas[]"><option value="Keluarga">RAB Renovasi / Domestik Keluarga</option><option value="Usaha">RAB Pengembangan Ekspansi Usaha</option></select></div>
                                    <div class="col-md-6"><label class="form-label small text-muted mb-1">Item Rencana Tindak Lanjut / Harapan Mendesak</label><input type="text" class="form-control form-control-sm" name="rab_item[]" placeholder="Contoh: Pembelian Traktor Mini Diesel / Biaya Masuk Kuliah Anak Pertama"></div>
                                    <div class="col-md-2"><label class="form-label small text-muted mb-1">Estimasi Kebutuhan Biaya</label><input type="text" class="form-control form-control-sm currency-input" name="rab_biaya[]" placeholder="0"></div>
                                    <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-sm btn-danger w-100 btn-remove-row" style="display:none;"><i class="bi bi-trash"></i></button></div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-info text-dark mt-2" id="btn-tambah-rab"><i class="bi bi-plus-circle"></i> Tambah Poin Usulan RAB</button>
                        </div>

                    </div>
                    
                    <div class="p-4 bg-white border-top">
                        <label class="form-label small fw-bold text-success"><i class="bi bi-check-square"></i> Kesimpulan Kelayakan Hasil Analisis Lapangan & Langkah Rekomendasi Petugas (Wajib) *</label>
                        <textarea class="form-control border-success" name="rekomendasi_petugas" rows="3" placeholder="Tulis ringkasan keputusan analis: Mengapa anggota ini layak/tidak layak, bagaimana mitigasi risikonya, serta skema kredit yang disarankan..." required></textarea>
                    </div>
                </div>
                
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-cloud-arrow-up-fill"></i> Simpan & Eksekusi Lembar Appraisal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahKeluarga" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white"><h5 class="modal-title"><i class="bi bi-person-plus-fill me-2"></i> Tambah Data Keluarga / Prospek</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <form action="profil.php?no_ba=<?= urlencode($no_ba) ?>" method="POST">
                <input type="hidden" name="action" value="tambah_keluarga">
                <div class="modal-body bg-light">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6"><label class="form-label fw-bold small">NIK KTP *</label><input type="text" class="form-control" name="nik" required maxlength="16"></div>
                        <div class="col-md-6"><label class="form-label fw-bold small">Nama Lengkap *</label><input type="text" class="form-control" name="nama" required></div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6"><label class="form-label fw-bold small">Hubungan Keluarga *</label><select class="form-select" name="hubungan" required><option value="" selected disabled>Pilih...</option><option value="Suami">Suami</option><option value="Istri">Istri</option><option value="Anak">Anak</option><option value="Orang Tua">Orang Tua</option></select></div>
                        <div class="col-md-6"><label class="form-label fw-bold small">Nomor Kontak</label><input type="text" class="form-control" name="no_telp_wa"></div>
                    </div>
                    <div class="row g-3 mb-2">
                        <div class="col-md-4"><label class="form-label small">Tempat Lahir</label><input type="text" class="form-control" name="tempat_lahir"></div>
                        <div class="col-md-4"><label class="form-label small">Tanggal Lahir</label><input type="date" class="form-control" name="tgl_lahir"></div>
                        <div class="col-md-4"><label class="form-label small">Agama</label><input type="text" class="form-control" name="agama"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label small">Pendidikan</label><input type="text" class="form-control" name="pendidikan"></div>
                        <div class="col-md-4"><label class="form-label small">Pekerjaan</label><input type="text" class="form-control" name="pekerjaan"></div>
                        <div class="col-md-4"><label class="form-label small">Estimasi Penghasilan</label><input type="text" class="form-control currency-input" name="penghasilan"></div>
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
            <div class="modal-header bg-primary text-white"><h5 class="modal-title"><i class="bi bi-diagram-2-fill me-2"></i> Input Portofolio Kompetensi Organisasi</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <form action="profil.php?no_ba=<?= urlencode($no_ba) ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="tambah_organisasi">
                <div class="modal-body bg-light">
                    <div class="mb-3"><label class="form-label fw-bold small">Nama Lembaga / Organisasi *</label><input type="text" class="form-control" name="nama_organisasi" required></div>
                    <div class="mb-3"><label class="form-label fw-bold small">Kategori Sektor *</label><select class="form-select" name="id_kategori" required><?php foreach ($master_kategori as $kat): ?><option value="<?= $kat['id_kategori'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option><?php endforeach; ?></select></div>
                    <div class="mb-3"><label class="form-label fw-bold small">Jabatan Struktural *</label><select class="form-select" name="id_jabatan" required><?php foreach ($master_jabatan as $jab): ?><option value="<?= $jab['id_jabatan'] ?>"><?= htmlspecialchars($jab['nama_jabatan']) ?></option><?php endforeach; ?></select></div>
                    <div class="mb-3"><label class="form-label fw-bold small">Cakupan Wilayah *</label><select class="form-select" name="id_wilayah" required><?php foreach ($master_wilayah as $wil): ?><option value="<?= $wil['id_wilayah'] ?>"><?= htmlspecialchars($wil['nama_wilayah']) ?></option><?php endforeach; ?></select></div>
                    <div class="row g-3 mb-3"><div class="col-6"><label class="form-label fw-bold small">Tahun Mulai *</label><input type="number" class="form-control" name="tahun_mulai" required min="1950"></div><div class="col-6"><label class="form-label fw-bold small">Tahun Selesai</label><input type="text" class="form-control" name="tahun_selesai"></div></div>
                    <div class="mb-3"><label class="form-label fw-bold small">Unggah Bukti SK (PDF/Gambar)</label><input type="file" class="form-control" name="file_bukti_sk" accept=".pdf,.png,.jpg,.jpeg"></div>
                    <div><label class="form-label small">Keterangan / Catatan Prestasi</label><textarea class="form-control" name="keterangan" rows="2"></textarea></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan Organisasi</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahDiklat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white"><h5 class="modal-title"><i class="bi bi-mortarboard-fill me-2"></i> Input Riwayat Diklat Anggota</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <form action="profil.php?no_ba=<?= urlencode($no_ba) ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="tambah_diklat">
                <div class="modal-body bg-light">
                    <div class="mb-3"><label class="form-label fw-bold small">Program Pendidikan / Pelatihan *</label><select class="form-select" name="id_diklat" required><?php foreach ($master_diklat as $md): ?><option value="<?= $md['id_diklat'] ?>"><?= htmlspecialchars($md['nama_diklat']) ?> (<?= $md['kategori'] ?>)</option><?php endforeach; ?></select></div>
                    <div class="mb-3"><label class="form-label fw-bold small">Tanggal Pelaksanaan *</label><input type="date" class="form-control" name="tanggal_pelaksanaan" required></div>
                    <div class="mb-3"><label class="form-label fw-bold small">Lembaga Penyelenggara</label><input type="text" class="form-control" name="penyelenggara" placeholder="Internal CU / Puskopdit"></div>
                    <div><label class="form-label fw-bold small">Unggah Scan Sertifikat (Opsional)</label><input type="file" class="form-control" name="file_sertifikat" accept=".pdf,.png,.jpg,.jpeg"></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan Riwayat</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahDokumen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white"><h5 class="modal-title"><i class="bi bi-cloud-upload-fill me-2"></i> Upload Arsip Digital</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <form action="profil.php?no_ba=<?= urlencode($no_ba) ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="tambah_dokumen">
                <div class="modal-body bg-light">
                    <div class="mb-3"><label class="form-label fw-bold small">Kategori File Dokumen *</label><select class="form-select" name="kategori_dokumen" required><option value="KTP">Scan / Foto KTP</option><option value="Kartu Keluarga">Scan / Foto Kartu Keluarga (KK)</option><option value="Foto Rumah/Aset">Foto Rumah & Aset Fisik</option><option value="Foto Usaha">Foto Tempat Usaha / Lahan Pertanian</option><option value="Formulir Fisik">Scan Formulir Fisik CU</option><option value="Lainnya">Dokumen Pendukung Lainnya</option></select></div>
                    <div class="mb-3"><label class="form-label fw-bold small">Pilih File (PDF, JPG, PNG) *</label><input type="file" class="form-control" name="file_dokumen" accept=".pdf,.png,.jpg,.jpeg" required></div>
                    <div><label class="form-label fw-bold small">Keterangan Singkat</label><textarea class="form-control" name="keterangan" rows="2"></textarea></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Upload File</button></div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../app/Views/Layouts/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // SCRIPT UTAMA UNTUK EVENT TAMBAH / CLONE NODE DINAMIS FORM SURVEI
    function initDynamicRowEngine(btnId, wrapperId, rowClass) {
        const btnAdd = document.getElementById(btnId);
        const wrapper = document.getElementById(wrapperId);
        
        if (!btnAdd || !wrapper) return;

        btnAdd.addEventListener('click', function() {
            // Ambil row pertama sebagai blueprint cetakan
            const rows = wrapper.getElementsByClassName(rowClass);
            if (rows.length === 0) return;

            const blueprint = rows[0];
            const newRow = blueprint.cloneNode(true);

            // Bersihkan isi seluruh input/textarea di baris baru
            const inputs = newRow.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                } else {
                    input.value = '';
                }
            });

            // Tampilkan tombol hapus berkode merah di baris baru
            const btnRemove = newRow.querySelector('.btn-remove-row');
            if (btnRemove) {
                btnRemove.style.display = 'block';
                btnRemove.addEventListener('click', function() {
                    newRow.remove();
                });
            }

            // Daftarkan ulang listener format rupiah untuk field mata uang di baris baru
            const currencyInputs = newRow.querySelectorAll('.currency-input');
            currencyInputs.forEach(input => {
                bindRupiahFormatter(input);
            });

            wrapper.appendChild(newRow);
        });

        // Daftarkan listener hapus untuk baris bawaan jika nanti dikloning banyak
        const initialRemoveBtn = wrapper.querySelector('.' + rowClass + ' .btn-remove-row');
        if (initialRemoveBtn) {
            initialRemoveBtn.addEventListener('click', function() {
                const totalRows = wrapper.getElementsByClassName(rowClass);
                if (totalRows.length > 1) {
                    initialRemoveBtn.closest('.' + rowClass).remove();
                }
            });
        }
    }

    // FUNGSI STANDAR FORMAT RUPIAH VANILLA JS
    function bindRupiahFormatter(input) {
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
    }

    // EKSEKUSI LISTENERS FORM DINAMIS UNTUK KE-5 COMPONENT TAB 5
    initDynamicRowEngine('btn-tambah-aset', 'wrapper-aset', 'item-row-aset');
    initDynamicRowEngine('btn-tambah-hutang', 'wrapper-hutang', 'item-row-hutang');
    initDynamicRowEngine('btn-tambah-cf', 'wrapper-cf', 'item-row-cf');
    initDynamicRowEngine('btn-tambah-usaha', 'wrapper-usaha', 'item-row-usaha');
    initDynamicRowEngine('btn-tambah-rab', 'wrapper-rab', 'item-row-rab');

    // Ikat formatter rupiah awal untuk semua input yang sudah ada di halaman saat render
    const initialCurrencies = document.querySelectorAll('.currency-input');
    initialCurrencies.forEach(input => {
        bindRupiahFormatter(input);
    });

    // SCRIPT SAKLAR FILTER STATUS PORTOFOLIO KEUANGAN (TAB 3)
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
});
</script>