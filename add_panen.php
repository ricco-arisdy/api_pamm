<?php
// Sambungkan ke database
$connect = new mysqli("localhost", "root", "Ricco18", "uji_pam", 3306);

// Periksa koneksi
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Validasi data POST
if (!isset($_POST['no_panen'], $_POST['jumlah'], $_POST['harga'], $_POST['foto'], $_POST['deskripsi'], $_POST['id_lahan'], $_POST['id_loading'])) {
    echo json_encode(array("status" => "error", "message" => "Invalid request"));
    exit();
}

// Ambil informasi dari permintaan
$no_panen = $_POST['no_panen'];
$jumlah = $_POST['jumlah'];
$harga = $_POST['harga'];
$foto = $_POST['foto'];
$deskripsi = $_POST['deskripsi'];
$id_lahan = $_POST['id_lahan'];
$id_loading = $_POST['id_loading'];

// Periksa apakah id_lahan dan id_loading yang diberikan valid
$queryCheckLahan = "SELECT * FROM lahan WHERE id = '$id_lahan'";
$queryCheckLoading = "SELECT * FROM loading WHERE id = '$id_loading'";
$resultLahan = $connect->query($queryCheckLahan);
$resultLoading = $connect->query($queryCheckLoading);

if ($resultLahan->num_rows == 0 || $resultLoading->num_rows == 0) {
    // Jika id_lahan atau id_loading tidak valid, kirimkan respons gagal
    echo json_encode(array("status" => "error", "message" => "ID lahan atau ID loading tidak valid"));
} else {
    // Jika id_lahan dan id_loading valid, tambahkan panen ke database
    $insertQuery = "INSERT INTO panen (no_panen, jumlah, harga, foto, deskripsi, id_lahan, id_loading) 
                    VALUES ('$no_panen', '$jumlah', '$harga', '$foto', '$deskripsi', '$id_lahan', '$id_loading')";
    if ($connect->query($insertQuery) === TRUE) {
        // Jika pembuatan panen berhasil, kirimkan respons berhasil
        echo json_encode(array("status" => "success", "message" => "Pembuatan panen berhasil"));
    } else {
        // Jika terjadi kesalahan saat pembuatan panen, kirimkan respons gagal
        echo json_encode(array("status" => "error", "message" => "Pembuatan panen gagal: " . $connect->error));
    }
}

// Tutup koneksi
$connect->close();
