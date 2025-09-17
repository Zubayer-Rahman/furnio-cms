<?php
// Include necessary files
require_once 'auth.php';
require_once __DIR__ . '/../db.php';

// --- Fetch Dashboard Statistics ---

// Total Products
$total_products_query = "SELECT COUNT(*) AS total FROM products";
$total_products_result = $conn->query($total_products_query);
$total_products = $total_products_result->fetch_assoc()['total'];

// Total Categories
$total_categories_query = "SELECT COUNT(*) AS total FROM categories";
$total_categories_result = $conn->query($total_categories_query);
$total_categories = $total_categories_result->fetch_assoc()['total'];

// Total Orders
$total_orders_query = "SELECT COUNT(*) AS total FROM orders";
$total_orders_result = $conn->query($total_orders_query);
$total_orders = $total_orders_result->fetch_assoc()['total'];

// --- Fetch Recent Orders ---
$recent_orders_query = "
    SELECT 
        o.id,
        o.customer_name,
        o.order_date,
        o.total_amount,
        o.status
    FROM orders o
    ORDER BY o.order_date DESC
    LIMIT 5";
$recent_orders_result = $conn->query($recent_orders_query);

?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
  <div class="dashboard-container">
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
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
            <!-- Statistic Cards -->
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
                <div class="card">
                    <div class="card-icon"><i class="fas fa-shopping-cart"></i></div>
                    <div class="card-content">
                        <h3>Total Orders</h3>
                        <p><?php echo $total_orders; ?></p>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="section-container">
                <h2>Recent Orders</h2>
                <?php if ($recent_orders_result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = $recent_orders_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                    <td>$<?php echo htmlspecialchars(number_format($order['total_amount'], 2)); ?></td>
                                    <td><span class="status-badge <?php echo strtolower($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No recent orders found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
  </div>
</body>
</html>
<?php $conn->close(); ?>
