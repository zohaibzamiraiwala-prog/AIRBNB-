<?php
// db.php - Database connection file
 
$host = 'localhost'; // Assuming local or default host, change if needed
$dbname = 'dbsnsxgmjusoha';
$user = 'unkuodtm3putf';
$pass = 'htk2glkxl4n4';
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
