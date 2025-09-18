<?php
session_start();
require_once __DIR__ . '/../db.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare($conn, "SELECT id, password_hash, role FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $password_hash, $role);
    if (mysqli_stmt_fetch($stmt)) {
        if (password_verify($password, $password_hash)) {
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_username'] = $username;
            $_SESSION['admin_role'] = $role;
            header('Location: dashboard.php');
            exit;
        } else {
            $msg = 'Invalid credentials.';
        }
    } else {
        $msg = 'Invalid credentials.';
    }
    mysqli_stmt_close($stmt);
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Admin Login</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 400px;
        margin: 100px auto;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    form {
        width: 100%;
        background-color: aliceblue;
        padding: 30px;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    input {
        display: block;
        margin: 10px 0;
        padding: 8px;
        width: 100%;
        height: 45px;
        border: none;
        border-radius: 12px;
        box-sizing: border-box;
    }
    input:focus {
        outline: 1px solid rgba(0, 123, 255, 0.5);
    }

    button {
        padding: 15px 20px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: bold;
    }
</style>

<body>
    <h2>Admin Login</h2>
    <?php if ($msg) echo "<p style='color:red;'>$msg</p>"; ?>
    <form method="post">
        <input name="username" placeholder="username" required />
        <input name="password" type="password" placeholder="password" required />
        <button type="submit">Login</button>
    </form>
</body>

</html>