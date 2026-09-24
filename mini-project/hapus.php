<?php
// FUNCTION HAPUS

session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

include_once "koneksi_database.php";

// Ambil ID dari URL
$id = $_GET['id'] ?? '';

if (!empty($id)) {
    // Fetch existing data for log
    $existing = $database->getReference('Produk/' . $id)->getValue();
    
    if ($existing) {
        // Insert Manual Delete Log
        $database->getReference('Log_User')->push([
            'waktu' => date('Y-m-d H:i:s'),
            'username' => $_SESSION["username"] ?? 'User',
            'brgKode' => $id,
            'aksi' => 'DELETE',
            'brgNama_lama' => $existing['brgNama'] ?? ''
        ]);
        
        // Execute Delete
        $database->getReference('Produk/' . $id)->remove();
    }
}

header("Location: index.php");
exit();
?>