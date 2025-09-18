<?php
require_once 'auth.php';
require_once __DIR__ . '/../db.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
  header('Location: products.php');
  exit;
}

$msg = '';
$catRes = mysqli_query($conn, "SELECT id, name FROM categories ORDER BY name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $name));
  $short = $_POST['short_description'] ?? '';
  $desc = $_POST['description'] ?? '';
  $price = floatval($_POST['price']);
  $category_id = $_POST['category_id'] ?: null;
  $stock = intval($_POST['stock'] ?? 0);

  // handle image upload
  if (!empty($_FILES['image']['name'])) {
    $targetDir = __DIR__ . '/../uploads/';
    if (!is_dir($targetDir))
      mkdir($targetDir, 0755, true);
    $filename = time() . '_' . basename($_FILES['image']['name']);
    $targetFile = $targetDir . $filename;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
      $imagePath = 'uploads/' . $filename;
      mysqli_query($conn, "UPDATE products SET image = '" . mysqli_real_escape_string($conn, $imagePath) . "' WHERE id = $id");
    }
  }

  $stmt = mysqli_prepare($conn, "UPDATE products SET name=?, slug=?, short_description=?, description=?, price=?, category_id=?, stock=? WHERE id = ?");
  mysqli_stmt_bind_param($stmt, 'sssdsiii', $name, $slug, $short, $desc, $price, $category_id, $stock, $id);
  if (mysqli_stmt_execute($stmt))
    $msg = 'Updated';
  else
    $msg = 'Error: ' . mysqli_error($conn);
  mysqli_stmt_close($stmt);
}

// fetch product
$res = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
$product = mysqli_fetch_assoc($res);
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Edit Product</title>
</head>

<body>
  <h1>Edit Product</h1>
  <?php if ($msg)
    echo "<p>$msg</p>"; ?>
  <form method="post" enctype="multipart/form-data">
    <label>Name: <input name="name" required value="<?php echo htmlspecialchars($product['name']); ?>"></label><br>
    <label>Short description: <input name="short_description"
        value="<?php echo htmlspecialchars($product['short_description']); ?>"></label><br>
    <label>Description: <textarea
        name="description"><?php echo htmlspecialchars($product['description']); ?></textarea></label><br>
    <label>Price: <input name="price" type="number" step="0.01" required
        value="<?php echo htmlspecialchars($product['price']); ?>"></label><br>
    <label>Stock: <input name="stock" type="number"
        value="<?php echo htmlspecialchars($product['stock']); ?>"></label><br>
    <label>Category:
      <select name="category_id">
        <option value="">--none--</option>
        <?php
        mysqli_data_seek($catRes, 0);
        while ($c = mysqli_fetch_assoc($catRes)): ?>
          <option value="<?php echo $c['id']; ?>" <?php echo ($product['category_id'] == $c['id']) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($c['name']); ?></option>
        <?php endwhile; ?>
      </select>
    </label><br>
    <div>Current image:
      <?php if ($product['image']): ?>
        <img src="../<?php echo htmlspecialchars($product['image']); ?>" width="120" />
      <?php endif; ?>
    </div>
    <label>Replace image: <input name="image" type="file" accept="image/*"></label><br>
    <button type="submit">Save</button>
  </form>
  <p><a href="products.php">Back to products</a></p>
</body>

</html>