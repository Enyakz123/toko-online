<?php
$host = "localhost"; // Sesuaikan dengan konfigurasi Laragon
$user = "root"; // Default user Laragon
$pass = ""; // Password kosong untuk Laragon
$dbname = "mapianho_toko-online"; // Ganti dengan nama database kamu

$conn = new mysqli($host, $user, $pass, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
