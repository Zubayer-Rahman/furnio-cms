<?php
require_once 'auth.php';
require_once __DIR__ . '/../db.php';
$id = intval($_GET['id'] ?? 0);
if ($id) {
    // optionally remove image file
    $res = mysqli_query($conn, "SELECT image FROM products WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
    if ($row && $row['image']) {
        $file = __DIR__ . '/../' . $row['image'];
        if (file_exists($file))
            @unlink($file);
    }
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
}
header('Location: products.php');
exit;
