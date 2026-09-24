<?php
// HALAMAN UTAMA

// Memulai session agar server dapat mengingat user yang sedang membuka website ]
// dan menyimpan id kecil agar saat membuka page lain yang memiliki session_start();
session_start();

// jika belum login balik ke halaman login
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

// koneksi ke server mysql
include_once "koneksi_database.php";

if (isset($_SESSION["username"])) {
    // Ambil nama user
    $aktif_user = mysqli_real_escape_string($conn, $_SESSION["username"]);
    
    // Kirim nama user ke MySQL sebagai variabel session database
    mysqli_query($conn, "SET @app_username = '$aktif_user'");
}

// mengambil isi search bar
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
// mengecek apakah search bar kosong
if ($search !== '') {
    // membuat prepared statement
    $query = "SELECT brgKode, brgNama, brgStok, brgGambar, brgHarga FROM Produk WHERE brgNama LIKE ?";
    $stmt = mysqli_prepare($conn, $query);
    
    // menentukan awal dan akhir dari isi search bar agar tidak terkena sql injection
    $searchTerm = "%" . $search . "%";
    // mengikat prepared statement dengan isi search bar yang sudah diamankan dan mengubahnya menjadi string agar tidak terbaca sebagai kode
    mysqli_stmt_bind_param($stmt, "s", $searchTerm); 
    // menjalankan statement
    mysqli_stmt_execute($stmt);
    // mendapatkan hasil
    $result = mysqli_stmt_get_result($stmt);
} else {
    // menunjukan semua barang tanpa terkecuali
    $query = "SELECT brgKode, brgNama, brgStok, brgHarga, brgGambar FROM Produk";
    $result = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link rel="stylesheet" href="mystyle.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        main {
            margin-left: auto;
            margin-right: auto;
        }
        .grey {
            background-color: #a3a3a3;
        }
    </style>
</head>

