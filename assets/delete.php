<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
if (!is_admin() && !is_operator()) {
    header('Location: index.php'); exit;
}
$id = intval($_GET['id'] ?? 0);
if (isset($_GET['confirm']) && $_GET['confirm']=='yes') {
    $stmt = $pdo->prepare('DELETE FROM equipment WHERE id = ?');
    $stmt->execute([$id]);
    header('Location: index.php?success=del'); exit;
}
// Текст подтверждения
?><!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><title>Удалить оборудование</title><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"></head><body class="p-4"><div class="container"><div class="alert alert-danger">Вы уверены, что хотите удалить оборудование ID <?=$id?>?<br><a href="?id=<?=$id?>&confirm=yes" class="btn btn-danger">Удалить</a> <a href="index.php" class="btn btn-secondary">Отмена</a></div></div></body></html>
