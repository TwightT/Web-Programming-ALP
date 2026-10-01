<?php
require_once __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = new Factory();
$firebaseEnv = getenv('FIREBASE_CREDENTIALS');

if ($firebaseEnv) {
    // Lingkungan Cloud
    $serviceAccount = json_decode($firebaseEnv, true);
    $factory = $factory->withServiceAccount($serviceAccount);
} else {
    // Lingkungan Lokal
    $jsonPath = __DIR__ . '/cloud-computing-d3ab3-firebase-adminsdk-fbsvc-1dda26e4a6.json';
    $factory = $factory->withServiceAccount($jsonPath);
}

// Inisialisasi Auth dan Database
$auth = $factory->createAuth();
$database = $factory->createDatabase();
?>