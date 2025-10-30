<?php
// backend/anggota/riwayat_iuran.php
header("Content-Type: application/json");
include("../../config/koneksi.php");

// Pastikan koneksi aktif
if (!$koneksi) {
  echo json_encode(["status" => "error", "message" => "Gagal terhubung ke database"]);
  exit();
}

// Ambil data riwayat iuran dari tabel
$query = "SELECT id, bulan, tahun, tanggal_bayar, jumlah, status FROM iuran ORDER BY tahun DESC, id DESC";
$result = mysqli_query($koneksi, $query);

$data = [];
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
      "id" => $row["id"],
      "bulan" => $row["bulan"] . " " . $row["tahun"],
      "tanggal" => $row["tanggal_bayar"] ?: "-",
      "jumlah" => "Rp " . number_format($row["jumlah"], 0, ',', '.'),
      "status" => ucfirst($row["status"])
    ];
  }
  echo json_encode(["status" => "success", "data" => $data]);
} else {
  echo json_encode(["status" => "error", "message" => "Gagal mengambil data"]);
}
?>