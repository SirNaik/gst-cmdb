<?php
require_once 'includes/db_connect.php';
?><!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= GST_SYS_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand mb-0 h1">GST-CMDB</span>
  </div>
</nav>

<div class="container mt-4">
    <h1>Добро пожаловать в <?= GST_SYS_NAME ?></h1>
    <p class="text-muted">Система учёта ИТ-активов, лицензий и уведомлений.</p>
    <div class="alert alert-success">Соединение с базой данных успешно!</div>
</div>

<footer class="text-center mt-5 mb-3 text-muted">
  &copy; <?= date('Y') ?> GST-CMDB
</footer>
</body>
</html>
