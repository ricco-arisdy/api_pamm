<?php
// Set error reporting and content type
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// Database connection
$connect = new mysqli("localhost", "root", "Ricco18", "uji_pam", 3306);
if ($connect->connect_error) {
    die(json_encode(array("status" => "error", "message" => "Connection failed: " . $connect->connect_error)));
}

// Validate POST data
if (!isset($_POST['no_panen'], $_POST['jumlah'], $_POST['harga'], $_POST['deskripsi'], $_POST['id_lahan'], $_POST['id_loading'])) {
    echo json_encode(array("status" => "error", "message" => "Invalid request"));
    exit();
}

// Extract request data
$no_panen = $_POST['no_panen'];
$jumlah = $_POST['jumlah'];
$harga = $_POST['harga'];
$deskripsi = $_POST['deskripsi'];
$id_lahan = $_POST['id_lahan'];
$id_loading = $_POST['id_loading'];

// Validate id_lahan and id_loading
$queryCheckLahan = "SELECT * FROM lahan WHERE id = '$id_lahan'";
$queryCheckLoading = "SELECT * FROM loading WHERE id = '$id_loading'";
$resultLahan = $connect->query($queryCheckLahan);
$resultLoading = $connect->query($queryCheckLoading);

if ($resultLahan->num_rows == 0 || $resultLoading->num_rows == 0) {
    echo json_encode(array("status" => "error", "message" => "ID lahan atau ID loading tidak valid"));
} else {
    // Handle file upload for foto
    if ($_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $tempName = $_FILES['foto']['tmp_name'];
        $fileName = $_FILES['foto']['name'];
        $fotoPath = './uploads/' . $fileName; // Storage directory

        // Move uploaded file to storage
        if (move_uploaded_file($tempName, $fotoPath)) {
            // Insert panen data into database
            $insertQuery = "INSERT INTO panen (no_panen, jumlah, harga, foto, deskripsi, id_lahan, id_loading) 
                            VALUES ('$no_panen', '$jumlah', '$harga', '$fotoPath', '$deskripsi', '$id_lahan', '$id_loading')";
            if ($connect->query($insertQuery) === TRUE) {
                echo json_encode(array("status" => "success", "message" => "Pembuatan panen berhasil"));
            } else {
                echo json_encode(array("status" => "error", "message" => "Pembuatan panen gagal: " . $connect->error));
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "Gagal memindahkan file gambar"));
        }
    } else {
        echo json_encode(array("status" => "error", "message" => "Gagal mengunggah gambar: " . $_FILES['foto']['error']));
    }
}

// Close database connection
$connect->close();
