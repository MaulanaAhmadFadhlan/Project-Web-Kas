<?php
require_once('../../helpers/koneksi.php');
header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

  // ========== GET ==========
  case 'GET':
    $sql = "SELECT p.id, p.tanggal, p.sumber, p.jumlah, a.nama AS nama_admin
            FROM pemasukan p
            LEFT JOIN admin a ON p.id_admin = a.id_admin
            ORDER BY p.tanggal DESC";
    $res = $conn->query($sql);
    $data = [];
    while ($r = $res->fetch_assoc()) {
      $data[] = $r;
    }
    echo json_encode(['success' => true, 'data' => $data]);
    break;

  // ========== POST ==========
  case 'POST':
    $input = json_decode(file_get_contents("php://input"), true);
    if (!$input) {
      echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
      exit;
    }

    $tanggal = $conn->real_escape_string($input['tanggal']);
    $sumber  = $conn->real_escape_string($input['sumber']);
    $jumlah  = (float)$input['jumlah'];
    $id_admin = (int)($input['id_admin'] ?? 'NULL');

    $stmt = $conn->prepare("INSERT INTO pemasukan (tanggal, sumber, jumlah, id_admin) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdi", $tanggal, $sumber, $jumlah, $id_admin);
    $success = $stmt->execute();

    echo json_encode(['success' => $success]);
    break;

  // ========== PUT ==========
  case 'PUT':
    $input = json_decode(file_get_contents("php://input"), true);
    if (!isset($input['id'])) {
      echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
      exit;
    }

    $id = (int)$input['id'];
    $tanggal = $conn->real_escape_string($input['tanggal']);
    $sumber  = $conn->real_escape_string($input['sumber']);
    $jumlah  = (float)$input['jumlah'];

    $stmt = $conn->prepare("UPDATE pemasukan SET tanggal=?, sumber=?, jumlah=? WHERE id=?");
    $stmt->bind_param("ssdi", $tanggal, $sumber, $jumlah, $id);
    $success = $stmt->execute();

    echo json_encode(['success' => $success]);
    break;

  // ========== DELETE ==========
  case 'DELETE':
    parse_str(file_get_contents("php://input"), $_DEL);
    $id = (int)($_DEL['id'] ?? 0);

    if ($id === 0) {
      echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
      exit;
    }

    $success = $conn->query("DELETE FROM pemasukan WHERE id=$id");
    echo json_encode(['success' => $success]);
    break;

  default:
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan']);
    break;
}
?>
