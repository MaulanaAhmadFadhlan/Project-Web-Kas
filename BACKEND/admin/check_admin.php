<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    echo json_encode(["success" => false, "message" => "Akses ditolak. Silakan login admin."]);
    exit;
}
?>
