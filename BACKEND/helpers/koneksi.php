<?php
$host = "localhost";
$user = "root";
$pass = ""; 
$db   = "kasdesa";

$conn = new mysqli($host, $user, $pass, $db);

// cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

?>
