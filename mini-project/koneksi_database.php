<!-- KONEKSI KE DATABASE MYSQL -->

<?php
$host     = "localhost";
$username = "root";
$password = "";
$database = "PW";
$conn = mysqli_connect($host, $username, $password, $database);

// JIKA KONEKSI GAGAL TAMPILKAN EROR
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>