<?php
session_start();
require_once('../../config/db.php'); // Sesuai struktur folder backend/admin/auth_admin.php

header('Content-Type: application/json');

// Ambil data POST dari form login
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Username dan password wajib diisi']);
    exit;
}

// Cek apakah username terdaftar dan role-nya admin
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin' LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Admin tidak ditemukan']);
    exit;
}

$user = $result->fetch_assoc();

// Verifikasi password
if (!password_verify($password, $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'Password salah']);
    exit;
}

// Simpan session login admin
$_SESSION['admin'] = [
    'id'       => $user['id'],
    'username' => $user['username'],
    'role'     => $user['role']
];

// Kirim respon ke frontend
echo json_encode(['success' => true, 'message' => 'Login berhasil!']);
exit;
?>