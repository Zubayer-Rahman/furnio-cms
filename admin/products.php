<?php
require_once 'auth.php';
require_once __DIR__ . '/../db.php';

// fetch products
$sql = "SELECT p.id, p.name, p.price, p.image, c.name as category FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC";
$res = mysqli_query($conn, $sql);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Products</title></head>
<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 900px;
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
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        text-align: left;
        padding: 8px;
    }
    th {
        background-color: #f2f2f2;
    }
    img {
        max-width: 100px;
        height: auto;
    }

</style>
<body>
  <h1>Products</h1>
  <p><a href="add_product.php">Add new product</a> | <a href="dashboard.php">Dashboard</a></p>
  <table border="1" cellpadding="6">
    <thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Image</th><th>Actions</th></tr></thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($res)): ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td><?php echo htmlspecialchars($row['category']); ?></td>
          <td><?php echo $row['price']; ?></td>
          <td><?php if ($row['image']): ?><img src="../<?php echo htmlspecialchars($row['image']); ?>" width="80" /><?php endif; ?></td>
          <td>
            <a href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a> |
            <a href="delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
