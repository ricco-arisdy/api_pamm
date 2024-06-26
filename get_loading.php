<?php
// Sambungkan ke database
$connect = new mysqli("localhost", "root", "Ricco18", "uji_pam", 3306);

// Periksa koneksi
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Ambil userId dari request (misalnya dari query string)
$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Periksa apakah userId valid
if ($userId > 0) {
    // Ambil data dari tabel 'loading' berdasarkan userId
    $sql = "SELECT * FROM loading WHERE user_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // untuk menyimpan data
    $data = array();

    // Periksa query dieksekusi
    if ($result->num_rows > 0) {
        // Output data setiap baris
        while ($row = $result->fetch_assoc()) {
            // Tambahkan data ke dalam array
            $data[] = $row;
        }
        // Encode data menjadi format JSON 
        echo json_encode($data);
    } else {
        echo json_encode(array("status" => "error", "message" => "Tidak ada data loading yang ditemukan untuk user_id: $userId"));
    }

    // Tutup statement
    $stmt->close();
} else {
    echo json_encode(array("status" => "error", "message" => "Invalid user_id"));
}

// Tutup koneksi
$connect->close();
