<?php
session_start();

// Hapus semua data session
session_unset();
session_destroy();

// Hapus cookie login (jika ada)
setcookie("anggota_session", "", time() - 3600, "/");

// Kirim respon JSON ke frontend
echo json_encode([
    "success" => true,
    "message" => "Logout berhasil"
]);
?>