<?php
require 'supplierControl.php';  // kontrol login supplier, validasi session dan koneksi db
require 'template/headerSupplier.php';

if (!isset($_GET['idTransaksi'])) {
    echo "<script>alert('ID Transaksi tidak ditemukan'); window.location.href='kelola_retur.php';</script>";
    exit;
}

$idTransaksi = $_GET['idTransaksi'];

// Ambil detail barang yang diretur
$queryDetail = mysqli_query($connect, "
    SELECT d.*, b.nama_bahan 
    FROM detail_transaksi_pembelian d
    JOIN bahan_baku b ON d.id_bahan = b.id_bahan
    WHERE d.idTransaksi = '$idTransaksi'
");

?>

<h2>Detail Retur Transaksi <?= htmlspecialchars($idTransaksi); ?></h2>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Nama Bahan</th>
            <th>Jumlah</th>
            <th>Harga</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($detail = mysqli_fetch_assoc($queryDetail)): ?>
        <tr>
            <td><?= htmlspecialchars($detail['nama_bahan']); ?></td>
            <td><?= htmlspecialchars($detail['qty']); ?></td>
            <td>Rp <?= number_format($detail['harga']); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<a href="kelola_retur.php">Kembali ke Daftar Retur</a>
