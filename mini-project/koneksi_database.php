<!-- KONEKSI KE DATABASE FIREBASE -->

<?php
require __DIR__.'/vendor/autoload.php';
use Kreait\Firebase\Factory;

$factory = new Factory();
$firebaseEnv = getenv('FIREBASE_CREDENTIALS');

if ($firebaseEnv) {
    // Membaca konfigurasi JSON langsung dari Environment Variable Railway
    $serviceAccount = json_decode($firebaseEnv, true);
    $factory = $factory->withServiceAccount($serviceAccount);
} else {
    // Membaca berkas JSON fisik saat dijalankan di komputer lokal
    $jsonPath = __DIR__ . '/cloud-computing-d3ab3-firebase-adminsdk-fbsvc-1dda26e4a6.json';
    $factory = $factory->withServiceAccount($jsonPath);
}

$auth = $factory->createAuth();
?>