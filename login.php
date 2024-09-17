<?php

$correctUsername = 'admin';
$correctPassword = 'admin';


$username = $_POST['username'];
$password = $_POST['password'];


if ($username === $correctUsername && $password === $correctPassword) {
 
    session_start();

    
    header('Location: index.php');
    exit();
} else {

    $error = 'Неверный логин или пароль!';
    header("Location: login-form.php?error=" . urlencode($error));
    exit();
}
?>
