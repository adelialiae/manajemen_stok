<?php
$title = 'Transaksi Belanja';

require 'custControl.php';
require 'template/headerCust.php';
require 'template/sidebarCust.php';

$username = $_SESSION["username"];
$idTransaksi = $_GET["id"];

$detailTransaksi = query("SELECT * FROM transaksi
JOIN customer ON transaksi.username = customer.username
WHERE transaksi.idTransaksi = '$idTransaksi' AND transaksi.username = '$username';")[0];

$keranjangUser = query("SELECT * FROM keranjang
JOIN produk ON keranjang.idProduk = produk.idProduk
WHERE keranjang.username = '$username' AND keranjang.idTransaksi = '$idTransaksi';");

$tanggalTransaksi = strtotime($detailTransaksi["tanggalTransaksi"]);
$tanggalFormatted = date("j F Y", $tanggalTransaksi);

if(isset($_POST["submit"])) {
    if(feedback($_POST) > 0) {
        echo "<script>
            alert('Feedback berhasil dikirim!');
            document.location.href = 'viewTransaksi.php';
        </script>";
    } else {
        echo "<script>
            alert('Feedback gagal dikirim!');
            document.location.href = 'viewTransaksi.php';
        </script>";
    }
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1 class="text-danger">Detail Transaksi</h1>
    </div>

    <section class="section">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="container mt-3">
                        <div class="row align-items-center">
                            <center>
                                <img src="../img/logobaru.png" width="60px" class="mb-1">
                                <div class="col-md-10">
                                    <h2 class="mb-1"><strong class="text-danger">Cheerful HETO</strong></h2>
                                    <h4 class="mb-2">Laporan Belanja Anda</h4>
                                </div>
                            </center>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group">
                                <li class="list-group-item"><strong>Username:</strong> <?= $detailTransaksi["username"]; ?></li>
                                <li class="list-group-item"><strong>User ID:</strong> <?= $detailTransaksi["userID"]; ?></li> <!-- Tambahan -->
                                <li class="list-group-item"><strong>Nama:</strong> <?= $detailTransaksi["namaLengkap"]; ?></li>
                                <li class="list-group-item"><strong>Alamat:</strong> <?= $detailTransaksi["alamat"]; ?></li>
                                <li class="list-group-item"><strong>No. Telp:</strong> <?= $detailTransaksi["contact"]; ?></li>
                                <li class="list-group-item"><strong>Tanggal Transaksi:</strong> <?= $tanggalFormatted; ?></li>
                                <li class="list-group-item"><strong>ID Paypal:</strong> <?= $detailTransaksi["paypalID"]; ?></li>
                                <li class="list-group-item"><strong>Nama Bank:</strong> <?= $detailTransaksi["bank"]; ?></li>
                                <li class="list-group-item"><strong>Cara Bayar:</strong> <?= $detailTransaksi["caraBayar"]; ?></li>
                                <li class="list-group-item"><strong>Status Transaksi:</strong> <?= $detailTransaksi["statusTransaksi"]; ?></li>
                                <li class="list-group-item"><strong>Status Pengiriman:</strong> <?= $detailTransaksi["statusPengiriman"]; ?></li>
                            </ul>
                        </div>

                        <div class="card col">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th style="padding-left: 10px;">ID Produk</th>
                                        <th style="padding-left: 10px;">Nama Produk</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach($keranjangUser as $keranjang) : ?>
                                    <tr>
                                        <td><?= $i; ?></td>
                                        <td style="padding-left: 10px;"><?= $keranjang["idProduk"]; ?></td>
                                        <td style="padding-left: 10px;"><?= $keranjang["namaProduk"]; ?></td>
                                        <td><?= $keranjang["jumlah"]; ?></td>
                                        <td>Rp<?= number_format($keranjang["harga"], 0, ',', '.'); ?></td>
                                    </tr>
                                    <?php $i++; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="fw-bold">Total Harga (Termasuk PPN 11%): Rp<?= number_format($detailTransaksi["totalHarga"], 0, ',', '.'); ?></div>
                            <div class="mt-3 text-end">
                                <h5>Pemilik Toko</h5>
                                <img src="../img/TTD_ADELL_1-removebg-preview.png" alt="Tanda Tangan" style="width: 200px; height: 200px;">
                                <p class="fw-bold">Cheerful HETO</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="fw-bold">Feedback:</div>
                        <?= $detailTransaksi["feedBack"] ? $detailTransaksi["feedBack"] : "Belum ada feedback."; ?>
                    </div>

                    <?php if($detailTransaksi["feedBack"] == NULL && $detailTransaksi["statusPengiriman"] == "Terkirim" && $detailTransaksi["statusTransaksi"] != 'Cancelled') : ?>
                    <form action="" method="post" class="mt-3">
                        <input type="hidden" name="idTransaksi" value="<?= $detailTransaksi["idTransaksi"]; ?>">
                        <div class="mb-3">
                            <label for="feedbackInput" class="form-label">Berikan Feedback:</label>
                            <input type="text" class="form-control" id="feedbackInput" name="feedBack" required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-danger">Kirim Feedback</button>
                    </form>
                    <?php endif; ?>

                    <div class="mt-4 text-center">
                        <a href="cetakPDF.php?id=<?= $idTransaksi; ?>" class="btn btn-secondary mx-2">Cetak & Kirim PDF</a>
                        <a href="viewTransaksi.php" class="btn btn-warning mx-2">Kembali</a>
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>
