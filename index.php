<?php
require_once 'config.php';
require_once 'auth/check_auth.php';
if (isset($_SESSION['user_id'])) {
  header('Location: ' . BASE_PATH . '/dashboard.php'); exit;
}
?><!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GST-CMDB</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand mb-0 h1">GST-CMDB</span>
  </div>
</nav>
<div class="container mt-4">
    <h1>Добро пожаловать в GST-CMDB</h1>
    <p class="text-muted">Система учёта ИТ-активов, лицензий и уведомлений.</p>
    <div class="alert alert-info mb-4">Пожалуйста, <a href="<?= BASE_PATH ?>/auth/login.php">войдите в систему</a> для работы с базой данных.</div>
    <div class="alert alert-secondary">Если вы только что установили систему, создайте первого администратора через <a href="<?= BASE_PATH ?>/auth/register_admin.php">эту страницу</a>.</div>
</div>
<footer class="text-center mt-5 mb-3 text-muted">
  &copy; <?= date('Y') ?> GST-CMDB
</footer>
</body>
</html>
