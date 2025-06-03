<?php 

$title = 'Tambah Produk';

require 'adminControl.php';
require 'template/headerAdmin.php';
require 'template/sidebarAdmin.php';

// Ambil semua kategori dari DB
$kategori = query("SELECT * FROM supplier");


// Cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {
    $idProduk = 'PRD-' . time(); // Ini akan digenerate setiap halaman dibuka
    $namaProduk = $_POST['namaProduk'];
    $idKategori = $_POST['idKategori'];
    $kategoriBaru = $_POST['kategoriBaru'] ?? '';
    $hargaProduk = $_POST['hargaProduk'];
    $stokProduk = $_POST['stokProduk'];
    $deskripsiProduk = $_POST['deskripsiProduk'];
    $gambarProduk = $_FILES['gambarProduk']['name'];
    $tmpGambar = $_FILES['gambarProduk']['tmp_name'];

    // Jika pilih 'lain', tambahkan kategori baru
    if ($idKategori === "lain" && !empty($kategoriBaru)) {
        mysqli_query($connect, "INSERT INTO kategori(namaKategori) VALUES('$kategoriBaru')");
        $idKategori = mysqli_insert_id($connect);
    }

    // Upload gambar
    move_uploaded_file($tmpGambar, '../img/' . $gambarProduk);

    // Simpan produk
    $query = "INSERT INTO produk(idProduk, namaProduk, idKategori, hargaProduk, stokProduk, gambarProduk, deskripsiProduk) 
    VALUES('$idProduk', '$namaProduk', '$idKategori', '$hargaProduk', '$stokProduk', '$gambarProduk', '$deskripsiProduk')";

    if (mysqli_query($connect, $query)) {
        echo "<script>
                alert('Data berhasil ditambahkan!');
                document.location.href = 'produkAdmin.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal ditambahkan!');
                document.location.href = 'produkAdmin.php';
              </script>";
    }
}
?>

<main id="main" class="main">
    <div class="pagetitle ">
        <h1 class="text-danger">Tambah Produk</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tambah Produk</h5>

                        <!-- Form Tambah Produk -->
                        <form class="row g-3" method="post" enctype="multipart/form-data">
                            <div class="col-12">
                                <label for="namaProduk" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" id="namaProduk" name="namaProduk" required>
                            </div>

                            <div class="col-12">
                                <label for="idKategori" class="form-label">Kategori Produk</label>
                                <select class="form-select" id="idKategori" name="idKategori" required onchange="toggleKategoriBaru()">
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($kategori as $k) : ?>
                                        <option value="<?= $k['idKategori']; ?>"><?= $k['namaKategori']; ?></option>
                                    <?php endforeach; ?>
                                    <option value="lain">Lain-lain (tambah kategori baru)</option>
                                </select>
                            </div>

                            <div class="col-12" id="kategoriBaruContainer" style="display: none;">
                                <label for="kategoriBaru" class="form-label">Kategori Baru</label>
                                <input type="text" class="form-control" id="kategoriBaru" name="kategoriBaru" placeholder="Masukkan nama kategori baru">
                            </div>

                            <div class="col-12">
                                <label for="hargaProduk" class="form-label">Harga Produk</label>
                                <input type="number" class="form-control" id="hargaProduk" name="hargaProduk" required>
                            </div>

                            <div class="col-12">
                                <label for="stokProduk" class="form-label">Stok Produk</label>
                                <input type="number" class="form-control" id="stokProduk" name="stokProduk" required>
                            </div>

                            <div class="col-12">
                                <label for="deskripsiProduk" class="form-label">Deskripsi Produk</label>
                                <textarea class="form-control" id="deskripsiProduk" name="deskripsiProduk" rows="4" required></textarea>
                            </div>

                            <div class="col-12">
                                <label for="gambarProduk" class="form-label">Gambar Produk</label>
                                <input type="file" class="form-control" id="gambarProduk" name="gambarProduk" required>
                            </div>

                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary" name="submit">Tambah</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    function toggleKategoriBaru() {
        var kategoriSelect = document.getElementById("idKategori");
        var kategoriBaruContainer = document.getElementById("kategoriBaruContainer");

        if (kategoriSelect.value === "lain") {
            kategoriBaruContainer.style.display = "block";
        } else {
            kategoriBaruContainer.style.display = "none";
        }
    }
</script>
