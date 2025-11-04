<?php
// BACKEND/anggota/dashboard_anggota.php
require_once('../../helpers/koneksi.php');
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['anggota_id'])) {
    echo json_encode(['success'=>false,'message'=>'Belum login']);
    exit;
}
$id = (int)$_SESSION['anggota_id'];

// bulan aktif (format: "Oktober 2025")
$bulanAktif = date('F Y');

// status iuran bulan ini (cek pemasukan dengan bulan tersebut)
$stmt = $conn->prepare("SELECT status, tanggal_bayar, jumlah FROM pemasukan WHERE id_anggota = ? AND bulan = ? LIMIT 1");
$stmt->bind_param("is", $id, $bulanAktif);
$stmt->execute();
$r = $stmt->get_result()->fetch_assoc();

$statusIuran = $r ? $r['status'] : 'Belum Bayar';
$tanggalBayar = $r ? $r['tanggal_bayar'] : null;

// total iuran tahun ini (sum pemasukan untuk anggota di tahun berjalan)
$yearStart = date('Y-01-01');
$yearEnd = date('Y-12-31');
$stmt2 = $conn->prepare("SELECT COALESCE(SUM(jumlah),0) AS total FROM pemasukan WHERE id_anggota = ? AND tanggal_bayar BETWEEN ? AND ?");
$stmt2->bind_param("iss", $id, $yearStart, $yearEnd);
$stmt2->execute();
$totalIuranTahun = (float)$stmt2->get_result()->fetch_assoc()['total'];

// saldo kas (global): total pemasukan - total pengeluaran
$res1 = $conn->query("SELECT COALESCE(SUM(jumlah),0) AS total_pemasukan FROM pemasukan");
$res2 = $conn->query("SELECT COALESCE(SUM(jumlah),0) AS total_pengeluaran FROM pengeluaran");
$totalP = (float)$res1->fetch_assoc()['total_pemasukan'];
$totalK = (float)$res2->fetch_assoc()['total_pengeluaran'];
$saldoKas = $totalP - $totalK;

// riwayat singkat (5 terbaru) untuk anggota
$stmt3 = $conn->prepare("SELECT bulan, tanggal_bayar AS tanggal, jumlah, status FROM pemasukan WHERE id_anggota = ? ORDER BY tanggal_bayar DESC LIMIT 5");
$stmt3->bind_param("i", $id);
$stmt3->execute();
$ri = $stmt3->get_result();
$riwayat = [];
while ($row = $ri->fetch_assoc()) {
    $riwayat[] = $row;
}

echo json_encode([
    'success'=>true,
    'bulanAktif'=>$bulanAktif,
    'statusIuran'=>$statusIuran,
    'tanggalBayar'=>$tanggalBayar,
    'totalIuranTahun'=>$totalIuranTahun,
    'periodeTahun' => date('Y'),
    'saldoKas'=>$saldoKas,
    'riwayat'=>$riwayat
], JSON_UNESCAPED_UNICODE);
