<?php
require '../connect.php';
$title = 'Bahan Baku';
require 'template/headerAdmin.php';
require 'template/sidebarAdmin.php';
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Bahan Baku</h1>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body pt-3">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Bahan</th>
                            <th>Gambar</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Supplier</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        // Query ambil data bahan baku + supplier
                        $query = mysqli_query($connect, "
                            SELECT b.id_bahan, b.nama_bahan, b.gambar, b.harga, b.stok, s.nama_supplier
                            FROM bahan_baku b
                            LEFT JOIN supplier s ON b.id_supplier = s.id_supplier
                        ");

                        while ($data = mysqli_fetch_assoc($query)) :
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($data['nama_bahan']); ?></td>
                            <td>
                                <?php if ($data['gambar']) : ?>
                                    <img src="../uploads/<?= htmlspecialchars($data['gambar']); ?>" width="50">
                                <?php else : ?>
                                    Tidak Ada
                                <?php endif; ?>
                            </td>
                            <td>Rp <?= number_format($data['harga']); ?></td>
                            <td><?= $data['stok']; ?></td>
                            <td><?= htmlspecialchars($data['nama_supplier']); ?></td>
                            <td>
                                <a href="pesanBahan.php?id=<?= $data['id_bahan']; ?>" class="btn btn-success btn-sm">Pesan</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

