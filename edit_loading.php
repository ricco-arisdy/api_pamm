<?php
// Sambungkan ke database
$connect = new mysqli("localhost", "root", "Ricco18", "uji_pam", 3306);

// Periksa koneksi
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}


// Validasi data POST
if (!isset($_POST['id'], $_POST['nama_loading'], $_POST['pemilik'], $_POST['alamat'], $_POST['lokasi'], $_POST['user_id'])) {
    echo json_encode(array("status" => "error", "message" => "Invalid request"));
    exit();
}

// Ambil informasi dari permintaan
$id = intval($_POST['id']);
$nama_loading = $_POST['nama_loading'];
$pemilik = $_POST['pemilik'];
$alamat = $_POST['alamat'];
$lokasi = $_POST['lokasi'];
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
    // Jika user ID valid, perbarui data loading di database
    $updateQuery = $connect->prepare("UPDATE loading SET nama_loading = ?, pemilik = ?, alamat = ?, lokasi = ? WHERE id = ? AND user_id = ?");
    $updateQuery->bind_param("ssssii", $nama_loading, $pemilik, $alamat, $lokasi, $id, $user_id);
    if ($updateQuery->execute()) {
        // Jika pembaruan loading berhasil, kirimkan respons berhasil
        echo json_encode(array("status" => "success", "message" => "Pembaruan loading berhasil"));
    } else {
        // Jika terjadi kesalahan saat pembaruan loading, kirimkan respons gagal
        echo json_encode(array("status" => "error", "message" => "Pembaruan loading gagal: " . $connect->error));
    }
}

// Tutup koneksi
$connect->close();
