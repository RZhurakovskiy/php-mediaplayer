<?php
require 'db_connection.php';

// JSON даннве с клиента
$orderData = json_decode(file_get_contents('php://input'), true);

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    if (is_array($orderData)) {
        foreach ($orderData as $orderItem) {
            $id = intval($orderItem['id']);
            $orderNum = intval($orderItem['order_num']);
            $stmt = $pdo->prepare("UPDATE media_files SET order_num = ? WHERE id = ?");
            $stmt->execute([$orderNum, $id]);
        }

        $pdo->commit();
        echo json_encode(['status' => 'success']);
    } else {
        throw new Exception('Некорректные данные');
    }
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
