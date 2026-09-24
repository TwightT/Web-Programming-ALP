<?php
include_once "koneksi_database.php";

$email = "admin@inventory.com"; // Gunakan email yang valid
$password_plain = "admin123";
$nama = "Administrator";

try {
    $userProperties = [
        'email' => $email,
        'emailVerified' => false,
        'password' => $password_plain,
        'displayName' => $nama,
    ];

    $createdUser = $auth->createUser($userProperties);
    
    echo "User admin berhasil dibuat! Email: $email | Password: $password_plain <br>";
    echo "<b>PENTING: Segera hapus file ini!</b>";
} catch (Exception $e) {
    echo "Gagal: " . $e->getMessage();
}
?>