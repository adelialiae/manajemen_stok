<?php
require '../connect.php';

// Logika tambah ke keranjang
if (isset($_GET['id'])) {
    $idBahan = $_GET['id'];

    // Cek stok dulu
    $stok = mysqli_fetch_assoc(mysqli_query($connect, "SELECT stok FROM bahan_baku WHERE id_bahan = '$idBahan'"))['stok'];
    if ($stok <= 0) {
        echo "<script>alert('Stok bahan baku habis!'); window.location.href = 'bahan_baku.php';</script>";
        exit;
    }

    // Cek apakah bahan sudah ada di keranjang (belum checkout = idTransaksi NULL)
    $cekkeranjang_pembelian = mysqli_query($connect, "SELECT * FROM keranjang_pembelian WHERE id_bahan = '$idBahan' AND idTransaksi IS NULL");
    if (mysqli_num_rows($cekkeranjang_pembelian) > 0) {
        // Sudah ada, update jumlah & harga
        $data = mysqli_fetch_assoc($cekkeranjang_pembelian);
        $jumlahBaru = $data['jumlah'] + 1;
        $hargaSatuan = mysqli_fetch_assoc(mysqli_query($connect, "SELECT harga FROM bahan_baku WHERE id_bahan = '$idBahan'"))['harga'];
        $hargaBaru = $jumlahBaru * $hargaSatuan;

        mysqli_query($connect, "UPDATE keranjang_pembelian SET jumlah = '$jumlahBaru', harga = '$hargaBaru' WHERE idKeranjang = '{$data['idKeranjang']}'");
    } else {
        // Belum ada, insert baru
        $hargaSatuan = mysqli_fetch_assoc(mysqli_query($connect, "SELECT harga FROM bahan_baku WHERE id_bahan = '$idBahan'"))['harga'];
        mysqli_query($connect, "INSERT INTO keranjang_pembelian (id_bahan, jumlah, harga) VALUES ('$idBahan', 1, '$hargaSatuan')");
    }

    // Kurangi stok bahan baku
    mysqli_query($connect, "UPDATE bahan_baku SET stok = stok - 1 WHERE id_bahan = '$idBahan'");

    echo "<script>alert('Bahan baku berhasil dimasukkan ke keranjang_pembelian.'); window.location.href = 'viewKeranjang.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idKeranjang']) && isset($_POST['jumlah'])) {
    $idKeranjang = $_POST['idKeranjang'];
    $jumlahBaru = intval($_POST['jumlah']);

    // Ambil data keranjang
    $dataKeranjang = mysqli_fetch_assoc(mysqli_query($connect, "SELECT id_bahan, jumlah FROM keranjang_pembelian WHERE idKeranjang = '$idKeranjang'"));
    $idBahan = $dataKeranjang['id_bahan'];
    $jumlahLama = $dataKeranjang['jumlah'];

    // Hitung selisih (jumlahBaru - jumlahLama)
    $selisih = $jumlahBaru - $jumlahLama;

    if ($selisih > 0) {
        // Tambah jumlah
        // Cek stok dulu
        $stok = mysqli_fetch_assoc(mysqli_query($connect, "SELECT stok FROM bahan_baku WHERE id_bahan = '$idBahan'"))['stok'];
        if ($stok < $selisih) {
            echo "<script>alert('Stok tidak cukup!'); window.location.href = 'viewKeranjang.php';</script>";
            exit;
        }

        // Update jumlah & harga
        mysqli_query($connect, "UPDATE keranjang_pembelian SET jumlah = '$jumlahBaru' WHERE idKeranjang = '$idKeranjang'");
        $hargaSatuan = mysqli_fetch_assoc(mysqli_query($connect, "SELECT harga FROM bahan_baku WHERE id_bahan = '$idBahan'"))['harga'];
        $hargaBaru = $jumlahBaru * $hargaSatuan;
        mysqli_query($connect, "UPDATE keranjang_pembelian SET harga = '$hargaBaru' WHERE idKeranjang = '$idKeranjang'");

        // Kurangi stok
        mysqli_query($connect, "UPDATE bahan_baku SET stok = stok - $selisih WHERE id_bahan = '$idBahan'");
    } elseif ($selisih < 0) {
        // Kurang jumlah
        // Update jumlah & harga
        mysqli_query($connect, "UPDATE keranjang_pembelian SET jumlah = '$jumlahBaru' WHERE idKeranjang = '$idKeranjang'");
        $hargaSatuan = mysqli_fetch_assoc(mysqli_query($connect, "SELECT harga FROM bahan_baku WHERE id_bahan = '$idBahan'"))['harga'];
        $hargaBaru = $jumlahBaru * $hargaSatuan;
        mysqli_query($connect, "UPDATE keranjang_pembelian SET harga = '$hargaBaru' WHERE idKeranjang = '$idKeranjang'");

        // Kembalikan stok
        mysqli_query($connect, "UPDATE bahan_baku SET stok = stok + ".abs($selisih)." WHERE id_bahan = '$idBahan'");
    }

    // Kalau jumlahBaru = 0, bisa langsung hapus
    if ($jumlahBaru == 0) {
        mysqli_query($connect, "DELETE FROM keranjang_pembelian WHERE idKeranjang = '$idKeranjang'");
        mysqli_query($connect, "UPDATE bahan_baku SET stok = stok + '$jumlahLama' WHERE id_bahan = '$idBahan'");
    }

    header('Location: viewKeranjang.php');
    exit;
}

