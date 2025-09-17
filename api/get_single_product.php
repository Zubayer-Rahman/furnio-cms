<?php
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json; charset=utf-8');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$slug = $_GET['slug'] ?? '';

if (!$id && !$slug) {
    echo json_encode(['error' => 'No id or slug provided']);
    exit;
}

if ($id) {
    $sql = "SELECT p.*, c.name as category FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
} else {
    $sql = "SELECT p.*, c.name as category FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.slug = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $slug);
}
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($res);
if (!$row) {
    echo json_encode(['error' => 'Product not found']);
    exit;
}
echo json_encode($row, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
