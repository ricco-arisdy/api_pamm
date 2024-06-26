<?php
// Sambungkan ke database
$connect = new mysqli("localhost", "root", "Ricco18", "uji_pam", 3306);

// Periksa koneksi
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Validasi data POST
if (!isset($_POST['nama_loading'], $_POST['pemilik'], $_POST['alamat'], $_POST['lokasi'], $_POST['user_id'])) {
    echo json_encode(array("status" => "error", "message" => "Invalid request"));
    exit();
}

// Ambil informasi dari permintaan
$nama_loading = $_POST['nama_loading'];
$pemilik = $_POST['pemilik'];
$alamat = $_POST['alamat'];
$lokasi = $_POST['lokasi'];
$user_id = $_POST['user_id'];

// Periksa apakah user ID yang diberikan valid
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = $connect->query($query);

if ($result->num_rows == 0) {
    // Jika user ID tidak valid, kirimkan respons gagal
    echo json_encode(array("status" => "error", "message" => "User ID tidak valid"));
} else {
    // Jika user ID valid, tambahkan loading ke database
    $insertQuery = "INSERT INTO loading (nama_loading, pemilik, alamat, lokasi, user_id) 
                    VALUES ('$nama_loading', '$pemilik', '$alamat', '$lokasi', '$user_id')";
    if ($connect->query($insertQuery) === TRUE) {
        // Jika pembuatan loading berhasil, kirimkan respons berhasil
        echo json_encode(array("status" => "success", "message" => "Pembuatan loading berhasil"));
    } else {
        // Jika terjadi kesalahan saat pembuatan loading, kirimkan respons gagal
        echo json_encode(array("status" => "error", "message" => "Pembuatan loading gagal: " . $connect->error));
    }
}

// Tutup koneksi
$connect->close();
