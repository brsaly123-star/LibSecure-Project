<?php
require_once 'session_check.php';
require_once 'config.php';

// ==============================================================================
// [IMPLEMENTASI 4: Role Based Access Control (RBAC)]
// Membatasi fitur Tambah Buku HANYA untuk pengguna dengan role 'admin'.
// Jika role bukan admin, akses ditolak dan eksekusi dihentikan.
// ==============================================================================
if ($_SESSION['role'] !== 'admin') {
    http_response_code(403);
    die("Access Denied");
}

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $coverImage = '';

    $uploadDir = __DIR__ . '/assets/img/book_covers/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['cover_image']['error'] !== UPLOAD_ERR_OK) {
            $errorMessage = 'Terjadi kesalahan saat mengunggah gambar.';
        } else {
            $allowedMimeTypes = [
                'image/jpeg' => 'jpg',
                'image/pjpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
            ];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $_FILES['cover_image']['tmp_name']);
            finfo_close($finfo);

            if (!array_key_exists($mimeType, $allowedMimeTypes)) {
                $errorMessage = 'Format gambar tidak valid. Gunakan JPG, PNG, atau GIF.';
            } else {
                $extension = $allowedMimeTypes[$mimeType];
                $coverImage = uniqid('cover_', true) . '.' . $extension;
                $destination = $uploadDir . $coverImage;

              if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], $destination)) {
                    // Jika server menolak simpan gambar di cloud, berikan gambar bawaan kosong agar database tetap jalan!
                    $coverImage = 'default_cover.jpg'; 
                }
            }
        }
    }

    if (empty($errorMessage)) {
        $stmt = $conn->prepare("INSERT INTO books(title, author, cover_image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $author, $coverImage);
        $stmt->execute();

        header("Location: books.php");
        exit();
    }
}

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<main class="main-content">
    <?php require_once 'includes/topbar.php'; ?>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card" style="border: none; border-radius: 20px; box-shadow: var(--shadow-sm); padding: 30px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1 fw-bold">Add New Book</h4>
                        <p class="text-muted small m-0">Fill in the details to add a new book to the library.</p>
                    </div>
                    <a href="books.php" class="icon-btn" style="background: var(--bg-body);">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>

                <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger mb-4" role="alert">
                    <?= htmlspecialchars($errorMessage) ?>
                </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold" style="color: var(--text-muted); font-size: 0.85rem;">Book Title</label>
                        <input type="text" class="form-control form-control-custom" id="title" name="title" placeholder="Enter book title" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="author" class="form-label fw-bold" style="color: var(--text-muted); font-size: 0.85rem;">Author Name</label>
                        <input type="text" class="form-control form-control-custom" id="author" name="author" placeholder="Enter author's name" required>
                    </div>

                    <div class="mb-4">
                        <label for="cover_image" class="form-label fw-bold" style="color: var(--text-muted); font-size: 0.85rem;">Cover Image</label>
                        <input type="file" class="form-control form-control-custom" id="cover_image" name="cover_image" accept="image/*">
                        <div class="form-text">Optional. Upload JPG, PNG, atau GIF untuk cover buku.</div>
                    </div>
                    
                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-action" style="padding: 14px; font-size: 1rem;">
                            Save Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>