<?php
require_once 'config.php';
require_once 'session_check.php';

$isAdmin = ($_SESSION['role'] == 'admin');

// Handle search if any
$search = isset($_GET['q']) ? $_GET['q'] : '';
$query = "SELECT * FROM books ";
if($search) {
    $searchEscaped = mysqli_real_escape_string($conn, $search);
    $query .= "WHERE title LIKE '%$searchEscaped%' OR author LIKE '%$searchEscaped%' ";
}
$query .= "ORDER BY id DESC";

$result = mysqli_query($conn, $query);

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<main class="main-content">
    <!-- Topbar (customized with search form) -->
    <header class="topbar">
        <form action="" method="GET" class="search-box m-0">
            <i class="bi bi-search text-muted"></i>
            <input type="text" name="q" placeholder="Search book..." value="<?= htmlspecialchars($search) ?>">
        </form>

        <div class="topbar-right">
            <button class="btn-live">Live</button>
            <a href="#" class="icon-btn"><i class="bi bi-moon"></i></a>
            <a href="#" class="icon-btn"><i class="bi bi-bell"></i></a>
            
            <div class="dropdown">
                <div class="user-profile dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                    <?= strtoupper(substr($_SESSION['username'], 0, 1)) ?>
                </div>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                    <li><h6 class="dropdown-header">Halo, <?= htmlspecialchars($_SESSION['username']) ?></h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div>
            <h4 class="mb-1 fw-bold">Library Collection</h4>
            <p class="text-muted small m-0">Explore all available books in our catalog</p>
        </div>
        <?php if ($isAdmin): ?>
        <a href="add_book.php" class="btn btn-action">
            <i class="bi bi-plus-circle me-1"></i> Add New Book
        </a>
        <?php endif; ?>
    </div>

    <div class="row g-4 mb-4">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): 
                $colors = ['#f0f3ff', '#fff0f5', '#f0fff4', '#fff9f0', '#f4f0ff'];
                $color = $colors[array_rand($colors)];
            ?>
            <div class="col-sm-6 col-md-4 col-xl-3">
                <div class="book-card position-relative">
                    <div class="book-img-wrapper" style="background-color: <?= $color ?>;">
                        <?php if (!empty($row['cover_image'])): ?>
                            <img src="assets/img/book_covers/<?= htmlspecialchars($row['cover_image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                        <?php else: ?>
                            <i class="bi bi-book-half"></i>
                            <div class="book-actions">
                                <i class="bi bi-bookmark"></i>
                                <i class="bi bi-heart"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="book-title" title="<?= htmlspecialchars($row['title']) ?>">
                        <?= htmlspecialchars($row['title']) ?>
                    </div>
                    <p class="book-author"><?= htmlspecialchars($row['author']) ?></p>
                    
                    <?php if ($isAdmin): ?>
                    <div class="mt-3 pt-3 border-top d-flex gap-2">
                        <a href="edit_book.php?id=<?= intval($row['id']) ?>" class="btn btn-sm btn-light text-primary flex-grow-1" style="font-size: 0.8rem; font-weight: 600; text-align: center;">
                            Edit
                        </a>
                        <form method="POST" action="delete_book.php" class="flex-grow-1" onsubmit="return confirm('Yakin ingin menghapus buku ini?');">
                            <input type="hidden" name="id" value="<?= intval($row['id']) ?>">
                            <button type="submit" class="btn btn-sm btn-light text-danger w-100" style="font-size: 0.8rem; font-weight: 600;">
                                Delete
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="text-center py-5" style="background: white; border-radius: 20px; box-shadow: var(--shadow-sm);">
                    <i class="bi bi-journal-x" style="font-size: 4rem; color: var(--border-color);"></i>
                    <h5 class="mt-3">No Books Found</h5>
                    <p class="text-muted">Try adjusting your search or add a new book.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>