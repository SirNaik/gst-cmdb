<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
include '../templates/header.php';

$stmt = $pdo->query('SELECT l.*, s.name as software_name FROM licenses l JOIN software s ON l.software_id = s.id ORDER BY l.id DESC');
$items = $stmt->fetchAll();
?>
<h2>Лицензии</h2>
<a href="add.php" class="btn btn-success mb-3">Добавить лицензию</a>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>ПО</th>
            <th>Ключ</th>
            <th>Тип</th>
            <th>Начало</th>
            <th>Окончание</th>
            <th>Статус</th>
            <th>Поставщик</th>
            <th>Стоимость</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['software_name']) ?></td>
            <td><?= htmlspecialchars($row['license_key']) ?></td>
            <td><?= htmlspecialchars($row['license_type']) ?></td>
            <td><?= $row['start_date'] ?></td>
            <td><?= $row['end_date'] ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['supplier']) ?></td>
            <td><?= $row['price'] ?></td>
            <td><a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Изм.</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../templates/footer.php'; ?>
