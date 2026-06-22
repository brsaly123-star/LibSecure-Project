<?php
// ==============================================================================
// [IMPLEMENTASI 1: Login dan Logout Aman]
// Proses logout yang aman untuk mengakhiri sesi pengguna secara total.
// Menghapus seluruh data sesi di server dan cookie sesi di sisi klien.
// ==============================================================================
require_once 'config.php';
session_start();

// Hapus semua isi variabel global session
$_SESSION = array();

// Hapus session cookie di browser pengguna (klien)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan session di level server
session_destroy();

// Redirect ke halaman login setelah berhasil logout
header("Location: login.php");
exit;
?>