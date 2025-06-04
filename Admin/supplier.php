<?php
$title = 'Daftar & Perangkingan Supplier';

require 'adminControl.php';
require 'template/headerAdmin.php';
require 'template/sidebarAdmin.php';

// Ambil data supplier
$supplier = query("SELECT * FROM supplier");

// Contoh perangkingan supplier: berdasarkan total transaksi
// Misalnya data total transaksi supplier ada di tabel 'pembelian' (field: idSupplier, totalBeli)
$rankingSupplier = query("
    SELECT s.id_supplier, s.nama_supplier, s.kontak_supplier, s.alamat,
           IFNULL(SUM(p.totalHarga), 0) as totalTransaksi
    FROM supplier s
    LEFT JOIN transaksi_pembelian p ON s.id_supplier = p.id_supplier
    GROUP BY s.id_supplier
    ORDER BY totalTransaksi DESC
");
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1 class="text-danger">Daftar & Perangkingan Supplier</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Supplier</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>ID Supplier</th>
                                        <th>Nama Supplier</th>
                                        <th>No HP</th>
                                        <th>Alamat</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($supplier as $s) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $s['id_supplier']; ?></td>
                                            <td><?= $s['nama_supplier']; ?></td>
                                            <td><?= $s['kontak_supplier']; ?></td>
                                            <td><?= $s['alamat']; ?></td>
                                            
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <hr class="my-4">

                        <h5 class="card-title text-success">Perangkingan Supplier (berdasarkan Total Transaksi)</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Ranking</th>
                                        <th>Nama Supplier</th>
                                        <th>Total Transaksi</th>
                                        <th>No HP</th>
                                        <th>Alamat</th>
                                    
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $rank = 1; ?>
                                    <?php foreach ($rankingSupplier as $r) : ?>
                                        <tr>
                                            <td><?= $rank++; ?></td>
                                            <td><?= $r['nama_supplier']; ?></td>
                                            <td>Rp <?= number_format($r['totalTransaksi'], 0, ',', '.'); ?></td>
                                            <td><?= $r['kontak_supplier']; ?></td>
                                            <td><?= $r['alamat']; ?></td>
                                           
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

