<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $upload_directory = 'uploads1/';
    $file_path = $upload_directory . basename($file['name']);

    try {
        if (!move_uploaded_file($file['tmp_name'], $file_path)) {
            throw new Exception("Ошибка загрузки файла: не удалось переместить загруженный файл.");
        }

        $file_type = strpos($file['type'], 'video') !== false ? 'video' : 'image';
        $current_datetime = date('Y-m-d H:i:s'); 

        require_once 'db_connection.php';

        $stmt = $pdo->prepare("INSERT INTO media_files (file_name, file_type, uploaded_at) VALUES (?, ?, ?)");
        if (!$stmt->execute([$file_path, $file_type, $current_datetime])) {
            throw new PDOException("Ошибка выполнения запроса: некорректный запрос.");
        }

        echo "Файл успешно загружен";
        header("Location: index.php");
        exit();

    } catch (Exception $e) {
        $error_message = $e->getMessage();
        error_log($error_message, 3, 'error_log.log');
        echo $error_message;
    }
}

?>
