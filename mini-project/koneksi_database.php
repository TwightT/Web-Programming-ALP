<!-- KONEKSI KE DATABASE FIREBASE -->

<?php
require __DIR__.'/vendor/autoload.php';
use Kreait\Firebase\Factory;

$factory = (new Factory)
    ->withServiceAccount(__DIR__ . '/cloud-computing-d3ab3-firebase-adminsdk-fbsvc-1dda26e4a6.json')
    ->withDatabaseUri('https://cloud-computing-d3ab3-default-rtdb.asia-southeast1.firebasedatabase.app/');

$database = $factory->createDatabase();
$auth = $factory->createAuth();
?>