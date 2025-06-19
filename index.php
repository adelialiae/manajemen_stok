<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Adda</title>
  <link rel="icon" href="img/LOGO ADDA.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .hero {
      background-color: #f8f9fa;
      padding: 100px 0;
      text-align: center;
    }

    .hero img {
      max-width: 120px;
      margin-bottom: 20px;
    }

    .produk-img {
      height: 200px;
      object-fit: cover;
    }
  </style>
</head>

<body>
  <?php 
  require 'connect.php'; // koneksi ke database

  // Ambil data produk + kategori
  // $produk = mysqli_query($connect, "
  //   SELECT produk.*, kategori.namaKategori 
  //   FROM produk 
  //   JOIN kategori ON produk.idKategori = kategori.idKategori 
  //   LIMIT 3
  // "); 
  ?>

  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="#">
        <img src="img/LOGO ADDA.png" alt="Logo" width="40"> Adda
      </a>
      <div>
        <a href="login.php" class="btn btn-outline-danger me-2">Login</a>
        <a href="registrasi.php" class="btn btn-danger">Daftar</a>
        <a href="registrasiSupplier.php" class="btn btn-danger">Daftar Supplier</a>
      </div>
    </div>
  </nav>

  <section class="hero">
    <div class="container">
      <img src="img/LOGO ADDA.png" alt="Logo Queen Dawet Suji">
      <h1 class="display-5">Selamat Datang di Adda</h1>
      <p class="lead">Aplikasi untuk manajemen stok anda</p>
    </div>
  </section>

  <!-- <section class="py-5" id="produk">
    <div class="container">
      <h2 class="mb-4 text-danger text-center">Produk Unggulan</h2>
      <div class="row">
        <?php while($p = mysqli_fetch_assoc($produk)) : ?>
        <div class="col-md-4 mb-4">
          <div class="card">
            <img src="img/<?= $p['gambarProduk']; ?>" class="card-img-top produk-img" alt="<?= $p['namaProduk']; ?>">
            <div class="card-body">
              <h5 class="card-title"><?= $p['namaProduk']; ?></h5>
              <p class="card-text">Kategori: <?= $p['namaKategori']; ?></p>
              <p class="card-text text-danger">Rp<?= number_format($p['hargaProduk'], 0, ',', '.'); ?></p>
              <a href="login.php" class="btn btn-warning w-100">Lihat Detail</a>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
        
        <div class="text-center mt-4">
          <a href="login.php" class="btn btn-outline-danger">
            Lihat Semua Produk 
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right ms-2" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 1 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
            </svg>
          </a>
        </div>

      </div>
    </div>
  </section> -->

  <footer class="bg-light text-center py-4">
    <p class="mb-0">&copy; <?= date('Y'); ?> Adda. Aplikasi Manajemen Stok Terpecaya.</p>
  </footer>

</body>
</html>
