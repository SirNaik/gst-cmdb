<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/../config.php';
$base = defined('BASE_PATH') ? BASE_PATH : '/gst';
?><!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= defined('GST_SYS_NAME') ? GST_SYS_NAME : 'GST-CMDB' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <?php
    $css_file = dirname(__DIR__) . '/public/css/style.css';
    $css_url = $base . '/public/css/style.css';
    ?>
    <link rel="stylesheet" href="<?= $css_url ?>?v=<?= file_exists($css_file) ? filemtime($css_file) : time() ?>" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= $base ?>/dashboard.php">GST-CMDB</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="topNav">
      <?php if (isset($_SESSION['user_id'])): ?>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="<?= $base ?>/dashboard.php">Дашборд</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $base ?>/assets/index.php">Оборудование</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $base ?>/software/index.php">ПО</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $base ?>/licenses/index.php">Лицензии</a></li>
          <?php if (is_admin()): ?>
            <li class="nav-item"><a class="nav-link" href="<?= $base ?>/settings/index.php">Настройки</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $base ?>/logs/index.php">Журнал</a></li>
          <?php endif; ?>
        </ul>
        <div class="d-flex align-items-center gap-3">
          <span class="text-muted mb-0">Роль: <strong><?=htmlspecialchars($_SESSION['role'])?></strong></span>
          <a href="<?= $base ?>/auth/logout.php" class="btn btn-primary btn-sm">Выход</a>
        </div>
      <?php else: ?>
        <a href="<?= $base ?>/auth/login.php" class="btn btn-primary btn-sm">Вход</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<div class="page-content container">
