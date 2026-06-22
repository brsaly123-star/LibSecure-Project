<?php
require_once 'session_check.php';
require_once 'config.php';

if ($_SESSION['role'] !== 'admin') {
    http_response_code(403);
    die("Access Denied");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: books.php');
    exit();
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    header('Location: books.php');
    exit();
}

$stmt = $conn->prepare("SELECT cover_image FROM books WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($coverImage);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

if (!empty($coverImage)) {
    $path = __DIR__ . '/assets/img/book_covers/' . $coverImage;
    if (file_exists($path)) {
        @unlink($path);
    }
}

header('Location: books.php');
exit();
