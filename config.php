<?php
/**
 * Konfigurasi Database dan Keamanan Dasar (Hybrid: Localhost & Railway)
 * * Aplikasi ini mengimplementasikan:
 * 1. Password Hashing: Menggunakan bcrypt melalui password_hash() dan password_verify().
 * 2. Session Management: Pengaturan sesi yang aman dengan regenerasi ID dan timeout.
 * 3. Role Based Access Control (RBAC): Pembatasan akses halaman berdasarkan peran 'admin' atau 'user'.
 * 4. HTTPS/TLS: Mengonfigurasi parameter cookie sesi agar hanya dikirim melalui jalur aman.
 */

// ==============================================================================
// [IMPLEMENTASI 5: HTTPS/TLS & REVERSE PROXY DETECTION]
// Memastikan keamanan cookie sesi. Mendukung deteksi HTTPS di localhost
// maupun di cloud platform (Railway) yang menggunakan Reverse Proxy.
// ==============================================================================
$isSecure = false;
if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || 
    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
    $isSecure = true;
}

// Konfigurasi cookie sebelum session dimulai
ini_set('session.cookie_httponly', 1);       // Mencegah akses cookie via JavaScript (XSS Protection)
ini_set('session.cookie_secure', $isSecure ? 1 : 0); // Memaksa HTTPS jika tersedia
ini_set('session.use_only_cookies', 1);      // Memaksa hanya menggunakan cookie untuk session ID

// ==============================================================================
// [KONFIGURASI DATABASE DINAMIS]
// Jika ada Environment Variable dari Railway, gunakan itu. Jika tidak, gunakan Localhost.
// ==============================================================================
$host     = getenv('MYSQLHOST') ?: "localhost";
$user     = getenv('MYSQLUSER') ?: "root";
$password = getenv('MYSQLPASSWORD') ?: "";
$database = getenv('MYSQLDATABASE') ?: "perpustakaan_security";
$port     = getenv('MYSQLPORT') ?: "3306";

// Hubungkan menggunakan objek mysqli dengan menyertakan port
$conn = new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset ke UTF-8
$conn->set_charset("utf8mb4");
?>