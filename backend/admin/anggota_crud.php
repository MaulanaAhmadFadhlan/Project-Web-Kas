<?php
include '../koneksi.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    // =================== GET ===================
    case 'GET':
        $query = mysqli_query($conn, "SELECT * FROM anggota ORDER BY id DESC");
        $data = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    // =================== POST ===================
    case 'POST':
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
        $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);

        $q = mysqli_query($conn, "INSERT INTO anggota (nama, alamat, no_hp) VALUES ('$nama', '$alamat', '$no_hp')");

        echo json_encode([
            'status' => $q ? 'success' : 'error',
            'message' => $q ? 'Anggota berhasil ditambahkan' : 'Gagal menambah anggota'
        ]);
        break;

    // =================== PUT ===================
    case 'PUT':
        parse_str(file_get_contents("php://input"), $_PUT);
        $id = intval($_PUT['id']);
        $nama = mysqli_real_escape_string($conn, $_PUT['nama']);
        $alamat = mysqli_real_escape_string($conn, $_PUT['alamat']);
        $no_hp = mysqli_real_escape_string($conn, $_PUT['no_hp']);

        $q = mysqli_query($conn, "UPDATE anggota SET nama='$nama', alamat='$alamat', no_hp='$no_hp' WHERE id=$id");

        echo json_encode([
            'status' => $q ? 'success' : 'error',
            'message' => $q ? 'Data anggota berhasil diperbarui' : 'Gagal memperbarui data'
        ]);
        break;

    // =================== DELETE ===================
    case 'DELETE':
        parse_str(file_get_contents("php://input"), $_DELETE);
        $id = intval($_DELETE['id']);

        $q = mysqli_query($conn, "DELETE FROM anggota WHERE id=$id");

        echo json_encode([
            'status' => $q ? 'success' : 'error',
            'message' => $q ? 'Anggota berhasil dihapus' : 'Gagal menghapus anggota'
        ]);
        break;

    // =================== DEFAULT ===================
    default:
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan']);
        break;
}
?>