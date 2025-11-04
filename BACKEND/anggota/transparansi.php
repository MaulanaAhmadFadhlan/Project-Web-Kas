<?php
require_once('../helpers/koneksi.php');
header('Content-Type: application/json');

// total pemasukan
$q1 = $conn->query("SELECT COALESCE(SUM(jumlah),0) AS total_pemasukan FROM pemasukan");
$total_pemasukan = $q1->fetch_assoc()['total_pemasukan'];

// total pengeluaran
$q2 = $conn->query("SELECT COALESCE(SUM(jumlah),0) AS total_pengeluaran FROM pengeluaran");
$total_pengeluaran = $q2->fetch_assoc()['total_pengeluaran'];

// saldo akhir
$saldo = $total_pemasukan - $total_pengeluaran;

// ambil histori kas
$q3 = $conn->query("SELECT keterangan, tanggal, jumlah, jenis FROM kas ORDER BY tanggal DESC");
$riwayat = [];
while ($row = $q3->fetch_assoc()) {
    $riwayat[] = $row;
}

echo json_encode([
    'success' => true,
    'total_pemasukan' => $total_pemasukan,
    'total_pengeluaran' => $total_pengeluaran,
    'saldo' => $saldo,
    'riwayat' => $riwayat
]);
?>
