<?php
/**
 * Konfigurasi Database dan Keamanan Dasar
 * 
 * Aplikasi ini mengimplementasikan:
 * 1. Password Hashing: Menggunakan bcrypt melalui password_hash() dan password_verify().
 * 2. Session Management: Pengaturan sesi yang aman dengan regenerasi ID dan timeout.
 * 3. Role Based Access Control (RBAC): Pembatasan akses halaman berdasarkan peran 'admin' atau 'user'.
 * 4. HTTPS/TLS: Mengonfigurasi parameter cookie sesi agar hanya dikirim melalui jalur aman.
 */

// ==============================================================================
// [IMPLEMENTASI 5: HTTPS/TLS]
// Memastikan keamanan cookie sesi. Di lingkungan produksi (server beneran),
// atribut 'secure' harus di-set true agar session cookie HANYA bisa dikirim lewat HTTPS.
// Karena ini berjalan di localhost (XAMPP), kita buat kondisional agar tetap bisa dinilai/didemokan.
// ==============================================================================
$isSecure = false;
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $isSecure = true;
}

// Konfigurasi cookie sebelum session dimulai (dipanggil di file lain)
ini_set('session.cookie_httponly', 1);      // Mencegah akses cookie via JavaScript (XSS Protection)
ini_set('session.cookie_secure', $isSecure ? 1 : 0); // Memaksa HTTPS jika tersedia
ini_set('session.use_only_cookies', 1);     // Memaksa hanya menggunakan cookie untuk session ID

$host = "localhost";
$user = "root";
$password = "";
$database = "perpustakaan_security";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset ke UTF-8
$conn->set_charset("utf8mb4");
?>