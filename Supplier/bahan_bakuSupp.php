<?php 
$title = "Daftar Bahan Baku Saya";

require 'supplierControl.php'; // cek session supplier
require 'template/headerSupplier.php';
require 'template/sidebarSupplier.php';

$idSupplier = $_SESSION['id_supplier'] ?? null;
if (!$idSupplier) {
    echo "<script>alert('Harap login terlebih dahulu!');window.location='login.php';</script>";
    exit;
}

// Ambil data bahan baku supplier
$bahanSupplier = query("SELECT * FROM bahan_baku WHERE id_supplier = '$idSupplier'");

?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1 class="text-danger">Daftar Bahan Baku Saya</h1>
    </div>

    <section class="section">
        <div class="mb-3">
            <a href="tambahBahanSupplier.php" class="btn btn-danger">Tambah Bahan Baku</a>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>ID Supplier</th> <!-- Tambah kolom ini -->
                        <th>Nama Bahan</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Gambar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($bahanSupplier): ?>
                        <?php $no = 1; foreach ($bahanSupplier as $bahan): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($bahan['id_supplier']); ?></td> <!-- Tampilkan ID Supplier -->
                            <td><?= htmlspecialchars($bahan['nama_bahan']); ?></td>
                            <td>Rp<?= number_format($bahan['harga'],0,',','.'); ?></td>
                            <td><?= $bahan['stok']; ?></td>
                            <td>
                                <?php if ($bahan['gambar']): ?>
                                    <img src="../img/<?= htmlspecialchars($bahan['gambar']); ?>" width="80">
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">Belum ada bahan baku.</td></tr>
                    <?php endif; ?>
                </tbody>
                </table>
            </div>
        </div>
    </section>
</main>
