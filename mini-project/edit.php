<?php
// HALAMAN EDIT BARANG

// Memulai session agar server dapat mengingat user yang sedang membuka website ]
// dan menyimpan id kecil agar saat membuka page lain yang memiliki session_start();
session_start();

// Jika tidak ada session login, tendang kembali ke halaman login
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

// koneksi ke server mysql
include_once "koneksi_database.php";

// Tambahan kode untuk trigger mysql, berfungsi untuk logging perubahan yang terjadi pada database melalui website
// Cek apakah ada session username (user sudah login)
if (isset($_SESSION["username"])) {
    // Ambil nama user
    $aktif_user = mysqli_real_escape_string($conn, $_SESSION["username"]);
    // Kirim nama user ke MySQL sebagai variabel session database
    mysqli_query($conn, "SET @app_username = '$aktif_user'");
}

// mengambil id dari url browser dan membersihkannya (http://localhost/mini-project/detail.php?||id=BK20|| id yang dimaksud)
// mengecek apakah ada id, jika ada bersihkan idnya, jika tidak maka kosongkan
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

// cek apakah ada id, jika kosong maka berikan error bahwa barang tidak ditemukan
if (empty($id)) {
    die("Akses ditolak: ID Barang tidak ditemukan.");
}

// membuat prepared statement untuk mengambil data dari database
$stmt_get = mysqli_prepare($conn, "SELECT * FROM Produk WHERE brgKode = ?");
// mengikat id dengan prepared statement dan mengubahnya menjadi string
mysqli_stmt_bind_param($stmt_get, "s", $id);
// menjalankan query
mysqli_stmt_execute($stmt_get);
// mendapatkan hasil
$result = mysqli_stmt_get_result($stmt_get);
// jika setelah database mencari produk dan tidak ketemu maka tunjukan error
if (mysqli_num_rows($result) === 0) {
    die("Barang tidak ditemukan.");
}
// jika ketemu barangnya maka disimpan di data
$data = mysqli_fetch_assoc($result);
// menutup query
mysqli_stmt_close($stmt_get);


