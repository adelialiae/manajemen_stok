<?php
$title = 'Manajemen Stok Bahan Baku';

require 'adminControl.php';
require 'template/headerAdmin.php';
require 'template/sidebarAdmin.php';

// Proses pengurangan stok manual
if (isset($_POST['kurangi_stok'])) {
    $idBahan = mysqli_real_escape_string($connect, $_POST['id_bahan']);
    $jumlahKurang = (int)$_POST['jumlah'];

    // Cek stok dulu
    $queryStok = "SELECT stokSisa FROM inventorystokbahan WHERE id_bahan = ?";
    $stmtStok = mysqli_prepare($connect, $queryStok);
    mysqli_stmt_bind_param($stmtStok, "i", $idBahan);
    mysqli_stmt_execute($stmtStok);
    $resultStok = mysqli_stmt_get_result($stmtStok);
    $stokSekarang = mysqli_fetch_assoc($resultStok)['stokSisa'];
    mysqli_stmt_close($stmtStok);

    if ($jumlahKurang > 0 && $stokSekarang >= $jumlahKurang) {
        // Kurangi stok
        $queryUpdate = "UPDATE inventorystokbahan SET stokSisa = stokSisa - ? WHERE id_bahan = ?";
        $stmtUpdate = mysqli_prepare($connect, $queryUpdate);
        mysqli_stmt_bind_param($stmtUpdate, "ii", $jumlahKurang, $idBahan);
        mysqli_stmt_execute($stmtUpdate);
        mysqli_stmt_close($stmtUpdate);

        // Tambahkan log ke permintaan_harian
        $tanggalHariIni = date('Y-m-d');
        $queryLog = "INSERT INTO permintaan_harian (id_bahan, tanggal, jumlah) VALUES (?, ?, ?)";
        $stmtLog = mysqli_prepare($connect, $queryLog);
        mysqli_stmt_bind_param($stmtLog, "isd", $idBahan, $tanggalHariIni, $jumlahKurang);
        mysqli_stmt_execute($stmtLog);
        mysqli_stmt_close($stmtLog);

        echo "<script>alert('Stok berhasil dikurangi dan log permintaan disimpan.');window.location='manajemenstok.php';</script>";
        exit;
    } else {
        echo "<script>alert('Jumlah pengurangan tidak valid atau stok tidak cukup.');</script>";
    }
}

// Ambil data inventory
$queryInventory = "
    SELECT 
        ib.id_bahan, 
        ib.namaBahan, 
        ib.stokSisa, 
        ib.tanggalUpdate, 
        bb.gambar, 
        bb.harga 
    FROM inventorystokbahan ib
    LEFT JOIN bahan_baku bb ON ib.id_bahan = bb.id_bahan
";
$resultInventory = mysqli_query($connect, $queryInventory);

$inventoryBahan = [];
while ($row = mysqli_fetch_assoc($resultInventory)) {
    $inventoryBahan[] = $row;
}

// Ambil rasio dari tabel rasio_bahan_produk
$queryRasio = "SELECT id_bahan, rasio_penggunaan FROM rasio_bahan_produk WHERE idProduk = 'PRD-1749269312'";
$resultRasio = mysqli_query($connect, $queryRasio);

$rasioBahan = [];
while ($row = mysqli_fetch_assoc($resultRasio)) {
    $rasioBahan[$row['id_bahan']] = $row['rasio_penggunaan'];
}

// Hitung kebutuhan harian
$produksiHarian = 500;
$rasioFiberCream = 1 / 100; // 0.01 kg/botol
$rasioDaunSuji = 5 / 100;   // 0.05 kg/botol

?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1 class="text-danger">Manajemen Stok Bahan Baku</h1>
    </div>

    <section class="section dashboard container">
        <div class="container">
            <!-- Form pengurangan stok -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5>Kurangi Stok Bahan Baku</h5>
                    <form method="POST" action="">
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="id_bahan" class="col-form-label">Pilih Bahan:</label>
                            </div>
                            <div class="col-auto">
                                <select name="id_bahan" id="id_bahan" class="form-select" required>
                                    <option value="" disabled selected>Pilih bahan</option>
                                    <?php foreach ($inventoryBahan as $bahan) : ?>
                                        <option value="<?= htmlspecialchars($bahan['id_bahan']); ?>">
                                            <?= htmlspecialchars($bahan['namaBahan']); ?> (Stok: <?= $bahan['stokSisa']; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label for="jumlah" class="col-form-label">Jumlah yang dipakai:</label>
                            </div>
                            <div class="col-auto">
                                <input type="number" name="jumlah" id="jumlah" min="1" class="form-control" required>
                            </div>
                            <div class="col-auto">
                                <button type="submit" name="kurangi_stok" class="btn btn-danger">Kurangi Stok</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel inventory & kebutuhan harian -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-responsive">
                            <h5 class="card-title">Inventory Bahan Baku & Kebutuhan Harian</h5>
                            <table class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Bahan</th>
                                        <th>Gambar</th>
                                        <th>Harga</th>
                                        <th>Stok Saat Ini</th>
                                        <th>Kebutuhan 1 Hari</th>
                                        <th>Sisa Stok Setelah Kebutuhan</th>
                                        <th>Update Terakhir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($inventoryBahan as $bahan) :
                                        $idBahan = $bahan['id_bahan'];
                                        $stok = $bahan['stokSisa'];
                                        $tanggalUpdate = $bahan['tanggalUpdate'];

                                        // Hitung kebutuhan harian
                                        if ($idBahan == 1) { // Fiber Cream
                                            $kebutuhan = $produksiHarian * $rasioFiberCream;
                                        } elseif ($idBahan == 2) { // Daun Suji
                                            $kebutuhan = $produksiHarian * $rasioDaunSuji;
                                        } else { // Lainnya
                                            $rasio = $rasioBahan[$idBahan] ?? 0;
                                            $kebutuhan = $produksiHarian * $rasio;
                                        }

                                        $sisa = $stok - $kebutuhan;
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= htmlspecialchars($bahan['namaBahan']); ?></td>
                                            <td>
                                                <?php if ($bahan['gambar']) : ?>
                                                    <img src="../img/<?= htmlspecialchars($bahan['gambar']); ?>" alt="" width="50">
                                                <?php else : ?>
                                                    Tidak ada gambar
                                                <?php endif; ?>
                                            </td>
                                            <td>Rp<?= number_format($bahan['harga'], 0, ',', '.'); ?></td>
                                            <td><?= htmlspecialchars($stok); ?></td>
                                            <td><?= number_format($kebutuhan, 2); ?></td>
                                            <td>
                                                <?= ($sisa < 0)
                                                    ? "<span style='color:red;'>$sisa (Kurang!)</span>"
                                                    : number_format($sisa, 2); ?>
                                            </td>
                                            <td><?= htmlspecialchars($tanggalUpdate); ?></td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php if (empty($inventoryBahan)) : ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Data tidak tersedia</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

