<?php
$servername = "sql212.infinityfree.com";
$username = "if0_36703854";
$password = "LFtJZwAHqG2";
$dbname = "if0_36703854_artikel";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
