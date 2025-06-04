<?php 
ob_start();

$title = 'Keranjang Belanja';

require 'custControl.php';
require 'template/headerCust.php';
require 'template/sidebarCust.php';

$username = $_SESSION["username"];
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idKeranjang'], $_POST['jumlah'])) {
    $idKeranjang = $_POST['idKeranjang'];
    $jumlahBaru = (int) $_POST['jumlah'];

    // Ambil semua data yg dibutuhkan sekali aja
    $keranjang = query("SELECT keranjang.idProduk, keranjang.jumlah AS jumlahLama, produk.hargaProduk, produkJadi.stokProduk 
                        FROM keranjang 
                        JOIN produkJadi ON keranjang.idProduk = produkJadi.idProduk 
                        WHERE keranjang.idKeranjang = '$idKeranjang'")[0];

    $idProduk = $keranjang['idProduk'];
    $jumlahLama = (int) $keranjang['jumlahLama'];
    $hargaProduk = (int) $keranjang['hargaProduk'];
    $stokSaatIni = (int) $keranjang['stokProduk'];

    $selisihJumlah = $jumlahBaru - $jumlahLama;

    if ($selisihJumlah > 0) { // Kalau user mau tambah barang
        if ($stokSaatIni >= $selisihJumlah) {
            $stokBaru = $stokSaatIni - $selisihJumlah;
        } else {
            $errorMsg = "Stok tidak mencukupi! Maksimal {$stokSaatIni} item.";
        }
    } else { // Kalau user mengurangi barang
        $stokBaru = $stokSaatIni + abs($selisihJumlah);
    }

    if ($errorMsg === '') {
        $hargaBaru = $jumlahBaru * $hargaProduk;

        // Update keranjang
        mysqli_query($connect, "UPDATE keranjang SET jumlah = '$jumlahBaru', harga = '$hargaBaru' WHERE idKeranjang = '$idKeranjang'");

        // Update stok produk
        mysqli_query($connect, "UPDATE produk SET stokProduk = '$stokBaru' WHERE idProduk = '$idProduk'");

        header("Location:".$_SERVER['PHP_SELF']);
        exit;
    }
}

// Hapus semua item keranjang
if (isset($_POST["hapusSemua"])) {
    $allKeranjang = query("SELECT idProduk, jumlah FROM keranjang WHERE username = '$username' AND status = 'Belum Dibayar'");
    foreach($allKeranjang as $item) {
        mysqli_query($connect, "UPDATE produk SET stokProduk = stokProduk + {$item['jumlah']} WHERE idProduk = '{$item['idProduk']}'");
    }

    mysqli_query($connect, "DELETE FROM keranjang WHERE username = '$username' AND status = 'Belum Dibayar'");

    echo "<script>
            alert('Semua produk di keranjang berhasil dihapus.');
            window.location.href = 'viewKeranjang.php';
        </script>";
    exit;
}

// Hapus 1 item keranjang
if (isset($_GET['hapus']) && isset($_GET['id'])) {
    $idKeranjang = $_GET['id'];

    $keranjangItem = query("SELECT idProduk, jumlah FROM keranjang WHERE idKeranjang = '$idKeranjang'")[0];

    mysqli_query($connect, "UPDATE produkJadi SET stokProduk = stokProduk + {$keranjangItem['jumlah']} WHERE idProduk = '{$keranjangItem['idProduk']}'");
    mysqli_query($connect, "DELETE FROM keranjang WHERE idKeranjang = '$idKeranjang'");

    echo "<script>
            alert('Item berhasil dihapus dari keranjang.');
            window.location.href = 'viewKeranjang.php';
        </script>";
    exit;
}

// Checkout
if (isset($_POST["submit"])) {
    $idTransaksiBaru = checkout($_POST);

    if ($idTransaksiBaru) {
        echo "<script>
                alert('Checkout berhasil! Silakan tunggu file PDF.');
                window.location.href = 'cetakPdf.php?id=$idTransaksiBaru';
            </script>";
        exit;
    } else {
        echo "<script>
                alert('Checkout gagal!');
                document.location.href = 'produkCust.php';
            </script>";
    }
}

// Ambil semua keranjang user
$allKeranjang = query("SELECT * FROM keranjang JOIN produkJadi ON keranjang.idProduk = produkJadi.idProduk WHERE username = '$username' AND status = 'Belum Dibayar'");

