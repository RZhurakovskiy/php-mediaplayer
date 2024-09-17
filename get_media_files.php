<?php 
ini_set('display_errors', 1); 
error_reporting(E_ALL);

require_once 'db_connection.php'; 

global $pdo;

try {
    $query = "SELECT file_name, file_type FROM media_files ORDER BY order_num ASC"; 
    $stmt = $pdo->prepare($query);
    $stmt->execute(); 
    
    $media_files = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    
    echo json_encode($media_files);
} catch (PDOException $e) {
    die("Ошибка запроса: " . $e->getMessage()); 
}
?>