<?php
require('koneksi.php');
session_start();

// Model auth
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
}

// Model tambah dan edit data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];

        // Cek apakah pengguna mengunggah gambar baru
        if (isset($_FILES['new_image']) && !empty($_FILES['new_image']['name'])) {
            $new_image_name = $_FILES['new_image']['name'];
            $new_image_tmp = $_FILES['new_image']['tmp_name'];

            // Validasi gambar baru
            $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
            $new_file_extension = pathinfo($new_image_name, PATHINFO_EXTENSION);

            if (in_array($new_file_extension, $allowed_extensions) && $_FILES['new_image']['size'] < 2000000) {
                // Baca isi file gambar baru
                $new_image_data = file_get_contents($new_image_tmp);

                // Perbarui data dalam database, termasuk gambar baru
                $query = "UPDATE items SET name=?, description=?, image_data=? WHERE id=?";
                $stmt = mysqli_prepare($koneksi, $query);
                mysqli_stmt_bind_param($stmt, "sssi", $name, $description, $new_image_data, $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            } else {
                // Berkas gambar baru tidak valid
                echo "Berkas gambar baru tidak valid atau terlalu besar.";
            }
        } else {
            // Pengguna tidak mengunggah gambar baru, perbarui data tanpa mengubah gambar
            $query = "UPDATE items SET name=?, description=? WHERE id=?";
            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, "ssi", $name, $description, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    } else {
        $name = $_POST['name'];
        $description = $_POST['description'];

        if (isset($_FILES['image'])) {
            $image_name = $_FILES['image']['name'];
            $image_tmp = $_FILES['image']['tmp_name'];

            // Validasi gambar
            $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
            $file_extension = pathinfo($image_name, PATHINFO_EXTENSION);

            if (in_array($file_extension, $allowed_extensions) && $_FILES['image']['size'] < 2000000) {
                // Baca isi file gambar
                $image_data = file_get_contents($image_tmp);

                $query = "INSERT INTO items (name, description, image_data) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($koneksi, $query);
                mysqli_stmt_bind_param($stmt, "sss", $name, $description, $image_data);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            } else {
                // Berkas gambar tidak valid
                echo "Berkas gambar tidak valid atau terlalu besar.";
            }
        } else {
            $query = "INSERT INTO items (name, description) VALUES (?, ?)";
            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, "ss", $name, $description);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
    header("Location: index.php");
    exit();
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM items WHERE id=$id";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
}

// Proses CRUD
$query = "SELECT * FROM items";
$result = mysqli_query($koneksi, $query);
?>


<!DOCTYPE html>
<html>
<head>
    <title>&#127916 List Film Favorit </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <style>
    table {
        table-layout: fixed;
        width: 100%;
    }
    td {
        max-width: 80%;
        text-align: justify;
    }
    .gambar-tabel {
        width: 350px; /* Tentukan lebar yang Anda inginkan di sini */
        height: auto; /* Biarkan tinggi mengikuti aspek gambar */
        max-width: 100%; /* Mencegah gambar melebihi lebar sel yang terlalu kecil */
    }
    .imgshadow{
        box-shadow: 0px 0px 7px;
    }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand ms-5" href="index.php"><h4><i class="bi bi-film"></i> List Film Favorit  </h4></a>
            <div class="" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                <!-- Button trigger modal logout -->
                <button type="button" class="btn btn-dark me-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#logout"></i><i class="bi bi-box-arrow-left"></i> Keluar</button>
                <!-- Modal Logout-->
                    <div class="modal fade" id="logout" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content modal-header">
                            <h1 class="modal-title fs-5">Yakin mau keluar?</h1>
                                <div class="modal-body">
                                    <div class="modal-footer justify-content-end">
                                    <button type="button" class="btn btn-primary shadow-sm" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                                    <a href="logout.php"><button type="button" class="btn btn-danger shadow-sm"><i class="bi bi-check-lg"></i></button> </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid" style="max-width: 1000px; margin: 0 auto; margin-top: 15px;">
        <div class="row align-items-start">
            <div class="col mb-3">
                <!-- Button trigger modal tambah data -->
                <button type="button" class="btn btn-primary mt-3 shadow-sm rounded"  data-bs-toggle="modal" data-bs-target="#tambahdata"><i class="bi bi-plus-lg"></i> Tambah Data</button>
                <!-- Modal Tambah Data-->
                <div class="modal fade" id="tambahdata" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header"><h1 class="modal-title fs-5">Tambah Data</h1></div>
                                <div class="modal-body">
                                <form method="post" action="" enctype="multipart/form-data" class="row g-3">
                                    <div class="col-md-12">
                                        <label for="validationServer01" class="form-label">Nama</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="validationServer02" class="form-label">Deskripsi</label>
                                        <textarea type="textarea" class="form-control" name="description" required></textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="image" class="form-label">Gambar</label>                                        
                                        <input type="file" class="form-control" id="image" name="image" required>
                                        <small style="color:#808080; margin-left : 0px;" > Upload dengan file ekstensi .jpg / .png / .jpeg <br>Ukuran gambar maks 1MB</small>
                                    </div>
                                    <div class="modal-footer justify-content-end">
                                        <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">Kembali</button>
                                        <input type="submit" class="btn btn-primary shadow-sm" value="Tambah">
                                    </div>                                    
                                </form>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
        <div class="row align-items-center">
            <div class="col">
                <table class="table table-bordered" >
                    <tr>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td><img class="gambar-tabel" src="data:image/jpeg;base64,<?php echo base64_encode($row['image_data']); ?>" alt="Gambar"></td>
                            <td class="action-column">
                                <!-- Button edit data -->
                                <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#editdata<?php echo $row['id']; ?>"><i class="bi bi-pencil-square"></i> Edit</button>
                                <!-- Modal edit data-->
                                <div class="modal fade" id="editdata<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header"><h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data</h1></div>
                                            <div class="modal-body">
                                                <form method="post" action="" enctype="multipart/form-data" class="row g-3">
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <div class="col-md-12">
                                                        <label for="validationServer01" class="form-label">Nama</label>
                                                        <input type="text" class="form-control" id="validationServer01" name="name" value="<?php echo $row['name']; ?>" required>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="validationServer02" class="form-label">Deskripsi</label>
                                                        <textarea type="textarea" class="form-control" id="validationServer02" name="description" required><?php echo $row['description']; ?></textarea>
                                                    </div>                                                    
                                                    <div class="col-md-12">
                                                        <label for="existing_image" class="form-label">Gambar Saat Ini</label> <br>
                                                        <img class="imgshadow" src="data:image/jpeg;base64,<?php echo base64_encode($row['image_data']); ?>" alt="Gambar" width="200">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="image" class="form-label">Edit gambar</label>
                                                        <input type="file" class="form-control" id="image" name="new_image">
                                                        <small style="color:#808080; margin-top: 10px;" > Upload dengan file ekstensi .jpg / .png / .jpeg<br>Ukuran gambar maks 1MB</small>
                                                    </div>
                                                    <div class="modal-footer justify-content-end">
                                                        <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">Kembali</button>
                                                        <input type="submit" class="btn btn-primary shadow-sm" value="Simpan">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Button hapus -->
                                <button type="button" class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#hapusdata<?php echo $row['id']; ?>"><i class="bi bi-trash"></i> Hapus</button>
                                <!-- Modal hapus data-->
                                <div class="modal fade" id="hapusdata<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Apakah Anda ingin menghapus data?</h1>
                                            <div class="modal-body">
                                                <div class="modal-footer justify-content-end">
                                                    <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">Kembali</button>
                                                    <a href="hapus.php?id=<?php echo $row['id']; ?>"><button type="button" class="btn btn-danger shadow-sm"> Hapus</button></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
