<?php
$title = 'Registrasi Supplier';

// Koneksi ke database
require 'connect.php';

if(isset($_POST["submit"])){
    // Ambil data dari form
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $password2 = htmlspecialchars($_POST['password2']);
    $nama_supplier = htmlspecialchars($_POST['nama_supplier']);
    $kontak_supplier = htmlspecialchars($_POST['kontak_supplier']);
    $alamat = htmlspecialchars($_POST['alamat']);

    // Validasi password dan retype password sama
    if($password !== $password2){
        echo "<script>alert('Konfirmasi password tidak sesuai!');</script>";
    } else {
        // Hash password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Query simpan data supplier
        $stmt = $connect->prepare("INSERT INTO supplier (username, password, nama_supplier, kontak_supplier, alamat) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $hash, $nama_supplier, $kontak_supplier, $alamat);

        if($stmt->execute()){
            echo "<script>
                alert('Supplier berhasil didaftarkan!');
                window.location.href = 'login.php';
                </script>";
        } else {
            echo "<script>alert('Pendaftaran gagal!');</script>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= $title; ?></title>
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2 class="text-center">Registrasi Supplier</h2>
    <form method="POST" class="mt-4">
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Retype Password</label>
        <input type="password" name="password2" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Nama Supplier</label>
        <input type="text" name="nama_supplier" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Kontak Supplier</label>
        <input type="text" name="kontak_supplier" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control" required></textarea>
      </div>
      <div class="d-flex justify-content-between">
        <button type="submit" name="submit" class="btn btn-danger">Submit</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
      </div>
    </form>
  </div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
