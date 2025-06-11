<?php
$title = "Edit Bahan Baku";
require 'supplierControl.php'; // Pastikan hanya supplier bisa akses

if (!isset($_GET['id_bahan'])) {
    echo "<script>alert('ID bahan tidak ditemukan.'); window.location='daftarBahanSupplier.php';</script>";
    exit;
}

$idBahan = $_GET['id_bahan'];

// Ambil data bahan baku
$bahan = query("SELECT * FROM bahan_baku WHERE id_bahan = '$idBahan'")[0];

if (!$bahan) {
    echo "<script>alert('Bahan baku tidak ditemukan.'); window.location='daftarBahanSupplier.php';</script>";
    exit;
}

// Proses update jika form disubmit
if (isset($_POST['submit'])) {
    $namaBahan = htmlspecialchars($_POST['nama_bahan']);
    $harga = (int)$_POST['harga'];
    $stok = (int)$_POST['stok'];

    // Update data
    $result = mysqli_query($connect, "UPDATE bahan_baku 
                                   SET nama_bahan='$namaBahan', harga='$harga', stok='$stok' 
                                   WHERE id_bahan='$idBahan'");

    if ($result) {
        echo "<script>alert('Data bahan baku berhasil diperbarui.'); window.location='bahan_bakuSUpp.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
}
?>

<?php require 'template/headerSupplier.php'; ?>
<?php require 'template/sidebarSupplier.php'; ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit Bahan Baku</h1>
    </div>

    <section class="section">
        <form method="POST">
            <div class="mb-3">
                <label for="nama_bahan" class="form-label">Nama Bahan</label>
                <input type="text" class="form-control" id="nama_bahan" name="nama_bahan" required
                       value="<?= htmlspecialchars($bahan['nama_bahan']); ?>">
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" class="form-control" id="harga" name="harga" required
                       value="<?= htmlspecialchars($bahan['harga']); ?>">
            </div>
            <div class="mb-3">
                <label for="stok" class="form-label">Stok</label>
                <input type="number" class="form-control" id="stok" name="stok" required
                       value="<?= htmlspecialchars($bahan['stok']); ?>">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Update</button>
            <a href="bahan_bakuSupp.php" class="btn btn-secondary">Batal</a>
        </form>
    </section>
</main>
