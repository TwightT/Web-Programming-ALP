<?php
// KONEKSI DATABASE FIREBASE

// Memuat autoload dari Composer
require_once __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;

// Lokasi berkas JSON Service Account Firebase Anda
$serviceAccountPath = __DIR__ . '/firebase_credentials.json';

// URL Firebase Realtime Database Anda (sesuaikan dengan URL proyek Firebase Anda)
$databaseUri = 'https://nama-proyek-anda-default-rtdb.firebaseio.com/';

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