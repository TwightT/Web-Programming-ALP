<?php
// HALAMAN DETAIL BARANG

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

// Ensure an ID was passed in the URL
if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
    die("Error: ID Barang tidak ditemukan. <a href='index.php'>Kembali</a>");
}

$id = trim($_GET['id']);

// Use Prepared Statement to fetch product details securely
$query = "SELECT * FROM Produk WHERE brgKode = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("Error: Barang tidak ditemukan di database. <a href='index.php'>Kembali</a>");
}

$query_log = "SELECT * FROM Log_User WHERE brgKode = ? ORDER BY waktu DESC";
$stmt_log = mysqli_prepare($conn, $query_log);
mysqli_stmt_bind_param($stmt_log, "s", $id);
mysqli_stmt_execute($stmt_log);
$result_log = mysqli_stmt_get_result($stmt_log);
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Barang - <?php echo htmlspecialchars($product['brgNama']); ?></title>
    <link rel="stylesheet" href="mystyle.css">
    <style>
        .detail-card {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 40px auto;
            text-align: center;
        }
        .detail-card img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .detail-info {
            text-align: left;
            margin-top: 20px;
        }
        .detail-info p {
            font-size: 18px;
            margin: 10px 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        nav {
            width: 20%;
            margin-left: 0;
            padding : 20px; 
        }

        .log-toggle-btn {
            background-color: #555;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
            font-size: 16px;
        }
        .log-toggle-btn:hover {
            background-color: #333;
        }
        .log-container {
            display: none; /* Disembunyikan secara default */
            margin-top: 15px;
            text-align: left;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .log-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-top: 10px;
        }
        .log-table th, .log-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .log-table th {
            background-color: #e2e2e2;
        }
    </style>
</head>
<body>
    <header>
        <a class="dotted-lines">
        INVENTORY DETAIL
        </a>
    </header>    
    <div class="body-container">    
        <nav><?php include_once "navigation.php"; ?></nav>
        <main>
            <div class="detail-card">
                <?php if (!empty($product['brgGambar'])): ?>
                    <img src="<?php echo htmlspecialchars($product['brgGambar']); ?>" alt="Gambar <?php echo htmlspecialchars($product['brgNama']); ?>">
                <?php else: ?>
                    <div style="background:#eee; padding:50px; border-radius:8px; margin-bottom:20px;">
                        <p style="color:#666;">TIDAK ADA GAMBAR</p>
                    </div>
                <?php endif; ?>

                <h2><?php echo htmlspecialchars($product['brgNama']); ?></h2>
                
                <div class="detail-info">
                    <p><strong>Kode Barang :</strong> <?php echo htmlspecialchars($product['brgKode']); ?></p>
                    <p><strong>Nama Barang :</strong> <?php echo htmlspecialchars($product['brgNama']); ?></p>
                    <p><strong>Stok Tersedia :</strong> <?php echo htmlspecialchars($product['brgStok']); ?></p>
                    <p><strong>Harga Barang :</strong> <?php echo htmlspecialchars($product['brgHarga']); ?></p>
                    <p><strong>Isi Barang :</strong> <?php echo htmlspecialchars($product['brgIsi']); ?></p>
                    <p><strong>Keterangan Barang :</strong> <?php echo htmlspecialchars($product['brgKeterangan']); ?></p>
                    <p>Terakhir diubah <?php echo htmlspecialchars($product['brgTanggal']); ?></p>
                </div>

                <button class="log-toggle-btn" onclick="toggleLogs()">Lihat Riwayat Perubahan Data</button>

                <div class="log-container" id="logSection">
                    <h3 style="margin-top: 0; font-size: 16px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">Riwayat Log Database</h3>
                    
                    <?php if (mysqli_num_rows($result_log) > 0): ?>
                        <table class="log-table">
                            <tr>
                                <th width="18%">Waktu</th>
                                <th width="15%">User</th>
                                <th width="10%">Aksi</th>
                                <th width="57%">Detail Perubahan</th>
                            </tr>
                            <?php while($log = mysqli_fetch_assoc($result_log)): ?>
                            <tr>
                                <td><?php echo date('d-M-Y H:i', strtotime($log['waktu'])); ?></td>
                                <td><?php echo htmlspecialchars($log['username']); ?></td>
                                <td>
                                    <span style="padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; color: white; 
                                        background-color: <?php echo ($log['aksi'] == 'INSERT') ? '#4CAF50' : (($log['aksi'] == 'UPDATE') ? '#2196F3' : '#f44336'); ?>;">
                                        <?php echo htmlspecialchars($log['aksi']); ?>
                                    </span>
                                </td>
                                <td style="line-height: 1.5;">
                                    <?php
                                    // Logika Cerdas Menampilkan Detail Perubahan
                                    if ($log['aksi'] == 'INSERT') {
                                        echo "Data awal ditambahkan. <br>Nama: <b>" . htmlspecialchars($log['brgNama_baru']) . "</b> (Stok: " . $log['brgStok_baru'] . ")";
                                    } 
                                    elseif ($log['aksi'] == 'DELETE') {
                                        echo "Data dihapus. <br>Nama terakhir: <b>" . htmlspecialchars($log['brgNama_lama']) . "</b>";
                                    } 
                                    elseif ($log['aksi'] == 'UPDATE') {
                                        $perubahan = []; // Array untuk menampung teks perubahan
                                        
                                        // Cek satu-satu mana yang berubah (Lama vs Baru)
                                        if ($log['brgNama_lama'] != $log['brgNama_baru']) {
                                            $perubahan[] = "Nama: <s>{$log['brgNama_lama']}</s> &rarr; <b>{$log['brgNama_baru']}</b>";
                                        }
                                        if ($log['brgStok_lama'] != $log['brgStok_baru']) {
                                            $perubahan[] = "Stok: <s>{$log['brgStok_lama']}</s> &rarr; <b>{$log['brgStok_baru']}</b>";
                                        }
                                        if ($log['brgHarga_lama'] != $log['brgHarga_baru']) {
                                            $harga_lama = "Rp " . number_format($log['brgHarga_lama'],0,',','.');
                                            $harga_baru = "Rp " . number_format($log['brgHarga_baru'],0,',','.');
                                            $perubahan[] = "Harga: <s>{$harga_lama}</s> &rarr; <b>{$harga_baru}</b>";
                                        }
                                        if ($log['brgIsi_lama'] != $log['brgIsi_baru']) {
                                            $perubahan[] = "Isi: <s>{$log['brgIsi_lama']}</s> &rarr; <b>{$log['brgIsi_baru']}</b>";
                                        }
                                        if ($log['brgKeterangan_lama'] != $log['brgKeterangan_baru']) {
                                            $perubahan[] = "Keterangan: <s>{$log['brgKeterangan_lama']}</s> &rarr; <b>{$log['brgKeterangan_baru']}</b>";
                                        }
                                        if ($log['brgGambar_lama'] != $log['brgGambar_baru']) {
                                            $perubahan[] = "Gambar diperbarui 📸";
                                        }

                                        // Jika disave tapi tidak ada data yang diganti (Duplikat)
                                        if (empty($perubahan)) {
                                            echo "<span style='color:#888; font-style:italic;'>Disimpan ulang (Tidak ada perubahan data)</span>";
                                        } else {
                                            echo implode("<br>", $perubahan); // Gabungkan semua teks dengan ganti baris (Enter)
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </table>
                    <?php else: ?>
                        <div style="text-align: center; padding: 40px 20px; background-color: #fff; border: 2px dashed #ccc; border-radius: 8px; margin-top: 15px;">
                            <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="#bdbdbd">
                                <path d="M480-120q-75 0-140.5-28.5t-114-77q-48.5-48.5-77-114T120-480q0-75 28.5-140.5t77-114q48.5-48.5 114-77T480-840q82 0 155.5 35T760-706v-94h80v240H600v-80h110q-41-56-101-88t-129-32q-117 0-198.5 81.5T200-480q0 117 81.5 198.5T480-200q105 0 183.5-68T756-440h82q-15 137-117.5 228.5T480-120Zm-40-360v-240h80v206l144 144-56 56-168-166Z"/>
                            </svg>
                            <h4 style="color: #555; margin: 10px 0 5px 0;">Belum Ada Riwayat</h4>
                            <p style="color: #888; font-size: 14px; margin: 0;">Barang ini belum pernah mengalami perubahan data (Update/Delete).</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div style="margin-top: 30px;">
                    <a href="index.php" class="btn" style="padding: 10px 20px; text-decoration: none;">&larr; Kembali ke Daftar</a>
                </div>
            </div>
        </main>    
        <nav></nav>
    </div>
<script>
        function toggleLogs() {
            var logSection = document.getElementById("logSection");
            var btn = document.querySelector(".log-toggle-btn");
            
            if (logSection.style.display === "none" || logSection.style.display === "") {
                logSection.style.display = "block";
                btn.innerHTML = "Sembunyikan Riwayat Perubahan";
            } else {
                logSection.style.display = "none";
                btn.innerHTML = "Lihat Riwayat Perubahan Data";
            }
        }
</script>
    <footer>
        &copy; Copyright 2026 - Hezekiah Austin Sunanto
    </footer>
</body>
</html>
<?php 
if (isset($stmt)) { mysqli_stmt_close($stmt); }
if (isset($stmt_log)) { mysqli_stmt_close($stmt_log); }
mysqli_close($conn); 
?>