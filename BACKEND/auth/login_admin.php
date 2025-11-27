<?php
session_start();
require_once("../helpers/koneksi.php");
    
// Pastikan format respons adalah JSON
header("Content-Type: application/json");

// Cegah error HTML ikut keluar
ob_clean();
error_reporting(E_ALL);
ini_set('display_errors', 0); // ubah ke 1 kalau mau debug

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    echo json_encode(["success" => false, "message" => "Username dan password wajib diisi."]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Admin tidak ditemukan."]);
    exit;
}

$user = $result->fetch_assoc();

// Karena password di database belum di-hash
if ($password === $user['password']) {
    $_SESSION['id_admin'] = $user['id_admin'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['level_akses'] = $user['level_akses'];

    echo json_encode([
        "success" => true,
        "message" => "Login berhasil!",
        "redirect" => "../admin/dashboard.html"
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Password salah."]);
}
exit;
?>
