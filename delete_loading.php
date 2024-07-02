<?php
// Sambungkan ke database
$connect = new mysqli("localhost", "root", "Ricco18", "uji_pam", 3306);

// Periksa koneksi
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Validasi data POST
if (!isset($_POST['id'])) {
    echo json_encode(array("status" => "error", "message" => "Invalid request"));
    exit();
}

// Ambil informasi dari permintaan
$id = $_POST['id'];

// Periksa apakah id yang diberikan valid
$query = "SELECT * FROM loading WHERE id = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Jika id tidak valid, kirimkan respons gagal
    echo json_encode(array("status" => "error", "message" => "ID loading tidak valid"));
} else {
    // Periksa apakah loading sedang digunakan di tabel panen
    $checkUsageQuery = "SELECT COUNT(*) as count FROM panen WHERE id_loading = ?";
    $stmtCheck = $connect->prepare($checkUsageQuery);
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $usageResult = $stmtCheck->get_result();
    $usageCount = $usageResult->fetch_assoc()['count'];

    if ($usageCount > 0) {
        // Jika loading sedang digunakan, kirimkan respons gagal khusus
        echo json_encode(array("status" => "error", "message" => "Loading sedang digunakan!"));
    } else {
        // Jika id valid dan tidak ada yang menggunakan loading, hapus dari database
        $deleteQuery = "DELETE FROM loading WHERE id = ?";
        $stmtDelete = $connect->prepare($deleteQuery);
        $stmtDelete->bind_param("i", $id);

        if ($stmtDelete->execute()) {
            // Jika penghapusan berhasil, kirimkan respons berhasil
            echo json_encode(array("status" => "success", "message" => "Penghapusan loading berhasil"));
        } else {
            // Jika terjadi kesalahan saat menghapus loading, kirimkan respons gagal
            echo json_encode(array("status" => "error", "message" => "Penghapusan loading gagal: " . $connect->error));
        }
    }
}

// Tutup statement
$stmt->close();
$stmtCheck->close();
$stmtDelete->close();

// Tutup koneksi
$connect->close();
