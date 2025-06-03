<?php 

$title = 'Daftar Produk';

require 'custControl.php';
require 'template/headerCust.php';
require 'template/sidebarCust.php';

// Ambil semua produk + nama kategori-nya
$allProduk = query("SELECT produk.*, kategori.namaKategori 
                    FROM produk 
                    LEFT JOIN kategori ON produk.idKategori = kategori.idKategori");

// Ambil daftar kategori dari DB
$kategori = query("SELECT * FROM kategori");

?>

<style>
    .custom-card-img {
        height: 220px;
        object-fit: cover;
    }
</style>

<main id="main" class="main">

    <div class="pagetitle">
        <h1 class="text-danger">Semua Produk Cheerful HETO</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <p>Pilih Kategori : </p>
                <select class="form-select mb-5" id="kategoriFilter">
                    <option value="all">Semua Kategori</option>
                    <?php foreach($kategori as $k) : ?>
                        <option value="<?= $k['namaKategori']; ?>"><?= $k['namaKategori']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row">
            <?php foreach($allProduk as $produk) : ?>
                <div class="col-md-4 mb-4" data-kategori="<?= $produk["namaKategori"]; ?>">
                    <div class="card" style="width: 22rem;">
                        <img src="../img/<?= $produk["gambarProduk"]; ?>" class="card-img-top custom-card-img" alt="...">
                        <div class="card-body">
                            <h5 class="card-title"><?= $produk["namaProduk"]; ?></h5>
                            <p class="card-text"><?= $produk["namaKategori"]; ?></p>
                            <center class="text-danger mb-2">
                                Rp<?= number_format($produk["hargaProduk"], 0, ',', '.'); ?>
                            </center>
                            <p class="card-text d-flex justify-content-center">
                                <?php if($produk["stokProduk"] == 0) : ?>
                                    <a href="#" class="btn btn-danger">Stok Kosong</a>
                                <?php else : ?>
                                    <a href="tambahKeranjang.php?idProduk=<?= $produk["idProduk"]; ?>" class="btn btn-danger">Beli</a>
                                <?php endif; ?>
                                <a href="detailProduk.php?id=<?= $produk["idProduk"]; ?>" class="btn btn-warning" style="margin-left: 20px;">Detail</a>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $("#kategoriFilter").on("change", function() {
            var selectedCategory = $(this).val();

            $(".col-md-4").each(function() {
                var cardCategory = $(this).data("kategori");
                var isCategoryMatch = selectedCategory === "all" || cardCategory === selectedCategory;
                
                if (isCategoryMatch) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>