// Hitung total
$totalHarga = query("SELECT SUM(harga) AS totalHarga FROM keranjang WHERE username = '$username' AND status = 'Belum Dibayar'")[0]["totalHarga"];
$ppn = 0.11 * $totalHarga;
$totalDenganPPN = $totalHarga + $ppn;

ob_end_flush();
?>
<!-- 
<?php ob_end_flush(); ?> -->

<main id="main" class="main">
    <div class="pagetitle">
        <h1 class="text-danger">Keranjang Anda</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Semua Barang di Keranjang</h5>

                        <?php if ($errorMsg): ?>
                            <div class="alert alert-danger"><?= $errorMsg; ?></div>
                        <?php endif; ?>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">ID dan Nama Produk</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Harga per Item</th>
                                    <th scope="col">Total Harga</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach($allKeranjang as $keranjang) : ?>
                                <tr>
                                    <td><?= $i; ?></td>
                                    <td><?= $keranjang["idProduk"]; ?> - <?= $keranjang["namaProduk"]; ?></td>
                                    <td>
                                        <form action="" method="post">
                                            <input type="hidden" name="idKeranjang" value="<?= $keranjang['idKeranjang']; ?>">
                                            <input type="number" name="jumlah" value="<?= $keranjang['jumlah']; ?>" min="1" class="form-control" style="width:80px;" onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td>Rp<?= number_format($keranjang["harga"] / $keranjang["jumlah"], 0, ',', '.'); ?></td>
                                    <td>Rp<?= number_format($keranjang["harga"], 0, ',', '.') ?></td>
                                    <td>
                                        <a href="?hapus=1&id=<?= $keranjang['idKeranjang']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini dari keranjang?')">Hapus</a>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="4"><strong>PPN (11%)</strong></td>
                                    <td colspan="2"><strong>Rp<?= number_format($ppn, 0, ',', '.'); ?></strong></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><strong>Total Harga (termasuk PPN)</strong></td>
                                    <td colspan="2"><strong>Rp<?= number_format($totalDenganPPN, 0, ',', '.'); ?></strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <form action="" method="post">
                            <input type="hidden" name="username" value="<?= $username; ?>">
                            <input type="hidden" name="totalHarga" value="<?= $totalDenganPPN; ?>">

                            <label for="pembayaran">Metode Pembayaran</label>
                            <select name="caraBayar" class="form-select" id="caraBayar" required onchange="toggleBankOptions()">
                                <option value="">-- Pilih Pembayaran --</option>
                                <option value="Prepaid">Prepaid</option>
                                <option value="Postpaid">PostPaid</option>
                            </select>

                            <div id="bankSection" style="margin-top: 10px;">
                                <label for="bank">Pilih Bank</label>
                                <select name="bank" class="form-select" id="bankSelect">
                                    <option value="">-- Pilih Bank --</option>
                                    <option value="Mandiri">Mandiri</option>
                                    <option value="BCA">BCA</option>
                                    <option value="BNI">BNI</option>
                                    <option value="BRI">BRI</option>
                                </select>
                            </div>

                            <input type="hidden" name="bank" id="finalBankInput" value="">
                            <div id="bayarDiTempat" style="display: none; margin-top: 10px;">
                                <strong>Bayar di Tempat (COD)</strong>
                            </div>

                            <br>
                            <button type="submit" class="btn btn-danger" name="submit">Checkout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
function toggleBankOptions() {
    const caraBayar = document.getElementById('caraBayar').value;
    const bankSection = document.getElementById('bankSection');
    const bankSelect = document.getElementById('bankSelect');
    const finalBankInput = document.getElementById('finalBankInput');
    const bayarDiTempat = document.getElementById('bayarDiTempat');

    if (caraBayar === 'Prepaid') {
        bankSection.style.display = 'block';
        bankSelect.disabled = false;
        finalBankInput.value = bankSelect.value;

        bankSelect.addEventListener('change', function () {
            finalBankInput.value = bankSelect.value;
        });
        bayarDiTempat.style.display = 'none';
    } else if (caraBayar === 'Postpaid') {
        bankSection.style.display = 'none';
        bankSelect.disabled = true;
        finalBankInput.value = 'Bayar Ditempat';
        bayarDiTempat.style.display = 'block';
    } else {
        bankSection.style.display = 'none';
        finalBankInput.value = '';
        bayarDiTempat.style.display = 'none';
    }
}
</script>