// Logika kurang jumlah
if (isset($_GET['kurang'])) {
    $idKeranjang = $_GET['kurang'];

    // Ambil data keranjang
    $item = mysqli_fetch_assoc(mysqli_query($connect, "SELECT id_bahan, jumlah FROM keranjang_pembelian WHERE idKeranjang = '$idKeranjang'"));
    $idBahan = $item['id_bahan'];
    $jumlah = $item['jumlah'];

    if ($jumlah > 1) {
        // Update jumlah & harga
        mysqli_query($connect, "UPDATE keranjang_pembelian SET jumlah = jumlah - 1 WHERE idKeranjang = '$idKeranjang'");
        $dataKeranjang = mysqli_fetch_assoc(mysqli_query($connect, "SELECT jumlah FROM keranjang_pembelian WHERE idKeranjang = '$idKeranjang'"));
        $jumlahBaru = $dataKeranjang['jumlah'];
        $hargaSatuan = mysqli_fetch_assoc(mysqli_query($connect, "SELECT harga FROM bahan_baku WHERE id_bahan = '$idBahan'"))['harga'];
        $hargaBaru = $jumlahBaru * $hargaSatuan;
        mysqli_query($connect, "UPDATE keranjang_pembelian SET harga = '$hargaBaru' WHERE idKeranjang = '$idKeranjang'");

        // Tambah stok kembali
        mysqli_query($connect, "UPDATE bahan_baku SET stok = stok + 1 WHERE id_bahan = '$idBahan'");
    } else {
        // Kalau jumlah = 1, lebih baik langsung hapus
        mysqli_query($connect, "DELETE FROM keranjang_pembelian WHERE idKeranjang = '$idKeranjang'");
        mysqli_query($connect, "UPDATE bahan_baku SET stok = stok + 1 WHERE id_bahan = '$idBahan'");
    }

     // Logika hapus item keranjang_pembelian (opsional)
if (isset($_GET['hapus'])) {
    $idKeranjang = $_GET['hapus'];

    // Ambil jumlah dan id_bahan (untuk mengembalikan stok)
    $item = mysqli_fetch_assoc(mysqli_query($connect, "SELECT id_bahan, jumlah FROM keranjang_pembelian WHERE idKeranjang = '$idKeranjang'"));
    $idBahan = $item['id_bahan'];
    $jumlah = $item['jumlah'];

    // Hapus dari keranjang_pembelian
    mysqli_query($connect, "DELETE FROM keranjang_pembelian WHERE idKeranjang = '$idKeranjang'");

    // Kembalikan stok
    mysqli_query($connect, "UPDATE bahan_baku SET stok = stok + '$jumlah' WHERE id_bahan = '$idBahan'");

    echo "<script>alert('Item berhasil dihapus dari keranjang_pembelian.'); window.location.href = 'viewkeranjang_pembelian.php';</script>";
    exit;
}

    header('Location: viewKeranjang.php');
    exit;
}


