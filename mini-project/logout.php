<?php
// FUNCTION LOGOUT

// Memulai session agar server dapat mengingat user yang sedang membuka website ]
// dan menyimpan id kecil agar saat membuka page lain yang memiliki session_start();
session_start();
require_once __DIR__ . '/koneksi_database.php';

// Hapus semua session
$_SESSION = [];
session_unset();
session_destroy();

// Arahkan kembali ke halaman login
header("Location: login.php");
exit();
?>