<body>
    <!-- header -->
    <header>
        <!-- judul -->
        <a class="dotted-lines">
        INVENTORY
        </a>
    </header>
    <!-- isi -->
    <main>
        <!-- search bar -->
        <div class="search-container">
            <!-- search form -->
            <form action="index.php" method="GET">
                <!-- input field search bar -->
                <input class="input" type="text" role="textbox" name="search" id="search" placeholder="Isi nama barang disini..." value="<?php echo htmlspecialchars($search); ?>"></input>
                <!-- tombol search -->
                <button type="submit" style="padding: 8px 10px;" class="btn"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F0F8FF"><path d="M375.88-310.14q-116.1 0-195.86-79.83-79.76-79.82-79.76-194.51 0-114.68 79.83-194.46t194.84-79.78q114.35 0 194.13 79.82 79.78 79.81 79.78 194.57 0 44.71-13.01 84.66-13 39.95-38.87 74.06L828.32-195.5q12.97 12.91 12.97 32.05 0 19.14-13.05 31.7-13.32 13.13-32.08 13.13-18.75 0-31.67-13.13l-229.5-229.7q-30.54 24.2-72.18 37.76-41.63 13.55-86.93 13.55Zm-.76-89.26q77.63 0 131.05-53.73 53.41-53.73 53.41-131.2t-53.41-131.3q-53.42-53.83-131.05-53.83-78.7 0-132.15 53.83t-53.45 131.3q0 77.47 53.45 131.2 53.45 53.73 132.15 53.73Z"/></svg></button>
                <!-- tombol reset yang muncul saat search bar ada isinya -->
                <?php if ($search !== ''): ?>
                    <a href="index.php" class="btn" style="padding: 12px 15px;" name="reset">Reset</a>
                <?php endif; ?>
            </form>
                <!-- menunjukan tombol yang ada di file navigation.php -->
                <nav><?php include_once "navigation.php"; ?></nav>
        </div>
        
        <!-- seperti navbar tetapi digunakan hanya untuk tombol tambah barang -->
        <div class="top-actions">
            <!-- tombol tambah barang yang mengarah ke halaman tambah.php -->
            <a href="tambah.php" class="btn" style="display: inline-flex; align-items: center; padding: 5px 10px; object-position: left;">
                <!-- logo tambah yang diambil dari google icons -->
                <svg style="margin-bottom: 2px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#F0F8FF">
                    <path d="M423.41-424.17H221.24q-23.67 0-40.13-16.58t-16.46-40.13q0-23.55 16.46-40.01 16.46-16.46 40.13-16.46h202.17v-202.41q0-23.44 16.58-40.01 16.58-16.58 40.01-16.58t40.01 16.58q16.58 16.57 16.58 40.01v202.41h202.17q23.67 0 40.13 16.46t16.46 40.01q0 23.55-16.46 40.13-16.46 16.58-40.13 16.58H536.59V-222q0 23.43-16.58 40.01T480-165.41q-23.43 0-40.01-16.58T423.41-222v-202.17Z"/>
                </svg> 
                <p>Tambah Barang</p>
            </a>
        </div>
        
        <!-- menujukan list produk -->
        <div class="product-list">
                <?php
                // jika jumlah barang diatas 0 (kecuali saat di search)
                if ($result && mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) { // while loop untuk mendapatkan semua barang (kecuali saat di search)
                        ?>
                        <!-- membuat satu kolom produk -->
                        <!-- saat diklik sekali akan berubah menjadi abu abu -->
                        <!-- saat diklik dua kali akan membuka detail barang -->
                        <div class="product-item"  
                            onclick="selectProduct(this)" 
                            ondblclick="window.location.href='detail.php?id=<?php echo urlencode($row['brgKode']); ?>'">
                            <!-- menunjukan gambar produk -->
                            <div class="product-image">
                                <?php if (!empty($row['brgGambar'])): ?>
                                    <img src="<?php echo htmlspecialchars($row['brgGambar']); ?>" alt="Gambar">
                                <?php else: ?>
                                    <!-- jika tidak ada gambar maka menunjukan tulisan GAMBAR -->
                                    GAMBAR
                                <?php endif; ?>
                            </div>
                            <!-- menunjukan info produk seperti stock dan harga -->
                            <div class="product-info">
                                <h2><?php echo htmlspecialchars($row['brgNama']); ?></h2>
                                <p>Stock : : <?php echo htmlspecialchars($row['brgStok']); ?></p>
                                <p>Harga: <?php echo "Rp " . number_format($row['brgHarga'], 0, ',', '.'); ?></p>
                            </div>
                            <!-- menunjukan tombol action seperti edit dan hapus -->
                            <div class="product-actions">
                                <!-- tombol edit -->
                                <a href="edit.php?id=<?php echo urlencode($row['brgKode']); ?>" class="btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F0F8FF">
                                        <path d="M141.72-96.75q-19.36 0-32.16-12.81-12.81-12.8-12.81-32.16v-91.39q0-17.41 7.04-34.28 7.03-16.86 19.48-29.37l547.87-547.87q9.24-8.82 21.28-13.46 12.04-4.65 25.52-4.65 12.22 0 23.67 4.27 11.45 4.26 21.91 12.91l82.28 81.8q9.55 10.38 13.62 22.3 4.07 11.91 4.07 24.27 0 12.97-4.57 25.14t-13.46 20.98l-548.22 547.8q-12.67 12.45-29.45 19.48-16.79 7.04-34.2 7.04h-91.87Zm577.93-575.24 45.67-45.85-46.57-46.48-46.34 45.33 47.24 47Z"/>
                                    </svg>
                                </a>
                                <!-- tombol hapus -->
                                <a href="hapus.php?id=<?php echo urlencode($row['brgKode']); ?>" class="btn" onclick="return confirm('Yakin ingin menghapus barang ini?');">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F0F8FF">
                                        <path d="M273.85-84.65q-47.87 0-80.52-32.65-32.66-32.66-32.66-80.53v-523.06q-23.43 0-39.89-16.46-16.45-16.45-16.45-40.01 0-23.55 16.45-40.13 16.46-16.58 39.89-16.58h192.07q0-21.67 15.19-36.98 15.2-15.32 37.11-15.32H556q21.91 0 37.23 15.32 15.31 15.31 15.31 36.98h192.31q23.67 0 40.13 16.58t16.46 40.13q0 23.56-16.46 40.01-16.46 16.46-40.13 16.46v523.06q0 47.87-32.65 80.53-32.66 32.65-80.53 32.65H273.85Zm206.91-299.92 67.57 67.33q15.24 15.48 37.59 15.36 22.36-.12 37.84-15.6Q639-332.72 639-355.08q0-22.35-15.24-37.83L556.2-460l67.56-67.33Q639-542.8 639-565.16t-15.36-37.72q-15.36-15.36-37.72-15.36-22.35 0-37.59 15.48l-67.57 67.33-67.33-67.33q-15.23-15.48-37.47-15.48t-37.6 15.36Q323-587.52 323-565.16t15.24 37.83L405.33-460 338-392.67q-15.24 15.47-15.12 37.71.12 22.24 15.48 37.6Q353.72-302 376.08-302q22.35 0 37.59-15.48l67.09-67.09Z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    // jika barang atau data barang tidak ditemukan
                    echo "<p>Data tidak ditemukan untuk pencarian: <strong>" . htmlspecialchars($search) . "</strong></p>";
                }
                ?>
            </div>
    </main>
    <!-- footer -->
    <footer>
        &copy; Copyright 2026 - Hezekiah Austin Sunanto
    </footer>
<!-- untuk mengubah kolom produk menjadi grey saat diklik sekali -->
<script>
        function selectProduct(clickedElement) {
            // 1. Cek apakah elemen yang diklik sudah berwarna abu-abu
            const isAlreadyGrey = clickedElement.classList.contains('grey');
            // 2. Cari semua elemen dengan class 'product-item' dan hapus warna abu-abunya
            const allProducts = document.querySelectorAll('.product-item');
            allProducts.forEach(function(item) {
                item.classList.remove('grey');
            });
            // 3. Jika elemen yang diklik belum abu-abu sebelumnya, maka tambahkan warna abu-abu
            // (Jika sudah abu-abu, dia akan tetap bersih karena sudah dihapus di langkah 2)
            if (!isAlreadyGrey) {
                clickedElement.classList.add('grey');
            }
        }
</script>
</body>
</html>
<!-- menutup koneksi website dengan database jika tidak digunakan -->
<?php 
if (isset($stmt)) { mysqli_stmt_close($stmt); }
mysqli_close($conn); 
?>