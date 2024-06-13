<?php
include 'hadeh/function.php';

// Pastikan ID artikel tersedia di URL
if (isset($_GET['id_artikel'])) {
    // Sanitasi input
    $id_artikel = intval($_GET['id_artikel']);
    
    // Debug: Tampilkan ID artikel untuk memastikan nilai yang benar diterima
    echo "ID Artikel: " . $id_artikel . "<br>";

    // Ambil data artikel dari database
    $koneksi = mysqli_connect($servername, $username, $password, $dbname);
    if (!$koneksi) { // Cek koneksi
        die("Tidak bisa terkoneksi ke database: " . mysqli_connect_error());
    }

    // Query untuk mengambil data artikel berdasarkan ID menggunakan prepared statement
    $stmt = $koneksi->prepare("SELECT * FROM artikel WHERE id_artikel = ?");
    $stmt->bind_param("i", $id_artikel);
    $stmt->execute();
    $result = $stmt->get_result();

    // Debug: Tampilkan jumlah baris yang ditemukan
    echo "Jumlah Baris Ditemukan: " . $result->num_rows . "<br>";
}
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tanggal = $row['tanggal'];
        $penulis = $row['penulis'];
        $kategori = $row['kategori'];
        $gambar = $row['gambar'];
        $judul = $row['judul'];
        $isi = $row['isi'];
    }
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Artikel</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Reset CSS */
body, h1, h2, p, img {
    margin: 0;
    padding: 0;
}

/* Global styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

/* Article styles */
.article {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 30px;
}

.article-header {
    font-size: 2em;
    margin-bottom: 10px;
}

.article-info {
    font-size: 0.9em;
    color: #777;
    margin-bottom: 15px;
}

.article-image {
    max-width: 100%;
    margin-bottom: 20px;
    border-radius: 8px;
}

.article-content {
    font-size: 1em;
    line-height: 1.6;
}

nav{
    text-align: end;
    margin-right: 20px;
}

    </style>
</head>
<body>
<nav>
    <a href="index.php">Kembali</a>
</nav>
<div class="container">
    <?php
    include 'hadeh/function.php';

    // Pastikan ID artikel tersedia di URL
    if (isset($_GET['id_artikel'])) {
        // Sanitasi input
        $id_artikel = intval($_GET['id_artikel']);

        // Ambil data artikel dari database
        $koneksi = mysqli_connect($servername, $username, $password, $dbname);
        if (!$koneksi) { // Cek koneksi
            die("Tidak bisa terkoneksi ke database: " . mysqli_connect_error());
        }

        // Query untuk mengambil data artikel berdasarkan ID menggunakan prepared statement
        $stmt = $koneksi->prepare("SELECT * FROM artikel WHERE id_artikel = ?");
        $stmt->bind_param("i", $id_artikel);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $tanggal = $row['tanggal'];
            $penulis = $row['penulis'];
            $kategori = $row['kategori'];
            $gambar = $row['gambar'];
            $judul = $row['judul'];
            $isi = $row['isi'];

            // Tampilkan data artikel
            echo '
            <div class="article">
                <h1 class="article-header">' . htmlspecialchars($judul) . '</h1>
                <p class="article-info">Tanggal: ' . htmlspecialchars($tanggal) . ' | Penulis: ' . htmlspecialchars($penulis) . ' | Kategori: ' . htmlspecialchars($kategori) . '</p>
                <img src="' . htmlspecialchars($gambar) . '" alt="Gambar Artikel" class="article-image">
                <div class="article-content">
                    ' . nl2br(htmlspecialchars($isi)) . '
                </div>
            </div>';
        } else {
            echo "Artikel tidak ditemukan.";
        }

        $stmt->close();
        mysqli_close($koneksi);
    } else {
        echo "ID artikel tidak tersedia.";
    }
    ?>
</div>

</body>
</html>



