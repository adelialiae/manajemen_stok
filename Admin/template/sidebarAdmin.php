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

    <!-- Produk Bahan Jadi -->
    <li class="nav-item">
        <a class="nav-link <?php echo($title == 'Produk Bahan Jadi') ? 'text-danger' : 'collapsed'; ?>" href="produkAdmin.php">
            <i class="bi bi-grid"></i>
            <span>Produk Bahan Jadi</span>
        </a>
    </li>

    <!-- Bahan Baku -->
    <li class="nav-item">
        <a class="nav-link <?php echo($title == 'Bahan Baku') ? 'text-danger' : 'collapsed'; ?>" href="bahan_baku.php">
            <i class="bi bi-basket-fill"></i>
            <span>Bahan Baku</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?php echo($title == 'Keranjang Bahan Baku') ? 'text-danger' : 'collapsed'; ?>" href="viewKeranjang.php">
            <i class="bi bi-basket-fill"></i>
            <span>Keranjang Pembelian Bahan Baku</span>
        </a>
    </li>

    <!-- Manajemen Stok -->
    <li class="nav-item">
        <a class="nav-link <?php echo($title == 'Manajemen Stok') ? 'text-danger' : 'collapsed'; ?>" href="manajemenStok.php">
            <i class="bi bi-archive-fill"></i>
            <span>Manajemen Stok</span>
        </a>
    </li>

    <!-- Transaksi & Pemesanan -->
    <li class="nav-item">
        <a class="nav-link <?php echo($title == 'Transaksi & Pemesanan') ? 'text-danger' : 'collapsed'; ?>" href="viewTransaksi.php">
            <i class="bi bi-cart-fill"></i>
            <span>Transaksi & Pemesanan</span>
        </a>
    </li>

    <!-- Supplier -->
    <li class="nav-item">
        <a class="nav-link <?php echo($title == 'Supplier') ? 'text-danger' : 'collapsed'; ?>" href="supplier.php">
            <i class="bi bi-truck"></i>
            <span>Supplier</span>
        </a>
    </li>

</ul>

</aside><!-- End Sidebar-->
