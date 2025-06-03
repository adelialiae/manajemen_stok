<?php 

$title = 'Dashboard';

require 'adminControl.php';
require 'template/headerAdmin.php';
require 'template/sidebarAdmin.php';

$totalTransaksiSelesai = count(query("SELECT * FROM transaksi WHERE statusPengiriman = 'Terkirim'"));
$totalCancelTransaksi = count(query("SELECT * FROM transaksi WHERE statusTransaksi = 'Cancelled'"));
$totalProduk = count(query("SELECT * FROM produk"));
$totalCustomer = count(query("SELECT * FROM customer"));
$totalKeuangan = query("SELECT SUM(totalHarga) FROM transaksi WHERE statusPengiriman = 'Terkirim'")[0]['SUM(totalHarga)'];
$stokRendah = count(query("SELECT * FROM produk WHERE stokProduk <= 10"));
$totalSupplier = count(query("SELECT * FROM supplier"));
?>

<style>
  .random-color {
    background-color:rgb(235, 167, 150);
    color: white;
    text-align: center;
    padding: 20px;
    border: 1px solid #ccc;
  }
</style>

<main id="main" class="main">
  <div class="pagetitle">
    <h1 class="text-danger">Dashboard</h1>
  </div>

  <section class="section dashboard container">
    <div class="container">
      <div class="row">

        <div class="col-lg-4">
          <div class="card info-card random-color succes-card">
            <div class="card-body">
              <h5 class="card-title text-white">Total Transaksi Selesai</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-bag-check"></i>
                </div>
                <div class="ps-3">
                  <h6><?= $totalTransaksiSelesai; ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card info-card random-color cancelled-card">
            <div class="card-body">
              <h5 class="card-title text-white">Total Transaksi Dibatalkan</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-calendar-x"></i>
                </div>
                <div class="ps-3">
                  <h6><?= $totalCancelTransaksi; ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card info-card random-color product-card">
            <div class="card-body">
              <h5 class="card-title text-white">Total Produk</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-box"></i>
                </div>
                <div class="ps-3">
                  <h6><?= $totalProduk; ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card info-card random-color customer-card">
            <div class="card-body">
              <h5 class="card-title text-white">Total Customer</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-person"></i>
                </div>
                <div class="ps-3">
                  <h6><?= $totalCustomer; ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card info-card random-color">
            <div class="card-body">
              <h5 class="card-title text-white">Total Pemasukan</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-cash"></i>
                </div>
                <div class="ps-3">
                  <h6 class="text-white">Rp<?= number_format($totalKeuangan, 0, ',', '.'); ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card info-card random-color">
            <div class="card-body">
              <h5 class="card-title text-white">Notifikasi Stok Rendah</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="ps-3">
                  <h6><?= $stokRendah; ?> produk</h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card info-card random-color">
            <div class="card-body">
              <h5 class="card-title text-white">Total Supplier</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-truck"></i>
                </div>
                <div class="ps-3">
                  <h6><?= $totalSupplier; ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</main>
