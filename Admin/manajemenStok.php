<?php
$title = 'Manajemen Stok Bahan Baku';

require 'adminControl.php';
require 'template/headerAdmin.php';
require 'template/sidebarAdmin.php';

// Jumlah produksi per hari (dalam botol)
$produksiHarian = 500;

// Rasio khusus untuk bahan dengan ID tertentu (jika tidak di rasio_bahan_produk)
$rasioFiberCream = 1 / 100; // 0.01 kg/botol
$rasioDaunSuji = 2 / 100;   // 0.02 kg/botol

// Proses PRODUKSI OTOMATIS
if (isset($_POST['produksi_otomatis'])) {
    $produksiHarian = 500;
    $tanggalHariIni = date('Y-m-d');

    // Ambil semua produk yang stok-nya rendah (misal < 100)
    $queryProdukRendah = mysqli_query($connect, "SELECT idProduk FROM produkjadi WHERE stokProduk < 100");

    while ($produk = mysqli_fetch_assoc($queryProdukRendah)) {
        $idProduk = $produk['idProduk'];

        // Ambil rasio bahan dari idProduk ini
        $queryRasio = mysqli_query($connect, "SELECT id_bahan, rasio_penggunaan FROM rasio_bahan_produk WHERE idProduk = '$idProduk'");
        
        $cukupSemua = true;
        $bahanList = [];

        while ($row = mysqli_fetch_assoc($queryRasio)) {
            $idBahan = $row['id_bahan'];
            $rasio = $row['rasio_penggunaan'];
            $jumlahKurang = $produksiHarian * $rasio;

            // Cek stok sekarang
            $stokNow = query("SELECT stokSisa FROM inventorystokbahan WHERE id_bahan = '$idBahan'")[0]['stokSisa'];

            if ($stokNow < $jumlahKurang) {
                $cukupSemua = false;
                break;
            }

            $bahanList[] = ['id' => $idBahan, 'jumlah' => $jumlahKurang];
        }

        // Jika semua bahan cukup, lakukan pengurangan stok dan update stok produk
        if ($cukupSemua) {
            foreach ($bahanList as $b) {
                mysqli_query($connect, "UPDATE inventorystokbahan SET stokSisa = stokSisa - {$b['jumlah']} WHERE id_bahan = '{$b['id']}'");
                mysqli_query($connect, "INSERT INTO permintaan_harian (id_bahan, tanggal, jumlah) VALUES ('{$b['id']}', '$tanggalHariIni', '{$b['jumlah']}')");
            }

            // Tambah stok produk jadi
            mysqli_query($connect, "UPDATE produkjadi SET stokProduk = stokProduk + $produksiHarian WHERE idProduk = '$idProduk'");
        }
    }

    echo "<script>alert('Produksi berhasil! Stok bahan baku telah dikurangi.');window.location='manajemenstok.php';</script>";
    exit;
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
$queryRasio = "SELECT id_bahan, rasio_penggunaan FROM rasio_bahan_produk";
$resultRasio = mysqli_query($connect, $queryRasio);

$rasioBahan = [];
while ($row = mysqli_fetch_assoc($resultRasio)) {
    $rasioBahan[$row['id_bahan']] = $row['rasio_penggunaan'];
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1 class="text-danger">Manajemen Stok Bahan Baku</h1>
    </div>

    <section class="section dashboard container">
        <div class="container">
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
                                        if ($idBahan == 1) {
                                            $kebutuhan = $produksiHarian * $rasioFiberCream;
                                        } elseif ($idBahan == 2) {
                                            $kebutuhan = $produksiHarian * $rasioDaunSuji;
                                        } else {
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
