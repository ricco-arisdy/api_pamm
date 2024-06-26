<?php
// Sambungkan ke database
$connect = new mysqli("localhost", "root", "Ricco18", "uji_pam", 3306);
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
        $nama_loading = $_POST['nama_loading'];
        $pemilik = $_POST['pemilik'];
        $alamat = $_POST['alamat'];
        $foto = addslashes(file_get_contents($_FILES['foto']['tmp_name']));
        $lokasi = $_POST['lokasi'];

        // Insert data ke database
        $insertQuery = "INSERT INTO loading (nama_loading, pemilik, alamat, foto, lokasi) 
                        VALUES ('$nama_loading', '$pemilik', '$alamat', '$foto', '$lokasi')";
        if ($connect->query($insertQuery) === TRUE) {
            echo json_encode(array("status" => "success", "message" => "Data loading berhasil ditambahkan"));
        } else {
            echo json_encode(array("status" => "error", "message" => "Gagal menambahkan data loading"));
        }
        break;

    case 'GET':
        // Ambil data dari database
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $query = "SELECT * FROM loading";
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
        $nama_loading = $_PUT['nama_loading'];
        $pemilik = $_PUT['pemilik'];
        $alamat = $_PUT['alamat'];
        $lokasi = $_PUT['lokasi'];

        // Update data di database
        $updateQuery = "UPDATE loading SET nama_loading = '$nama_loading', pemilik = '$pemilik', alamat = '$alamat', lokasi = '$lokasi' WHERE id = '$id'";
        if ($connect->query($updateQuery) === TRUE) {
            echo json_encode(array("status" => "success", "message" => "Data loading berhasil diperbarui"));
        } else {
            echo json_encode(array("status" => "error", "message" => "Gagal memperbarui data loading"));
        }
        break;

    case 'DELETE':
        // Ambil informasi dari permintaan DELETE
        parse_str(file_get_contents("php://input"), $_DELETE);
        $id = $_DELETE['id'];

        // Hapus data dari database
        $deleteQuery = "DELETE FROM loading WHERE id = '$id'";
        if ($connect->query($deleteQuery) === TRUE) {
            echo json_encode(array("status" => "success", "message" => "Data loading berhasil dihapus"));
        } else {
            echo json_encode(array("status" => "error", "message" => "Gagal menghapus data loading"));
        }
        break;

    default:
        echo json_encode(array("status" => "error", "message" => "Metode tidak dikenali"));
        break;
}

// Tutup koneksi
$connect->close();
