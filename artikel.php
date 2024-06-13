<?php
include 'hadeh/function.php';

$koneksi = mysqli_connect($servername, $username, $password, $dbname);

// Memeriksa apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$id         = "";
$tanggal    = date('Y-m-d'); // Set to current date
$judul      = "";
$penulis    = isset($_SESSION['user']) ? $_SESSION['user'] : ""; // Set to logged-in user
$kategori   = "";
$pembuka    = "";
$isi        = "";
$gambar     = "";
$sukses     = "";
$error      = "";

// Query untuk mengambil data kategori
$kategoriQuery = "SELECT * FROM kategori ORDER BY id_kategori";
$kategoriResult = mysqli_query($koneksi, $kategoriQuery);
if (!$kategoriResult) {
    die("Query error: " . mysqli_error($koneksi));
}

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id = $_GET['id'];
    
    // Ambil data gambar yang akan dihapus
    $sql1 = "SELECT gambar FROM artikel WHERE id_artikel = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $gambar = $r1['gambar'];
    
    // Hapus data dari database
    $sql2 = "DELETE FROM artikel WHERE id_artikel = '$id'";
    $q2 = mysqli_query($koneksi, $sql2);
    
    if ($q2) {
        // Hapus gambar dari folder
        if (file_exists("dbArtikel/" . $gambar)) {
            unlink("dbArtikel/" . $gambar);
        }
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data";
    }
}

if ($op == 'edit') {
    // Periksa apakah parameter id_artikel tersedia dalam URL
    if (isset($_GET['id_artikel'])) {
        $id = $_GET['id_artikel'];
        
        // Lakukan query SELECT berdasarkan id_artikel
        $sql1 = "SELECT * FROM artikel WHERE id_artikel = '$id'";
        $q1 = mysqli_query($koneksi, $sql1);
        $r1 = mysqli_fetch_array($q1);
        
        if ($r1) {
            // Jika data ditemukan, isi variabel dengan data yang diambil dari database
            $id = $r1['id_artikel'];
            $tanggal = $r1['tanggal'];
            $judul = $r1['judul'];
            $penulis = $r1['penulis'];
            $pembuka = $r1['pembuka'];
            $isi = $r1['isi'];
            $kategori = $r1['kategori'];
            $gambar = $r1['gambar'];
        } else {
            $error = "Data tidak ditemukan";
        }
    } else {
        $error = "ID artikel tidak ditemukan";
    }
}

if (isset($_POST['simpan'])) {
    $id = $_POST['id_artikel'];
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $pembuka = $_POST['pembuka'];
    $kategori = $_POST['kategori'];
    $gambar = $_FILES['gambar']['name'];
    $target_dir = "dbArtikel/";
    $target_file = $target_dir . basename($_FILES["gambar"]["name"]);

    if ($judul && $isi && $gambar && $kategori) {
        if ($op == 'edit') {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                $sql1 = "UPDATE artikel SET id_artikel='$id', judul='$judul', penulis='$penulis', pembuka='$pembuka', isi='$isi', kategori='$kategori',                     gambar='$target_file', tanggal='$tanggal' WHERE id_artikel='$id'";
                $q1 = mysqli_query($koneksi, $sql1);
                if ($q1) {
                    $sukses = "Data berhasil diupdate";
                } else {
                    $error = "Data gagal diupdate";
                }
            } else {
                $error = "Gagal mengupload gambar";
            }
        } else {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                $sql1 = "INSERT INTO artikel (id_artikel, tanggal, judul, penulis, pembuka, isi, kategori, gambar) VALUES ('$id', '$tanggal', '$judul',                         '$penulis', '$pembuka', '$isi', '$kategori', '$target_file')";
                $q1 = mysqli_query($koneksi, $sql1);
                if ($q1) {
                    $sukses = "Berhasil memasukkan data baru";
                } else {
                    $error = "Gagal memasukkan data";
                }
            } else {
                $error = "Gagal mengupload gambar";
            }
        }
    } else {
        $error = "Silakan masukkan semua data";
    }
}
?>

