<?php
require_once 'auth.php';
require_once __DIR__ . '/../db.php';
// Handle adding a new category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $category_name = $conn->real_escape_string($_POST['category_name']);
    $sql = "INSERT INTO categories (name) VALUES ('$category_name')";
    $conn->query($sql);
    header("Location: categories.php"); // Refresh the page
    exit();
}

// Handle deleting a category
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $sql = "DELETE FROM categories WHERE id=$delete_id";
    $conn->query($sql);
    header("Location: categories.php"); // Refresh the page
    exit();
}

// Fetch all categories from the database
$categories_result = $conn->query("SELECT * FROM categories ORDER BY id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
</head>
<style>
    body {
        font-family: 'Inter', sans-serif;
        margin: 20px;
    }
    h1 {
        font-size: 28px;
        margin-bottom: 20px;
        color: #333;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 2px;
        border-bottom: 2px solid #007BFF;
        padding-bottom: 10px;
    }
    h2{
        font-size: 24px;
        margin-top: 40px;
        color: #555;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        border-bottom: 1px solid #ccc;
        padding-bottom: 8px;
    }
    form {
        margin-bottom: 30px;
        text-align: center;
        display: flex;
        flex-direction: column;
        gap: 20px;
        justify-self: center;
    }
    input[type="text"] {
        padding: 8px;
        width: 250px;
        margin-right: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    button {
        padding: 8px 16px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    table {
        width: 50%;
        border-collapse: collapse;
        margin-top: 20px;
        margin-left: auto;
        margin-right: auto;
    }
    th, td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }
    th {
        background-color: #f4f4f4;
    }
    a {
        color: #007BFF;
        text-decoration: none;
    }
    a:hover {
        text-decoration: underline;
    }
</style>
<body>

<h1>Manage Product Categories</h1>

<!-- Form to Add a New Category -->
<form action="categories.php" method="post">
    <label for="category_name">New Category:</label>
    <input type="text" name="category_name" required>
    <button type="submit" name="add_category">Add Category</button>
</form>

<h2>Existing Categories</h2>

<!-- Display Existing Categories -->
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $categories_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><a href="categories.php?delete_id=<?php echo $row['id']; ?>">Delete</a></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>

<?php $conn->close(); ?>
