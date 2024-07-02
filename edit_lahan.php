<?php
// Sambungkan ke database
$connect = new mysqli("localhost", "root", "Ricco18", "uji_pam", 3306);

// Periksa koneksi
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Validasi data POST
if (!isset($_POST['id'], $_POST['nama_lahan'], $_POST['lokasi'], $_POST['luas'], $_POST['user_id'])) {
    echo json_encode(array("status" => "error", "message" => "Invalid request"));
    exit();
}

// Ambil informasi dari permintaan
$id = intval($_POST['id']);
$nama_lahan = $_POST['nama_lahan'];
$lokasi = $_POST['lokasi'];
$luas = intval($_POST['luas']);
$user_id = intval($_POST['user_id']);

// Periksa apakah user ID yang diberikan valid
$query = $connect->prepare("SELECT * FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows == 0) {
    // Jika user ID tidak valid, kirimkan respons gagal
    echo json_encode(array("status" => "error", "message" => "User ID tidak valid"));
} else {
    // Jika user ID valid, perbarui data lahan di database
    $updateQuery = $connect->prepare("UPDATE lahan SET nama_lahan = ?, lokasi = ?, luas = ? WHERE id = ? AND user_id = ?");
    $updateQuery->bind_param("ssiii", $nama_lahan, $lokasi, $luas, $id, $user_id);
    if ($updateQuery->execute()) {
        // Jika pembaruan lahan berhasil, kirimkan respons berhasil
        echo json_encode(array("status" => "success", "message" => "Pembaruan lahan berhasil"));
    } else {
        // Jika terjadi kesalahan saat pembaruan lahan, kirimkan respons gagal
        echo json_encode(array("status" => "error", "message" => "Pembaruan lahan gagal: " . $connect->error));
    }
}

// Tutup koneksi
$connect->close();
