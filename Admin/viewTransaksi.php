<?php 

$title = 'Manajemen Transaksi & Pesanan';
require 'adminControl.php';
require 'template/headerAdmin.php';
require 'template/sidebarAdmin.php';

// Pesanan Customer
$pesananCustomer = query("SELECT * FROM transaksi ORDER BY idTransaksi DESC");

// Transaksi Bahan Baku
$transaksiBahanBaku = query("
  SELECT tp.*, s.nama_supplier, 
         GROUP_CONCAT(CONCAT(b.nama_bahan, ' (', dt.qty, ')') SEPARATOR ', ') AS detail_bahan
  FROM transaksi_pembelian tp
  JOIN supplier s ON tp.id_supplier = s.id_supplier
  JOIN detail_transaksi_pembelian dt ON tp.idTransaksi = dt.idTransaksi
  JOIN bahan_baku b ON dt.id_bahan = b.id_bahan
  GROUP BY tp.idTransaksi
  ORDER BY tp.tanggal DESC
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
                    <?php if($pesanan["statusTransaksi"] == 'Accepted' || $pesanan["statusPengiriman"] == 'Dalam Perjalanan') : ?>
                      <span class="badge bg-success">Diterima</span>
                    <?php elseif($pesanan["statusTransaksi"] == 'Rejected' || $pesanan["statusTransaksi"] == 'Ditolak') : ?>
                      <span class="badge bg-danger">Ditolak</span>
                    <?php else : ?>
                      <a href="terimaTransaksi.php?idTransaksi=<?= $pesanan["idTransaksi"]; ?>" 
                        title="Terima Pesanan" 
                        onclick="return confirm('Yakin menerima pesanan <?= $pesanan["idTransaksi"]; ?>?');">
                        <i class="fas fa-check-circle text-success"></i>
                      </a>
                      <a href="rejectTransaksi.php?idTransaksi=<?= $pesanan["idTransaksi"]; ?>"
                        title="Tolak Pesanan"
                        onclick="return confirm('Yakin menolak pesanan <?= $pesanan["idTransaksi"]; ?>?');">
                        <i class="fas fa-times-circle text-danger"></i>
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
        <table class="table table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>ID Transaksi</th>
              <th>Supplier</th>
              <th>Detail Bahan</th>
              <th>Tanggal</th>
              <th>Cara Bayar</th>
              <th>Bank</th>
              <th>Status</th>
              <th>Total Harga</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach($transaksiBahanBaku as $transaksiBB): ?>
              <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($transaksiBB["idTransaksi"]); ?></td>
                <td><?= htmlspecialchars($transaksiBB["nama_supplier"]); ?></td>
                <td><?= htmlspecialchars($transaksiBB["detail_bahan"]); ?></td>
                <td><?= htmlspecialchars($transaksiBB["tanggal"]); ?></td>
                <td><?= htmlspecialchars($transaksiBB["caraBayar"]); ?></td>
                <td><?= htmlspecialchars($transaksiBB["bank"]); ?></td>
                <td><?= htmlspecialchars($transaksiBB["statusTransaksi"]); ?></td>
                <td>Rp<?= number_format($transaksiBB["totalHarga"], 0, ',', '.'); ?></td>
                <td>
                  <?php if ($transaksiBB["statusTransaksi"] === 'diterima' || $transaksiBB["statusTransaksi"] === 'Rejected' || $transaksiBB["statusTransaksi"] === 'Cancelled'): ?>
                    <span class="badge bg-success"><?= htmlspecialchars($transaksiBB["statusTransaksi"]); ?></span>
                  <?php else: ?>
                    <a href="terimaTransaksi.php?idTransaksi=<?= $transaksiBB["idTransaksi"]; ?>" 
                      title="Terima Pesanan" 
                      onclick="return confirm('Yakin menerima transaksi <?= $transaksiBB["idTransaksi"]; ?>?');">
                      <i class="fas fa-check-circle text-success"></i>
                    </a>
                    <a href="retur.php?idTransaksi=<?= $transaksiBB["idTransaksi"]; ?>" 
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
