<?php
// Sambungkan ke database
$connect = new mysqli("localhost", "root", "Ricco18", "uji_pam", 3306);

// Periksa koneksi
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Check if HTTP method is DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Ambil data JSON dari body request
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Check if ID parameter exists
    $id = isset($data['id']) ? intval($data['id']) : null;

    if (!empty($id)) {
        // SQL query to delete lahan by ID
        $query = "DELETE FROM lahan WHERE id = $id";

        // Execute query
        if (mysqli_query($connect, $query)) {
            // Return success response
            http_response_code(200);
            echo json_encode(array("message" => "Lahan deleted successfully"));
        } else {
            // Return error response
            http_response_code(500);
            echo json_encode(array("message" => "Failed to delete lahan: " . mysqli_error($connect)));
        }
    } else {
        // Return error response if ID parameter is missing or empty
        http_response_code(400);
        echo json_encode(array("message" => "ID parameter is required"));
    }
} else {
    // Return error response for unsupported HTTP method
    http_response_code(405);
    echo json_encode(array("message" => "Method Not Allowed"));
}

// Tutup koneksi
$connect->close();
