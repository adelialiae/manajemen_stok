<?php
require '../connect.php';

if (!isset($_POST['idProduk'])) {
    die("ID Produk tidak ditemukan.");
}

$idProduk = mysqli_real_escape_string($connect, $_POST['idProduk']);

// Ambil rasio bahan dari DB
$query = mysqli_query($connect, "SELECT id_bahan, rasio_penggunaan FROM rasio_bahan_produk WHERE idProduk = '$idProduk'");
$rasioBahan = [];
while ($row = mysqli_fetch_assoc($query)) {
    $rasioBahan[$row['id_bahan']] = $row['rasio_penggunaan'];
}

// Jumlah produksi (misal 100 botol)
$jumlahProduksi = 100;

// Kurangi stok masing-masing bahan
foreach ($rasioBahan as $idBahan => $rasio) {
    $jumlahKurang = $jumlahProduksi * $rasio;

    // Cek stok
    $cekStok = mysqli_query($connect, "SELECT stokSisa FROM inventorystokbahan WHERE id_bahan = '$idBahan'");
    $stokSisa = mysqli_fetch_assoc($cekStok)['stokSisa'];

    if ($stokSisa < $jumlahKurang) {
        echo "<script>alert('Stok bahan ID $idBahan tidak cukup');window.location='dashboard.php';</script>";
        exit;
    }

    // Update stok
    mysqli_query($connect, "UPDATE inventorystokbahan SET stokSisa = stokSisa - $jumlahKurang WHERE id_bahan = '$idBahan'");
}

// Update stok produk jadi (naik +100 botol)
mysqli_query($connect, "UPDATE produkjadi SET stokProduk = stokProduk + $jumlahProduksi WHERE idProduk = '$idProduk'");

echo "<script>alert('Produksi berhasil diproses');window.location='dashboard.php';</script>";
?>
