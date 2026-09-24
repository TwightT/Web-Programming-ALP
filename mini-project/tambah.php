<?php
// HALAMAN TAMBAH BARANG

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

// Tambahan kode untuk trigger mysql, berfungsi untuk logging perubahan yang terjadi pada database melalui website
// Cek apakah ada session username (user sudah login)
if (isset($_SESSION["username"])) {
    // Ambil nama user
    $aktif_user = mysqli_real_escape_string($conn, $_SESSION["username"]);
    // Kirim nama user ke MySQL sebagai variabel session database
    mysqli_query($conn, "SET @app_username = '$aktif_user'");
}

// mengambil data yang ada di form submit
if (isset($_POST['submit'])) {
    // mengambil data id menggunakan $id dari URL untuk kode, karena input disabled tidak mengirim POST
    $kode = $_POST['brgKode'];
    // mengambil data nama
    $nama = $_POST['brgNama'];
    // mengambil data stok
    $stok = (int)$_POST['brgStok'];
    // Penanganan jika harga kosong
    $harga = (float)$_POST['brgHarga'];
    // mengambil data isi
    $isi = $_POST['brgIsi'];
    // mengambil data keterangan
    $keterangan = $_POST['brgKeterangan'];

    // membuat default path gambar
    $path_gambar_db = ""; 

    // cek apakah user mengupload file gambar
    if (isset($_FILES['gambarBarang']) && $_FILES['gambarBarang']['error'] === UPLOAD_ERR_OK) {
        // membuat path folder tujuan
        $folder_tujuan = "gambar/"; 
        // membuat nama file gambar yang diupload user menjadi waktu_namafilegambar
        $nama_file_asli = basename($_FILES['gambarBarang']['name']);
        // meembuat path file gambar yang baru
        $nama_file_baru = time() . "_" . $nama_file_asli; 
        $path_lengkap = $folder_tujuan . $nama_file_baru;

        // Pindahkan gambar baru
        if (move_uploaded_file($_FILES['gambarBarang']['tmp_name'], $path_lengkap)) {
            // mengubah path lama dengan path baru
            $path_gambar_db = $path_lengkap; 
        }
    }

    // membuat prepared statement
    $query = "INSERT INTO Produk (brgKode, brgNama, brgStok, brgHarga, brgIsi, brgGambar, brgKeterangan) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    // mengikat prepared statement dengan data di form tambah, dan mengubah isi data sesuai dengan 
    // jenis data masing - masing
    // Tipe data parameter: s=string, i=integer, d=double/float
    // sidssss = $kode = string, $nama = String, $stok = Int, $harga = Decimal, $isi = String, $path_gambar_db = String, $keterangan = String
    mysqli_stmt_bind_param($stmt, "ssidsss", $kode, $nama, $stok, $harga, $isi, $path_gambar_db, $keterangan);
    
    // menjalankan statement sekaligus mengecek apakah proses update berhasil di database
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Barang berhasil ditambahkan!'); window.location.href='index.php';</script>";
        exit();
    // jika tidak berhasil maka tampilkan error
    } else {
        echo "<script>alert('Gagal menambah barang: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Barang</title>
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
            padding: 20px; 
        }
        .btn-tambah {
            background-color: #23d953;
            font-weight: normal;
        }
        .btn-tambah:hover {
            background-color: #1ebd48;
        }
    </style>
</head>
<body>
    <!-- header -->
    <header>
        <!-- judul -->
        <a class="dotted-lines">
        TAMBAH BARANG
        </a>
    </header>
    <!-- isi -->
    <div class="body-container">
        <!-- menunjukan tombol yang ada di file navigation.php -->
        <nav><?php include_once "navigation.php"; ?></nav>
        <!-- container form tambah -->
        <main>
            <!-- form tambah -->
            <form id="formTambah" action="" method="POST" enctype="multipart/form-data">
                <!-- label kode barang -->
                <label>Kode Barang:</label>
                <!-- input field kode barang -->
                <input type="text" name="brgKode" required>
                
                <!-- label nama barang -->
                <label>Nama Barang:</label>
                <!-- input field nama barang -->
                <input type="text" name="brgNama" required>
                
                <!-- label stok barang -->
                <label>Stok:</label>
                <!-- input field stok barang -->
                <input type="number" name="brgStok" required>
                
                <!-- label harga barang -->
                <label>Harga:</label>
                <!-- input field harga barang -->
                <input type="number" name="brgHarga" step="0.01">
                
                <!-- label isi barang -->
                <label>Isi/Satuan:</label>
                <!-- input field isi barang -->
                <input type="text" name="brgIsi">

                <!-- label keterangan tambahan barang -->
                <label>Keterangan:</label>
                <!-- input field keterangan tambahan barang -->
                <input type="text" name="brgKeterangan">                
                
                <!-- label gambar barang -->
                <label>Pilih Gambar:</label>
                <!-- input button "Choose File" gambar barang -->
                <input type="file" name="gambarBarang" id="inputGambar" accept="image/*">
                
                <!-- tombol submit -->
                <button type="submit" name="submit" class="btn btn-tambah" style="margin-top: 30px;">Simpan Barang</button>
                <!-- tombol batal -->
                <a class="btn" href="index.php" style="text-align:center; margin-top:10px;">Batal</a>
            </form>
        </main>
        <nav></nav>
    </div>
    <footer>
        &copy; Copyright 2026 - Hezekiah Austin Sunanto
    </footer>
</body>
</html>
<?php 
if (isset($stmt)) { mysqli_stmt_close($stmt); }
mysqli_close($conn); 
?>