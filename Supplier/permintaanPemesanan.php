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

    if ($aksi === 'terima') {
    $statusSupplier = 'menyetujui';
    $statusTransaksi = 'diterima supplier'; // sesuai logika
    } elseif ($aksi === 'tolak') {
        $statusSupplier = 'menolak';
        $statusTransaksi = 'ditolak supplier';
    }

    $queryUpdate = "UPDATE transaksi_pembelian 
                    SET statusSupplier = '$statusSupplier',
                        statusTransaksi = '$statusTransaksi'
                    WHERE idTransaksi = '$idTransaksi'";


    if (mysqli_query($connect, $queryUpdate)) {
    echo "<script>
        alert('Status persetujuan berhasil diperbarui!');
        window.open('invoice.php?id=$idTransaksi', '_blank');
        window.location='permintaanPemesanan.php';
    </script>";
    exit;
}
    
}


// Ambil data transaksi pembelian untuk supplier yang login
// Kita anggap transaksi_pembelian hanya menyimpan 1 record per transaksi tanpa detail bahan,
// jadi perlu join atau query lain untuk detail bahan jika perlu

$query = "
  SELECT tp.*, s.nama_supplier,
    GROUP_CONCAT(CONCAT(bb.nama_bahan, ' (', dtp.qty, ')') SEPARATOR ', ') AS namaBahanBaku
  FROM transaksi_pembelian tp
  JOIN supplier s ON tp.id_supplier = s.id_supplier
  JOIN detail_transaksi_pembelian dtp ON tp.idTransaksi = dtp.idTransaksi
  JOIN bahan_baku bb ON dtp.id_bahan = bb.id_bahan
  WHERE tp.id_supplier = '$idSupplier'
  GROUP BY tp.idTransaksi
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
                                        <?php if ($tr['statusSupplier'] === 'menunggu') : ?>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="id_transaksi" value="<?= $tr['idTransaksi']; ?>">
                                                <button type="submit" name="aksi" value="terima" class="btn btn-success btn-sm" onclick="return confirm('Terima pesanan ini?')">Setujui</button>
                                                <button type="submit" name="aksi" value="tolak" class="btn btn-danger btn-sm" onclick="return confirm('Tolak pesanan ini?')">Tolak</button>
                                                <input type="hidden" name="konfirmasi" value="1">
                                            </form>
                                        <?php elseif ($tr['statusSupplier'] === 'menyetujui') : ?>
                                            <span class="badge bg-success">Disetujui</span><br>
                                            <a href="invoice.php?id=<?= $tr['idTransaksi']; ?>" target="_blank" class="btn btn-warning btn-sm mt-1">Cetak Invoice</a>
                                        <?php elseif ($tr['statusSupplier'] === 'menolak') : ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php else : ?>
                                            <?= htmlspecialchars($tr['statusSupplier']); ?>
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
