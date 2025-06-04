<?php
include '../connect.php';
$id = $_GET['idTransaksi'];
mysqli_query($conn, "UPDATE transaksi_pembelian SET statusTransaksi='retur' WHERE idTransaksi='$id'");
header("Location: viewTransaksi.php?pesan=retur");
?>
