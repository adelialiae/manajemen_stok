<?php
require_once '../connect.php';

if (!$connect) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (isset($_GET['idTransaksi'])) {
    $id = mysqli_real_escape_string($connect, $_GET['idTransaksi']);

    mysqli_begin_transaction($connect);

    try {
        // Update status transaksi
        $queryUpdate = "UPDATE transaksi_pembelian SET statusTransaksi='diterima' WHERE idTransaksi='$id'";
        if (!mysqli_query($connect, $queryUpdate)) {
            throw new Exception("Gagal update status: " . mysqli_error($connect));
        }

        // Ambil data detail transaksi
        $queryDetail = "SELECT id_bahan, qty FROM detail_transaksi_pembelian WHERE idTransaksi='$id'";
        $resultDetail = mysqli_query($connect, $queryDetail);
        if (!$resultDetail) {
            throw new Exception("Gagal ambil detail transaksi: " . mysqli_error($connect));
        }

        while ($row = mysqli_fetch_assoc($resultDetail)) {
            $idBahan = $row['id_bahan'];
            $jumlahBeli = $row['qty'];

            // Ambil nama bahan dan satuan dari tabel bahan_baku
            // Ambil data nama bahan dan satuan
            $queryGetNama = "SELECT nama_bahan FROM bahan_baku WHERE id_bahan='$idBahan'";
            $resultNama = mysqli_query($connect, $queryGetNama);
            if (!$resultNama) {
                throw new Exception("Gagal ambil data bahan: " . mysqli_error($connect));
            }

            $rowNama = mysqli_fetch_assoc($resultNama);
            $namaBahan = $rowNama['nama_bahan'];
            $satuan = $rowNama['satuan'];

            // Cek apakah bahan sudah ada di inventorystokbahan
            $cekExist = mysqli_query($connect, "SELECT * FROM inventorystokbahan WHERE id_bahan='$idBahan'");
            if (!$cekExist) {
                throw new Exception("Gagal cek stok bahan: " . mysqli_error($connect));
            }

            if (mysqli_num_rows($cekExist) > 0) {
                // Update stok jika sudah ada
                $queryUpdateStok = "UPDATE inventorystokbahan SET stokSisa = stokSisa + $jumlahBeli WHERE id_bahan='$idBahan'";
                if (!mysqli_query($connect, $queryUpdateStok)) {
                    throw new Exception("Gagal update stok bahan: " . mysqli_error($connect));
                }
            } else {
                // Insert stok baru jika belum ada
                $queryInsertStok = "INSERT INTO inventorystokbahan (id_bahan, namaBahan, satuan, stokSisa) 
                     VALUES ('$idBahan', '$namaBahan', '$satuan', $jumlahBeli)";

                if (!mysqli_query($connect, $queryInsertStok)) {
                    throw new Exception("Gagal insert stok bahan baru: " . mysqli_error($connect));
                }
            }
        }

        // Commit transaksi jika semua berhasil
        mysqli_commit($connect);

        header("Location: viewTransaksi.php?pesan=terima");
        exit;

    } catch (Exception $e) {
        mysqli_rollback($connect);
        echo "Terjadi kesalahan: " . $e->getMessage();
    }

} else {
    echo "ID transaksi tidak valid.";
}
?>
