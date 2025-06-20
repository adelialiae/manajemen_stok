<?php 

$title = 'Customer';

require 'adminControl.php';
require 'template/headerAdmin.php';
require 'template/sidebarAdmin.php';

$allCustomer = query("SELECT * FROM customer");

?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1 class="text-danger">Customer</h1>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Semua Customer</h5>
              <div class="col-md-6 mt-2 mb-3">
                  <input type="text" placeholder="Cari customer..." class="form-control" id="searchingTable">
              </div>

              <!-- Default Table -->
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th scope="col">No</th>
                            <th scope="col">User ID</th>
                            <th scope="col">Username</th>
                            <th scope="col">Nama Lengkap</th>
                            <th scope="col">E-Mail</th>
                            <th scope="col">DOB</th>
                            <th scope="col">Gender</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Kota</th>
                            <th scope="col">Contact</th>
                            <th scope="col">Paypal ID</th>
                            <th scope="col" style="width: 150px;">Aksi</th> <!-- tambah width supaya tombol cukup -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($allCustomer as $customer) : ?>
                            <tr class="text-center align-middle">
                                <td><?= $i++; ?></td>
                                <td><?= $customer["userID"]; ?></td>
                                <td><?= $customer["username"]; ?></td>
                                <td><?= $customer["namaLengkap"]; ?></td>
                                <td><?= $customer["email"]; ?></td>
                                <td><?= $customer["dob"]; ?></td>
                                <td><?= $customer["gender"]; ?></td>
                                <td><?= $customer["alamat"]; ?></td>
                                <td><?= $customer["kota"]; ?></td>
                                <td><?= $customer["contact"]; ?></td>
                                <td><?= $customer["paypalID"]; ?></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="editCustomer.php?username=<?= $customer["username"]; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="deleteCustomer.php?username=<?= $customer["username"]; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus <?= $customer["username"]; ?>?');">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
              </div>
              <!-- End Default Table Example -->
            </div>
          </div>

        </div>
      </div>
    </section>
</main><!-- End #main -->
