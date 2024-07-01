<?php
header('Content-Type: application/json');

// Koneksi ke database
$connect = new mysqli("localhost", "root", "Ricco18", "uji_pam", 3306);

// Periksa koneksi
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Check if image file is a actual image or fake image
if ($_FILES['image']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['image']['tmp_name'])) {
    $upload_dir = 'uploads/'; // Directory where the file will be stored
    $uploaded_file = $upload_dir . basename($_FILES['image']['name']);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploaded_file)) {
        // File uploaded successfully, now insert into database
        $file_name = basename($_FILES['image']['name']);
        $file_path = $upload_dir . $file_name;

        // Insert into database
        $sql = "INSERT INTO image (image_path) VALUES ('$file_path')";
        if ($connect->query($sql) === TRUE) {
            $response = array('status' => 'success', 'message' => 'Image uploaded and saved to database');
        } else {
            $response = array('status' => 'error', 'message' => 'Error saving image to database: ' . $connect->error);
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to upload image');
    }
} else {
    $response = array('status' => 'error', 'message' => 'No file uploaded');
}

echo json_encode($response);
