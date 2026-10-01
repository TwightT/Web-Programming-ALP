<?php
require_once __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;

try {
    $factory = new Factory();
    
    // Cek Environment Variable di Railway
    $firebaseEnv = getenv('FIREBASE_CREDENTIALS');

    if ($firebaseEnv) {
        $serviceAccount = json_decode($firebaseEnv, true);
        $factory = $factory->withServiceAccount($serviceAccount);
    } else {
        // Mode Lokal (Laptop)
        $jsonPath = __DIR__ . '/cloud-computing-d3ab3-firebase-adminsdk-fbsvc-1dda26e4a6.json';
        if (!file_exists($jsonPath)) {
            throw new Exception("Berkas kredensial Firebase tidak ditemukan di komputer lokal!");
        }
        $factory = $factory->withServiceAccount($jsonPath);
    }

    // Inisialisasi Auth dan Realtime Database
    $auth = $factory->createAuth();
    $database = $factory->createDatabase();

} catch (Exception $e) {
    die("Koneksi Firebase Gagal: " . $e->getMessage());
}
?>