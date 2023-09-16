<?php
session_start();
require('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) { // Jika yang di-post adalah login
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($koneksi, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $_SESSION['username'] = $username;
            header("Location: index.php");
        } else {
            $error = "<p class='error'>Login gagal. Cek kembali username dan password Anda.</p>";
        }
    }
}

if (isset($_POST['register'])) { // Jika yang di-post adalah pendaftaran
    $username = $_POST['new_username'];
    $password = $_POST['new_password'];

    // Periksa apakah username sudah digunakan
    $check_query = "SELECT * FROM users WHERE username = '$username'";
    $check_result = mysqli_query($koneksi, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $error = "<p class='error'>Username sudah digunakan. Silakan pilih username lain.</p>";
    } else {
        // Tambahkan pengguna baru ke database
        $insert_query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if (mysqli_query($koneksi, $insert_query)) {
            $_SESSION['username'] = $username;
            header("Location: login.php"); // Redirect ke halaman setelah pendaftaran sukses
        } else {
            $error = "<p class='error'>Pendaftaran gagal. Silakan coba lagi.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laman Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

    <style>
        .error {
            padding: 5px;
            width: 30%;
            margin: 0 auto;
            font-size: 12px;
            font-weight: 650;
            line-height: 1;
            color: #fff;
            background-color: red;
            text-align: center;
            border-radius: 0.8em;
        }
    </style>
</head>
<body>
    <div class="mb-4 text-center" style="margin-top: 7%"><h2><i class="bi bi-film"></i> List Film Favorit </h2></div> 
    <div class="container-sm"> 
        <form method="post" action="" class="alert alert-info" style="width: 350px; margin: 0 auto; align-items: center;">
            <div class="mb-3" >
                <label for="exampleInputEmail1" class="form-label"><i class="bi bi-person-fill"></i> Nama Panggilan</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="username" required>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label"><i class="bi bi-key-fill"></i> Kata Sandi</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" value="Login" class="col-md-6 offset-md-3 btn btn-primary" name="login"><i class="bi bi-box-arrow-in-right"></i> Masuk</button>
        </form>
        <?php if (isset($error)) { echo "<p>$error</p>"; } ?>      
        <p class="text-center mt-3">Belum punya akun? <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registerModal"><i class="bi bi-pencil-square"></i> Daftar</button></p>
    </div>

    <!-- Modal Register -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel"><i class="bi bi-pencil-square"></i> Form Daftar Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="" class="row g-3">
                        <div class="mb-2">
                            <label for="new_username" class="form-label"><i class="bi bi-person-fill"></i> Nama Panggilan</label>
                            <input type="text" class="form-control" id="new_username" name="new_username" required>
                        </div>
                        <div class="">
                            <label for="new_password" class="form-label"><i class="bi bi-key-fill"></i> Kata Sandi</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="invalidCheck3" aria-describedby="invalidCheck3Feedback" required>
                        <label class="form-check-label " for="invalidCheck3">
                            Setuju untuk melanjutkan
                        </label>
                        </div>
                        <button type="submit" value="Register" class="btn btn-success" name="register">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
