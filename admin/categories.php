<?php
require_once 'auth.php';
require_once __DIR__ . '/../db.php';

// Handle adding a new category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
  // Sanitize input to prevent SQL injection (improved security)
  $category_name = mysqli_real_escape_string($conn, trim($_POST['category_name']));
  if (!empty($category_name)) {
    // Use prepared statements for safer database interaction
    $stmt = mysqli_prepare($conn, "INSERT INTO categories (name) VALUES (?)");
    mysqli_stmt_bind_param($stmt, 's', $category_name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
  header("Location: categories.php");
  exit();
}

// Handle deleting a category
if (isset($_GET['delete_id'])) {
  $delete_id = intval($_GET['delete_id']);
  // Use prepared statements for safer database interaction
  $stmt = mysqli_prepare($conn, "DELETE FROM categories WHERE id = ?");
  mysqli_stmt_bind_param($stmt, 'i', $delete_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  header("Location: categories.php");
  exit();
}

// Fetch all categories from the database
$categories_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY id");
?>

<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Manage Categories</title>
  <link rel="stylesheet" href="categories.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <h2>Admin Panel</h2>
      <ul>
        <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="products.php"><i class="fas fa-box"></i> Manage Products</a></li>
        <li class="active"><a href="categories.php"><i class="fas fa-tags"></i> Manage Categories</a></li>
        <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <div class="top-bar">
        <h1>Manage Categories</h1>
      </div>

      <div class="dashboard-content">
        <div class="section-container">
          <!-- Form to Add a New Category -->
          <form action="categories.php" method="post" class="form-container">
            <div class="form-group-inline">
              <label for="category_name">New Category:</label>
              <input type="text" name="category_name" id="category_name" required>
              <button type="submit" name="add_category" class="button"><i class="fas fa-plus"></i> Add Category</button>
            </div>
          </form>

          <h2>Existing Categories</h2>

          <!-- Display Existing Categories -->
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($categories_result)): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['id']); ?></td>
                  <td><?php echo htmlspecialchars($row['name']); ?></td>
                  <td>
                    <a href="categories.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this category?')" class="action-link delete-link">Delete</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
          <?php mysqli_free_result($categories_result); ?>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
<?php mysqli_close($conn); ?>