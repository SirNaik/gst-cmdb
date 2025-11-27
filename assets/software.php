<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
include '../templates/header.php';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM equipment WHERE id = ?');
$stmt->execute([$id]);
$equipment = $stmt->fetch();
if (!$equipment) {
    echo "<div class='alert alert-warning'>Оборудование не найдено</div>";
    include '../templates/footer.php';
    exit;
}

// При изменении связей
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (is_admin() || is_operator())) {
    $pdo->prepare('DELETE FROM equipment_software WHERE equipment_id = ?')->execute([$id]);
    if (!empty($_POST['software_ids'])) {
        foreach ($_POST['software_ids'] as $soft_id) {
            $pdo->prepare('INSERT INTO equipment_software (equipment_id, software_id) VALUES (?, ?)')->execute([$id, $soft_id]);
        }
    }
    header('Location: software.php?id=' . $id); exit;
}

// Список всего ПО
$all_software = $pdo->query('SELECT id, name FROM software ORDER BY name')->fetchAll();
// Уже назначенное ПО
$used = $pdo->prepare('SELECT software_id FROM equipment_software WHERE equipment_id = ?');
$used->execute([$id]);
$used_ids = array_column($used->fetchAll(), 'software_id');
?>
<h2>Установленное ПО на оборудовании</h2>
<p><b>Оборудование:</b> <?=htmlspecialchars($equipment['name'])?> (ID: <?=$equipment['id']?>)</p>
<form method="post">
  <?php if (is_admin() || is_operator()): ?>
  <label for="software_ids">Назначить ПО:</label>
  <select multiple name="software_ids[]" id="software_ids" class="form-select" size="8">
    <?php foreach ($all_software as $sw): ?>
      <option value="<?=$sw['id']?>" <?=in_array($sw['id'],$used_ids)?'selected':''?>><?=htmlspecialchars($sw['name'])?></option>
    <?php endforeach; ?>
  </select>
  <button class="btn btn-primary mt-2">Сохранить список ПО</button>
  <?php else: ?>
    <div class="alert alert-info">Только просмотр</div>
  <?php endif; ?>
</form>
<?php if ($used_ids): ?>
<hr>
<h5>Данный компьютер/устройство использует:</h5>
<ul>
  <?php foreach ($all_software as $sw) {
            if (in_array($sw['id'],$used_ids))
              echo '<li>'.htmlspecialchars($sw['name']).'</li>';
        }
  ?>
</ul>
<?php endif; ?>
<a href="index.php" class="btn btn-secondary mt-3">Назад к оборудованию</a>
<?php include '../templates/footer.php'; ?>
