<?php
require_once("../helpers/koneksi.php");
require_once("check_admin.php");
header("Content-Type: application/json");

// Bulan dan tahun saat ini
$bulan = date('F');      // contoh: November
$tahun = date('Y');      // contoh: 2025
$jumlah_iuran = 50000;   // bisa diubah sesuai ketentuan

// Insert otomatis untuk semua anggota
$sql = "
INSERT INTO status_pembayaran (id_anggota, bulan, tahun, jumlah, status)
SELECT a.id, '$bulan', $tahun, $jumlah_iuran, 'Belum Lunas'
FROM anggota a
WHERE NOT EXISTS (
    SELECT 1 
    FROM status_pembayaran s 
    WHERE s.id_anggota = a.id 
    AND s.bulan = '$bulan'
    AND s.tahun = $tahun
)";

// Eksekusi
if ($conn->query($sql)) {
    echo json_encode([
        "success" => true,
        "message" => "Data iuran bulan $bulan $tahun berhasil dibuat."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $conn->error
    ]);
}
?>
