<?php
// ==============================================================================
// [IMPLEMENTASI 1: Login dan Logout Aman]
// Proses autentikasi login yang memverifikasi username dan password secara aman,
// serta mencegah serangan SQL Injection dan Session Fixation.
// ==============================================================================

require_once 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi input kosong
    if (empty($username) || empty($password)) {
        header("Location: login.php?error=empty");
        exit;
    }

    // Menggunakan Prepared Statement untuk query database yang aman dari SQL Injection
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // ==============================================================================
        // [IMPLEMENTASI 3: Password Hashing]
        // Memverifikasi password yang diinputkan dengan hash bcrypt yang tersimpan di DB
        // menggunakan fungsi aman password_verify() bawaan PHP.
        // ==============================================================================
        if (password_verify($password, $user['password'])) {
            
            // Regenerasi ID sesi untuk mencegah serangan Session Fixation
            session_regenerate_id(true);

            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['last_activity'] = time();

            // ==============================================================================
            // [IMPLEMENTASI 4: Role Based Access Control (RBAC)]
            // Mengarahkan pengguna ke antarmuka yang berbeda berdasarkan perannya (admin/user).
            // ==============================================================================
            if ($user['role'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: user.php");
            }
            exit();
        } else {
            header("Location: login.php?error=invalid");
            exit;
        }
    } else {
        header("Location: login.php?error=invalid");
        exit;
    }

    $stmt->close();
}
?>