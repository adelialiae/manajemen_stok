<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">

    <!-- Dashboard -->
    <li class="nav-item">
        <a class="nav-link <?php echo($title == 'Dashboard') ? 'text-danger' : 'collapsed'; ?>" href="dashboard.php">
            <i class="bi bi-house-door-fill"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?php echo($title == 'Manajemen Stok') ? 'text-danger' : 'collapsed'; ?>" href="bahan_baku.php">
            <i class="bi bi-archive-fill"></i>
            <span>Bahan Baku</span>
        </a>
    </li>

    <!-- Produk Bahan Jadi -->
    <li class="nav-item">
        <a class="nav-link <?php echo($title == 'Produk Bahan Jadi') ? 'text-danger' : 'collapsed'; ?>" href="permintaanPemesanan.php">
            <i class="bi bi-grid"></i>
            <span>Permintaan Pemesanan</span>
        </a>
    </li>

    <!-- Bahan Baku -->
    <li class="nav-item">
        <a class="nav-link <?php echo($title == 'Bahan Baku') ? 'text-danger' : 'collapsed'; ?>" href="kelola_retur.php">
            <i class="bi bi-basket-fill"></i>
            <span>Kelola Retur</span>
        </a>
    </li>

</ul>

</aside><!-- End Sidebar-->
