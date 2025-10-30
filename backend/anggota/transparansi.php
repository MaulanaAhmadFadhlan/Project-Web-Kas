<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Koneksi ke database MySQL
$host = "localhost";
$user = "root";
$pass = "";
$db   = "kas_rt";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Koneksi database gagal"]);
    exit();
}

// Ambil total pemasukan
$sql_pemasukan = "SELECT IFNULL(SUM(jumlah), 0) AS total_pemasukan FROM pemasukan";
$result_pemasukan = $conn->query($sql_pemasukan);
$total_pemasukan = $result_pemasukan->fetch_assoc()['total_pemasukan'];

// Ambil total pengeluaran
$sql_pengeluaran = "SELECT IFNULL(SUM(jumlah), 0) AS total_pengeluaran FROM pengeluaran";
$result_pengeluaran = $conn->query($sql_pengeluaran);
$total_pengeluaran = $result_pengeluaran->fetch_assoc()['total_pengeluaran'];

// Hitung saldo akhir
$saldo_akhir = $total_pemasukan - $total_pengeluaran;

// Ambil daftar ringkas transaksi terakhir (opsional)
$sql_transaksi = "
    (SELECT tanggal, keterangan, jumlah, 'Pemasukan' AS jenis FROM pemasukan)
    UNION ALL
    (SELECT tanggal, keterangan, jumlah, 'Pengeluaran' AS jenis FROM pengeluaran)
    ORDER BY tanggal DESC
    LIMIT 10
";
$result_transaksi = $conn->query($sql_transaksi);

$transaksi = [];
while ($row = $result_transaksi->fetch_assoc()) {
    $transaksi[] = $row;
}

// Kirim data JSON
echo json_encode([
    "status" => "success",
    "total_pemasukan" => $total_pemasukan,
    "total_pengeluaran" => $total_pengeluaran,
    "saldo_akhir" => $saldo_akhir,
    "transaksi_terakhir" => $transaksi
], JSON_PRETTY_PRINT);

$conn->close();
?>