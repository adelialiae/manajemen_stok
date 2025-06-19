<?php
require 'supplierControl.php';

if (!isset($_GET['id_bahan'])) {
    echo "<script>alert('ID bahan tidak ditemukan.'); window.location='daftarBahanSupplier.php';</script>";
    exit;
}

$idBahan = $_GET['id_bahan'];

// Cek apakah masih dipakai di detail_transaksi_pembelian
$cek = mysqli_query($connect, "SELECT COUNT(*) AS total FROM detail_transaksi_pembelian WHERE id_bahan = '$idBahan'");
$data = mysqli_fetch_assoc($cek);

if ($data['total'] > 0) {
    echo "<script>alert('Tidak bisa dihapus. Bahan ini masih digunakan dalam transaksi.'); window.location='bahan_bakuSupp.php';</script>";
    exit;
}

// Aman untuk dihapus
$result = mysqli_query($connect, "DELETE FROM bahan_baku WHERE id_bahan = '$idBahan'");

if ($result) {
    echo "<script>alert('Data bahan baku berhasil dihapus.'); window.location='bahan_bakuSupp.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data.'); window.location='bahan_bakuSupp.php';</script>";
}
?>
