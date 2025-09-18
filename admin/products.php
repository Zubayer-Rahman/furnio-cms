<?php
require_once 'auth.php';
require_once __DIR__ . '/../db.php'; // Use the correct path to your database file

// fetch products
$sql = "SELECT p.id, p.name, p.price, p.image, c.name as category FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC";
$res = mysqli_query($conn, $sql);

// Check for a query error
if (!$res) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Products</title>
    <link rel="stylesheet" href="products.css">
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
                <h1>Products</h1>
            </div>

            <div class="dashboard-content">
                <div class="section-container">
                    <p><a href="add_product.php" class="button"><i class="fas fa-plus"></i> Add new product</a></p>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($res)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                                    <td>$<?php echo htmlspecialchars(number_format($row['price'], 2)); ?></td>
                                    <td>
                                        <?php if ($row['image']): ?>
                                            <img src="../<?php echo htmlspecialchars($row['image']); ?>" width="80"
                                                alt="Product Image" />
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="edit_product.php?id=<?php echo $row['id']; ?>"
                                            class="action-link edit-link">Edit</a> |
                                        <a href="delete_product.php?id=<?php echo $row['id']; ?>"
                                            onclick="return confirm('Are you sure you want to delete this product?')"
                                            class="action-link delete-link">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php mysqli_free_result($res); ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php mysqli_close($conn); ?>