<?php
require_once '../includes/db_connect.php';
session_start();

// Проверка: если пользователь уже есть, перенаправить на логин
$count = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
if ($count > 0) {
    header('Location: login.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = trim($_POST['email']);
    if (strlen($username)<3 || strlen($password)<6) {
        $error = 'Минимум 3 символа логин и минимум 6 пароль.';
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('INSERT INTO users (username,password,email,role) VALUES (?,?,?,?)');
        $stmt->execute([$username,$hash,$email,'admin']);
        header('Location: login.php?registered=1');
        exit;
    }
}
?><!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Создание администратора — GST-CMDB</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Первый запуск: создать администратора</div>
                <div class="card-body">
                    <?php if($error): ?><div class="alert alert-danger"><?=$error?></div><?php endif; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Логин администратора</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Пароль</label>
                            <input type="password" name="password" class="form-control" required autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-success w-100">Создать аккаунт администратора</button>
                    </form>
                </div>
            </div>
            <p class="text-center mt-2 text-muted small">GST-CMDB &copy; <?=date('Y')?></p>
        </div>
    </div>
</div>
</body></html>
