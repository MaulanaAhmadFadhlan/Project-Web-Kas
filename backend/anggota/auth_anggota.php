<?php
session_start();
include '../koneksi.php'; // pastikan file koneksi.php ada dan tersambung ke database

// Cek aksi
if (isset($_GET['aksi'])) {
    $aksi = $_GET['aksi'];

    // ==== LOGIN ANGGOTA ====
    if ($aksi == 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = mysqli_query($conn, "SELECT * FROM anggota WHERE username='$username' AND password='$password'");
        $data = mysqli_fetch_assoc($query);

        if ($data) {
            $_SESSION['anggota_id'] = $data['id'];
            $_SESSION['anggota_nama'] = $data['nama'];
            header("Location: ../../frontend/anggota/dashboard.html");
        } else {
            echo "<script>alert('Username atau Password salah!'); window.location='../../frontend/anggota/login.html';</script>";
        }
    }

    // ==== LOGOUT ANGGOTA ====
    elseif ($aksi == 'logout') {
        session_unset();
        session_destroy();
        header("Location: ../../frontend/anggota/login.html");
    }
}
?>