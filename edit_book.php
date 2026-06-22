<?php
require_once 'session_check.php';
require_once 'config.php';

if ($_SESSION['role'] !== 'admin') {
    http_response_code(403);
    die("Access Denied");
}

$errorMessage = '';
$book = null;
$uploadDir = __DIR__ . '/assets/img/book_covers/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $existingCover = trim($_POST['existing_cover'] ?? '');
    $newCover = $existingCover;

    if ($id <= 0) {
        $errorMessage = 'ID buku tidak valid.';
    }

    if (empty($errorMessage) && isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] !== UPLOAD_ERR_NO_FILE) {
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
                $newCover = uniqid('cover_', true) . '.' . $extension;
                $destination = $uploadDir . $newCover;

                if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], $destination)) {
                    $errorMessage = 'Tidak dapat menyimpan file gambar.';
                    $newCover = $existingCover;
                }
            }
        }
    }

    if (empty($errorMessage)) {
        $stmt = $conn->prepare("SELECT cover_image FROM books WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($oldCover);
        $stmt->fetch();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, cover_image = ? WHERE id = ?");
        $stmt->bind_param('sssi', $title, $author, $newCover, $id);
        $stmt->execute();
        $stmt->close();

        if (!empty($oldCover) && $oldCover !== $newCover) {
            $oldPath = $uploadDir . $oldCover;
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        header('Location: books.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' || empty($book)) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id <= 0) {
        header('Location: books.php');
        exit();
    }

    $stmt = $conn->prepare("SELECT id, title, author, cover_image FROM books WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $stmt->close();

    if (!$book) {
        header('Location: books.php');
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
                        <h4 class="mb-1 fw-bold">Edit Book</h4>
                        <p class="text-muted small m-0">Update book details and cover image.</p>
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
                    <input type="hidden" name="id" value="<?= intval($book['id']) ?>">
                    <input type="hidden" name="existing_cover" value="<?= htmlspecialchars($book['cover_image']) ?>">

                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold" style="color: var(--text-muted); font-size: 0.85rem;">Book Title</label>
                        <input type="text" class="form-control form-control-custom" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="author" class="form-label fw-bold" style="color: var(--text-muted); font-size: 0.85rem;">Author Name</label>
                        <input type="text" class="form-control form-control-custom" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
                    </div>

                    <?php if (!empty($book['cover_image'])): ?>
                    <div class="mb-4">
                        <label class="form-label fw-bold" style="color: var(--text-muted); font-size: 0.85rem;">Current Cover</label>
                        <div class="book-img-wrapper" style="height: 180px;">
                            <img src="assets/img/book_covers/<?= htmlspecialchars($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <label for="cover_image" class="form-label fw-bold" style="color: var(--text-muted); font-size: 0.85rem;">Replace Cover Image</label>
                        <input type="file" class="form-control form-control-custom" id="cover_image" name="cover_image" accept="image/*">
                        <div class="form-text">Optional. Unggah gambar baru untuk mengganti cover yang lama.</div>
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-action" style="padding: 14px; font-size: 1rem;">
                            Update Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
