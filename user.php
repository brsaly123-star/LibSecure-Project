<?php
// [Session Management] Memastikan sesi pengguna valid dan menangani timeout otomatis
require_once 'session_check.php';
require_once 'config.php';

// [Role Based Access Control (RBAC)] Membatasi akses halaman hanya untuk pengguna dengan role 'user'
// ==============================================================================
// [IMPLEMENTASI 4: Role Based Access Control (RBAC)]
// Membatasi akses halaman ini HANYA untuk pengguna dengan role 'user'.
// Jika role bukan user, akses ditolak (403 Forbidden) dan eksekusi dihentikan.
// ==============================================================================
if ($_SESSION['role'] !== 'user') {
    http_response_code(403);
    die("403 — Access Denied: Anda tidak memiliki akses ke halaman ini.");
}

// Fetch some books for the "Popular" / Recent section
$books = mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC LIMIT 4");

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<main class="main-content">
    <?php require_once 'includes/topbar.php'; ?>

    <!-- Banner -->
    <div class="hero-banner">
        <div class="hero-content">
            <h1>Hi, <?= htmlspecialchars($_SESSION['username']) ?></h1>
            <p>The library serves as a welcoming home for knowledge seekers and avid readers alike. Explore our vast collection today.</p>
            <a href="books.php" class="hero-btn">Learn more</a>
        </div>

    </div>

    <div class="row">
        <!-- Left Column: Books Grid -->
        <div class="col-lg-8">
            <div class="section-header">
                <h4>Popular Books</h4>
                <a href="books.php" class="view-all">VIEW ALL</a>
            </div>
            
            <div class="row g-4 mb-4">
                <?php while($b = mysqli_fetch_assoc($books)): 
                    // Random color for placeholder image background to match the colorful 3D look
                    $colors = ['#f0f3ff', '#fff0f5', '#f0fff4', '#fff9f0'];
                    $color = $colors[array_rand($colors)];
                ?>
                <div class="col-md-6 col-xl-4">
                    <div class="book-card">
                        <div class="book-img-wrapper" style="background-color: <?= $color ?>;">
                            <?php if (!empty($b['cover_image'])): ?>
                                <img src="assets/img/book_covers/<?= htmlspecialchars($b['cover_image']) ?>" alt="<?= htmlspecialchars($b['title']) ?>">
                            <?php else: ?>
                                <i class="bi bi-book-half"></i>
                                <div class="book-actions">
                                    <i class="bi bi-bookmark"></i>
                                    <i class="bi bi-heart"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="book-title" title="<?= htmlspecialchars($b['title']) ?>">
                            <?= htmlspecialchars($b['title']) ?>
                        </div>
                        <p class="book-author">This is just a general example...</p>
                    </div>
                </div>
                <?php endwhile; ?>
                
                <?php if(mysqli_num_rows($books) == 0): ?>
                    <div class="col-12 text-center text-muted my-5">Belum ada buku.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Column: Sidebar Panels -->
        <div class="col-lg-4">
            <div class="side-panel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Session Status</h6>
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input" type="checkbox" checked disabled>
                    </div>
                </div>
                <p class="panel-sub mb-3">Goal achieved success unlocked.</p>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="user-profile me-3" style="width:32px; height:32px; font-size:0.8rem;">U</div>
                    <div class="flex-grow-1">
                        <div style="font-size: 0.8rem; font-weight: 600;">Active</div>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: 86%;"></div>
                        </div>
                    </div>
                    <div class="ms-3" style="font-size: 0.75rem; color: var(--text-muted);">Timeout 15m</div>
                </div>
            </div>

            <div class="side-panel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Security Info</h6>
                    <a href="#" class="view-all">VIEW ALL</a>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-btn me-3" style="width:36px; height:36px; background:#fef2f2; color:#ef4444;">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-size: 0.85rem; font-weight: 600;">Password Hashing</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Bcrypt Encrypted</div>
                    </div>
                    <span class="badge bg-light text-dark">Secure</span>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-btn me-3" style="width:36px; height:36px; background:#f0fdf4; color:#22c55e;">
                        <i class="bi bi-database"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-size: 0.85rem; font-weight: 600;">SQL Injection Safe</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Prepared Stmt</div>
                    </div>
                    <span class="badge bg-light text-dark">Secure</span>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>