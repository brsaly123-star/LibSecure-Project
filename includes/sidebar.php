<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';
$dashLink = ($role === 'admin') ? 'admin' : 'user'; // Mengarah ke /admin atau /user
?>
<aside class="sidebar">
    <a href="<?= $dashLink ?>" class="sidebar-logo">
        <i class="bi bi-book-half"></i> LibSecure
    </a>

    <nav class="sidebar-nav">
        <a href="<?= $dashLink ?>" class="nav-item-custom <?= ($currentPage === 'admin.php' || $currentPage === 'user.php') ? 'active' : '' ?>">
            <i class="bi bi-house-door"></i> Dashboard
        </a>
        <a href="books" class="nav-item-custom <?= ($currentPage === 'books.php') ? 'active' : '' ?>">
            <i class="bi bi-journal-bookmark"></i> Daftar Buku
        </a>
        
        <?php if ($role === 'admin'): ?>
        <a href="add_book" class="nav-item-custom <?= ($currentPage === 'add_book.php') ? 'active' : '' ?>">
            <i class="bi bi-plus-square"></i> Tambah Buku
        </a>
        <?php endif; ?>
    </nav>
</aside>