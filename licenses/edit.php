<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
include '../templates/header.php';

if (!is_admin() && !is_operator()) {
    echo "<div class='alert alert-danger'>Доступ запрещён</div>";
    include '../templates/footer.php';
    exit;
}
$id = intval($_GET['id'] ?? 0);
$item = $pdo->prepare('SELECT * FROM licenses WHERE id = ?');
$item->execute([$id]);
$item = $item->fetch();
if (!$item) {
    echo "<div class='alert alert-warning'>Лицензия не найдена</div>";
    include '../templates/footer.php';
    exit;
}
$soft = $pdo->query('SELECT id, name FROM software ORDER BY name')->fetchAll();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE licenses SET software_id=?, license_key=?, license_type=?, start_date=?, end_date=?, price=?, supplier=?, status=? WHERE id=?");
    $stmt->execute([
        $_POST['software_id'], $_POST['license_key'], $_POST['license_type'],
        $_POST['start_date'], $_POST['end_date'], $_POST['price'],
        $_POST['supplier'], $_POST['status'], $id
    ]);
    header('Location: index.php'); exit;
}
?>
<h2>Редактировать лицензию</h2>
<form method="post">
<div class="row g-2">
    <div class="col-md-4 mb-2">
        <select name="software_id" class="form-select" required>
            <option value="">Выберите ПО</option>
            <?php foreach ($soft as $s): ?>
            <option value="<?=$s['id']?>" <?=$item['software_id']==$s['id']?'selected':''?>><?=htmlspecialchars($s['name'])?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-4 mb-2"><input name="license_key" class="form-control" value="<?=htmlspecialchars($item['license_key'])?>" required></div>
    <div class="col-md-2 mb-2">
        <select name="license_type" class="form-select" required>
            <option value="perpetual" <?=$item['license_type']=='perpetual'?'selected':''?>>Пожизненная</option>
            <option value="subscription" <?=$item['license_type']=='subscription'?'selected':''?>>Подписка</option>
        </select>
    </div>
    <div class="col-md-2 mb-2"><input type="date" name="start_date" class="form-control" value="<?=$item['start_date']?>" required></div>
    <div class="col-md-2 mb-2"><input type="date" name="end_date" class="form-control" value="<?=$item['end_date']?>" required></div>
    <div class="col-md-2 mb-2"><input name="price" class="form-control" value="<?=$item['price']?>" type="number" min="0" step="0.01"></div>
    <div class="col-md-3 mb-2"><input name="supplier" class="form-control" value="<?=htmlspecialchars($item['supplier'])?>"></div>
    <div class="col-md-2 mb-2">
        <select name="status" class="form-select">
            <option value="active" <?=$item['status']=='active'?'selected':''?>>Активна</option>
            <option value="expiring" <?=$item['status']=='expiring'?'selected':''?>>Истекает</option>
            <option value="expired" <?=$item['status']=='expired'?'selected':''?>>Просрочена</option>
        </select>
    </div>
</div>
<button class="btn btn-primary mt-3">Сохранить</button>
<a href="index.php" class="btn btn-secondary mt-3">Отмена</a>
</form>
<?php include '../templates/footer.php'; ?>
