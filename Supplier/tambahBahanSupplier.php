<?php 
$title = 'Tambah Bahan Baku';

require 'supplierControl.php'; 
require 'template/headerSupplier.php';
require 'template/sidebarSupplier.php';

$idSupplier = $_SESSION['id_supplier'] ?? null;
if (!$idSupplier) {
    echo "<script>alert('Harap login terlebih dahulu!');window.location='login.php';</script>";
    exit;
}

if (isset($_POST['submit'])) {
    $namaBahan = htmlspecialchars(trim($_POST['namaBahan']));
    $harga = (int)$_POST['harga'];
    $stok = (int)$_POST['stok'];
    
    $gambar = null;
    if ($_FILES['gambar']['error'] == 0) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $namaFileBaru = uniqid('bahan_') . '.' . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], '../img/' . $namaFileBaru);
        $gambar = $namaFileBaru;
    }
    
    $query = "INSERT INTO bahan_baku (nama_bahan, harga, stok, gambar, id_supplier) 
              VALUES ('$namaBahan', $harga, $stok, '$gambar', '$idSupplier')";
              
    if (mysqli_query($connect, $query)) {
        echo "<script>alert('Bahan baku berhasil ditambahkan!');window.location='bahan_bakuSupp.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menambahkan bahan baku!');</script>";
    }
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1 class="text-danger">Tambah Bahan Baku</h1>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data" class="row g-3">
                    <div class="col-12">
                        <label for="namaBahan" class="form-label">Nama Bahan</label>
                        <input type="text" id="namaBahan" name="namaBahan" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" id="harga" name="harga" class="form-control" required min="0">
                    </div>

                    <div class="col-12">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" id="stok" name="stok" class="form-control" required min="0">
                    </div>

                    <div class="col-12">
                        <label for="gambar" class="form-label">Gambar (opsional)</label>
                        <input type="file" id="gambar" name="gambar" class="form-control" accept="image/*">
                    </div>

                    <div class="col-6">
                        <button type="submit" name="submit" class="btn btn-primary">Tambah</button>
                        <a href="bahan_bakuSupp.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
