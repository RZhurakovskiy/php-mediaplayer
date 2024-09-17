<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'db_connection.php';


    $id = intval($_POST['id']);

    try {
        $sql = "SELECT file_name FROM media_files WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $file_name = $stmt->fetchColumn();

        $sql = "DELETE FROM media_files WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$id])) {
            if (file_exists($file_name)) { 
                unlink($file_name); 
            }

            // updateFileOrder($pdo);

            echo "Файл успешно удалён.";
        } else {
            echo "Ошибка при удалении файла: " . $stmt->errorInfo()[2];
        }

        header("Location: index.php");
        exit();

    } catch (PDOException $e) {
        echo "Ошибка при выполнении запросов к базе данных: " . $e->getMessage();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
