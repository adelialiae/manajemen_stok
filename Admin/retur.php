<?php
require 'adminControl.php';

if (isset($_GET['idTransaksi'])) {
  $idTransaksi = $_GET['idTransaksi'];

  // Update status jadi "Retur Diajukan"
  $query = "UPDATE transaksi_pembelian SET statusTransaksi = 'retur' WHERE idTransaksi = '$idTransaksi'";
  $result = mysqli_query($connect, $query);

  if ($result) {
    echo "
      <script>
        alert('Permintaan retur sudah diajukan ke supplier.');
        window.location.href = 'viewTransaksi.php';
      </script>
    ";
  } else {
    echo "
      <script>
        alert('Gagal mengajukan retur.');
        window.location.href = 'viewTransaksi.php';
      </script>
    ";
  }
} else {
  echo "
    <script>
      alert('ID Transaksi tidak valid.');
      window.location.href = 'viewTransaksi.php';
    </script>
  ";
}
?>
