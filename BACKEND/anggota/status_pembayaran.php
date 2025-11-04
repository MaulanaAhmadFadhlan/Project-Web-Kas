<?php
require_once('../helpers/koneksi.php');
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['anggota_id'])) {
    echo json_encode(['success' => false, 'message' => 'Belum login']);
    exit;
}

$id_anggota = $_SESSION['anggota_id'];
$bulan_ini = date('F Y');

$query = "SELECT status, tanggal_bayar, jumlah 
          FROM iuran 
          WHERE anggota_id = ? AND bulan = ? 
          LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bind_param('is', $id_anggota, $bulan_ini);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    echo json_encode([
        'success' => true,
        'bulan' => $bulan_ini,
        'status' => $data['status'],
        'tanggal' => $data['tanggal_bayar'],
        'jumlah' => $data['jumlah']
    ]);
} else {
    echo json_encode([
        'success' => true,
        'bulan' => $bulan_ini,
        'status' => 'Belum Bayar',
        'tanggal' => null,
        'jumlah' => 0
    ]);
}
?>