<!-- ... HTML Form dan Tampilan Data ... -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTIKEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="artikel.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">ARTIKEL</h1>
        <nav>
        <ul>
            <li><a href="penulis.php">Penulis</a></li>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="kategorii.php">Kategori</a></li>
        </ul>
        <!-- Display logged-in user information -->
        <div class="card mt-3">
            <div class="card-body">
                <?php if (isset($_SESSION['user'])) { ?>
                    <h5>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h5>
                <?php } else { ?>
                    <p>Please <a href="login.php">login</a> to manage your articles.</p>
                <?php } ?>
            </div>
        </div>
    </nav>
        <?php if ($error) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error ?>
            </div>
        <?php } ?>

        <?php if ($sukses) { ?>
            <div class="alert alert-success" role="alert">
            <?php echo $sukses ?>
            </div>
        <?php } ?>

        <div class="card mt-3">
            <div class="card-header">
                Tambah / Edit Data Artikel
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3 row">
                        <label for="id_artikel" class="col-sm-2 col-form-label">ID Artikel</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="id_artikel" name="id_artikel" value="<?php echo $id ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo $tanggal ?>" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="judul" class="col-sm-2 col-form-label">Judul</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="judul" name="judul" value="<?php echo $judul ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="penulis" class="col-sm-2 col-form-label">Penulis</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="penulis" name="penulis" value="<?php echo $penulis ?>" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="pembuka" class="col-sm-2 col-form-label">Pembuka</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="pembuka" name="pembuka" value="<?php echo $pembuka ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="isi" class="col-sm-2 col-form-label">Isi</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="isi" name="isi"><?php echo $isi ?></textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="kategori" name="kategori">
                                <option value="">Pilih Kategori</option>
                                <?php 
                                while($kategoriRow = mysqli_fetch_assoc($kategoriResult)) { 
                                    $selected = ($kategoriRow['id_kategori'] == $kategori) ? "selected" : "";
                                ?>
                                    <option value="<?php echo $kategoriRow['id_kategori']; ?>" <?php echo $selected; ?>>
                                        <?php echo $kategoriRow['macam_kategori']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="gambar" class="col-sm-2 col-form-label">Gambar</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" id="gambar" name="gambar">
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header text-white bg-secondary">
                Data Artikel
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">ID</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Judul</th>
                            <th scope="col">Penulis</th>
                            <th scope="col">Pembuka</th>
                            <th scope="col">Isi</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Gambar</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                <tbody>
                        <?php
                        $sql2 = "SELECT * FROM artikel ORDER BY id_artikel DESC";
                        $q2 = mysqli_query($koneksi, $sql2);
                        if (!$q2) {
                            die("Query error: " . mysqli_error($koneksi));
                        }
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id = $r2['id_artikel'];
                            $tanggal = $r2['tanggal'];
                            $judul = $r2['judul'];
                            $penulis = $r2['penulis'];
                            $pembuka = $r2['pembuka'];
                            $isi = $r2['isi'];
                            $gambar = $r2['gambar'];

                            // Mengambil nama kategori dari tabel kategori berdasarkan ID kategori
                            // Query untuk mengambil nama kategori berdasarkan ID kategori
                            $idKat = $r2['kategori'];
                            $sql_kategori = "SELECT macam_kategori FROM kategori WHERE id_kategori = '$idKat'";
                            $q_kategori = mysqli_query($koneksi, $sql_kategori);
                            $row_kategori = mysqli_fetch_assoc($q_kategori);
                            if ($row_kategori) {
                                $kategori = $row_kategori['macam_kategori'];
                            } else {
                                $kategori = "Kategori tidak ditemukan"; // atau berikan nilai default lainnya
                            }
                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td><?php echo $id ?></td>
                                <td><?php echo $tanggal ?></td>
                                <td><?php echo $judul ?></td>
                                <td><?php echo $penulis ?></td>
                                <td><?php echo $pembuka ?></td>
                                <td><?php echo $isi ?></td>
                                <td><?php echo $kategori ?></td>
                                <td><img src="<?php echo $gambar ?>" width="100"></td>
                                <td>
                                    <a href="artikel.php?op=edit&id_artikel=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="artikel.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>
                                </td>
                            </tr
                            <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<script>
    function showSelectedValue() {
        var dropdown = document.getElementById("kategori");
        var selectedOption = dropdown.options[dropdown.selectedIndex].text;
        document.getElementById("selectedValue").innerHTML = "Anda memilih kategori: " + selectedOption;
    }
</script>
</html>
