<?php
require_once 'session_check.php';
require_once 'config.php';

// ==============================================================================
// [IMPLEMENTASI 4: Role Based Access Control (RBAC)]
// Membatasi akses halaman ini HANYA untuk pengguna dengan role 'admin'.
// Jika role bukan admin, akses ditolak (403 Forbidden) dan eksekusi dihentikan.
// ==============================================================================
if ($_SESSION['role'] !== 'admin') {
    http_response_code(403);
    die("403 — Access Denied: Anda tidak memiliki akses ke halaman ini.");
}

$totalBuku  = $conn->query("SELECT COUNT(*) AS c FROM books")->fetch_assoc()['c'];
$totalUser  = $conn->query("SELECT COUNT(*) AS c FROM users WHERE role='user'")->fetch_assoc()['c'];
$totalAdmin = $conn->query("SELECT COUNT(*) AS c FROM users WHERE role='admin'")->fetch_assoc()['c'];

// Fetch all users for the admin dashboard
$users = $conn->query("SELECT id, username, role FROM users ORDER BY role, username");

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<main class="main-content">
    <?php require_once 'includes/topbar.php'; ?>

    <!-- Banner -->
    <div class="hero-banner">
        <div class="hero-content">
            <h1>Hi, <?= htmlspecialchars($_SESSION['username']) ?></h1>
            <p>Welcome to the Admin Dashboard. Manage library assets, monitor users, and oversee the entire system.</p>
            <a href="books.php" class="hero-btn">Manage Library</a>
        </div>
        <i class="bi bi-shield-lock" style="font-size: 8rem; opacity: 0.8; position: relative; z-index: 2;"></i>
    </div>

    <div class="row">
        <!-- Left Column: Users Table -->
        <div class="col-lg-8">
            <div class="section-header">
                <h4>Registered Users</h4>
                <span class="badge bg-primary rounded-pill px-3 py-2">Total: <?= $totalUser + $totalAdmin ?></span>
            </div>
            
            <div class="bg-white rounded-4 p-4 shadow-sm mb-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="text-muted" style="font-size: 0.85rem; text-transform: uppercase;">
                            <tr>
                                <th width="80" class="border-0 pb-3">ID</th>
                                <th class="border-0 pb-3">Username</th>
                                <th width="150" class="border-0 pb-3">Role</th>
                                <th width="100" class="border-0 pb-3 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $users->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td class="py-3">
                                    <span class="fw-bold text-muted">#<?= $row['id'] ?></span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="user-profile me-3 shadow-sm" style="width:36px; height:36px; font-size:0.85rem; background: <?= $row['role'] == 'admin' ? '#ede9fe' : '#eff6ff' ?>; color: <?= $row['role'] == 'admin' ? '#7c3aed' : '#3b82f6' ?>;">
                                            <?= strtoupper(substr($row['username'], 0, 1)) ?>
                                        </div>
                                        <span class="fw-bold text-dark"><?= htmlspecialchars($row['username']) ?></span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <?php if($row['role'] == 'admin'): ?>
                                        <span class="badge" style="background:#ede9fe; color:#7c3aed; padding: 6px 12px; border-radius: 8px;">
                                            <i class="bi bi-shield-lock me-1"></i> Admin
                                        </span>
                                    <?php else: ?>
                                        <span class="badge" style="background:#eff6ff; color:#3b82f6; padding: 6px 12px; border-radius: 8px;">
                                            <i class="bi bi-person me-1"></i> User
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 text-end">
                                    <button class="btn btn-sm btn-light text-danger rounded-3">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Sidebar Panels (Stats) -->
        <div class="col-lg-4">
            <div class="side-panel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">System Overview</h6>
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input" type="checkbox" checked disabled>
                    </div>
                </div>
                <p class="panel-sub mb-3">Current statistics of LibSecure</p>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="user-profile me-3" style="width:32px; height:32px; font-size:0.8rem; background:#ede9fe; color:#7c3aed;">
                        <i class="bi bi-book"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-size: 0.8rem; font-weight: 600;">Total Books</div>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar" style="width: 100%; background:#7c3aed;"></div>
                        </div>
                    </div>
                    <div class="ms-3" style="font-size: 0.85rem; font-weight:700;"><?= $totalBuku ?></div>
                </div>

                <div class="d-flex align-items-center mb-3">
                    <div class="user-profile me-3" style="width:32px; height:32px; font-size:0.8rem; background:#eff6ff; color:#3b82f6;">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-size: 0.8rem; font-weight: 600;">Total Users</div>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar" style="width: 100%; background:#3b82f6;"></div>
                        </div>
                    </div>
                    <div class="ms-3" style="font-size: 0.85rem; font-weight:700;"><?= $totalUser ?></div>
                </div>

                <div class="d-flex align-items-center mb-3">
                    <div class="user-profile me-3" style="width:32px; height:32px; font-size:0.8rem; background:#f0fdf4; color:#22c55e;">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-size: 0.8rem; font-weight: 600;">Active Admins</div>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar" style="width: 100%; background:#22c55e;"></div>
                        </div>
                    </div>
                    <div class="ms-3" style="font-size: 0.85rem; font-weight:700;"><?= $totalAdmin ?></div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="side-panel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="add_book.php" class="btn btn-action w-100 mb-2">
                        <i class="bi bi-plus-circle me-2"></i> Add New Book
                    </a>
                    <a href="books.php" class="btn btn-outline-primary w-100 border-2" style="border-radius: 12px; font-weight: 600;">
                        View All Books
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>