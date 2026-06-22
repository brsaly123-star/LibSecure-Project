<?php

// ==============================================================================
// [IMPLEMENTASI 2: Session Management]
// Mengelola masa aktif sesi (timeout) dan memvalidasi keberadaan sesi.
// ==============================================================================
require_once 'config.php';

$timeout = 900; // Sesi akan berakhir otomatis setelah 15 menit (900 detik) tidak ada aktivitas

// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memastikan pengguna telah melalui proses Login yang sah.
// Jika tidak ada ID sesi, akses ditolak dan diarahkan kembali ke halaman login.
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Pengecekan timeout sesi
if (isset($_SESSION['last_activity'])) {
    if ((time() - $_SESSION['last_activity']) > $timeout) {
        // Sesi telah melewati batas waktu, bersihkan seluruh data sesi
        session_unset();
        session_destroy();
        header("Location: login.php?expired=1");
        exit;
    }
}

// Update last activity (perbarui waktu aktivitas terakhir jika pengguna aktif)
$_SESSION['last_activity'] = time();
?>