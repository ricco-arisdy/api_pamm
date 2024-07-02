<?php
// Sambungkan ke database
$connect = new mysqli("localhost", "root", "Ricco18", "uji_pam", 3306);
// Periksa koneksi
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Ambil id_lahan dari request (misalnya dari query string)
$idLoading = isset($_GET['id_loading']) ? intval($_GET['id_loading']) : 0;

// Periksa apakah id_lahan valid
if ($idLoading > 0) {
    // Ambil data dari tabel 'panen' berdasarkan id_lahan
    $sql = "SELECT * FROM panen WHERE id_loading = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $idLoading);
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
        echo json_encode(array("status" => "error", "message" => "Tidak ada data panen yang ditemukan untuk id_loading: $idLoading"));
    }

    // Tutup statement
    $stmt->close();
} else {
    echo json_encode(array("status" => "error", "message" => "Invalid id_loading"));
}

// Tutup koneksi
$connect->close();
