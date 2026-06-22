<?php
/**
 * Halaman Login - Redesign (Centered Form + Library Theme)
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — LibSecure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Memisahkan CSS agar HTML lebih rapi -->
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>

<div class="form-card">
    <div class="d-flex align-items-center mb-4">
        <div class="app-logo mb-0 me-3">
            <i class="bi bi-book-half"></i>
        </div>
        <h3 class="mb-0 fw-bold" style="color: var(--text-main); font-size: 1.4rem;">LibSecure</h3>
    </div>
    
    <h2>Welcome Back!</h2>
    <p class="subtitle">Login to your library account</p>

    <?php if (isset($_GET['expired'])): ?>
        <div class="alert-custom alert-warn">
            <i class="bi bi-clock-history"></i> Sesi habis. Silakan login kembali.
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert-custom alert-error">
            <i class="bi bi-exclamation-circle"></i>
            <?= $_GET['error'] === 'empty' ? 'Username dan password wajib diisi.' : 'Username atau password salah.' ?>
        </div>
    <?php endif; ?>

    <form action="authenticate.php" method="POST" novalidate>
        <div class="input-group-custom" id="group-username">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Masukkan username" required onfocus="setFocus(this)" onblur="removeFocus(this)">
        </div>

        <div class="input-group-custom unfocused" id="group-password">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Masukkan password" required onfocus="setFocus(this)" onblur="removeFocus(this)">
        </div>

        <a href="#" class="forgot-link">Forgot Password ?</a>

        <button type="submit" class="btn-login">Login</button>
    </form>

    <div class="divider">or</div>

    <a href="#" class="btn-google">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="18px" height="18px">
            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
        </svg>
        Login with Google
    </a>
    
</div>

<script>
    // Simple script to toggle focus classes for the custom input styling
    function setFocus(element) {
        element.parentElement.classList.remove('unfocused');
    }
    
    function removeFocus(element) {
        if (element.value === '') {
            element.parentElement.classList.add('unfocused');
        }
    }

    // Initialize unfocused state for empty fields on load
    document.addEventListener("DOMContentLoaded", function() {
        const inputs = document.querySelectorAll('.input-group-custom input');
        inputs.forEach(input => {
            if (input.value === '') {
                input.parentElement.classList.add('unfocused');
            } else {
                input.parentElement.classList.remove('unfocused');
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>