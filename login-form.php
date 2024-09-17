<?php 
if(isset($_SERVER['HTTP_COOKIE'])) {
    session_start(); 
    $error = isset($_SESSION['error']) ? $_SESSION['error'] : ''; 
    unset($_SESSION['error']); 
}

$sessionName = session_name();

if(isset($_COOKIE[$sessionName])) {
    header("Location: index.php");
}

?> 
<!DOCTYPE html> 
<html lang="ru"> 
<head> 
    <meta charset="UTF-8"> 
    <title>Авторизация</title> 
    <style> 
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .form-title {
            text-align: center;
            color: #333;
            font-size: 22px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px 20px;
            font-size: 22px;
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
        button:hover {
            background-color: #334d84;
        }
        .error { 
            color: red;
            text-align: center;
            margin-top: 15px;
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
            text-align: center;
            line-height: 1.4rem;
        }
        nav a:hover {
            background-color: #0056b3;
        }
        nav a.active {
            background-color: #6c757d;
        } 
    </style> 
</head> 
<body> 
    
    <div class="container">   
    <h1 class="form-title">Авторизация пользователя</h1>
    <?php if (isset($_GET['error'])): ?>
        <p style="color:red;"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>
    <form action="login.php" method="post" >
        <label for="username" class="form-group">Имя пользователя:</label>
        <input type="text" id="username" name="username"  class="form-group" required>
        <br>
        <label for="password" class="form-group">Пароль:</label>
        <input type="password" id="password" name="password"  class="form-group" required>
        <br>
        <button type="submit">Войти</button>
        <nav> 
            <a href="client_view.php">Перейти в проигрыватель без авторизации</a> 
        </nav> 
    </form>
        
    </div>
</body> 
</html>
