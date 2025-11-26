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
    $stmt = $pdo->prepare("INSERT INTO software (name, vendor, type) VALUES (?, ?, ?)");
    $stmt->execute([
        $_POST['name'], $_POST['vendor'], $_POST['type']
    ]);
    header('Location: index.php'); exit;
}
?>
<h2>Добавить программное обеспечение</h2>
<form method="post">
<div class="row g-2">
    <div class="col-md-4 mb-2"><input required name="name" class="form-control" placeholder="Название"></div>
    <div class="col-md-3 mb-2"><input name="vendor" class="form-control" placeholder="Производитель"></div>
    <div class="col-md-3 mb-2">
        <select name="type" class="form-select" required>
            <option value="os">ОС</option>
            <option value="app">Приложение</option>
            <option value="dbms">СУБД</option>
        </select>
    </div>
</div>
<button class="btn btn-success mt-3">Сохранить</button>
<a href="index.php" class="btn btn-secondary mt-3">Отмена</a>
</form>
<?php include '../templates/footer.php'; ?>