function checkout($data) {
    global $connect;

    $caraBayar = $data['caraBayar'];
    $bank = isset($data['bank']) ? $data['bank'] : '';

    // Cek keranjang
    $cekKeranjang = mysqli_query($connect, "SELECT * FROM keranjang_pembelian WHERE idTransaksi IS NULL");
    if (mysqli_num_rows($cekKeranjang) == 0) {
        return false;
    }

    $idTransaksiBaru = uniqid('TRX');

    // Hitung total harga
    $totalHarga = 0;
    $itemsKeranjang = [];
    while ($item = mysqli_fetch_assoc($cekKeranjang)) {
        $totalHarga += $item['harga'];
        $itemsKeranjang[] = $item; // simpan dulu data keranjang
    }

    // Ambil supplier
    $querySupplier = mysqli_query($connect, "
        SELECT b.id_supplier
        FROM keranjang_pembelian k
        JOIN bahan_baku b ON k.id_bahan = b.id_bahan
        WHERE k.idTransaksi IS NULL
        LIMIT 1
    ");
    $dataSupplier = mysqli_fetch_assoc($querySupplier);
    $idSupplier = $dataSupplier['id_supplier'];

    // Simpan transaksi pembelian
    $stmt = $connect->prepare("INSERT INTO transaksi_pembelian (idTransaksi, totalHarga, caraBayar, bank, id_supplier, statusTransaksi) VALUES (?, ?, ?, ?, ?, 'menunggu supplier')");
    $stmt->bind_param("sissi", $idTransaksiBaru, $totalHarga, $caraBayar, $bank, $idSupplier);
    $stmt->execute();

    // Simpan detail transaksi
    foreach ($itemsKeranjang as $item) {
        $idBahan = $item['id_bahan'];
        $jumlah = $item['jumlah'];
        $harga = $item['harga'];

        $stmtDetail = $connect->prepare("INSERT INTO detail_transaksi_pembelian (idTransaksi, id_bahan, qty, harga) VALUES (?, ?, ?, ?)");
        $stmtDetail->bind_param("siii", $idTransaksiBaru, $idBahan, $jumlah, $harga);
        $stmtDetail->execute();
    }

    // Update idTransaksi di keranjang_pembelian
    mysqli_query($connect, "UPDATE keranjang_pembelian SET idTransaksi = '$idTransaksiBaru' WHERE idTransaksi IS NULL");

    return $idTransaksiBaru;
}


if (isset($_POST['submit'])) {
    $hasilCheckout = checkout($_POST);
    if ($hasilCheckout) {
        echo "<script>alert('Checkout berhasil! ID Transaksi: $hasilCheckout'); window.location.href = 'viewKeranjang.php';</script>";
        exit;
    } else {
        echo "<script>alert('Checkout gagal. Pastikan keranjang tidak kosong.');</script>";
    }
}

?>

<?php
$title = 'Keranjang Pembelian Bahan Baku';
require 'template/headerAdmin.php';
require 'template/sidebarAdmin.php';
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Keranjang Bahan Baku</h1>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body pt-3">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Bahan</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Total Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = mysqli_query($connect, "
                            SELECT k.*, b.nama_bahan, b.harga AS harga_satuan
                            FROM keranjang_pembelian k
                            LEFT JOIN bahan_baku b ON k.id_bahan = b.id_bahan
                            WHERE k.idTransaksi IS NULL
                        ");

                        $totalSemua = 0;
                        while ($keranjang = mysqli_fetch_assoc($query)) :
                            // Hitung total harga per item
                            $totalHargaItem = $keranjang['jumlah'] * $keranjang['harga_satuan'];
                            $totalSemua += $totalHargaItem;
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($keranjang['nama_bahan']); ?></td>
                            <td>Rp <?= number_format($keranjang['harga_satuan']); ?></td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="idKeranjang" value="<?= $keranjang['idKeranjang']; ?>">
                                    <input type="number" name="jumlah" value="<?= $keranjang['jumlah']; ?>" min="1" class="form-control" style="width:80px;" onchange="this.form.submit()">
                                </form>
                            </td>
                            <td>Rp <?= number_format($totalHargaItem); ?></td>
                            <td>
                                <a href="viewKeranjang.php?hapus=<?= $keranjang['idKeranjang']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus item ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <tr>
                            <th colspan="4">Total Semua</th>
                            <th colspan="2">Rp <?= number_format($totalSemua); ?></th>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">Total Semua</th>
                            <th colspan="2">Rp <?= number_format($totalSemua); ?></th>

                        </tr>
                    </tfoot>
                    
                </table>
                <form action="" method="POST">
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

                <input type="hidden" name="finalBank" id="finalBankInput" value="">

                <div id="bayarDiTempat" style="display: none; margin-top: 10px;">
                    <strong>Bayar di Tempat (COD)</strong>
                </div>

                <button type="submit" class="btn btn-danger" name="submit">Checkout</button>
            </form>

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
