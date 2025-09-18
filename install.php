#installation of the CMS
<!-- http://localhost/furnio-cms/install.php -->

<?php
// install.php - run once
$rootUser = 'root';      // default XAMPP
$rootPass = '';          // default XAMPP blank
$host = '127.0.0.1';
$dbName = 'furnio_db';

try {
    $pdo = new PDO("mysql:host=$host", $rootUser, $rootPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $sql = file_get_contents(__DIR__ . '/install.sql');
    if ($sql === false) {
        echo "Cannot find install.sql. Make sure it's in the same folder as install.php";
        exit;
    }

    // Run SQL
    $pdo->exec($sql);
    echo "<p>Database and tables created.</p>";

    // Create initial admin user
    $username = 'admin';
    $password = 'admin'; // change this after login
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT id FROM furnio_db.users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo "<p>Admin user already exists.</p>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO furnio_db.users (username, password_hash, role) VALUES (?, ?, 'admin')");
        $stmt->execute([$username, $hash]);
        echo "<p>Created admin user: <strong>$username</strong> with password: <strong>$password</strong></p>";
    }

    echo "<p>Remove or secure install.php now.</p>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
