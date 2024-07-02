<?php
// Sambungkan ke database
$connect = new mysqli("localhost", "root", "Ricco18", "uji_pam", 3306);

// Periksa koneksi
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}
// Validasi data POST
if (!isset($_POST['nama_lahan'], $_POST['lokasi'], $_POST['luas'], $_POST['user_id'])) {
    echo json_encode(array("status" => "error", "message" => "Invalid request"));
    exit();
}

// Ambil informasi dari permintaan
$nama_lahan = $_POST['nama_lahan'];
$lokasi = $_POST['lokasi'];
$luas = $_POST['luas'];
$user_id = $_POST['user_id'];

// Periksa apakah user ID yang diberikan valid
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = $connect->query($query);

if ($result->num_rows == 0) {
    // Jika user ID tidak valid, kirimkan respons gagal
    echo json_encode(array("status" => "error", "message" => "User ID tidak valid"));
} else {
    // Jika user ID valid, tambahkan lahan ke database
    $insertQuery = "INSERT INTO lahan (nama_lahan, lokasi, luas, user_id) 
                    VALUES ('$nama_lahan', '$lokasi', '$luas', '$user_id')";
    if ($connect->query($insertQuery) === TRUE) {
        // Jika pembuatan lahan berhasil, kirimkan respons berhasil
        echo json_encode(array("status" => "success", "message" => "Pembuatan lahan berhasil"));
    } else {
        // Jika terjadi kesalahan saat pembuatan lahan, kirimkan respons gagal
        echo json_encode(array("status" => "error", "message" => "Pembuatan lahan gagal"));
    }
}

// Tutup koneksi
$connect->close();
?>