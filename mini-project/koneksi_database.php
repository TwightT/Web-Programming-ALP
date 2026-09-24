<?php
// KONEKSI DATABASE FIREBASE

// Memuat autoload dari Composer
require_once __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;

// Lokasi berkas JSON Service Account Firebase Anda
$serviceAccountPath = __DIR__ . '/cloud-computing-d3ab3-firebase-adminsdk-fbsvc-1dda26e4a6.json';

// URL Firebase Realtime Database Anda (sesuaikan dengan URL proyek Firebase Anda)
$databaseUri = 'https://cloud-computing-d3ab3-default-rtdb.asia-southeast1.firebasedatabase.app/';

try {
    $factory = (new Factory)
        ->withServiceAccount($serviceAccountPath)
        ->withDatabaseUri($databaseUri);

    // Inisialisasi objek Realtime Database dan Authentication
    $database = $factory->createDatabase();
    $auth = $factory->createAuth();
} catch (Exception $e) {
    die("Gagal terhubung ke Firebase: " . $e->getMessage());
}
?>