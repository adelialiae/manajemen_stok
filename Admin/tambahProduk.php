<?php 

$title = 'Tambah Produk';

require 'adminControl.php';
require 'template/headerAdmin.php';
require 'template/sidebarAdmin.php';


// Cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {
    $idProduk = 'PRD-' . time();
    $namaProduk = $_POST['namaProduk'];
    $varianRasa = $_POST['varianRasa'];
    $hargaProduk = $_POST['hargaProduk'];
    $stokProduk = $_POST['stokProduk'];
    $deskripsiProduk = $_POST['deskripsiProduk'];
    $gambarProduk = $_FILES['gambarProduk']['name'];
    $tmpGambar = $_FILES['gambarProduk']['tmp_name'];

    move_uploaded_file($tmpGambar, '../img/' . $gambarProduk);

    $query = "INSERT INTO produkjadi(idProduk, namaProduk, varianRasa, hargaProduk, stokProduk, gambarProduk, deskripsiProduk) 
    VALUES('$idProduk', '$namaProduk', '$varianRasa', '$hargaProduk', '$stokProduk', '$gambarProduk', '$deskripsiProduk')";

    if (mysqli_query($connect, $query)) {
        echo "<script>
                alert('Produk berhasil ditambahkan!');
                document.location.href = 'produkAdmin.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menambahkan produk!');
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
                                <label for="varianRasa" class="form-label">Varian Rasa</label>
                                <input type="text" class="form-control" id="varianRasa" name="varianRasa" required>
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

