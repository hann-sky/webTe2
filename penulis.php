<?php
include 'hadeh/function.php';

$koneksi    = mysqli_connect($servername, $username, $password, $dbname);
if (!$koneksi) { // Check connection
    die("Tidak bisa terkoneksi ke database");
}
$username       = isset($_SESSION['user']) ? $_SESSION['user'] : "";
$nama_lengkap   = "";
$email          = "";
$phone          = "";
$password       = "";

$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $username = $_GET['username'];
    $sql1 = "DELETE FROM penulis WHERE username = '$username'";
    $q1 = mysqli_query($koneksi, $sql1);
    if ($q1) {
       

    } else {
        $error = "Gagal melakukan delete data";
    }
}

if ($op == 'edit') {
    $username = $_GET['username'];
    $sql1 = "SELECT * FROM penulis WHERE username = '$username'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $username = $r1['username'];
    $nama_lengkap = $r1['nama_lengkap'];
    $email = $r1['email'];
    $password = $r1['password'];
    $phone = $r1['phone'];

    if ($username == '') {
        $error = "Data tidak ditemukan";
    }
}

if (isset($_POST['simpan'])) { // For create and update
    $username = $_POST['username'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];


    if ($username && $nama_lengkap && $email && $password && $phone) {
        if ($op == 'edit') {
            $sql1 = "UPDATE penulis SET username='$username', nama_lengkap='$nama_lengkap', email='$email', password='$password', phone='$phone' WHERE username='$username'";
            $q1 = mysqli_query($koneksi, $sql1);

            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error = "Data gagal diupdate";
            }
        } else {
            $sql1 = "INSERT INTO penulis (username, nama_lengkap, email, password, phone) VALUES ('$username', '$nama_lengkap', '$email', '$password', '$phone')";
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



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="penulis.css" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
                <a href="index.php">Dashboard</a>
                <a href="kategorii.php">Kategori</a>
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
                header("refresh:5;url=penulis.php");//5 : detik
            }
            ?>
            <?php
            if ($sukses) {
            ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $sukses ?>
                </div>
            <?php
                header("refresh:5;url=penulis.php");
            }
            ?>
            <h2>Tambah Kategori Baru</h2>
        </div>
        <form action="" method="POST">
            <div class="mb-3 row">
                    <div class="mb-3 row">
                    <label for="macam_kategori" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $username?>" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="keterangan" class="col-sm-2 col-form-label">Nama Lengkap</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo $nama_lengkap ?>">
                </div>
                <div class="mb-3 row">
                    <label for="keterangan" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="email" name="email" value="<?php echo $email ?>">
                </div>
                <div class="mb-3 row">
                    <label for="keterangan" class="col-sm-2 col-form-label">Nomer Hp</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $phone ?>">
                </div>
                <div class="mb-3 row">
                    <label for="keterangan" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="password" name="password" value="<?php echo $password ?>">
                </div>
            </div>
        </form>
    </div>
        <div class="col-12">
            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
            <a href="?op=tambah" class="btn btn-primary">Tambah Kategori</a>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
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
                                <th scope="col">Username</th>
                                <th scope="col">Nama Lengkap</th>
                                <th scope="col">Email</th>
                                <th scope="col">Password</th>
                                <th scope="col">Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql2 = "select * from penulis order by username desc";
                                $q2 = mysqli_query($koneksi, $sql2);
                                $urut = 1;
                                while ($r2 = mysqli_fetch_array($q2)) {
                                    $username       = $r2['username'];
                                    $nama_lengkap   = $r2['nama_lengkap'];
                                    $email          = $r2['email'];
                                    $password       = $r2['password'];
                                    $phone          = $r2['phone'];
                                }
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $urut++ ?></th>
                                    <td><?php echo $username ?></td>
                                    <td><?php echo $nama_lengkap ?></td>
                                    <td><?php echo $email ?></td>
                                    <td><?php echo $password ?></td>
                                    <td><?php echo $phone ?></td>
                                    <td scope="row">
                                    <a href="penulis.php?op=edit&username=<?php echo $username ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                        <a href="penulis.php?op=delete&username=<?php echo $username?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>            
                                    </td>
                                </tr>
                            <?php
                            ?>
                        </tbody>
                    </thead>
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

