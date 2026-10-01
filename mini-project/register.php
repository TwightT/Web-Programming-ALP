<?php
session_start();

if (isset($_SESSION["login"])) {
    header("Location: index.php");
    exit();
}

require __DIR__ . '/vendor/autoload.php';

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
use Kreait\Firebase\Auth;

$error = false;
$success = false;
$message = "";

if (isset($_POST["register"])) {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    $confirm_password = $_POST["confirm_password"] ?? '';

    if ($password !== $confirm_password) {
        $error = true;
        $message = "Konfirmasi kata sandi tidak cocok!";
    } else {
        try {
            $factory = (new Factory)->withServiceAccount(__DIR__ . '/cloud-computing-d3ab3-firebase-adminsdk-fbsvc-1dda26e4a6.json');
            $auth = $factory->createAuth();

            $user = $auth->createUserWithEmailAndPassword($email, $password);
            $success = true;
            $message = "Registrasi berhasil! Anda akan dialihkan ke halaman Login dalam 2 detik...";

            // Auto redirect ke login.php setelah 2 detik
            header("refresh:2;url=login.php?registered=1");
        } catch (Exception $e) {
            $error = true;
            $message = "Gagal mendaftar: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Inventory</title>
    <link rel="stylesheet" href="mystyle.css">
    <style>
        .register-container {
            position: absolute;
            inset: 0;
            margin: auto;
            width: 30%;
            height: fit-content;
            background-color: #d81515;
            padding: 40px 50px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .register-container h2 {
            margin-bottom: 20px;
            color: aliceblue;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: aliceblue;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .btn-register {
            width: 50%;
            padding: 10px;
            background-color: aliceblue;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        .btn-register:hover {
            background-color: #d1d8de;
        }

        .msg-box {
            color: #ffffff;
            background-color: rgba(0, 0, 0, 0.3);
            padding: 10px;
            border-radius: 5px;
            font-style: italic;
            margin-bottom: 15px;
            display: block;
        }

        .success-box {
            background-color: #28a745;
            color: #ffffff;
        }

        .login-link {
            margin-top: 15px;
            display: block;
            color: aliceblue;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container"> 
        <h2 class="dotted-lines">REGISTER</h2>

        <?php if ($error): ?>
            <span class="msg-box"><?= htmlspecialchars($message) ?></span>
        <?php endif; ?>

        <?php if ($success): ?>
            <span class="msg-box success-box"><?= htmlspecialchars($message) ?></span>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required autocomplete="off">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit" name="register" class="btn-register">Daftar</button>
        </form>
        <a href="login.php" class="login-link">Sudah punya akun? Login di sini</a>
    </div>
</body>
</html>