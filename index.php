<?php
session_start();

if (isset($_SESSION['role'])) {
  if ($_SESSION['role'] === 'admin') {
    header('Location: frontend/admin/dashboard.html');
    exit;
  } elseif ($_SESSION['role'] === 'anggota') {
    header('Location: frontend/anggota/dashboard.html');
    exit;
  }
}

// Jika belum login
header('Location: frontend/auth/login.html');
exit;
?>
