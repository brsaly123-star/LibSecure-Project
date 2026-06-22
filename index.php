<?php
/**
 * Entry Point Utama Aplikasi LibSecure
 * Mengalihkan pengguna langsung ke halaman login demi alasan keamanan.
 */
header("Location: login.php");
exit();
?>