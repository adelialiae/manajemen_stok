<?php
$title = 'Kelola Retur';
require 'supplierControl.php';
require 'template/headerSupplier.php';

$idSupplier = $_SESSION['id_supplier'];

// Proses konfirmasi retur
if (isset($_POST['konfirmasi'])) {
    $idTransaksi = $_POST['id_transaksi'];
    $aksi = $_POST['aksi'];

    if ($aksi === 'terima') {
        $statusTransaksi = 'retur_diterima';
    } elseif ($aksi === 'tolak') {
        $statusTransaksi = 'retur_ditolak';
    }

    // Update status
    $queryUpdate = "UPDATE transaksi_pembelian 
                     SET statusTransaksi = '$statusTransaksi'
                     WHERE idTransaksi = '$idTransaksi'";

    if (mysqli_query($connect, $queryUpdate)) {
        echo "<script>alert('Status retur berhasil diperbarui!'); window.location='kelola_retur.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui status retur!');</script>";
    }
}

// Ambil daftar transaksi retur
$queryRetur = "
    SELECT tp.*, s.nama_supplier,
        GROUP_CONCAT(CONCAT(bb.nama_bahan, ' (', dtp.qty, ')') SEPARATOR ', ') AS namaBahanBaku
    FROM transaksi_pembelian tp
    JOIN supplier s ON tp.id_supplier = s.id_supplier
    JOIN detail_transaksi_pembelian dtp ON tp.idTransaksi = dtp.idTransaksi
    JOIN bahan_baku bb ON dtp.id_bahan = bb.id_bahan
    WHERE tp.id_supplier = '$idSupplier' AND tp.statusTransaksi = 'retur'
    GROUP BY tp.idTransaksi
    ORDER BY tp.tanggal DESC
";
$resultRetur = mysqli_query($connect, $queryRetur);
$returList = [];
if ($resultRetur) {
    while ($row = mysqli_fetch_assoc($resultRetur)) {
        $returList[] = $row;
    }
}

// Jika tombol "Detail" ditekan, ambil data detail retur
$detailTransaksi = [];
if (isset($_GET['detail'])) {
    $idDetail = $_GET['detail'];
    $queryDetail = "
        SELECT dtp.*, bb.nama_bahan 
        FROM detail_transaksi_pembelian dtp
        JOIN bahan_baku bb ON dtp.id_bahan = bb.id_bahan
        WHERE dtp.idTransaksi = '$idDetail'
    ";
    $resultDetail = mysqli_query($connect, $queryDetail);
    if ($resultDetail) {
        while ($row = mysqli_fetch_assoc($resultDetail)) {
            $detailTransaksi[] = $row;
        }
    }
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1 class="text-success">Kelola Permintaan Retur</h1>
    </div>

    <section class="section dashboard container">
        <div class="card mb-4">
            <div class="card-body table-responsive">
                <h5 class="card-title">Daftar Retur dari Admin</h5>
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Bahan Baku</th>
                            <th>Status</th>
                            <th>Total Harga</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($returList)) : ?>
                            <?php $no = 1; foreach ($returList as $r) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($r['namaBahanBaku']); ?></td>
                                    <td><?= htmlspecialchars($r['statusTransaksi']); ?></td>
                                    <td>Rp<?= number_format($r['totalHarga'], 0, ',', '.'); ?></td>
                                    <td><?= htmlspecialchars($r['tanggal']); ?></td>
                                    <td>
                                        <?php if ($r['statusTransaksi'] === 'retur') : ?>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="id_transaksi" value="<?= $r['idTransaksi']; ?>">
                                                <button type="submit" name="aksi" value="terima" class="btn btn-success btn-sm" onclick="return confirm('Terima retur ini?')">Setujui</button>
                                                <button type="submit" name="aksi" value="tolak" class="btn btn-danger btn-sm" onclick="return confirm('Tolak retur ini?')">Tolak</button>
                                                <input type="hidden" name="konfirmasi" value="1">
                                            </form>
                                        <?php else : ?>
                                            <?= htmlspecialchars($r['statusTransaksi']); ?>
                                        <?php endif; ?>
                                        <a href="?detail=<?= $r['idTransaksi']; ?>" class="btn btn-info btn-sm">Detail</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada permintaan retur</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if (!empty($detailTransaksi)) : ?>
            <div class="card">
                <div class="card-body table-responsive">
                    <h5 class="card-title">Detail Retur</h5>
                    <table class="table table-bordered table-hover">
                        <thead class="table-secondary">
                            <tr>
                                <th>No</th>
                                <th>Nama Bahan</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($detailTransaksi as $d) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($d['nama_bahan']); ?></td>
                                    <td><?= htmlspecialchars($d['qty']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <a href="kelola_retur.php" class="btn btn-secondary btn-sm">Tutup Detail</a>
                </div>
            </div>
        <?php endif; ?>
    </section>
</main>

