<?php
require 'supplierControl.php'; // file session supplier

if (isset($_GET['idTransaksi'])) {
  $idTransaksi = $_GET['idTransaksi'];
  $query = "UPDATE transaksi_pembelian SET statusTransaksi = 'Retur diterima' WHERE idTransaksi = '$idTransaksi'";
  mysqli_query($conn, $query);
  header("Location: supplierTransaksi.php");
}
?>
