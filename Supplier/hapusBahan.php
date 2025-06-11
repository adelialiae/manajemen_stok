<?php
require 'supplierControl.php'; // Pastikan hanya supplier bisa akses
require 'connect.php'; // Koneksi database

if (!isset($_GET['id_bahan'])) {
    echo "<script>alert('ID bahan tidak ditemukan.'); window.location='daftarBahanSupplier.php';</script>";
    exit;
}

$idBahan = $_GET['id_bahan'];

// Hapus data
$result = mysqli_query($connect, "DELETE FROM bahan_baku WHERE id_bahan='$idBahan'");

if ($result) {
    echo "<script>alert('Data bahan baku berhasil dihapus.'); window.location='daftarBahanSupplier.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data.'); window.location='daftarBahanSupplier.php';</script>";
}
?>
