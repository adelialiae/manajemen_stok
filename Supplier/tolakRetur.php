<?php
require 'supplierControl.php';

if (isset($_GET['idTransaksi'])) {
  $idTransaksi = $_GET['idTransaksi'];
  $query = "UPDATE transaksi_pembelian SET statusTransaksi = 'Retur ditolak' WHERE idTransaksi = '$idTransaksi'";
  mysqli_query($conn, $query);
  header("Location: supplierTransaksi.php");
}
?>
