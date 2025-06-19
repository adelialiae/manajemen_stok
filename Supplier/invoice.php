<?php
use Dompdf\Dompdf;

require_once '../connect.php';
require_once '../dompdf/autoload.inc.php';

if (!isset($_GET['id'])) {
    die("ID transaksi tidak ditemukan.");
}

$id = $_GET['id'];

// Ambil data transaksi pembelian dari supplier
$query = "
    SELECT tp.*, s.nama_supplier, s.alamat,
        GROUP_CONCAT(CONCAT(bb.nama_bahan, ' (', dtp.qty, ' x ', bb.harga, ')') SEPARATOR '<br>') AS daftarBahan
    FROM transaksi_pembelian tp
    JOIN supplier s ON tp.id_supplier = s.id_supplier
    JOIN detail_transaksi_pembelian dtp ON tp.idTransaksi = dtp.idTransaksi
    JOIN bahan_baku bb ON dtp.id_bahan = bb.id_bahan
    WHERE tp.idTransaksi = '$id'
    GROUP BY tp.idTransaksi
";

$result = mysqli_query($connect, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data transaksi tidak ditemukan.");
}

// Siapkan logo
$logoPath = realpath('../img/logo.jpg');
$logoSrc = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath));

// HTML Invoice
ob_start();
?>

<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        color: #333;
        padding: 30px;
    }
    .logo {
        text-align: center;
        margin-bottom: 10px;
    }
    .logo img {
        height: 60px;
    }
    h2 {
        text-align: center;
        color: #e74c3c;
    }
    table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }
    .info-table td {
        padding: 6px;
        vertical-align: top;
    }
    .info-table td:first-child {
        font-weight: bold;
        width: 25%;
    }
    .produk-table th, .produk-table td {
        border: 1px solid #ccc;
        padding: 6px;
    }
    .produk-table th {
        background: #f4f4f4;
    }
    .total {
        text-align: right;
        font-weight: bold;
        margin-top: 10px;
    }
</style>

<div class="logo">
    <img src="<?= $logoSrc ?>" alt="Logo">
</div>

<h2>INVOICE PEMBELIAN BAHAN BAKU</h2>

<table class="info-table">
    <tr><td>ID Transaksi</td><td><?= $data['idTransaksi']; ?></td></tr>
    <tr><td>Supplier</td><td><?= $data['nama_supplier']; ?></td></tr>
    <tr><td>Alamat Supplier</td><td><?= $data['alamat']; ?></td></tr>
    <tr><td>Tanggal</td><td><?= date('d F Y', strtotime($data['tanggal'])); ?></td></tr>
    <tr><td>Pembayaran</td><td><?= $data['caraBayar']; ?></td></tr>
</table>

<h4>Daftar Bahan Baku</h4>
<table class="produk-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Bahan</th>
            <th>Qty</th>
            <th>Harga Satuan</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $detail = mysqli_query($connect, "
            SELECT bb.nama_bahan, dtp.qty, bb.harga 
            FROM detail_transaksi_pembelian dtp
            JOIN bahan_baku bb ON dtp.id_bahan = bb.id_bahan
            WHERE dtp.idTransaksi = '$id'
        ");
        $no = 1;
        while ($row = mysqli_fetch_assoc($detail)) {
            echo "<tr>
                <td>{$no}</td>
                <td>{$row['nama_bahan']}</td>
                <td>{$row['qty']}</td>
                <td>Rp" . number_format($row['harga'], 0, ',', '.') . "</td>
            </tr>";
            $no++;
        }
        ?>
    </tbody>
</table>

<p class="total">Total Harga: Rp<?= number_format($data['totalHarga'], 0, ',', '.'); ?></p>

<?php
$html = ob_get_clean();

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$output = $dompdf->output();

// Simpan file sementara
$pdfPath = "../temp/laporan_transaksi_$idTransaksi.pdf";
file_put_contents($pdfPath, $output);

// Hapus file sementara
unlink($pdfPath);

// Download PDF ke browser
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="laporan_transaksi_supplier' . $idTransaksi . '.pdf"');
echo $output;
exit;
?>
