<?php
// BACKEND/anggota/riwayat_iuran.php
require_once('../../helpers/koneksi.php');
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['anggota_id'])) {
    echo json_encode(['success'=>false,'message'=>'Belum login','data'=>[]]);
    exit;
}

$id_anggota = (int)$_SESSION['anggota_id'];

// Ambil riwayat pemasukan (iuran) untuk anggota
$stmt = $conn->prepare("SELECT bulan, tanggal_bayar AS tanggal, jumlah, status, sumber FROM pemasukan WHERE id_anggota = ? ORDER BY tanggal_bayar DESC");
$stmt->bind_param("i", $id_anggota);
$stmt->execute();
$res = $stmt->get_result();

$data = [];
while ($r = $res->fetch_assoc()) {
    $data[] = [
      'bulan' => $r['bulan'],
      'tanggal' => $r['tanggal'],
      'jumlah' => (float)$r['jumlah'],
      'status' => $r['status'],
      'sumber' => $r['sumber']
    ];
}

echo json_encode(['success'=>true,'data'=>$data], JSON_UNESCAPED_UNICODE);
