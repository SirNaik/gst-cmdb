<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
include '../templates/header.php';
if (!is_admin()) { echo '<div class="alert alert-danger">Только для администратора.</div>'; include '../templates/footer.php'; exit; }
$stmt = $pdo->query('SELECT l.*, u.username FROM logs l LEFT JOIN users u ON l.user_id=u.id ORDER BY l.id DESC LIMIT 50');
?>
<h2>Журнал действий</h2>
<table class="table table-bordered table-hover">
  <thead><tr><th>Пользователь</th><th>Действие</th><th>Описание</th><th>Время</th></tr></thead>
  <tbody>
    <?php foreach ($stmt as $row): ?>
    <tr>
      <td><?=htmlspecialchars($row['username']?:'-')?></td>
      <td><?=htmlspecialchars($row['action'])?></td>
      <td><?=htmlspecialchars($row['details'])?></td>
      <td><?=date('d.m.Y H:i',strtotime($row['created_at']))?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php include '../templates/footer.php'; ?>
