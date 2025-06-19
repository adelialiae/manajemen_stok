<?php
$title = 'Dashboard Supplier';

require 'supplierControl.php';
require 'template/headerSupplier.php';  // Sudah ada session_start() dan validasi login
require 'template/sidebarSupplier.php';

// Ambil id_supplier dari session login
$id_supplier = $_SESSION['id_supplier'];

// Inisialisasi controller
$supplierController = new SupplierController($connect);

// Ambil data dashboard khusus supplier
$dataDashboard = $supplierController->dashboard($id_supplier);

$totalbahan_baku = $dataDashboard['total_bahan_baku'] ?? 0;
$pesananBelumDiterima = $dataDashboard['pesanan_belum_diterima'];
$pesananDiterima = $dataDashboard['pesanan_diterima'];
$pesananDitolak = $dataDashboard['pesanan_ditolak'];

// Total pesanan yang statusSupplier = 'menunggu' (belum dikonfirmasi supplier)
$queryBelumDiterima = mysqli_query($connect, "
  SELECT COUNT(*) AS total FROM transaksi_pembelian
  WHERE id_supplier = '$id_supplier' AND statusTransaksi = 'menunggu supplier'
");
$pesananBelumDiterima = mysqli_fetch_assoc($queryBelumDiterima)['total'];

// Total pesanan yang statusSupplier = 'menyetujui' (disetujui supplier)
$queryDiterima = mysqli_query($connect, "
  SELECT COUNT(*) AS total FROM transaksi_pembelian
  WHERE id_supplier = '$id_supplier' AND statusSupplier = 'menyetujui'
");
$pesananDiterima = mysqli_fetch_assoc($queryDiterima)['total'];

// Total pesanan yang statusSupplier = 'menolak' (ditolak supplier)
$queryDitolak = mysqli_query($connect, "
  SELECT COUNT(*) AS total FROM transaksi_pembelian
  WHERE id_supplier = '$id_supplier' AND statusSupplier = 'menolak'
");
$pesananDitolak = mysqli_fetch_assoc($queryDitolak)['total'];


?>

<style>
  .random-color {
    background-color: rgb(235, 167, 150);
    color: white;
    text-align: center;
    padding: 20px;
    border: 1px solid #ccc;
  }
</style>

<main id="main" class="main">
  <div class="pagetitle">
    <h1 class="text-danger">Dashboard Supplier</h1>
  </div>

  <section class="section dashboard container">
    <div class="container">
      <div class="row">

        <div class="col-lg-4">
          <div class="card info-card random-color succes-card">
            <div class="card-body">
              <h5 class="card-title text-white">Total Produk Supplier</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-box"></i>
                </div>
                <div class="ps-3">
                  <h6><?= $totalbahan_baku; ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card info-card random-color cancelled-card">
            <div class="card-body">
              <h5 class="card-title text-white">Pesanan Belum Diterima</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="ps-3">
                  <h6><?= $pesananBelumDiterima; ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card info-card random-color product-card">
            <div class="card-body">
              <h5 class="card-title text-white">Pesanan Diterima</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-check-circle"></i>
                </div>
                <div class="ps-3">
                  <h6><?= $pesananDiterima; ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card info-card random-color customer-card">
            <div class="card-body">
              <h5 class="card-title text-white">Pesanan Ditolak</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-x-circle"></i>
                </div>
                <div class="ps-3">
                  <h6><?= $pesananDitolak; ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</main>
