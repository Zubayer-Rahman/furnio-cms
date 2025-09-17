<?php
// db.php - include this from admin and api files
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'furnio_db';

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$conn) {
    die('DB Connection error: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');
