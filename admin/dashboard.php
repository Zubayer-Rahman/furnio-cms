<?php
require_once 'auth.php';
require_once __DIR__ . '/../db.php';

// Fetch Total Products and Total Categories only
$total_products_query = "SELECT COUNT(*) AS total FROM products";
$total_products_result = $conn->query($total_products_query);
$total_products = $total_products_result->fetch_assoc()['total'];

$total_categories_query = "SELECT COUNT(*) AS total FROM categories";
$total_categories_result = $conn->query($total_categories_query);
$total_categories = $total_categories_result->fetch_assoc()['total'];

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<style>

</style>

<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="products.php"><i class="fas fa-box"></i> Manage Products</a></li>
                <li><a href="categories.php"><i class="fas fa-tags"></i> Manage Categories</a></li>
                <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="top-bar">
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></h1>
            </div>
            <div class="dashboard-content">
                <div class="card-container">
                    <div class="card">
                        <div class="card-icon"><i class="fas fa-box"></i></div>
                        <div class="card-content">
                            <h3>Total Products</h3>
                            <p><?php echo $total_products; ?></p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-icon"><i class="fas fa-tags"></i></div>
                        <div class="card-content">
                            <h3>Total Categories</h3>
                            <p><?php echo $total_categories; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php $conn->close(); ?>