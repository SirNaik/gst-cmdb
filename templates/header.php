<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?><!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= defined('GST_SYS_NAME') ? GST_SYS_NAME : 'GST-CMDB' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/index.php">GST-CMDB</a>
    <?php if (isset($_SESSION['user_id'])): ?>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="/assets/index.php">Оборудование</a></li>
        <li class="nav-item"><a class="nav-link" href="/software/index.php">ПО</a></li>
        <li class="nav-item"><a class="nav-link" href="/licenses/index.php">Лицензии</a></li>
      </ul>
      <span class="text-light me-2">Пользователь: <b><?=htmlspecialchars($_SESSION['role'])?></b></span>
      <a href="/auth/logout.php" class="btn btn-outline-light btn-sm">Выход</a>
    <?php else: ?>
      <a href="/auth/login.php" class="btn btn-outline-light btn-sm">Вход</a>
    <?php endif; ?>
  </div>
</nav>
<div class="container mt-4">
