<?php 

$title = 'Edit Produk';

require 'adminControl.php';
require 'template/headerAdmin.php';
require 'template/sidebarAdmin.php';

$idProduk = $_GET["id"];
$produk = query("SELECT * FROM produkjadi WHERE idProduk = '$idProduk'")[0];

// Proses update saat submit
if (isset($_POST["submit"])) {
    $namaProduk = $_POST['namaProduk'];
    $varianRasa = $_POST['varianRasa'];
    $hargaProduk = $_POST['hargaProduk'];
    $stokProduk = $_POST['stokProduk'];
    $deskripsiProduk = $_POST['deskripsiProduk'];

    // Cek apakah ada upload gambar baru
    if ($_FILES['gambarProduk']['error'] === 4) {
        // Tidak ada gambar baru
        $gambarProduk = $produk['gambarProduk'];
    } else {
        // Ada gambar baru
        $gambarProduk = $_FILES['gambarProduk']['name'];
        $tmpGambar = $_FILES['gambarProduk']['tmp_name'];
        move_uploaded_file($tmpGambar, '../img/' . $gambarProduk);
    }

    $query = "UPDATE produkjadi SET 
                namaProduk = '$namaProduk',
                varianRasa = '$varianRasa',
                hargaProduk = '$hargaProduk',
                stokProduk = '$stokProduk',
                gambarProduk = '$gambarProduk',
                deskripsiProduk = '$deskripsiProduk'
              WHERE idProduk = '$idProduk'";

    if (mysqli_query($connect, $query)) {
        echo "<script>
                alert('Produk berhasil diupdate!');
                document.location.href = 'produkAdmin.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal update produk!');
                document.location.href = 'produkAdmin.php';
              </script>";
    }
}

?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1 class="text-danger">Edit Produk</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Produk</h5>

                        <!-- Form Edit Produk -->
                        <form class="row g-3" method="post" enctype="multipart/form-data">
                            <div class="col-12">
                                <label for="namaProduk" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" id="namaProduk" name="namaProduk" required value="<?= $produk["namaProduk"]; ?>">
                            </div>

                            <div class="col-12">
                                <label for="varianRasa" class="form-label">Varian Rasa</label>
                                <input type="text" class="form-control" id="varianRasa" name="varianRasa" required value="<?= $produk["varianRasa"]; ?>">
                            </div>

                            <div class="col-12">
                                <label for="hargaProduk" class="form-label">Harga Produk</label>
                                <input type="number" class="form-control" id="hargaProduk" name="hargaProduk" required value="<?= $produk["hargaProduk"]; ?>">
                            </div>

                            <div class="col-12">
                                <label for="stokProduk" class="form-label">Stok Produk</label>
                                <input type="number" class="form-control" id="stokProduk" name="stokProduk" required value="<?= $produk["stokProduk"]; ?>">
                            </div>

                            <div class="col-12">
                                <label for="deskripsiProduk" class="form-label">Deskripsi Produk</label>
                                <textarea class="form-control" id="deskripsiProduk" name="deskripsiProduk" rows="4" required><?= $produk["deskripsiProduk"]; ?></textarea>
                            </div>

                            <div class="col-12">
                                <label for="gambarProduk" class="form-label">Gambar Produk</label><br>
                                <img src="../img/<?= $produk["gambarProduk"]; ?>" width="300" class="mb-2"><br>
                                <input type="file" class="form-control" id="gambarProduk" name="gambarProduk">
                            </div>

                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary" name="submit">Update</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </form>
                        <!-- End Form Edit Produk -->

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
