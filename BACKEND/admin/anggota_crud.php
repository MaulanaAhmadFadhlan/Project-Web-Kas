<?php
header("Content-Type: application/json");
require_once("../helpers/koneksi.php");

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'getAll':
        $query = mysqli_query($conn, "SELECT * FROM anggota ORDER BY id ASC");
        if (!$query) {
            echo json_encode(["status"=>"error","message"=>mysqli_error($conn)]);
            exit;
        }
        $data = [];
        while($row = mysqli_fetch_assoc($query)){
            $data[] = $row;
        }
        echo json_encode(["status"=>"success","data"=>$data]);
        break;

    case 'insert':
        $nama = mysqli_real_escape_string($conn, $_POST['nama'] ?? '');
        $username = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
        $password = mysqli_real_escape_string($conn, $_POST['password'] ?? '');
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat'] ?? '');
        $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp'] ?? '');

        if ($nama === '' || $username === '' || $password === '') {
            echo json_encode(["status"=>"error","message"=>"Nama, username, dan password wajib diisi"]);
            exit;
        }

        $query = mysqli_query($conn, "INSERT INTO anggota (nama, username, password, alamat, no_hp)
                                      VALUES ('$nama', '$username', '$password', '$alamat', '$no_hp')");
        if (!$query) {
            echo json_encode(["status"=>"error","message"=>mysqli_error($conn)]);
            exit;
        }
        echo json_encode(["status"=>"success"]);
        break;

    case 'update':
        $id = mysqli_real_escape_string($conn, $_POST['id'] ?? '');
        $nama = mysqli_real_escape_string($conn, $_POST['nama'] ?? '');
        $username = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
        $password = mysqli_real_escape_string($conn, $_POST['password'] ?? '');
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat'] ?? '');
        $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp'] ?? '');

        if ($id === '' || $nama === '' || $username === '') {
            echo json_encode(["status"=>"error","message"=>"ID, nama, dan username wajib diisi"]);
            exit;
        }

        $updateFields = "nama='$nama', username='$username', alamat='$alamat', no_hp='$no_hp'";
        if($password !== ''){
            $updateFields .= ", password='$password'";
        }

        $query = mysqli_query($conn, "UPDATE anggota SET $updateFields WHERE id='$id'");
        if (!$query) {
            echo json_encode(["status"=>"error","message"=>mysqli_error($conn)]);
            exit;
        }
        echo json_encode(["status"=>"success"]);
        break;

    case 'delete':
        $id = mysqli_real_escape_string($conn, $_POST['id'] ?? '');
        if ($id === '') {
            echo json_encode(["status"=>"error","message"=>"ID wajib diisi"]);
            exit;
        }
        $query = mysqli_query($conn, "DELETE FROM anggota WHERE id='$id'");
        if (!$query) {
            echo json_encode(["status"=>"error","message"=>mysqli_error($conn)]);
            exit;
        }
        echo json_encode(["status"=>"success"]);
        break;

    default:
        echo json_encode(["status"=>"error","message"=>"Invalid action"]);
}
?>
