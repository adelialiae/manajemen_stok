<?php 

require '../connect.php';

// ===================== INSERT =====================
// Funtion untuk tambah produk ke keranjang

function tambahKeranjang($idProduk) {
    global $connect;

    $username = $_SESSION["username"];
    $jumlah = 1;

    $harga = query("SELECT hargaProduk FROM produkJadi WHERE idProduk = '$idProduk'")[0]["hargaProduk"];

    $cekProduk = mysqli_query($connect, "SELECT * FROM keranjang WHERE idProduk = '$idProduk' && username = '$username' && status = 'Belum Dibayar'");
    if(mysqli_num_rows($cekProduk) > 0) {
        $row = mysqli_fetch_assoc($cekProduk);
        $jumlah = $row["jumlah"] + 1;
        $totalHarga = $harga * $jumlah;
        mysqli_query($connect, "UPDATE keranjang SET jumlah = '$jumlah', hargaProduk = '$totalHarga' WHERE idProduk = '$idProduk' && username = '$username' && status = 'Belum Dibayar'");
        //mengurangi stok produk
        mysqli_query($connect, "UPDATE produkJadi SET stokProduk = stokProduk - 1 WHERE idProduk = '$idProduk'");
        return mysqli_affected_rows($connect);
    } else {
        $totalHarga = $harga * $jumlah;
        $query = "INSERT INTO keranjang (username, idProduk, jumlah, hargaProduk, status, idTransaksi) 
                  VALUES ('$username', '$idProduk', '$jumlah', '$totalHarga', 'Belum Dibayar', '')";
        //mengurangi stok produk
        mysqli_query($connect, "UPDATE produkJadi SET stokProduk = stokProduk - 1 WHERE idProduk = '$idProduk'");
        mysqli_query($connect, $query);
        return mysqli_affected_rows($connect);
    }
}


// Hapus produk dari keranjang
function hapusKeranjang($username){
    global $connect;

    //dapatkan semua jumlah produk di keranjang lalu tambahkan ke stok produk
    $allKeranjang = query("SELECT * FROM keranjang WHERE username = '$username' && status = 'Belum Dibayar'");
    foreach($allKeranjang as $keranjang) {
        $idProduk = $keranjang["idProduk"];
        $jumlah = $keranjang["jumlah"];
        mysqli_query($connect, "UPDATE produkJadi SET stokProduk = stokProduk + '$jumlah' WHERE idProduk = '$idProduk'");
    }

    //hapus semua produk di keranjang
    $query = "DELETE FROM keranjang WHERE username = '$username' && status = 'Belum Dibayar'";
    mysqli_query($connect, $query);
    return mysqli_affected_rows($connect);
}

// ===========================================================

function checkout($data){
    global $connect;

    $idTransaksi = 'TRS-'. date('YmdHis');
    $username = $data["username"];
    $tanggalTransaksi = date("Y-m-d");
    $caraBayar = $data["caraBayar"];
    $bank = ($caraBayar === "Prepaid") ? $data["bank"] : "Bayar Ditempat"; 
    $statusTransaksi = "Pending";

    // Hitung total harga + ppn
    $subtotal = $data["totalHarga"];
    $totalHarga = $subtotal + $ppn;

    // Simpan transaksi
    $queryTransaksi = "INSERT INTO transaksi 
    (idTransaksi, username, tanggalTransaksi, caraBayar, bank, statusTransaksi, totalHarga, statusPengiriman, feedBack) 
    VALUES 
    ('$idTransaksi', '$username', '$tanggalTransaksi', '$caraBayar', '$bank', '$statusTransaksi', '$totalHarga', 'Pending', '')";

    mysqli_query($connect, $queryTransaksi);
    
    // Update keranjang yang belum dibayar
    $queryKeranjang = "UPDATE keranjang 
                       SET status = 'Dibayar', idTransaksi='$idTransaksi' 
                       WHERE username = '$username' AND status = 'Belum Dibayar'";
    mysqli_query($connect, $queryKeranjang);

    return $idTransaksi; // ← Kembalikan ID transaksi
}

// ===========================================================
// Function untuk batalkan transaksi
function batalkanTransaksi($idTransaksi){
    global $connect;

    $statusTransaksi = query("SELECT statusTransaksi FROM transaksi WHERE idTransaksi = '$idTransaksi'")[0]["statusTransaksi"];
    $username = $_SESSION["username"];

    //jika status transaksi sudah accepted maka tidak bisa dibatalkan
    if($statusTransaksi == 'Accepted') {
        return 0;
    }
    else {
        //dapatkan semua jumlah produk di keranjang lalu tambahkan ke stok produk
        $allKeranjang = query("SELECT * FROM keranjang WHERE idTransaksi = '$idTransaksi' && username = '$username' && status = 'Dibayar'");
        foreach($allKeranjang as $keranjang) {
            $idProduk = $keranjang["idProduk"];
            $jumlah = $keranjang["jumlah"];
            mysqli_query($connect, "UPDATE produkJadi SET stokProduk = stokProduk + '$jumlah' WHERE idProduk = '$idProduk'");
        }
        //update status transaksi menjadi cancelled
        mysqli_query($connect, "UPDATE transaksi SET statusTransaksi = 'Cancelled', statusPengiriman = 'Dibatalkan' WHERE idTransaksi = '$idTransaksi' AND username = '$username'");
        //update status keranjang menjadi dibatalkan
        mysqli_query($connect, "UPDATE keranjang SET status = 'Dibatalkan' WHERE idTransaksi = '$idTransaksi'");
        return mysqli_affected_rows($connect);
    }
}

// ===========================================================
// Function untuk selesaikan transaksi
function selesaikanTransaksi($idTransaksi){
    global $connect;
    //update statusPengiriman

    $statusTransaksi = query("SELECT statusTransaksi FROM transaksi WHERE idTransaksi = '$idTransaksi'")[0]["statusTransaksi"];
    $username = $_SESSION["username"];

    //jika status transaksi sudah rejected maka tidak bisa diterima
    if($statusTransaksi == 'Rejected' || $statusTransaksi == 'Cancelled') {
        return 0;
    }
    else {
        $query = "UPDATE transaksi SET statusPengiriman = 'Terkirim' WHERE idTransaksi = '$idTransaksi' && username = '$username'";
        mysqli_query($connect, $query);
        return mysqli_affected_rows($connect);
    }
}

// Function untuk feedback
function feedback($data){
    global $connect;

    $idTransaksi = $data["idTransaksi"];
    $feedBack = htmlspecialchars($data["feedBack"]);

    $query = "UPDATE transaksi SET feedBack = '$feedBack' WHERE idTransaksi = '$idTransaksi'";
    mysqli_query($connect, $query);
    return mysqli_affected_rows($connect);
}

?>