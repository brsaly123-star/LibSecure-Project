<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "perpustakaan_security"
);

if (!$conn) {
    die("Koneksi gagal");
}
