<?php 

$title = 'Manajemen Transaksi & Pesanan';
require 'adminControl.php';
require 'template/headerAdmin.php';
require 'template/sidebarAdmin.php';

// Pesanan Customer
$pesananCustomer = query("SELECT * FROM transaksi ORDER BY idTransaksi DESC");

// Transaksi Bahan Baku
$transaksiBahanBaku = query("
  SELECT tb.*, s.nama_supplier, bb.nama_bahan, tb.qty
  FROM transaksi_pembelian tb
  JOIN supplier s ON tb.id_supplier = s.id_supplier
  JOIN bahan_baku bb ON tb.id_bahan = bb.id_bahan
  ORDER BY tb.idTransaksi DESC
");

?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1 class="text-danger">Manajemen Pesanan & Transaksi</h1>
  </div>

  <!-- PESANAN CUSTOMER -->
  <section class="section">
    <div class="row">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Daftar Pesanan Customer</h5>
          <table class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>ID Transaksi</th>
                <th>Username</th>
                <th>Tanggal</th>
                <th>Cara Bayar</th>
                <th>Bank</th>
                <th>Status Transaksi</th>
                <th>Status Pengiriman</th>
                <th>Total Harga</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $i=1; foreach($pesananCustomer as $pesanan): ?>
                <tr>
                  <td><?= $i++; ?></td>
                  <td><?= $pesanan["idTransaksi"]; ?></td>
                  <td><?= $pesanan["username"]; ?></td>
                  <td><?= $pesanan["tanggalTransaksi"]; ?></td>
                  <td><?= $pesanan["caraBayar"]; ?></td>
                  <td><?= $pesanan["bank"]; ?></td>
                  <td><?= $pesanan["statusTransaksi"]; ?></td>
                  <td><?= $pesanan["statusPengiriman"]; ?></td>
                  <td>Rp<?= number_format($pesanan["totalHarga"], 0, ',', '.'); ?></td>
                  <td>
                    <?php if($pesanan["statusPengiriman"] == 'Accepted' || $pesanan["statusPengiriman"] == 'Rejected' || $pesanan["statusTransaksi"] == 'Cancelled') : ?>
                      <?= $pesanan["statusPengiriman"]; ?>
                    <?php else : ?>
                      <a href="terimaTransaksi.php?idTransaksi=<?= $pesanan["idTransaksi"]; ?>" 
                        title="Terima Pesanan" 
                        onclick="return confirm('Yakin menerima pesanan <?= $pesanan["idTransaksi"]; ?>?');">
                        <i class="fas fa-check-circle text-success"></i>
                      </a>
                      <a href="returTransaksi.php?idTransaksi=<?= $pesanan["idTransaksi"]; ?>" 
                        title="Retur" 
                        onclick="return confirm('Yakin retur pesanan <?= $pesanan["idTransaksi"]; ?>?');">
                        <i class="fas fa-undo text-warning"></i>
                      </a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  <!-- TRANSAKSI BAHAN BAKU -->
  <section class="section">
    <div class="row">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Daftar Transaksi Bahan Baku (Supplier)</h5>
          <table class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>ID Transaksi</th>
                <th>Supplier</th>
                <th>Nama Bahan Baku</th>
                <th>Qty</th>
                <th>Tanggal</th>
                <th>Cara Bayar</th>
                <th>Bank</th>
                <th>Status</th>
                <th>Total Harga</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $j=1; foreach($transaksiBahanBaku as $transaksiBB): ?>
                <tr>
                  <td><?= $j++; ?></td>
                  <td><?= $transaksiBB["idTransaksi"]; ?></td>
                  <td><?= $transaksiBB["nama_supplier"]; ?></td>
                  <td><?= htmlspecialchars($transaksiBB["nama_bahan"]); ?></td>
                  <td><?= $transaksiBB["qty"]; ?></td>
                  <td><?= $transaksiBB["tanggal"]; ?></td>
                  <td><?= $transaksiBB["caraBayar"]; ?></td>
                  <td><?= $transaksiBB["bank"]; ?></td>
                  <td><?= $transaksiBB["statusTransaksi"]; ?></td>
                  <td>Rp<?= number_format($transaksiBB["totalHarga"], 0, ',', '.'); ?></td>
                  <td>
                    <?php if($transaksiBB["statusTransaksi"] == 'Accepted' || $transaksiBB["statusTransaksi"] == 'Rejected' || $transaksiBB["statusTransaksi"] == 'Cancelled') : ?>
                      <?= $transaksiBB["statusTransaksi"]; ?>
                    <?php else : ?>
                      <a href="terimaTransaksi.php?idTransaksi=<?= $transaksiBB["idTransaksi"]; ?>" 
                        title="Terima Pesanan" 
                        onclick="return confirm('Yakin menerima transaksi <?= $transaksiBB["idTransaksi"]; ?>?');">
                        <i class="fas fa-check-circle text-success"></i>
                      </a>
                      <a href="returTransaksi.php?idTransaksi=<?= $transaksiBB["idTransaksi"]; ?>" 
                        title="Retur" 
                        onclick="return confirm('Yakin retur transaksi <?= $transaksiBB["idTransaksi"]; ?>?');">
                        <i class="fas fa-undo text-warning"></i>
                      </a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

</main>
