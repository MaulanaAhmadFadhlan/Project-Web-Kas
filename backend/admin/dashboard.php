<?php
header('Content-Type: application/json');
include '../koneksi.php';

// Ambil data anggota yang belum lunas bulan berjalan
$bulanSekarang = date('F'); // misal: 'October'
$tahunSekarang = date('Y');

// Query ambil anggota yang belum bayar bulan ini
$query = "
  SELECT a.nama, i.bulan, i.jumlah, i.status 
  FROM iuran i
  JOIN anggota a ON i.id_anggota = a.id
  WHERE i.status = 'Belum Lunas'
  AND i.bulan = '$bulanSekarang'
  AND i.tahun = '$tahunSekarang'
  ORDER BY a.nama ASC
";

$result = mysqli_query($conn, $query);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
  $data[] = $row;
}

echo json_encode($data);