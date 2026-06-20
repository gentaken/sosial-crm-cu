<div class="offcanvas offcanvas-start sidebar-offcanvas sidebar" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title text-primary fw-bold" id="sidebarMenuLabel">Menu Navigasi</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-3">
        <ul class="nav flex-column">
            
            <li class="nav-item">
                <a class="nav-link <?= ($active_menu == 'dashboard') ? 'active' : '' ?>" href="dashboard.php">
                    <i class="bi bi-grid"></i> Beranda
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($active_menu == 'pencarian') ? 'active' : '' ?>" href="index.php">
                    <i class="bi bi-search"></i> Cari Anggota
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($active_menu == 'kunjungan') ? 'active' : '' ?>" href="#">
                    <i class="bi bi-calendar-check"></i> Rencana Kunjungan
                </a>
            </li>
            
            <?php if (is_admin()): ?>
            <hr class="my-2">
            <small class="text-muted fw-bold px-3 py-1 d-block mb-1" style="font-size: 11px;">ADMINISTRATOR</small>
            <li class="nav-item">
                <a class="nav-link <?= ($active_menu == 'pengaturan') ? 'active' : '' ?>" href="pengaturan.php">
                    <i class="bi bi-gear"></i> Pengaturan Instansi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($active_menu == 'mapping') ? 'active' : '' ?>" href="#">
                    <i class="bi bi-database-gear"></i> Mapping Database
                </a>
            </li>
            <?php endif; ?>
            
            <hr class="my-2">
            <li class="nav-item">
                <a class="nav-link text-danger" href="logout.php">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </a>
            </li>
        </ul>
    </div>
</div>