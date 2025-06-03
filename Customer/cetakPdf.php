<?php
use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

require_once '../dompdf/autoload.inc.php';
require_once '../PHPMailer-master/src/PHPMailer.php';
require_once '../PHPMailer-master/src/SMTP.php';
require_once '../PHPMailer-master/src/Exception.php';
require_once 'custControl.php';

$idTransaksi = $_GET["id"];
$username = $_SESSION["username"] ?? '';

if (!$username) {
    die("Session tidak ditemukan. Silakan login ulang.");
}

// Ambil data transaksi dan customer
$detail = query("SELECT * FROM transaksi 
    JOIN customer ON transaksi.username = customer.username 
    WHERE transaksi.idTransaksi = '$idTransaksi' AND transaksi.username = '$username'");
if (!$detail) {
    die("Data transaksi tidak ditemukan.");
}
$detail = $detail[0];

// Ambil produk yang dibeli
$produk = query("SELECT * FROM keranjang 
    JOIN produk ON keranjang.idProduk = produk.idProduk 
    WHERE keranjang.username = '$username' AND keranjang.idTransaksi = '$idTransaksi'");

$emailUser = $detail["email"];

// Siapkan gambar logo, ttd, watermark
$pathTtd = realpath('../img/TTD_ADELL_1-removebg-preview.png');
$srcTtd = 'data:image/png;base64,' . base64_encode(file_get_contents($pathTtd));

$logoPath = realpath('../img/logobaru.png');
$logoSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

$wmPath = realpath('../img/watermark_cheerful.png');
$watermarkSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($wmPath));

// Mulai HTML
ob_start();
?>
<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        color: #333;
        padding: 30px;
        position: relative;
    }
    .watermark {
        position: fixed;
        top: 35%;
        left: 25%;
        width: 300px;
        opacity: 0.08;
        z-index: -1;
    }
    h2 {
        text-align: center;
        margin-bottom: 10px;
        color: #2a7ae2;
    }
    .logo {
        text-align: center;
        margin-bottom: 10px;
    }
    .logo img {
        height: 60px;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 20px;
    }
    .info-table td {
        padding: 5px 8px;
        vertical-align: top;
    }
    .info-table td:first-child {
        width: 25%;
        font-weight: bold;
        color: #555;
    }
    .info-table tr {
        border-bottom: 1px solid #ddd;
    }
    .product-table th {
        background-color: #2a7ae2;
        color: white;
        padding: 8px;
        border: 1px solid #ccc;
    }
    .product-table td {
        padding: 6px 8px;
        border: 1px solid #ccc;
    }
    .total {
        font-weight: bold;
        text-align: right;
        margin-top: 10px;
    }
    .signature {
        text-align: right;
        margin-top: 40px;
    }
    .signature img {
        width: 120px;
        height: auto;
        margin-bottom: 5px;
    }
    .signature p {
        margin: 0;
        font-weight: bold;
    }
</style>

<div class="logo">
    <img src="<?= $logoSrc ?>" alt="Logo Cheerful HETO">
</div>

<h2>Cheerful HETO<br>Laporan Belanja Anda</h2>

<img src="<?= $watermarkSrc ?>" class="watermark" alt="Watermark">

<table class="info-table">
    <tr>
        <td>User ID</td><td><?= $detail["userID"] ?? '-'; ?></td>
        <td>Tanggal</td><td><?= date("d F Y", strtotime($detail["tanggalTransaksi"])); ?></td>
    </tr>
    <tr>
        <td>Nama</td><td><?= $detail["namaLengkap"]; ?></td>
        <td>ID Paypal</td><td><?= $detail["paypalID"] ?? '-'; ?></td>
    </tr>
    <tr>
        <td>Alamat</td><td><?= $detail["alamat"] ?? '-'; ?></td>
        <td>Nama Bank</td><td><?= $detail["bank"] ?? '-'; ?></td>
    </tr>
    <tr>
        <td>No HP</td><td><?= $detail["contact"] ?? '-'; ?></td>
        <td>Cara Bayar</td><td><?= $detail["caraBayar"] ?? '-'; ?></td>
    </tr>
</table>

<table class="product-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Jumlah</th>
            <th>Harga</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; foreach($produk as $p): ?>
        <tr>
            <td><?= $i++; ?></td>
            <td><?= $p["namaProduk"] . ' ' . $p["idProduk"]; ?></td>
            <td><?= $p["jumlah"]; ?></td>
            <td>Rp<?= number_format($p["harga"], 0, ',', '.'); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
$subTotal = $detail["totalHarga"] / 1.11;
$ppn = $subTotal * 0.11;
?>

<p class="total">Sub Total (Sebelum Pajak 11%): Rp<?= number_format($subTotal, 0, ',', '.'); ?></p>
<p class="total">PPN (11%): Rp<?= number_format($ppn, 0, ',', '.'); ?></p>
<p class="total">Total belanja (Termasuk Pajak 11%): Rp<?= number_format($detail["totalHarga"], 0, ',', '.'); ?></p>

<div class="signature">
    <p>Pemilik Toko</p>
    <img src="<?= $srcTtd ?>" alt="Tanda Tangan">
    <p>Cheerful HETO</p>
</div>

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

// Kirim Email
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'cheerfulheto@gmail.com';
    $mail->Password   = 'ybrihezucdzurugn'; // App Password Gmail
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('cheerfulheto@gmail.com', 'Cheerful HETO');
    $mail->addAddress($emailUser);

    $mail->isHTML(true);
    $mail->Subject = 'Laporan Transaksi Anda';
    $mail->Body    = 'Halo ' . $detail["namaLengkap"] . ',<br>Berikut kami lampirkan laporan transaksi Anda dalam bentuk PDF.<br><br>Terima kasih telah berbelanja di Cheerful HETO.';

    $mail->addAttachment($pdfPath);
    $mail->send();
} catch (Exception $e) {
    echo "Gagal kirim email: {$mail->ErrorInfo}";
}

// Hapus file sementara
unlink($pdfPath);

// Download PDF ke browser
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="laporan_transaksi_' . $idTransaksi . '.pdf"');
echo $output;
exit;
?>
