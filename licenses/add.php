<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
include '../templates/header.php';

if (!is_admin() && !is_operator()) {
    echo "<div class='alert alert-danger'>Доступ запрещён</div>";
    include '../templates/footer.php';
    exit;
}
// Получить список ПО для выбора
$soft = $pdo->query('SELECT id, name FROM software ORDER BY name')->fetchAll();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO licenses (software_id, license_key, license_type, start_date, end_date, price, supplier, status) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $_POST['software_id'], $_POST['license_key'], $_POST['license_type'],
        $_POST['start_date'], $_POST['end_date'], $_POST['price'],
        $_POST['supplier'], $_POST['status']
    ]);
    header('Location: index.php'); exit;
}
?>
<h2>Добавить лицензию</h2>
<form method="post">
<div class="row g-2">
    <div class="col-md-4 mb-2">
        <select name="software_id" class="form-select" required>
            <option value="">Выберите ПО</option>
            <?php foreach ($soft as $s): ?>
            <option value="<?=$s['id']?>"><?=htmlspecialchars($s['name'])?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-4 mb-2"><input name="license_key" class="form-control" placeholder="Лицензионный ключ" required></div>
    <div class="col-md-2 mb-2">
        <select name="license_type" class="form-select" required>
            <option value="perpetual">Пожизненная</option>
            <option value="subscription">Подписка</option>
        </select>
    </div>
    <div class="col-md-2 mb-2"><input type="date" name="start_date" class="form-control" placeholder="Дата начала" required></div>
    <div class="col-md-2 mb-2"><input type="date" name="end_date" class="form-control" placeholder="Дата окончания" required></div>
    <div class="col-md-2 mb-2"><input name="price" class="form-control" placeholder="Стоимость" type="number" min="0" step="0.01"></div>
    <div class="col-md-3 mb-2"><input name="supplier" class="form-control" placeholder="Поставщик"></div>
    <div class="col-md-2 mb-2">
        <select name="status" class="form-select">
            <option value="active">Активна</option>
            <option value="expiring">Истекает</option>
            <option value="expired">Просрочена</option>
        </select>
    </div>
</div>
<button class="btn btn-success mt-3">Сохранить</button>
<a href="index.php" class="btn btn-secondary mt-3">Отмена</a>
</form>
<?php include '../templates/footer.php'; ?>
