<?php
// Sambungkan ke database
$connect = new mysqli("localhost", "root", "Ricco18", "uji_pam", 3306);

// Periksa koneksi
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Validasi data POST
if (!isset($_POST['username'], $_POST['alamat'], $_POST['no_telp'], $_POST['email'], $_POST['password'])) {
    echo json_encode(array("status" => "error", "message" => "Invalid request"));
    exit();
}

// Ambil informasi dari permintaan register
$nama = $_POST['username'];
$alamat = $_POST['alamat'];
$no_telp = $_POST['no_telp'];
$email = $_POST['email'];
$password = $_POST['password']; // Disarankan untuk melakukan hashing pada kata sandi

// Periksa apakah email sudah digunakan
$query = "SELECT * FROM users WHERE email = '$email'";
$result = $connect->query($query);

if ($result->num_rows > 0) {
    // Jika email sudah digunakan, kirimkan respons gagal
    echo json_encode(array("status" => "error", "message" => "Email sudah terdaftar"));
} else {
    // Jika email belum digunakan, tambahkan pengguna ke database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash kata sandi
    $insertQuery = "INSERT INTO users (username, alamat, no_telp, email, password, created_at) 
                    VALUES ('$nama', '$alamat', '$no_telp', '$email', '$hashedPassword', NOW())";
    if ($connect->query($insertQuery) === TRUE) {
        // Jika registrasi berhasil, kirimkan respons berhasil
        echo json_encode(array("status" => "success", "message" => "Registrasi berhasil"));
    } else {
        // Jika terjadi kesalahan saat registrasi, kirimkan respons gagal
        echo json_encode(array("status" => "error", "message" => "Registrasi gagal"));
    }
}

// Tutup koneksi
$connect->close();
