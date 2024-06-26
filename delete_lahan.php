<?php
// Sambungkan ke database
$connect = new mysqli("localhost", "root", "Ricco18", "uji_pam", 3306);

// Periksa koneksi
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Check if HTTP method is DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if ID parameter exists
    parse_str(file_get_contents("php://input"), $delete_vars);
    $id = isset($delete_vars['id']) ? intval($delete_vars['id']) : null;

    if (!empty($id)) {
        // Sanitize the ID input (optional but recommended)
        // $id = mysqli_real_escape_string($connect, $id);

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
