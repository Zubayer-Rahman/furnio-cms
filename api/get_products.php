<?php
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json; charset=utf-8');

$page = max(1, intval($_GET['page'] ?? 1));
$limit = max(1, intval($_GET['limit'] ?? 100));
$offset = ($page - 1) * $limit;

$sql = "SELECT p.id, p.name, p.slug, p.short_description, p.price, p.image, c.name as category FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ii', $limit, $offset);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

$products = [];
while ($row = mysqli_fetch_assoc($res)) {
    // Ensure full image path
    if ($row['image']) {
        $row['image'] = $row['image'];
    }
    $products[] = $row;
}
echo json_encode(['products' => $products], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
