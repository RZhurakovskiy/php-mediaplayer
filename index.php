<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Админ панель</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);

        }

        label {
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
            color: #333;
        }

        input[type="file"] {
            display: block;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            width: calc(100% - 22px);
        }

        button {
            display: inline-block;
            padding: 7px 12px;
            font-size: 18px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .delete-btn {
            display: inline-block;
            padding: 5px 10px;
            font-size: 12px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .exit-btn {
            display: inline-block;
            padding: 7px 12px;
            font-size: 18px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        nav {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        nav a {
            color: white;
            margin: 0 10px;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 18px;
            background-color: #007bff;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #0056b3;
        }

        nav a.active {
            background-color: #6c757d;
        }

        hr {
            color: #333;
            opacity: 0.3;
            max-width: 1200px;
            width: 100%;
            margin: 40px auto;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #007bff;
            color: #fff;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        tr:hover {
            background: #f1f1f1;
        }

        td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }

        td a {
            color: #007bff;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php

        $sessionName = session_name();

        if (!isset($_COOKIE[$sessionName])) {
            header("Location: login-form.php");
            exit();
        }
        ?>
        <div class="logout-container">
            <form action="logout.php" method="post">
                <button class="exit-btn" type="submit" class="logout">Выйти</button>
                <h1>Админ панель</h1>
            </form>
        </div>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="file">Выберите файл:</label>
            <input type="file" id="file" name="file" accept="video/*,image/*" required>
            <button type="submit">Загрузить</button>
        </form>
        <nav>
            <a href="client_view.php">Перейти в проигрыватель</a>
        </nav>
        <hr>
        <h2>Загруженные файлы:</h2>
        <table id="file-table">
            <tr>
                <th>Имя файла</th>
                <th>Дата загрузки</th>
                <th>Действия</th>
                <th>Сортировка</th>
            </tr>

            <?php
            require 'db_connection.php';

            try {

                $stmt = $pdo->prepare('SELECT * FROM media_files ORDER BY order_num ASC');
                $stmt->execute();


                $files = $stmt->fetchAll();

                
            
                if (count($files) > 0) {
                    foreach ($files as $file) {
                        $file_path = htmlspecialchars($file["file_name"]);
                        $file_name = basename($file_path);
                        $display_name = strlen($file_name) > 30 ? substr($file_name, 0, 30) . '...' : $file_name;

                        $datetime = new DateTime($file['uploaded_at']);
                        $file['uploaded_at'] = $datetime->format('d-m-Y H:i:s');

                        echo "<tr id='file-{$file['id']}'>
             
                    <td><a href='uploads/{$file_name}' title='{$file_name}' target='_blank'>{$display_name}</a></td>  
                    <td>{$file['uploaded_at']}</td>
                    <td>
                        <button class='delete-btn' onclick='deleteFile({$file['id']})'>Удалить</button>
                    </td>
                    <td>
                        <button onclick='moveFileUp({$file['id']})'>↑</button>
                        <button onclick='moveFileDown({$file['id']})'>↓</button>
                    </td>
                  </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Нет загруженных файлов</td></tr>";
                }
            } catch (PDOException $e) {
                echo "<tr><td colspan='4'>Ошибка при получении данных: " . $e->getMessage() . "</td></tr>";
            }
            ?>


        </table>

        <script>
            function deleteFile(id) {
                if (confirm('Вы действительно хотите удалить этот файл?')) {
                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', 'delete.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            let row = document.getElementById('file-' + id);
                            row.parentNode.removeChild(row);
                            alert('Файл успешно удалён.');
                        }
                    };
                    xhr.send('id=' + id);

                }

            }

            function moveFileUp(id) {
                let row = document.getElementById('file-' + id);
                let prevRow = row.previousElementSibling;
                if (prevRow && prevRow.tagName === 'TR') {
                    row.parentNode.insertBefore(row, prevRow);
                    updateOrder();
                }
            }

            function moveFileDown(id) {
                let row = document.getElementById('file-' + id);
                let nextRow = row.nextElementSibling;
                if (nextRow && nextRow.tagName === 'TR') {

                    row.parentNode.insertBefore(nextRow, row);
                    updateOrder();
                }
            }

            function updateOrder() {
                let rows = document.querySelectorAll('#file-table tr[id^="file-"]');
                let orderData = [];
                rows.forEach(function (row, index) {
                    let id = row.id.replace('file-', '');
                    orderData.push({ id: id, order_num: index + 1 });
                });

                let xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_order.php', true);
                xhr.setRequestHeader('Content-type', 'application/json');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        console.log('Порядок успешно обновлён.');
                    }
                };
                xhr.send(JSON.stringify(orderData));
            }
        </script>
    </div>
</body>

</html>