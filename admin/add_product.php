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
        $msg = 'Product added successfully!';
    } else {
        $msg = 'Error: ' . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Add Product</title>
  <link rel="stylesheet" href="add_products.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="active"><a href="products.php"><i class="fas fa-box"></i> Manage Products</a></li>
            <li><a href="categories.php"><i class="fas fa-tags"></i> Manage Categories</a></li>
            <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <div class="top-bar">
          <h1>Add New Product</h1>
      </div>

      <div class="dashboard-content">
        <div class="section-container">
          <?php if ($msg): ?>
            <div class="alert <?php echo (strpos($msg, 'Error') !== false) ? 'alert-danger' : 'alert-success'; ?>">
                <?php echo htmlspecialchars($msg); ?>
            </div>
          <?php endif; ?>

          <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="short_description">Short Description:</label>
                <textarea name="short_description" id="short_description" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" rows="5"></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input name="price" id="price" type="number" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock:</label>
                <input name="stock" id="stock" type="number" value="0">
            </div>
            <div class="form-group">
                <label for="category_id">Category:</label>
                <select name="category_id" id="category_id">
                  <option value="">--none--</option>
                  <?php while ($c = mysqli_fetch_assoc($catRes)) : ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                  <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <input name="image" id="image" type="file" accept="image/*">
            </div>
            <button type="submit" class="button"><i class="fas fa-plus"></i> Add Product</button>
          </form>
          <p class="mt-20"><a href="products.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to products</a></p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
<?php mysqli_close($conn); ?>
