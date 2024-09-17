<?php 
$host = "localhost"; 
$port = "5432"; 
$dbname = "postgres"; 
$user = "postgres"; 
$password = "aufv2x6n";

try { 
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname"; 
    $pdo = new PDO($dsn, $user, $password); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) { 
    die("Ошибка соединения: " . $e->getMessage()); 
} 
?>
