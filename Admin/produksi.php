<?php
require 'adminControl.php';
require 'template/headerAdmin.php';
require 'template/sidebarAdmin.php';

$idProduk = $_POST['idProduk'];
$namaProduk = $_POST['namaProduk'];
$stokSekarang = $_POST['stokSekarang'];
?>

<main class="main container mt-4">
  <h2 class="text-danger">Produksi Produk</h2>
  <p><strong><?= htmlspecialchars($namaProduk) ?></strong> (Stok Saat Ini: <?= $stokSekarang ?>)</p>

  <form method="POST" action="produksiProses.php">
    <input type="hidden" name="idProduk" value="<?= $idProduk ?>">
    <label>Jumlah yang ingin diproduksi:</label>
    <input type="number" name="jumlahProduksi" class="form-control mb-2" min="1" required>
    <button type="submit" class="btn btn-success">Proses Produksi</button>
    <a href="dashboard.php" class="btn btn-secondary">Batal</a>
  </form>
</main>
