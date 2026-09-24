<?php
// HALAMAN TAMBAH BARANG

session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

include_once "koneksi_database.php";

// Memproses formulir hanya jika tombol submit ditekan
if (isset($_POST['submit'])) {
    $kode = $_POST['brgKode'] ?? '';
    $nama = $_POST['brgNama'] ?? '';
    $stok = (int)($_POST['brgStok'] ?? 0);
    $harga = !empty($_POST['brgHarga']) ? (float)$_POST['brgHarga'] : 0;
    $isi = $_POST['brgIsi'] ?? '';
    $keterangan = $_POST['brgKeterangan'] ?? '';

    $path_gambar_db = '';

    // Penanganan Upload Gambar
    if (isset($_FILES['gambarBarang']) && $_FILES['gambarBarang']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['gambarBarang']['tmp_name'];
        $fileName = $_FILES['gambarBarang']['name'];
        $newFileName = time() . '_' . $fileName;
        $uploadFileDir = './uploads/';

        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true);
        }

        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $path_gambar_db = $dest_path;
        }
    }

    // Eksekusi ke Firebase
    try {
        $database->getReference('Produk/' . $kode)->set([
            'brgNama' => $nama,
            'brgStok' => $stok,
            'brgHarga' => $harga,
            'brgIsi' => $isi,
            'brgGambar' => $path_gambar_db,
            'brgKeterangan' => $keterangan,
            'brgTanggal' => date('Y-m-d H:i:s')
        ]);

        // Catat Log Insert di Firebase
        $database->getReference('Log_User')->push([
            'waktu' => date('Y-m-d H:i:s'),
            'username' => $_SESSION["username"] ?? 'User',
            'brgKode' => $kode,
            'aksi' => 'INSERT',
            'brgNama_baru' => $nama,
            'brgStok_baru' => $stok,
        ]);

        echo "<script>alert('Barang berhasil ditambahkan!'); window.location.href='index.php';</script>";
        exit();
    } catch (Exception $e) {
        echo "<script>alert('Gagal menambah barang: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
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
    <header>
        <a class="dotted-lines">TAMBAH BARANG</a>
    </header>
    <div class="body-container">
        <nav><?php include_once "navigation.php"; ?></nav>
        <main>
            <form id="formTambah" action="" method="POST" enctype="multipart/form-data">
                <label>Kode Barang:</label>
                <input type="text" name="brgKode" required>
                
                <label>Nama Barang:</label>
                <input type="text" name="brgNama" required>
                
                <label>Stok:</label>
                <input type="number" name="brgStok" required>
                
                <label>Harga:</label>
                <input type="number" name="brgHarga" step="0.01">
                
                <label>Isi/Satuan:</label>
                <input type="text" name="brgIsi">

                <label>Keterangan:</label>
                <input type="text" name="brgKeterangan">                
                
                <label>Pilih Gambar:</label>
                <input type="file" name="gambarBarang" id="inputGambar" accept="image/*">
                
                <button type="submit" name="submit" class="btn btn-tambah" style="margin-top: 30px;">Simpan Barang</button>
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