<?php 

require '../connect.php';

// Function untuk ambil data
// function query($query){
//     global $connect;
//     $result = mysqli_query($connect, $query);
//     $rows = [];
//     while($row = mysqli_fetch_assoc($result)){
//         $rows[] = $row;
//     }
//     return $rows;
// }

// Function untuk tambah produk
function tambahProduk($data){
    global $connect;

    $idProduk = 'PRD-' . date('YmdHis');
    $namaProduk = htmlspecialchars($data["namaProduk"]);
    $hargaProduk = htmlspecialchars($data["hargaProduk"]);
    $stokProduk = htmlspecialchars($data["stokProduk"]);
    $deskripsiProduk = htmlspecialchars($data["deskripsiProduk"]);

    // Cek apakah admin pilih kategori "lain"
    if ($data["idKategori"] === "lain") {
        $kategoriBaru = htmlspecialchars($data["kategoriBaru"]);

        // Tambahkan kategori baru ke database
        $queryKategoriBaru = "INSERT INTO kategori (namaKategori) VALUES ('$kategoriBaru')";
        mysqli_query($connect, $queryKategoriBaru);

        // Ambil ID dari kategori baru tersebut
        $idKategori = mysqli_insert_id($connect);
    } else {
        $idKategori = htmlspecialchars($data["idKategori"]);
    }

    // Upload gambar
    $gambarProduk = upload();
    if (!$gambarProduk){
        return false;
    }

    // Simpan produk
    $query = "INSERT INTO produkJadi (idProduk, namaProduk, hargaProduk, stokProduk, gambarProduk, idKategori, deskripsiProduk) 
              VALUES ('$idProduk','$namaProduk','$hargaProduk','$stokProduk','$gambarProduk','$idKategori','$deskripsiProduk')";

    mysqli_query($connect, $query);
    return mysqli_affected_rows($connect);
}


// Function untuk upload gambar
function upload(){
    $namaFile = $_FILES['gambarProduk']['name'];
    $ukuranFile = $_FILES['gambarProduk']['size'];
    $error = $_FILES['gambarProduk']['error'];
    $tmpName = $_FILES['gambarProduk']['tmp_name'];

    if($error === 4){
        echo "<script>alert('Pilih Gambar Dahulu!');</script>";
        return false;
    }

    $ekstensiValid = ['jpg', 'jpeg', 'png', 'jfif', 'webp'];
    $ekstensiGambar = strtolower(end(explode('.', $namaFile)));

    if(!in_array($ekstensiGambar, $ekstensiValid)){
        echo "<script>alert('Ekstensi gambar tidak valid!');</script>";
        return false;
    }

    if($ukuranFile > 1000000){
        echo "<script>alert('Ukuran gambar terlalu besar!');</script>";
        return false;
    }

    $namaFileBaru = uniqid() . '.' . $ekstensiGambar;
    move_uploaded_file($tmpName, '../img/' . $namaFileBaru);
    return $namaFileBaru;
}

// Function untuk update produk
function updateProduk($data){
    global $connect;
    $idProduk = $data["idProduk"];
    $namaProduk = htmlspecialchars($data["namaProduk"]);
    $hargaProduk = htmlspecialchars($data["hargaProduk"]);
    $stokProduk = htmlspecialchars($data["stokProduk"]);
    $deskripsiProduk = htmlspecialchars($data["deskripsiProduk"]);
    $varianRasa = $data["varianRasa"];
    $beforeUpdate = htmlspecialchars($data["beforeupdate"]);

    if($_FILES['gambarProduk']['error'] === 4){
        $gambarProduk = $beforeUpdate;
    } else {
        $gambarProduk = upload();
        unlink('../img/' . $beforeUpdate);
    }

    $query = "UPDATE produkJadi SET 
                namaProduk = '$namaProduk',
                hargaProduk = '$hargaProduk',
                stokProduk = '$stokProduk',
                deskripsiProduk = '$deskripsiProduk',
                gambarProduk = '$gambarProduk',
                varianRasa = '$varianRasa'
              WHERE idProduk = '$idProduk'";
    
    mysqli_query($connect, $query);
    return mysqli_affected_rows($connect);
}

// Function untuk hapus produk
function deleteProduk($idProduk){
    global $connect;
    $result = mysqli_query($connect, "SELECT gambarProduk FROM produkJadi WHERE idProduk = '$idProduk'");
    $row = mysqli_fetch_assoc($result);
    $gambarProduk = $row["gambarProduk"];

    mysqli_query($connect, "DELETE FROM produkJadi WHERE idProduk = '$idProduk'");

    if(mysqli_affected_rows($connect) > 0) {
        unlink('../img/' . $gambarProduk);
    }

    return mysqli_affected_rows($connect);
}


// ====================================================================================================

// FUnction untuk hapus customer
function deleteCustomer($username){
    global $connect;
    mysqli_query($connect, "DELETE FROM customer WHERE username = '$username'");
    return mysqli_affected_rows($connect);
}

// Function untuk update customer
function updateCustomer($data){
    global $connect;
    $username = $data["username"];
    $password = htmlspecialchars($data["password"]);
    $namaLengkap = htmlspecialchars($data["namaLengkap"]);
    $email = htmlspecialchars($data["email"]);
    $dob = htmlspecialchars($data["dob"]);
    $gender = htmlspecialchars($data["gender"]);
    $alamat = htmlspecialchars($data["alamat"]);
    $kota = htmlspecialchars($data["kota"]);
    $contact = htmlspecialchars($data["contact"]);
    $paypalID = htmlspecialchars($data["paypalID"]);

    //if no password input
    if($password == ''){
        $password = $data["passwordOLD"];
    }
    else {
        $password = password_hash($password, PASSWORD_DEFAULT);
    }

    $query = "UPDATE customer SET 
            password = '$password',
            namaLengkap = '$namaLengkap',
            email = '$email',
            dob = '$dob',
            gender = '$gender',
            alamat = '$alamat',
            kota = '$kota',
            contact = '$contact',
            paypalID = '$paypalID'
            WHERE username = '$username'
            ";
    mysqli_query($connect, $query);
    return mysqli_affected_rows($connect);
}


// ====================================================================================================

// Function untuk accept transaksi
function acceptTransaksi($idTransaksi){
    global $connect;
    $query = "UPDATE transaksi SET 
                statusTransaksi = 'Accepted',
                statusPengiriman = 'Dalam Perjalanan'
                WHERE idTransaksi = '$idTransaksi'
                ";
    mysqli_query($connect, $query);
    return mysqli_affected_rows($connect);
}

// Function untuk reject transaksi
function rejectTransaksi($idTransaksi){
    global $connect;
    $query = "UPDATE transaksi SET 
                statusTransaksi = 'Rejected',
                statusPengiriman = 'Dibatalkan'
                WHERE idTransaksi = '$idTransaksi'
                ";
    mysqli_query($connect, $query);

    //dapatkan semua jumlah produk di keranjang lalu tambahkan ke stok produk
    $allKeranjang = query("SELECT * FROM keranjang WHERE idTransaksi = '$idTransaksi'");
    foreach($allKeranjang as $keranjang) {
        $idProduk = $keranjang["idProduk"];
        $jumlah = $keranjang["jumlah"];
        mysqli_query($connect, "UPDATE produk SET stokProduk = stokProduk + '$jumlah' WHERE idProduk = '$idProduk'");
    }
    return mysqli_affected_rows($connect);
}


?>