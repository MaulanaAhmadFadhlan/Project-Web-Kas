<?php
session_start();
require_once('../helpers/koneksi.php');

header('Content-Type: application/json; charset=utf-8');

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Username dan password wajib diisi']);
    exit;
}

// bisa pakai tabel users atau anggota
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = 'anggota' LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Akun anggota tidak ditemukan']);
    exit;
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'Password salah']);
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = 'anggota';

echo json_encode(['success' => true, 'message' => 'Login berhasil']);
exit;
?>
