<?php
$title = 'Manajemen Stok Bahan Baku';

require 'adminControl.php';
require 'template/headerAdmin.php';
require 'template/sidebarAdmin.php';

// Proses pengurangan stok
if (isset($_POST['kurangi_stok'])) {
    $idBahan = $_POST['id_bahan'];
    $jumlahKurang = (int) $_POST['jumlah'];

    $stokSekarang = query("SELECT stokSisa FROM inventorystokbahan WHERE id_bahan = '$idBahan'")[0]['stokSisa'];

    if ($jumlahKurang > 0 && $stokSekarang >= $jumlahKurang) {
        mysqli_query($conn, "UPDATE inventorystokbahan SET stokSisa = stokSisa - $jumlahKurang WHERE id_bahan = '$idBahan'");
        echo "<script>alert('Stok berhasil dikurangi.');window.location='manajemenStok.php';</script>";
        exit;
    } else {
        echo "<script>alert('Jumlah pengurangan tidak valid atau stok tidak cukup.');</script>";
    }
}

// Data stok bahan baku
$inventoryBahan = query("
    SELECT 
        ib.id_bahan, 
        bb.nama_bahan, 
        bb.gambar, 
        bb.harga, 
        ib.stokSisa, 
        bb.id_supplier 
    FROM inventorystokbahan ib
    JOIN bahan_baku bb ON ib.id_bahan = bb.id_bahan
");

// Contoh rasio bahan baku per produk (1 produk butuh berapa bahan)
$rasioPerProduk = [
    'B001' => 2,
    'B002' => 3,
    'B003' => 1,
    // dst
];

// Proyeksi kebutuhan stok 3 bulan ke depan
// $proyeksiKebutuhan = [];
// foreach ($inventoryBahan as $bahan) {
//     $idBahan = $bahan['id_bahan'];
//     $stokSekarang = $bahan['stokSisa'];

//     $proyeksiBulan1 = round($stokSekarang * 0.9);
//     $proyeksiBulan2 = round($proyeksiBulan1 * 0.9);
//     $proyeksiBulan3 = round($proyeksiBulan2 * 0.9);

//     $proyeksiKebutuhan[$idBahan] = [$proyeksiBulan1, $proyeksiBulan2, $proyeksiBulan3];
// }
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
                    <h5>Kurangi Stok Bahan Baku untuk Produksi</h5>
                    <form method="POST" action="">
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="id_bahan" class="col-form-label">Pilih Bahan:</label>
                            </div>
                            <div class="col-auto">
                                <select name="id_bahan" id="id_bahan" class="form-select" required>
                                    <option value="" disabled selected>Pilih bahan</option>
                                    <?php foreach ($inventoryBahan as $bahan) : ?>
                                        <option value="<?= $bahan['id_bahan']; ?>">
                                            <?= htmlspecialchars($bahan['nama_bahan']); ?> (Stok: <?= $bahan['stokSisa']; ?>)
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

            <!-- Tabel inventory bahan baku -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-responsive">
                            <h5 class="card-title">Inventory Bahan Baku</h5>
                            <table class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Bahan</th>
                                        <th>Gambar</th>
                                        <th>Harga</th>
                                        <th>Stok Sekarang</th>
                                        <th>Bisa Jadi Produk</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($inventoryBahan as $bahan) :
                                        $idBahan = $bahan['id_bahan'];
                                        $stok = $bahan['stokSisa'];

                                        // Hitung produk yang bisa dibuat
                                        $rasio = $rasioPerProduk[$idBahan] ?? 1; // Default 1 jika tidak ada rasio
                                        $bisaJadiProduk = ($rasio > 0) ? floor($stok / $rasio) : 0;
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= htmlspecialchars($bahan['nama_bahan']); ?></td>
                                            <td>
                                                <?php if ($bahan['gambar']) : ?>
                                                    <img src="../img/<?= htmlspecialchars($bahan['gambar']); ?>" alt="" width="50">
                                                <?php else : ?>
                                                    Tidak ada gambar
                                                <?php endif; ?>
                                            </td>
                                            <td>Rp<?= number_format($bahan['harga'], 0, ',', '.'); ?></td>
                                            <td><?= htmlspecialchars($stok); ?></td>
                                            <td><?= $bisaJadiProduk; ?></td>
                                            <!-- <td><?= $proyeksiKebutuhan[$idBahan][0]; ?></td>
                                            <td><?= $proyeksiKebutuhan[$idBahan][1]; ?></td>
                                            <td><?= $proyeksiKebutuhan[$idBahan][2]; ?></td> -->
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($inventoryBahan)) : ?>
                                        <tr>
                                            <td colspan="9" class="text-center">Data tidak tersedia</td>
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

