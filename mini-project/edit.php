<?php
// HALAMAN EDIT BARANG

session_start();

// Jika tidak ada session login, tendang kembali ke halaman login
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

include_once "koneksi_database.php";

// 1. Ambil ID dari URL (query string)
$id = $_GET['id'] ?? '';

if (empty($id)) {
    die("ID barang tidak ditemukan.");
}

// Ambil data lama sebelum diupdate
$data_lama = $database->getReference('Produk/' . $id)->getValue();

if (!$data_lama) {
    die("Barang tidak ditemukan.");
}

if (isset($_POST['update'])) {
    $kode = $id; 
    $nama = $_POST['brgNama'] ?? '';
    $stok = (int)($_POST['brgStok'] ?? 0);
    $harga = !empty($_POST['brgHarga']) ? (float)$_POST['brgHarga'] : 0; 
    $isi = $_POST['brgIsi'] ?? '';
    $keterangan = $_POST['brgKeterangan'] ?? '';
    $gambar_lama = $_POST['gambarLama'] ?? ''; 

    // Default path gambar memakai gambar lama
    $path_gambar_db = $gambar_lama; 

    // Penanganan upload gambar baru jika ada
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

    // 2. Update Produk di Firebase
    $database->getReference('Produk/' . $kode)->update([
        'brgNama' => $nama,
        'brgStok' => $stok,
        'brgHarga' => $harga,
        'brgIsi' => $isi,
        'brgGambar' => $path_gambar_db,
        'brgKeterangan' => $keterangan,
        'brgTanggal' => date('Y-m-d H:i:s')
    ]);

    // 3. Masukkan Log Perubahan secara Manual
    $database->getReference('Log_User')->push([
        'waktu' => date('Y-m-d H:i:s'),
        'username' => $_SESSION["username"] ?? 'System',
        'brgKode' => $kode,
        'aksi' => 'UPDATE',
        'brgNama_lama' => $data_lama['brgNama'] ?? '',
        'brgNama_baru' => $nama,
        'brgStok_lama' => $data_lama['brgStok'] ?? 0,
        'brgStok_baru' => $stok,
        'brgHarga_lama' => $data_lama['brgHarga'] ?? 0,
        'brgHarga_baru' => $harga,
        'brgIsi_lama' => $data_lama['brgIsi'] ?? '',
        'brgIsi_baru' => $isi,
        'brgKeterangan_lama' => $data_lama['brgKeterangan'] ?? '',
        'brgKeterangan_baru' => $keterangan,
        'brgGambar_lama' => $data_lama['brgGambar'] ?? '',
        'brgGambar_baru' => $path_gambar_db
    ]);

    echo "<script>alert('Data berhasil diperbarui!'); window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
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
        <a class="dotted-lines">
        EDIT BARANG
        </a>
    </header>

    <!-- isi -->
    <div class="body-container">
        <nav><?php include_once "navigation.php"; ?></nav>
        <main>
            <form id="formEdit" action="" method="POST" enctype="multipart/form-data">
                <label>Kode Barang (Tidak bisa diubah):</label>
                <input type="text" value="<?php echo htmlspecialchars($id); ?>" disabled>
                
                <label for="brgNama">Nama Barang:</label>
                <input type="text" name="brgNama" value="<?php echo htmlspecialchars($data_lama['brgNama'] ?? ''); ?>" required>
                
                <label for="brgStok">Stok:</label>
                <input type="number" name="brgStok" value="<?php echo htmlspecialchars($data_lama['brgStok'] ?? 0); ?>" required>
                
                <label for="brgHarga">Harga:</label>
                <input type="number" name="brgHarga" step="0.01" value="<?php echo htmlspecialchars($data_lama['brgHarga'] ?? 0); ?>">
                
                <label for="brgIsi">Isi/Satuan:</label>
                <input type="text" name="brgIsi" value="<?php echo htmlspecialchars($data_lama['brgIsi'] ?? ''); ?>">
                
                <input type="hidden" name="gambarLama" value="<?php echo htmlspecialchars($data_lama['brgGambar'] ?? ''); ?>">
                
                <label>Ganti Gambar (Biarkan kosong jika tidak ingin diganti):</label>
                <?php if(!empty($data_lama['brgGambar'])): ?>
                    <img src="<?php echo htmlspecialchars($data_lama['brgGambar']); ?>" style="max-width: 150px; border-radius: 5px; margin-bottom: 10px;" alt="Gambar Lama">
                <?php endif; ?>
                
                <input type="file" name="gambarBarang" id="inputGambar" accept="image/*">
                
                <label for="brgKeterangan">Keterangan:</label>
                <input type="text" name="brgKeterangan" value="<?php echo htmlspecialchars($data_lama['brgKeterangan'] ?? ''); ?>">
                
                <button type="submit" name="update" class="btn btn-update" style="margin-top: 30px;">Update Data</button>
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