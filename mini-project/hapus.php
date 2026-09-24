<?php
// FUNCTION HAPUS

// Memulai session agar server dapat mengingat user yang sedang membuka website ]
// dan menyimpan id kecil agar saat membuka page lain yang memiliki session_start();
session_start();

// jika belum login balik ke halaman login
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

// koneksi ke server mysql
include_once "koneksi_database.php";

// Tambahan kode untuk trigger mysql, berfungsi untuk logging perubahan yang terjadi pada database melalui website
// Cek apakah ada session username (user sudah login)
if (isset($_SESSION["username"])) {
    // Ambil nama user
    $aktif_user = mysqli_real_escape_string($conn, $_SESSION["username"]);
    // Kirim nama user ke MySQL sebagai variabel session database
    mysqli_query($conn, "SET @app_username = '$aktif_user'");
}

// mengambil id dari url browser dan membersihkannya (http://localhost/mini-project/detail.php?||id=BK20|| id yang dimaksud)
// mengecek apakah ada id, jika ada bersihkan idnya, jika tidak maka kosongkan
$id = isset($_GET['id']) ? trim($_GET['id']) : '';
// cek apakah ada id (menjaga agar tidak menghapus semua produk jika id kosong)
if (!empty($id)) {
    // membuat prepared statement
    $query = "DELETE FROM Produk WHERE brgKode = ?";
    $stmt = mysqli_prepare($conn, $query);
    // cek apakah prepared statement yang dibuat, diterima oleh database atau tidak
    // jika iya maka lanjutkan, jika tidak maka lewati 
    if ($stmt) {
        // mengikat id dengan prepared statement dan mengubahnya menjadi string
        mysqli_stmt_bind_param($stmt, "s", $id);
        // menjalankan query
        mysqli_stmt_execute($stmt);
        // menutup query
        mysqli_stmt_close($stmt);
    }
}
// menutup koneksi setelah selesai menghapus
mysqli_close($conn);
// jika eror balik ke halaman beranda website
header("Location: index.php");
exit;
?>
<?php 
if (isset($stmt)) { mysqli_stmt_close($stmt); }
mysqli_close($conn); 
?>