<?php
session_start();
require_once("../helpers/koneksi.php");
header("Content-Type: application/json");

// Cek login
if (!isset($_SESSION["id_admin"])) {
    echo json_encode(["success" => false, "message" => "Akses ditolak, silakan login terlebih dahulu."]);
    exit;
}

// Ambil bulan sekarang
$currentMonth = date('m');
$currentYear = date('Y');

// Query: ambil anggota yang belum lunas bulan ini
// Pastikan kamu punya tabel `anggota` dan `status_pembayaran` atau semacamnya
// Misalnya tabel `status_pembayaran` berisi (id_status, id_anggota, bulan, tahun, status, jumlah)
$query = "
    SELECT a.nama, s.bulan, s.jumlah
    FROM anggota a
    JOIN status_pembayaran s ON a.id_anggota = s.id_anggota
    WHERE s.status = 'Belum Lunas'
    AND s.bulan = MONTHNAME(CURDATE())
    AND s.tahun = YEAR(CURDATE())
";

$result = $conn->query($query);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "bulan" => $row["bulan"],
            "nama" => $row["nama"],
            "jumlah" => $row["jumlah"]
        ];
    }
}

// Keluarkan hasil dalam format JSON
echo json_encode($data);
?>
