<?php
session_start();

if (isset($_SESSION["login"])) {
    header("Location: index.php");
    exit();
}

require __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

$error = false;
$error_msg = "";
$success_msg = "";

// Cek status registrasi dari URL
if (isset($_GET['registered']) && $_GET['registered'] == 1) {
    $success_msg = "Registrasi berhasil! Silakan login dengan akun baru Anda.";
}

if (isset($_POST["login"])) {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    try {
        $factory = (new Factory)->withServiceAccount(__DIR__ . '/cloud-computing-d3ab3-firebase-adminsdk-fbsvc-1dda26e4a6.json');
        $auth = $factory->createAuth();

        $signInResult = $auth->signInWithEmailAndPassword($email, $password);
        
        $_SESSION["login"] = true;
        $_SESSION["user_id"] = $signInResult->firebaseUserId();
        $_SESSION["email"] = $email;

        header("Location: index.php");
        exit();

    } catch (Exception $e) {
        $error = true;
        $error_msg = "Login gagal: Email atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventory</title>
    <link rel="stylesheet" href="mystyle.css">
    <style>
        .login-container {
            position: absolute;
            inset: 0;
            margin: auto;
            width: 30%;
            height: fit-content;
            background-color: #d81515;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: aliceblue;
        }

<<<<<<< Updated upstream
        /* grup input email dan password */
=======
>>>>>>> Stashed changes
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

        .btn-login {
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

        .btn-login:hover {
            background-color: #d1d8de;
        }

        .msg-box {
            color: #ffffff;
            background-color: rgba(0, 0, 0, 0.3);
            padding: 8px;
            border-radius: 5px;
            font-style: italic;
            margin-bottom: 15px;
            display: block;
        }

        .success-msg {
            background-color: #28a745;
            color: #ffffff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            display: block;
        }

        .register-link {
            margin-top: 15px;
            display: block;
            color: aliceblue;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container"> 
        <!-- judul -->
        <h2 class="dotted-lines">
        INVENTORY
        </h2>
        <!-- munculkan pesan jika error="true" -->
        <?php if ($error): ?>
            <span class="error-msg">Username atau Password salah!</span>
        <?php endif; ?>
        <h2 class="dotted-lines">INVENTORY</h2>

        <?php if (!empty($success_msg)): ?>
            <span class="success-msg"><?= htmlspecialchars($success_msg) ?></span>
        <?php endif; ?>

        <?php if ($error): ?>
            <span class="msg-box"><?= htmlspecialchars($error_msg) ?></span>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="input-group">

                <!-- label email -->
                <label for="email">Email</label>
                <!-- input field email -->

                <label for="email">Email</label>

                <input type="email" name="email" id="email" required autocomplete="off">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" name="login" class="btn-login">Login</button>
        </form>

        <!-- Tautan ke halaman registrasi -->
        <a href="register.php" class="register-link">Belum punya akun? Register di sini</a>
    </div>
</body>
</html>