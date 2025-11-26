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
$stmt = $pdo->prepare('SELECT * FROM software WHERE id = ?');
$stmt->execute([$id]);
$item = $stmt->fetch();
if (!$item) {
    echo "<div class='alert alert-warning'>Элемент не найден</div>";
    include '../templates/footer.php';
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE software SET name=?, vendor=?, type=? WHERE id=?");
    $stmt->execute([
        $_POST['name'], $_POST['vendor'], $_POST['type'], $id
    ]);
    header('Location: index.php'); exit;
}
?>
<h2>Редактировать ПО</h2>
<form method="post">
<div class="row g-2">
    <div class="col-md-4 mb-2"><input required name="name" class="form-control" value="<?=htmlspecialchars($item['name'])?>"></div>
    <div class="col-md-3 mb-2"><input name="vendor" class="form-control" value="<?=htmlspecialchars($item['vendor'])?>"></div>
    <div class="col-md-3 mb-2">
        <select name="type" class="form-select" required>
            <option value="os" <?=$item['type']=='os'?'selected':''?>>ОС</option>
            <option value="app" <?=$item['type']=='app'?'selected':''?>>Приложение</option>
            <option value="dbms" <?=$item['type']=='dbms'?'selected':''?>>СУБД</option>
        </select>
    </div>
</div>
<button class="btn btn-primary mt-3">Сохранить</button>
<a href="index.php" class="btn btn-secondary mt-3">Отмена</a>
</form>
<?php include '../templates/footer.php'; ?>
