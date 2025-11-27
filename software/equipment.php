<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
include '../templates/header.php';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM software WHERE id = ?');
$stmt->execute([$id]);
$software = $stmt->fetch();
if (!$software) {
    echo "<div class='alert alert-warning'>ПО не найдено</div>";
    include '../templates/footer.php';
    exit;
}
// Получить все устройства, где установлено данное ПО
$q = $pdo->prepare('SELECT e.* FROM equipment_software es JOIN equipment e ON es.equipment_id = e.id WHERE es.software_id=?');
$q->execute([$id]);
$devices = $q->fetchAll();
?>
<h2>Устройства с установленным ПО</h2>
<p><b>Программное обеспечение:</b> <?=htmlspecialchars($software['name'])?> (ID: <?=$software['id']?>)</p>
<?php if ($devices): ?>
<ul>
<?php foreach ($devices as $dev): ?>
  <li><?=htmlspecialchars($dev['name'])?> (<?=$dev['type']?>), инв.№ <?=htmlspecialchars($dev['inventory_number'])?></li>
<?php endforeach; ?>
</ul>
<?php else: ?>
<div class="alert alert-info">На данный момент это ПО не установлено ни на одном устройстве.</div>
<?php endif; ?>
<a href="index.php" class="btn btn-secondary mt-3">Назад к ПО</a>
<?php include '../templates/footer.php'; ?>
