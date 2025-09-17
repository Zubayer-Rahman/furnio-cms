<?php
require_once 'auth.php';
require_once __DIR__ . '/../db.php';

$msg = '';
// fetch categories
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
    $imagePath = '';
    if (!empty($_FILES['image']['name'])) {
        $targetDir = __DIR__ . '/../uploads/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = 'uploads/' . $filename;
        }
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO products (name, slug, short_description, description, price, image, category_id, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssssdssi', $name, $slug, $short, $desc, $price, $imagePath, $category_id, $stock);
    if (mysqli_stmt_execute($stmt)) {
        $msg = 'Product added';
    } else {
        $msg = 'Error: ' . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Add Product</title></head>
<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 600px;
        margin: 20px auto;
        padding: 0 10px;
    }
    h1{
      margin-bottom: 20px;
      font-weight: 600;
      font-size: 32px;
      color: #333;
      text-align: center;
      text-transform: uppercase;
      letter-spacing: 2px;
      border-bottom: 2px solid #007BFF;
      padding-bottom: 10px;
    }
    p{
      background-color: aliceblue;
      padding: 10px;
      border-radius: 10px;
      display: inline-block;
      margin-bottom: 20px;
    }

    p a{
      text-decoration: none;
      color: #007BFF;
      font-weight: 600;
    }
    form {
        display: flex;
        flex-direction: column;
    }
    label {
        margin-bottom: 10px;
        font-weight: 600;
    }
    input, textarea, select, button {
        padding: 8px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
        width: 100%;
        box-sizing: border-box;
    }
    button {
        background-color: #007BFF;
        color: white;
        border: none;
        cursor: pointer;
        margin-top: 20px;
    }
    button:hover {
        background-color: #0056b3;
    }
</style>

<body>
  <h1>Add Product</h1>
  <?php if ($msg) echo "<p>$msg</p>"; ?>
  <form method="post" enctype="multipart/form-data">
    <label>Name: <input name="name" required></label><br>
    <label>Short description: <input name="short_description"></label><br>
    <label>Description: <textarea name="description"></textarea></label><br>
    <label>Price: <input name="price" type="number" step="0.01" required></label><br>
    <label>Stock: <input name="stock" type="number" value="0"></label><br>
    <label>Category:
      <select name="category_id">
        <option value="">--none--</option>
        <?php while ($c = mysqli_fetch_assoc($catRes)) : ?>
          <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
        <?php endwhile; ?>
      </select>
    </label><br>
    <label>Image: <input name="image" type="file" accept="image/*"></label><br>
    <button type="submit">Add</button>
  </form>
  <p><a href="products.php">Back to products</a></p>
</body>
</html>
