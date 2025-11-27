<?php
require_once("../helpers/koneksi.php");
require_once("check_admin.php");
header("Content-Type: application/json");

// Bulan & tahun saat ini
$bulan = date('F');      // contoh: "November"
$tahun = date('Y');      // contoh: 2025

$data = [
    "belum_lunas" => [],
    "menunggu_verifikasi" => [],
    "lunas" => []
];

// ====== 1. BELUM LUNAS ======
$query = $conn->prepare("
    SELECT a.nama, s.jumlah, s.bulan, s.tahun 
    FROM status_pembayaran s
    JOIN anggota a ON s.id_anggota = a.id
    WHERE s.status = 'Belum Lunas'
    AND s.bulan = ?
    AND s.tahun = ?
    ORDER BY a.nama ASC
");
$query->bind_param("si", $bulan, $tahun);
$query->execute();
$result = $query->get_result();

while ($row = $result->fetch_assoc()) {
    $data["belum_lunas"][] = $row;
}


// ====== 2. MENUNGGU VERIFIKASI ======
$query = $conn->prepare("
    SELECT a.nama, s.jumlah, s.bulan, s.tahun, s.bukti, s.tanggal_bayar
    FROM status_pembayaran s
    JOIN anggota a ON s.id_anggota = a.id
    WHERE s.status = 'Menunggu Verifikasi'
    AND s.bulan = ?
    AND s.tahun = ?
    ORDER BY a.nama ASC
");
$query->bind_param("si", $bulan, $tahun);
$query->execute();
$result = $query->get_result();

while ($row = $result->fetch_assoc()) {
    $data["menunggu_verifikasi"][] = $row;
}


// ====== 3. LUNAS ======
$query = $conn->prepare("
    SELECT a.nama, s.jumlah, s.bulan, s.tahun, s.tanggal_bayar
    FROM status_pembayaran s
    JOIN anggota a ON s.id_anggota = a.id
    WHERE s.status = 'Lunas'
    AND s.bulan = ?
    AND s.tahun = ?
    ORDER BY a.nama ASC
");
$query->bind_param("si", $bulan, $tahun);
$query->execute();
$result = $query->get_result();

while ($row = $result->fetch_assoc()) {
    $data["lunas"][] = $row;
}

// Output JSON lengkap
echo json_encode([
    "success" => true,
    "bulan" => $bulan,
    "tahun" => $tahun,
    "data" => $data
]);
?>
