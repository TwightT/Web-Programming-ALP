<?php
session_start();

if (isset($_SESSION["login"])) { 
    header("Location: index.php"); 
    exit(); 
}

include_once "koneksi_database.php";
$error = false;

if (isset($_POST["login"])) {
    $email = trim($_POST["email"]); // Ubah penamaan POST menjadi email
    $pass = trim($_POST["password"]);

    try {
        // Melakukan otentikasi ke Firebase
        $signInResult = $auth->signInWithEmailAndPassword($email, $pass);
        
        // Mengambil data identitas pengguna (seperti nama)
        $userData = $auth->getUser($signInResult->firebaseUserId());

        // Set session login
        $_SESSION["login"] = true;
        $_SESSION["username"] = $userData->displayName ?? 'User';
        $_SESSION["email"] = $userData->email;

        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        // Otentikasi gagal (email/password salah atau pengguna tidak ditemukan)
        $error = true;
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
        /* container login form */
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

        /* Judul form */
        .login-container h2 {
            margin-bottom: 20px;
            color: aliceblue;
        }

        /* grup input email dan password */
        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        /* Teks label username dan password */
        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: aliceblue;
        }

        /* input field username dan password */
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        /* tombol login */
        .btn-login {
            width: 50%;
            padding: 10px;
            background-color: aliceblue;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 100px;
        }

        /* tombol login saat cursor mouse menunjuknya */
        .btn-login:hover {
            background-color: #d1d8de;
        }

        /* Tampilan error */
        .error-msg {
            color: black;
            font-style: italic;
            margin-bottom: 15px;
            display: block;
        }
    </style>
</head>
<body>
    <!-- container form login -->
    <div class="login-container"> 
        <!-- judul -->
        <h2 class="dotted-lines">
        INVENTORY
        </h2>
        <!-- munculkan pesan jika error="true" -->
        <?php if ($error): ?>
            <span class="error-msg">Username atau Password salah!</span>
        <?php endif; ?>
        <!-- form input -->
        <form action="" method="POST">
            <div class="input-group">
                <!-- label email -->
                <label for="email">Email</label>
                <!-- input field email -->
                <input type="email" name="email" id="email" required autocomplete="off">
            </div>
            <div class="input-group">
                <!-- label password -->
                <label for="password">Password</label>
                <!-- input field password -->
                <input type="password" name="password" id="password" required>
            </div>
            <!-- tombol login -->
            <button type="submit" name="login" class="btn-login">Login</button>
        </form>
    </div>
</body>
</html>