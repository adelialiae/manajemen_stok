<?php
$title = 'Permintaan Pemesanan';
session_start();
require 'supplierControl.php';  // kontrol login supplier, validasi session dan koneksi db
require 'template/headerSupplier.php';

$idSupplier = $_SESSION['id_supplier'];  // id supplier yang login

// Proses konfirmasi pesanan
if (isset($_POST['konfirmasi'])) {
    $idTransaksi = $_POST['id_transaksi'];
    $aksi = $_POST['aksi'];
    $tanggalSekarang = date('Y-m-d');

    if ($aksi === 'terima') {
        $status = 'Diterima';
        $tanggal_terima = $tanggalSekarang;
    } elseif ($aksi === 'tolak') {
        $status = 'Ditolak';
        $tanggal_terima = null;
    }

    // Update status transaksi_pembelian
    $queryUpdate = "UPDATE transaksi_pembelian 
                    SET statusTransaksi = '$status', 
                        tanggal_terima = " . ($tanggal_terima ? "'$tanggal_terima'" : "NULL") . "
                    WHERE idTransaksi = '$idTransaksi'";

    if (mysqli_query($connect, $queryUpdate)) {
        echo "<script>alert('Status pemesanan berhasil diperbarui!'); window.location='permintaanPemesanan.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui status!');</script>";
    }
}

// Ambil data transaksi pembelian untuk supplier yang login
// Kita anggap transaksi_pembelian hanya menyimpan 1 record per transaksi tanpa detail bahan,
// jadi perlu join atau query lain untuk detail bahan jika perlu

$query = "
    SELECT tp.*, s.nama_supplier
    FROM transaksi_pembelian tp
    JOIN supplier s ON tp.id_supplier = s.id_supplier
    WHERE tp.id_supplier = '$idSupplier'
    ORDER BY tp.tanggal DESC
";

$result = mysqli_query($connect, $query);
$transaksi = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $transaksi[] = $row;
    }
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1 class="text-success">Permintaan Pemesanan dari Admin</h1>
    </div>

    <section class="section dashboard container">
        <div class="card">
            <div class="card-body table-responsive">
                <h5 class="card-title">Daftar Permintaan Pemesanan</h5>
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Bahan Baku</th>
                            <th>Status</th>
                            <th>Total Harga</th>
                            <th>Cara Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($transaksi)) : ?>
                            <?php $no = 1; foreach ($transaksi as $tr) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($tr['namaBahanBaku']); ?></td>
                                    <td><?= htmlspecialchars($tr['statusTransaksi']); ?></td>
                                    <td>Rp<?= number_format($tr['totalHarga'], 0, ',', '.'); ?></td>
                                    <td><?= htmlspecialchars($tr['caraBayar']); ?></td>
                                    <td>
                                        <?php if ($tr['statusTransaksi'] === 'Menunggu Konfirmasi') : ?>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="id_transaksi" value="<?= $tr['idTransaksi']; ?>">
                                                <button type="submit" name="aksi" value="terima" class="btn btn-success btn-sm" onclick="return confirm('Terima pesanan ini?')">Terima</button>
                                                <button type="submit" name="aksi" value="tolak" class="btn btn-danger btn-sm" onclick="return confirm('Tolak pesanan ini?')">Tolak</button>
                                                <input type="hidden" name="konfirmasi" value="1">
                                            </form>
                                        <?php else : ?>
                                            Tidak ada aksi
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data pesanan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>
