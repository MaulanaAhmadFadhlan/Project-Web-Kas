<?php
// backend/admin/pengeluaran_crud.php
include '../koneksi.php';

// Tangkap aksi dari query string
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

if ($aksi == 'tambah') {
    // ========== CREATE ==========
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    $jumlah = $_POST['jumlah'];

    $query = "INSERT INTO pengeluaran (tanggal, keterangan, jumlah) VALUES ('$tanggal', '$keterangan', '$jumlah')";
    if (mysqli_query($conn, $query)) {
        header("Location: ../../frontend/admin/pengeluaran.html?status=sukses");
    } else {
        echo "Gagal menambah data: " . mysqli_error($conn);
    }

} elseif ($aksi == 'hapus') {
    // ========== DELETE ==========
    $id = $_GET['id'];
    $query = "DELETE FROM pengeluaran WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        header("Location: ../../frontend/admin/pengeluaran.html?status=hapus_sukses");
    } else {
        echo "Gagal menghapus data: " . mysqli_error($conn);
    }

} elseif ($aksi == 'update') {
    // ========== UPDATE ==========
    $id = $_POST['id'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    $jumlah = $_POST['jumlah'];

    $query = "UPDATE pengeluaran SET tanggal='$tanggal', keterangan='$keterangan', jumlah='$jumlah' WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        header("Location: ../../frontend/admin/pengeluaran.html?status=update_sukses");
    } else {
        echo "Gagal mengubah data: " . mysqli_error($conn);
    }

} else {
    // ========== READ ==========
    $result = mysqli_query($conn, "SELECT * FROM pengeluaran ORDER BY tanggal DESC");

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
}
?>