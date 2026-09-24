<?php
// GUNAKAN HANYA SEKALI UNTUK MENAMBAH USER AGAR DAPAT LOGIN KE WEBSITE
// koneksi ke server mysql
include_once "koneksi_database.php";

$username = "admin";
$password_plain = "admin123";
$password_hash = password_hash($password_plain, PASSWORD_DEFAULT);
$nama = "Administrator";

$query = "INSERT INTO Users (username, password, nama_lengkap) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "sss", $username, $password_hash, $nama);

if (mysqli_stmt_execute($stmt)) {
    echo "User admin berhasil dibuat! Username: admin | Password: admin123 <br>";
    echo "<b>PENTING: Segera hapus file ini!</b>";
} else {
    echo "Gagal: " . mysqli_error($conn);
}
?>