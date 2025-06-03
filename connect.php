<?php 

//Koneksi ke database
$connect = mysqli_connect("localhost", "root", "", "onlineshop");

//Function untuk query
function query($query){
    global $connect;
    $result = mysqli_query($connect, $query);
    $rows = [];
    //Mengambil data dari database dan memasukkannya ke array
    while ($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return $rows;
}

// Function untuk registrasi
function registrasi($data) {
    global $connect;

    $username = htmlspecialchars(strtolower(stripslashes($data["username"])));
    $password = mysqli_real_escape_string($connect, $data["password"]);
    $password2 = mysqli_real_escape_string($connect, $data["password2"]);

    // Cek username sudah ada atau belum
    $result = mysqli_query($connect, "SELECT username FROM customer WHERE username = '$username'");
    if (mysqli_fetch_assoc($result)) {
        echo "<script>alert('Username sudah terdaftar!');</script>";
        return false;
    }

    // Cek konfirmasi password
    if ($password !== $password2) {
        echo "<script>alert('Konfirmasi password tidak sesuai!');</script>";
        return false;
    }

    $namaLengkap = htmlspecialchars($data["namaLengkap"]);
    $email = htmlspecialchars(strtolower(stripslashes($data["email"])));
    $dob = htmlspecialchars($data["dob"]);
    $gender = $data["gender"];
    $alamat = htmlspecialchars($data["alamat"]);
    $kota = htmlspecialchars($data["kota"]);
    $contact = htmlspecialchars($data["contact"]);
    $paypalID = htmlspecialchars($data["paypalID"]);

    // Enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Generate UserID: USR-YYYYMMDDHHMMSS
    $userID = 'USR - ' . date('YmdHisX');

    // Tambahkan user baru ke database
    $query = "INSERT INTO customer 
            (userID, username, password, namaLengkap, email, dob, gender, alamat, kota, contact, paypalID) 
            VALUES 
            ('$userID', '$username', '$password', '$namaLengkap', '$email', '$dob', '$gender', '$alamat', '$kota', '$contact', '$paypalID')";
    
    mysqli_query($connect, $query);

    return mysqli_affected_rows($connect);
}
?>