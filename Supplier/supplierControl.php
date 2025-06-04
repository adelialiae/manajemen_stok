<?php
require '../connect.php';
require 'template/sidebarSupplier.php';

class SupplierController
{
    private $connect;

    public function __construct($db)
    {
        $this->connect = $db;
    }

    // 1️⃣ Dashboard Supplier
    public function dashboard($id_supplier)
    {
        // Hitung total produk/bahan baku supplier
        $stmt = $this->connect->prepare("SELECT COUNT(*) as total_bahan_baku FROM bahan_baku WHERE id_supplier=?");
        $stmt->bind_param("i", $id_supplier);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $totalbahan_baku = $row['total_bahan_baku'];

        // Hitung pesanan dengan status 'Belum Diterima'
        $stmt = $this->connect->prepare("SELECT COUNT(*) as pesanan_belum_diterima FROM pemesanan_bahan_baku WHERE id_supplier=? AND status_pemesanan='Belum Diterima'");
        $stmt->bind_param("i", $id_supplier);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $pesananBelumDiterima = $row['pesanan_belum_diterima'];

        // Hitung pesanan dengan status 'Diterima'
        $stmt = $this->connect->prepare("SELECT COUNT(*) as pesanan_diterima FROM pemesanan_bahan_baku WHERE id_supplier=? AND status_pemesanan='Diterima'");
        $stmt->bind_param("i", $id_supplier);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $pesananDiterima = $row['pesanan_diterima'];

        // Hitung pesanan dengan status 'Ditolak'
        $stmt = $this->connect->prepare("SELECT COUNT(*) as pesanan_ditolak FROM pemesanan_bahan_baku WHERE id_supplier=? AND status_pemesanan='Ditolak'");
        $stmt->bind_param("i", $id_supplier);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $pesananDitolak = $row['pesanan_ditolak'];

        return [
            'total_bahan_baku' => $totalbahan_baku,
            'pesanan_belum_diterima' => $pesananBelumDiterima,
            'pesanan_diterima' => $pesananDiterima,
            'pesanan_ditolak' => $pesananDitolak
        ];
    }

    // 2️⃣ Lihat daftar pesanan
    public function getPesanan($id_supplier)
    {
        $stmt = $this->connect->prepare("SELECT * FROM pemesanan_bahan_baku WHERE id_supplier=? ORDER BY tanggal_pemesanan DESC");
        $stmt->bind_param("i", $id_supplier);
        $stmt->execute();
        $result = $stmt->get_result();

        $pesanan = [];
        while ($row = $result->fetch_assoc()) {
            $pesanan[] = $row;
        }

        return $pesanan;
    }

    // 2️⃣ Update status pesanan (Diterima, Ditolak, Dikirim, Selesai)
    public function updateStatusPesanan($id_pesanan, $status_baru)
    {
        $stmt = $this->connect->prepare("UPDATE pemesanan_bahan_baku SET status=? WHERE pemesanan_bahan_id=?");
        $stmt->bind_param("si", $status_baru, $id_pesanan);
        return $stmt->execute();
    }

    // 3️⃣ Lihat daftar retur
    public function getRetur($id_supplier)
    {
        $stmt = $this->connect->prepare("SELECT * FROM retur_bahan_baku WHERE id_supplier=? ORDER BY tanggal_retur DESC");
        $stmt->bind_param("i", $id_supplier);
        $stmt->execute();
        $result = $stmt->get_result();

        $retur = [];
        while ($row = $result->fetch_assoc()) {
            $retur[] = $row;
        }

        return $retur;
    }

    // 3️⃣ Update status retur
    public function updateStatusRetur($id_retur, $status_baru)
    {
        $stmt = $this->connect->prepare("UPDATE retur_bahan_baku SET status=? WHERE retur_id=?");
        $stmt->bind_param("si", $status_baru, $id_retur);
        return $stmt->execute();
    }
}
