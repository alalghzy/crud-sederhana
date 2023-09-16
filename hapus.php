<?php
require('koneksi.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM items WHERE id=$id";
    mysqli_query($koneksi, $query);

    header("Location: index.php");
} else {
    header("Location: index.php");
}
?>