// mengambil data yang ada di form update
if (isset($_POST['update'])) {
    // mengambil data id menggunakan $id dari URL untuk kode, karena input disabled tidak mengirim POST
    $kode = $id; 
    // mengambil data nama
    $nama = $_POST['brgNama'];
    // mengambil data stok 
    $stok = (int)$_POST['brgStok'];
    // Penanganan jika harga kosong
    $harga = !empty($_POST['brgHarga']) ? (float)$_POST['brgHarga'] : 0; 
    // mengambil data isi
    $isi = $_POST['brgIsi'];
    // mengambil data keterangan
    $keterangan = $_POST['brgKeterangan'];
    // mengambil data gambar
    $gambar_lama = $_POST['gambarLama']; 

    // gunakan gambar lama jika tidak ada upload baru
    $path_gambar_db = $gambar_lama; 

    // cek apakah user mengupload file gambar BARU
    if (isset($_FILES['gambarBarang']) && $_FILES['gambarBarang']['error'] === UPLOAD_ERR_OK) {
        // membuat path folder tujuan
        $folder_tujuan = "gambar/";
        // membuat nama file gambar yang diupload user menjadi waktu_namafilegambar
        $nama_file_baru = time() . "_" . basename($_FILES['gambarBarang']['name']);
        // meembuat path file gambar yang baru
        $path_lengkap = $folder_tujuan . $nama_file_baru;

        // Pindahkan gambar baru
        if (move_uploaded_file($_FILES['gambarBarang']['tmp_name'], $path_lengkap)) {
            // mengubah path lama dengan path baru
            $path_gambar_db = $path_lengkap;
            
            // Hapus gambar lama dari folder
            if (file_exists($gambar_lama) && $gambar_lama != "") {
                unlink($gambar_lama); 
            }
        }
    }

    // membuat prepared statement
    $query = "UPDATE Produk SET brgNama=?, brgStok=?, brgHarga=?, brgIsi=?, brgGambar=?, brgKeterangan=? WHERE brgKode=?";
    $stmt = mysqli_prepare($conn, $query);
    
    // mengikat prepared statement dengan data di form update, dan mengubah isi data sesuai dengan 
    // jenis data masing - masing
    // Tipe data parameter: s=string, i=integer, d=double/float
    // sidssss = $nama = String, $stok = Int, $harga = Decimal, $isi = String, $path_gambar_db = String, $keterangan = String, $kode = String
    mysqli_stmt_bind_param($stmt, "sidssss", $nama, $stok, $harga, $isi, $path_gambar_db, $keterangan, $kode);
    
    // menjalankan statement sekaligus mengecek apakah proses update berhasil di database
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='index.php';</script>";
        exit();
    // jika tidak berhasil maka tampilkan error
    } else {
        echo "<script>alert('Gagal mengupdate data: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Barang</title>
    <link rel="stylesheet" href="mystyle.css">
    <style>
        form { 
            display: flex; 
            flex-direction: column; 
            gap: 10px; 
            max-width: 400px; 
            margin: 20px auto; 
        }

        input { 
            padding: 8px; 
        }

        nav {
            width: 20%;
            margin-left: 0;
            padding : 20px; 
        }

        .btn-update {
            background-color: #23d953;
            font-weight: normal;
        }

        .btn-update:hover {
            background-color: #1ebd48;
        }
    </style>
</head>
<body>
    <!-- header -->
    <header>
        <!-- judul -->
        <a class="dotted-lines">
        EDIT BARANG
        </a>
    </header>
    <!-- isi -->
    <div class="body-container">
        <!-- menunjukan tombol yang ada di file navigation.php -->
        <nav><?php include_once "navigation.php"; ?></nav>
        <!-- container form edit -->
        <main>
            <!-- form edit -->
            <form id="formEdit" action="" method="POST" enctype="multipart/form-data">
                <!-- label kode barang -->
                <label>Kode Barang (Tidak bisa diubah):</label>
                <!-- input field kode barang yang didisabled -->
                <input type="text" value="<?php echo htmlspecialchars($data['brgKode']); ?>" disabled>
                
                <!-- label nama barang -->
                <label for="brgNama">Nama Barang:</label>
                <!-- input field nama barang -->
                <input type="text" name="brgNama" value="<?php echo htmlspecialchars($data['brgNama']); ?>" required>
                
                <!-- label stock barang -->
                <label for="brgStok">Stok:</label>
                <!-- input field stock barang -->
                <input type="number" name="brgStok" value="<?php echo htmlspecialchars($data['brgStok']); ?>" required>
                
                <!-- label harga barang -->
                <label for="brgHarga">Harga:</label>
                <!-- input field harga barang -->
                <input type="number" name="brgHarga" step="0.01" value="<?php echo htmlspecialchars($data['brgHarga']); ?>">
                
                <!-- label isi barang -->
                <label for="brgIsi">Isi/Satuan:</label>
                <!-- input field isi barang -->
                <input type="text" name="brgIsi" value="<?php echo htmlspecialchars($data['brgIsi']); ?>">
                
                <!-- input field gambar lama yang disembunyikan -->
                <input type="hidden" name="gambarLama" value="<?php echo htmlspecialchars($data['brgGambar']); ?>">
                <label>Ganti Gambar (Biarkan kosong jika tidak ingin diganti):</label>
                <!-- gambar lama -->
                <?php if(!empty($data['brgGambar'])): ?>
                    <img src="<?php echo htmlspecialchars($data['brgGambar']); ?>" style="max-width: 150px; border-radius: 5px; margin-bottom: 10px;">
                <?php endif; ?>
                <!-- input button (Choose File) gambar barang -->
                <input type="file" name="gambarBarang" id="inputGambar" accept="image/*">
                
                <!-- label keterangan barang -->
                <label for="brgKeterangan">Keterangan:</label>
                <!-- input field keterangan barang -->
                <input type="text" name="brgKeterangan" value="<?php echo htmlspecialchars($data['brgKeterangan']); ?>">
                
                <!-- tombol submit -->
                <button type="submit" name="update" class="btn btn-update" style=" margin-top: 30px;">Update Data</button>
                <!-- tombol batal -->
                <a class="btn" href="index.php" style="text-align:center; margin-top:10px;">Batal</a>
            </form>
        </main>
        <nav></nav>
    </div>
    <!-- footer -->
    <footer>
        &copy; Copyright 2026 - Hezekiah Austin Sunanto
    </footer>
</body>
</html>
<?php 
if (isset($stmt)) { mysqli_stmt_close($stmt); }
mysqli_close($conn); 
?>