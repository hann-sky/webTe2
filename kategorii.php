<?php

include 'hadeh/function.php';

$koneksi = mysqli_connect($servername, $username, $password, $dbname);

// Memeriksa apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$id         = "";
$macamKategori    = "";
$keterangan    = "";

$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id = $_GET['id'];
    $sql1 = "DELETE FROM kategori WHERE id_kategori = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data";
    }
}

if ($op == 'edit') {
    if (isset($_GET['id_kategori'])) {
        $id = $_GET['id_kategori'];
        
        $sql1 = "SELECT * FROM kategori WHERE id_kategori = '$id'";
        $q1 = mysqli_query($koneksi, $sql1);

        if ($q1 && mysqli_num_rows($q1) > 0) {
            $r1 = mysqli_fetch_assoc($q1);
            $id = $r1['id_kategori'];
            $macamKategori = $r1['macam_kategori'];
            $keterangan = $r1['keterangan'];

        } else {
            $error = "Data tidak ditemukan";
        }
    } else {
        $error = "Parameter id_artikel tidak ditemukan dalam URL";
    }
}

// ... (Database connection code)

if (isset($_POST['simpan'])) {
    $id = $_POST['id_kategori'];
    $macamkategori = $_POST['macam_kategori'];
    $keterangan = $_POST['keterangan'];

    if ($id && $macamkategori && $keterangan) {
        if ($op == 'edit') {
            $sql1 = "UPDATE kategori SET id_kategori='$id', macam_kategori='$macamKategori', keterangan='$keterangan' WHERE id_kategori='$id'";
            $q1 = mysqli_query($koneksi, $sql1);

            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error = "Data gagal diupdate";
            }
        } else {
            $sql1 = "INSERT INTO kategori (id_kategori, macam_kategori, keterangan) VALUES ('$id', '$macamkategori', '$keterangan')";
            $q1 = mysqli_query($koneksi, $sql1);

            if ($q1) {
                $sukses = "Berhasil memasukkan data baru";
            } else {
                $error = "Gagal memasukkan data";
            }
        }
    } else {
        $error = "Silakan masukkan semua data";
    }
}

// ... (Rest of the code)

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="kategori.css" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
                <a href="index.php">Dashboard</a>
                <a href="penulis.php">Penulis</a>
                <a href="artikel.php">Artikel</a>
                <div class="card mt-3">
        </div>
        </nav>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            
        </nav>
    </header>
    <div class="card">
        <div class="card-header">
            Create / Edit Data
        </div>
        <div class="card-body">
            <?php
            if ($error) {
            ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error ?>
                </div>
            <?php
                header("refresh:5;url=kategorii.php");//5 : detik
            }
            ?>
            <?php
            if ($sukses) {
            ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $sukses ?>
                </div>
            <?php
                header("refresh:5;url=kategorii.php");
            }
            ?>
        </div>
        <h2>Tambah Kategori Baru</h2>
        <form action="" method="POST">
        <div class="mb-3 row">
            <label for="id_kategori" class="col-sm-2 col-form-label">ID Kategori</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="id_kategori" name="id_kategori" value="<?php echo $id ?>">
            </div>
        </div>
            <div class="mb-3 row">
            <label for="macam_kategori" class="col-sm-2 col-form-label">Macam Kategori</label>
            <div class="col-sm-10">
            <input type="text" class="form-control" id="macam_kategori" name="macam_kategori" value="<?php echo $macamKategori ?>">
            </div>
            </div>
        <div class="mb-3 row">
            <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
            <div class="col-sm-10">
        <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?php echo $keterangan ?>">
    </div>
</div>

            <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
  </form>

            </div>
    </div>
     
    <!-- untuk mengeluarkan data -->
    <div class="card">
            <div class="card-header text-white bg-secondary">
                Data Mahasiswa
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">No</th>
                            <th scope="col">Nama Kategori</th>
                            <th scope="col">keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "select * from kategori order by id_kategori desc";
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id         = $r2['id_kategori'];
                            $macamKategori        = $r2['macam_kategori'];
                            $keterangan       = $r2['keterangan'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td><?php echo $id ?></td>
                                <td><?php echo $macamKategori ?></td>
                                <td><?php echo $keterangan ?></td>
                                <td scope="row">
                                <a href="kategorii.php?op=edit&id_kategori=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="kategorii.php?op=delete&id=<?php echo $id?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>            
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    
                </table>
            </div>
        </div>

    <footer class="bg-light text-center py-3">
        <p>&copy; 2024 MyBlog. All rights reserved.</p>
    </footer>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>

