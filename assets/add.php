<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
include '../templates/header.php';

if (!is_admin() && !is_operator()) {
    echo "<div class='alert alert-danger'>Доступ запрещён</div>";
    include '../templates/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO equipment (type, name, model, serial_number, inventory_number, cpu, ram, hdd, os, start_date, warranty, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $_POST['type'], $_POST['name'], $_POST['model'], $_POST['serial_number'],
        $_POST['inventory_number'], $_POST['cpu'], $_POST['ram'], $_POST['hdd'],
        $_POST['os'], $_POST['start_date'], $_POST['warranty'], $_POST['status']
    ]);
    header('Location: index.php'); exit;
}
?>
<h2>Добавить оборудование</h2>
<form method="post">
<div class="row g-2">
    <div class="col-md-3 mb-2"><input required name="name" class="form-control" placeholder="Название"></div>
    <div class="col-md-2 mb-2"><input name="model" class="form-control" placeholder="Модель"></div>
    <div class="col-md-2 mb-2"><input name="serial_number" class="form-control" placeholder="Серийный номер"></div>
    <div class="col-md-2 mb-2"><input name="inventory_number" class="form-control" placeholder="Инвентарный номер"></div>
    <div class="col-md-2 mb-2"><input name="cpu" class="form-control" placeholder="CPU"></div>
    <div class="col-md-2 mb-2"><input name="ram" class="form-control" placeholder="RAM"></div>
    <div class="col-md-2 mb-2"><input name="hdd" class="form-control" placeholder="HDD"></div>
    <div class="col-md-2 mb-2"><input name="os" class="form-control" placeholder="ОС"></div>
    <div class="col-md-2 mb-2"><input type="date" name="start_date" class="form-control" placeholder="Дата ввода"></div>
    <div class="col-md-2 mb-2"><input type="date" name="warranty" class="form-control" placeholder="Гарантия"></div>
    <div class="col-md-2 mb-2">
        <select name="type" class="form-select" required>
            <option value="server">Сервер</option>
            <option value="pc">ПК</option>
            <option value="network_device">Сетевое устройство</option>
        </select>
    </div>
    <div class="col-md-2 mb-2">
        <select name="status" class="form-select">
            <option value="active">В работе</option>
            <option value="reserve">В резерве</option>
            <option value="decommissioned">Списан</option>
        </select>
    </div>
</div>
<button class="btn btn-success mt-3">Сохранить</button>
<a href="index.php" class="btn btn-secondary mt-3">Отмена</a>
</form>
<?php include '../templates/footer.php'; ?>
