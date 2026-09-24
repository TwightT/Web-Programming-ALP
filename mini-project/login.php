<?php
// HALAMAN LOGIN

// Memulai session agar server dapat mengingat user yang sedang membuka website ]
// dan menyimpan id kecil agar saat membuka page lain yang memiliki session_start();
session_start();

if (isset($_SESSION["login"])) { // Cek status login
    header("Location: index.php"); // Alihkan halaman
    exit(); // Hentikan eksekusi
}

// koneksi ke server mysql
include_once "koneksi_database.php";

// Reset status error
$error = false;

// Cek apakah tombol login sudah dipencet
if (isset($_POST["login"])) {
    // Bersihkan username dari spasi atau blank space
    $user = trim($_POST["username"]);
    // Bersihkan password dari spasi atau blank space
    $pass = trim($_POST["password"]);

    // Siapkan query
    $query = "SELECT * FROM Users WHERE username = ?";
    // membuat prepared statement
    $stmt = mysqli_prepare($conn, $query);
    // mengikat username dengan prepared statement dan mengubahnya menjadi string
    mysqli_stmt_bind_param($stmt, "s", $user);
    // Jalankan query prepared statement
    mysqli_stmt_execute($stmt);
    // Ambil hasil
    $result = mysqli_stmt_get_result($stmt);

    // Pastikan user unik
    if (mysqli_num_rows($result) === 1) {
        // Bongkar data
        $row = mysqli_fetch_assoc($result);
        
        // Verifikasi password
        if (password_verify($pass, $row["password"])) {
            // Set flag success
            $_SESSION["login"] = true;
            // Simpan identitas
            $_SESSION["username"] = $row["username"];
            // Simpan nama
            $_SESSION["nama_lengkap"] = $row["nama_lengkap"];
            // Masuk ke halaman beranda website
            header("Location: index.php");
            // menyelesaikan dan menutup process
            exit();
        }
    }
    // set flag gagal
    $error = true;
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

        /* grup input username dan password */
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
                <!-- label username -->
                <label for="username">Username</label>
                <!-- input field username -->
                <input type="text" name="username" id="username" required autocomplete="off">
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