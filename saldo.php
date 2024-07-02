<?php

// Koneksi ke database (ganti dengan koneksi sesuai dengan konfigurasi Anda)
$servername = "localhost";
$username = "root";
$password = "Ricco18";
$dbname = "uji_pam";

$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil id_loading dan id_user dari request POST atau GET
$id_loading = $_POST['id_loading']; // Sesuaikan dengan metode pengiriman data dari aplikasi Anda
$id_user = $_POST['id_user']; // Sesuaikan dengan metode pengiriman data dari aplikasi Anda

// Query untuk menghitung total_panen dan pendapatan dari tabel panen
$sql = "SELECT SUM(jumlah) AS total_panen, SUM(harga) AS pendapatan
        FROM panen
        WHERE id_loading = $id_loading";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Ambil hasil perhitungan
    $row = $result->fetch_assoc();
    $total_panen = $row['total_panen'];
    $pendapatan = $row['pendapatan'];

    // Simpan hasil perhitungan ke tabel saldo
    $insert_sql = "INSERT INTO saldo (id_loading, id_user, total_panen, pendapatan)
                   VALUES ($id_loading, $id_user, $total_panen, $pendapatan)";

    if ($conn->query($insert_sql) === TRUE) {
        // Hapus data panen yang sudah dihitung
        $delete_sql = "DELETE FROM panen WHERE id_loading = $id_loading";
        if ($conn->query($delete_sql) === TRUE) {
            echo "Saldo berhasil diupdate dan data panen dihapus.";
        } else {
            echo "Error: " . $delete_sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $insert_sql . "<br>" . $conn->error;
    }
} else {
    echo "Tidak ada data panen yang ditemukan untuk id_loading = $id_loading";
}

$conn->close();
