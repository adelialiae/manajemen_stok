<?php

$title = 'Dashboard';

require 'adminControl.php';
require 'template/headerAdmin.php';
require 'template/sidebarAdmin.php';

// Ambil total stok produkJadi
$totalStokProduk = query("SELECT COUNT(idProduk) AS totalProduk FROM produkjadi")[0]['totalProduk'];

// Ambil total pesanan dari customer (status apapun, misalnya jika semua status dihitung)
$totalPesanan = count(query("SELECT * FROM transaksi"));

// Ambil total pemasukan (hanya transaksi yang statusnya sudah 'Terkirim')
$totalIncome = query("SELECT SUM(totalHarga) AS total FROM transaksi WHERE statusPengiriman = 'Terkirim'")[0]['total'];

// Notifikasi stok rendah (stok <= 10)
$stokRendah = count(query("SELECT SUM(stokProduk) AS totalStokJadi FROM produkJadi WHERE stokProduk <= 10"));

// Ambil total stok bahan baku
$totalStokBahanBaku = query("SELECT SUM(stokSisa) AS totalStokBahan FROM inventorystokbahan")[0]['totalStokBahan'];


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
    <h1 class="text-danger">Dashboard</h1>
  </div>

  <section class="section dashboard container">
    <div class="container">
      <div class="row">

        <div class="col-lg-4">
          <div class="card info-card random-color">
            <div class="card-body">
              <h5 class="card-title text-white">Total Produk</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-box"></i>
                </div>
                <div class="ps-3">
                  <h6><?= $totalStokProduk; ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card info-card random-color">
            <div class="card-body">
              <h5 class="card-title text-white">Total Pesanan</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-cart"></i>
                </div>
                <div class="ps-3">
                  <h6><?= $totalPesanan; ?></h6>
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
                  <h6 class="text-white">Rp<?= number_format($totalIncome ?? 0, 0, ',', '.'); ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
        <div class="card info-card random-color">
          <div class="card-body">
            <h5 class="card-title text-white">Total Stok Bahan Baku</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-box-seam"></i>
              </div>
              <div class="ps-3">
                <h6><?= $totalStokBahanBaku ?? 0; ?></h6>
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

      </div>
    </div>
  </section>
</main>
