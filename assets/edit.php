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
$stmt = $pdo->prepare('SELECT * FROM equipment WHERE id = ?');
$stmt->execute([$id]);
$item = $stmt->fetch();
if (!$item) {
    echo "<div class='alert alert-warning'>Элемент не найден</div>";
    include '../templates/footer.php';
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE equipment SET type=?, name=?, model=?, serial_number=?, inventory_number=?, cpu=?, ram=?, hdd=?, os=?, start_date=?, warranty=?, status=? WHERE id = ?");
    $stmt->execute([
        $_POST['type'], $_POST['name'], $_POST['model'], $_POST['serial_number'],
        $_POST['inventory_number'], $_POST['cpu'], $_POST['ram'], $_POST['hdd'],
        $_POST['os'], $_POST['start_date'], $_POST['warranty'], $_POST['status'], $id
    ]);
    header('Location: index.php'); exit;
}
?>
<h2>Редактировать оборудование</h2>
<form method="post">
<div class="row g-2">
    <div class="col-md-3 mb-2"><input required name="name" class="form-control" value="<?=htmlspecialchars($item['name'])?>"></div>
    <div class="col-md-2 mb-2"><input name="model" class="form-control" value="<?=htmlspecialchars($item['model'])?>"></div>
    <div class="col-md-2 mb-2"><input name="serial_number" class="form-control" value="<?=htmlspecialchars($item['serial_number'])?>"></div>
    <div class="col-md-2 mb-2"><input name="inventory_number" class="form-control" value="<?=htmlspecialchars($item['inventory_number'])?>"></div>
    <div class="col-md-2 mb-2"><input name="cpu" class="form-control" value="<?=htmlspecialchars($item['cpu'])?>"></div>
    <div class="col-md-2 mb-2"><input name="ram" class="form-control" value="<?=htmlspecialchars($item['ram'])?>"></div>
    <div class="col-md-2 mb-2"><input name="hdd" class="form-control" value="<?=htmlspecialchars($item['hdd'])?>"></div>
    <div class="col-md-2 mb-2"><input name="os" class="form-control" value="<?=htmlspecialchars($item['os'])?>"></div>
    <div class="col-md-2 mb-2"><input type="date" name="start_date" class="form-control" value="<?=$item['start_date']?>"></div>
    <div class="col-md-2 mb-2"><input type="date" name="warranty" class="form-control" value="<?=$item['warranty']?>"></div>
    <div class="col-md-2 mb-2">
        <select name="type" class="form-select" required>
            <option value="server" <?=$item['type']=='server'?'selected':''?>>Сервер</option>
            <option value="pc" <?=$item['type']=='pc'?'selected':''?>>ПК</option>
            <option value="network_device" <?=$item['type']=='network_device'?'selected':''?>>Сетевое устройство</option>
        </select>
    </div>
    <div class="col-md-2 mb-2">
        <select name="status" class="form-select">
            <option value="active" <?=$item['status']=='active'?'selected':''?>>В работе</option>
            <option value="reserve" <?=$item['status']=='reserve'?'selected':''?>>В резерве</option>
            <option value="decommissioned" <?=$item['status']=='decommissioned'?'selected':''?>>Списан</option>
        </select>
    </div>
</div>
<button class="btn btn-primary mt-3">Сохранить</button>
<a href="index.php" class="btn btn-secondary mt-3">Отмена</a>
</form>
<?php include '../templates/footer.php'; ?>
