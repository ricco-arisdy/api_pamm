<?php
// Sambungkan ke database
$connect = new mysqli("localhost", "root", "Ricco18", "uji_pam", 3307);

// Periksa koneksi
if ($connect->connect_error) {
    die(json_encode(array("status" => "error", "message" => "Connection failed: " . $connect->connect_error)));
}

// Tentukan metode HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Buat respons JSON untuk setiap operasi CRUD
switch ($method) {
    case 'POST':
        // Ambil informasi dari permintaan POST
        $id_user = $_POST['id_user'];
        $nama_lahan = $_POST['nama_lahan'];
        $lokasi = $_POST['lokasi'];
        $luas = $_POST['luas'];

        // Insert data ke database
        $insertQuery = "INSERT INTO lahan (id_user, nama_lahan, lokasi, luas) 
                        VALUES ('$id_user', '$nama_lahan', '$lokasi', '$luas')";
        if ($connect->query($insertQuery) === TRUE) {
            echo json_encode(array("status" => "success", "message" => "Data lahan berhasil ditambahkan"));
        } else {
            echo json_encode(array("status" => "error", "message" => "Gagal menambahkan data lahan"));
        }
        break;

    case 'GET':
        // Ambil data dari database
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $query = "SELECT * FROM lahan";
        if ($id) {
            $query .= " WHERE id = '$id'";
        }
        $result = $connect->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    case 'PUT':
        // Ambil informasi dari permintaan PUT
        parse_str(file_get_contents("php://input"), $_PUT);
        $id = $_PUT['id'];
        $id_user = $_PUT['id_user'];
        $nama_lahan = $_PUT['nama_lahan'];
        $lokasi = $_PUT['lokasi'];
        $luas = $_PUT['luas'];

        // Update data di database
        $updateQuery = "UPDATE lahan SET id_user = '$id_user', nama_lahan = '$nama_lahan', lokasi = '$lokasi', luas = '$luas' WHERE id = '$id'";
        if ($connect->query($updateQuery) === TRUE) {
            echo json_encode(array("status" => "success", "message" => "Data lahan berhasil diperbarui"));
        } else {
            echo json_encode(array("status" => "error", "message" => "Gagal memperbarui data lahan"));
        }
        break;

    case 'DELETE':
        // Ambil informasi dari permintaan DELETE
        parse_str(file_get_contents("php://input"), $_DELETE);
        $id = $_DELETE['id'];

        // Hapus data dari database
        $deleteQuery = "DELETE FROM lahan WHERE id = '$id'";
        if ($connect->query($deleteQuery) === TRUE) {
            echo json_encode(array("status" => "success", "message" => "Data lahan berhasil dihapus"));
        } else {
            echo json_encode(array("status" => "error", "message" => "Gagal menghapus data lahan"));
        }
        break;

    default:
        echo json_encode(array("status" => "error", "message" => "Metode tidak dikenali"));
        break;
}

// Tutup koneksi
$connect->close();
