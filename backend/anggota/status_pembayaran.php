<?php
header('Content-Type: application/json');
session_start();
include('../koneksi.php');

// Pastikan user sudah login
if (!isset($_SESSION['anggota_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Belum login sebagai anggota.'
    ]);
    exit;
}

$anggota_id = $_SESSION['anggota_id'];

try {
    // Ambil data pembayaran terbaru milik anggota
    $sql = "SELECT id, tanggal, jenis_iuran, jumlah, status 
            FROM iuran 
            WHERE anggota_id = '$anggota_id' 
            ORDER BY tanggal DESC 
            LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        throw new Exception(mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode([
            'success' => true,
            'data' => [
                'tanggal' => $row['tanggal'],
                'jenis_iuran' => $row['jenis_iuran'],
                'jumlah' => $row['jumlah'],
                'status' => $row['status']
            ]
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'data' => null,
            'message' => 'Belum ada pembayaran tercatat.'
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}

mysqli_close($conn);
?>