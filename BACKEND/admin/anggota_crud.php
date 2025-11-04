<?php
require_once('../../helpers/koneksi.php');
header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  // ===================== [ GET DATA ] =====================
  case 'GET':
    $res = $conn->query("SELECT * FROM anggota ORDER BY id DESC");
    $data = [];
    while ($row = $res->fetch_assoc()) {
      $data[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $data]);
    break;

  // ===================== [ TAMBAH DATA ] =====================
  case 'POST':
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input || !isset($input['nama']) || !isset($input['username']) || !isset($input['password'])) {
      echo json_encode(['success'=>false,'message'=>'Nama, Username, dan Password wajib diisi.']);
      exit;
    }

    $nama     = $conn->real_escape_string($input['nama']);
    $username = $conn->real_escape_string($input['username']);
    $password = $conn->real_escape_string($input['password']);
    $alamat   = $conn->real_escape_string($input['alamat'] ?? '');
    $no_hp    = $conn->real_escape_string($input['no_hp'] ?? '');
    $added_by = $conn->real_escape_string($input['added_by'] ?? null);

    // insert ke database
    $sql = "INSERT INTO anggota (nama, username, password, alamat, no_hp, added_by)
            VALUES ('$nama', '$username', '$password', '$alamat', '$no_hp', " . ($added_by ? "'$added_by'" : "NULL") . ")";

    if ($conn->query($sql)) {
      echo json_encode(['success'=>true,'message'=>'Anggota baru berhasil ditambahkan.']);
    } else {
      echo json_encode(['success'=>false,'message'=>'Gagal menambah data: '.$conn->error]);
    }
    break;

  // ===================== [ UPDATE DATA ] =====================
  case 'PUT':
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['id'])) {
      echo json_encode(['success'=>false,'message'=>'ID anggota wajib diisi.']);
      exit;
    }

    $id       = (int)$input['id'];
    $nama     = $conn->real_escape_string($input['nama']);
    $username = $conn->real_escape_string($input['username']);
    $password = $conn->real_escape_string($input['password']);
    $alamat   = $conn->real_escape_string($input['alamat']);
    $no_hp    = $conn->real_escape_string($input['no_hp']);

    $sql = "UPDATE anggota 
            SET nama='$nama', username='$username', password='$password',
                alamat='$alamat', no_hp='$no_hp'
            WHERE id=$id";

    if ($conn->query($sql)) {
      echo json_encode(['success'=>true,'message'=>'Data anggota berhasil diperbarui.']);
    } else {
      echo json_encode(['success'=>false,'message'=>'Gagal memperbarui data: '.$conn->error]);
    }
    break;

  // ===================== [ HAPUS DATA ] =====================
  case 'DELETE':
    parse_str(file_get_contents("php://input"), $_DEL);
    $id = (int)($_DEL['id'] ?? 0);

    if ($conn->query("DELETE FROM anggota WHERE id=$id")) {
      echo json_encode(['success'=>true,'message'=>'Data anggota berhasil dihapus.']);
    } else {
      echo json_encode(['success'=>false,'message'=>'Gagal menghapus data: '.$conn->error]);
    }
    break;

  // ===================== [ DEFAULT / INVALID METHOD ] =====================
  default:
    http_response_code(405);
    echo json_encode(['success'=>false,'message'=>'Metode tidak diizinkan']);
}
?>
