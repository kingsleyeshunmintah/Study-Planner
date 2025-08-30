<?php
// Database configuration
$host = 'localhost';
$dbname = 'study';
$username = 'root';
$password = '';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<script> alert('Database connected successfully!');</script>";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$conn = new mysqli($host, $username, $password);
if ($conn->connect_error) {
    handleError("Connection failed: " . $conn->connect_error);
}
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if (!$conn->query($sql)) {
    handleError("Database creation failed: " . $conn->error);
}else{echo "<script> alert('Database already exist'); </script>";}

// Select database
$conn->select_db($dbname);
?> 