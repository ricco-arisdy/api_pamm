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
        $id_lahan = $_POST['id_lahan'];
        $id_loading = $_POST['id_loading'];
        $tanggal_panen = $_POST['tanggal_panen'];
        $jumlah_panen = $_POST['jumlah_panen'];
        $harga_panen = $_POST['harga_panen'];
        $foto_panen = addslashes(file_get_contents($_FILES['foto_panen']['tmp_name']));
        $deskripsi_panen = $_POST['deskripsi_panen'];

        // Insert data ke database
        $insertQuery = "INSERT INTO panen (id_lahan, id_loading, tanggal_panen, jumlah_panen, harga_panen, foto_panen, deskripsi_panen) 
                        VALUES ('$id_lahan', '$id_loading', '$tanggal_panen', '$jumlah_panen', '$harga_panen', '$foto_panen', '$deskripsi_panen')";
        if ($connect->query($insertQuery) === TRUE) {
            echo json_encode(array("status" => "success", "message" => "Data panen berhasil ditambahkan"));
        } else {
            echo json_encode(array("status" => "error", "message" => "Gagal menambahkan data panen"));
        }
        break;

    case 'GET':
        // Ambil data dari database
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $query = "SELECT * FROM panen";
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
        $id_lahan = $_PUT['id_lahan'];
        $id_loading = $_PUT['id_loading'];
        $tanggal_panen = $_PUT['tanggal_panen'];
        $jumlah_panen = $_PUT['jumlah_panen'];
        $harga_panen = $_PUT['harga_panen'];
        $deskripsi_panen = $_PUT['deskripsi_panen'];

        // Update data di database
        $updateQuery = "UPDATE panen SET id_lahan = '$id_lahan', id_loading = '$id_loading', tanggal_panen = '$tanggal_panen', 
                        jumlah_panen = '$jumlah_panen', harga_panen = '$harga_panen', deskripsi_panen = '$deskripsi_panen' WHERE id = '$id'";
        if ($connect->query($updateQuery) === TRUE) {
            echo json_encode(array("status" => "success", "message" => "Data panen berhasil diperbarui"));
        } else {
            echo json_encode(array("status" => "error", "message" => "Gagal memperbarui data panen"));
        }
        break;

    case 'DELETE':
        // Ambil informasi dari permintaan DELETE
        parse_str(file_get_contents("php://input"), $_DELETE);
        $id = $_DELETE['id'];

        // Hapus data dari database
        $deleteQuery = "DELETE FROM panen WHERE id = '$id'";
        if ($connect->query($deleteQuery) === TRUE) {
            echo json_encode(array("status" => "success", "message" => "Data panen berhasil dihapus"));
        } else {
            echo json_encode(array("status" => "error", "message" => "Gagal menghapus data panen"));
        }
        break;

    default:
        echo json_encode(array("status" => "error", "message" => "Metode tidak dikenali"));
        break;
}

// Tutup koneksi
$connect->close();
