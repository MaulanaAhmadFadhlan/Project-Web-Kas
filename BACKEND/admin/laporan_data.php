<?php
// BACKEND/admin/laporan_data.php
require_once(__DIR__ . '/../helpers/koneksi.php');
header('Content-Type: application/json; charset=utf-8');

$response = [
    'success' => false,
    'pemasukan' => [],
    'pengeluaran' => [],
    'total_pemasukan' => 0,
    'total_pengeluaran' => 0,
    'saldo' => 0,
    'error' => ''
];

try {
    // ===================== PEMASUKAN =====================
    $pemasukan_q = "
        SELECT p.tanggal, p.sumber AS keterangan, p.jumlah
        FROM pemasukan p
        ORDER BY p.tanggal DESC
    ";
    $res1 = $conn->query($pemasukan_q);
    $pemasukan = [];
    $total_pemasukan = 0;

    if ($res1) {
        while ($r = $res1->fetch_assoc()) {
            $jumlah = (float)($r['jumlah'] ?? 0);
            $pemasukan[] = [
                'tanggal' => $r['tanggal'],
                'keterangan' => $r['keterangan'] ?? '-',
                'jumlah' => $jumlah
            ];
            $total_pemasukan += $jumlah;
        }
    }

    // ===================== PENGELUARAN =====================
    $pengeluaran_q = "
        SELECT tanggal, keperluan AS keterangan, jumlah
        FROM pengeluaran
        ORDER BY tanggal DESC
    ";
    $res2 = $conn->query($pengeluaran_q);
    $pengeluaran = [];
    $total_pengeluaran = 0;

    if ($res2) {
        while ($r = $res2->fetch_assoc()) {
            $jumlah = (float)($r['jumlah'] ?? 0);
            $pengeluaran[] = [
                'tanggal' => $r['tanggal'],
                'keterangan' => $r['keterangan'] ?? '-',
                'jumlah' => $jumlah
            ];
            $total_pengeluaran += $jumlah;
        }
    }

    // ===================== SALDO =====================
    $saldo = $total_pemasukan - $total_pengeluaran;

    $response = [
        'success' => true,
        'pemasukan' => $pemasukan,
        'pengeluaran' => $pengeluaran,
        'total_pemasukan' => $total_pemasukan,
        'total_pengeluaran' => $total_pengeluaran,
        'saldo' => $saldo
    ];
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
