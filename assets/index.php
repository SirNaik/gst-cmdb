<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
include '../templates/header.php';

$stmt = $pdo->query('SELECT * FROM equipment ORDER BY id DESC');
$items = $stmt->fetchAll();
?>
<h2>Оборудование</h2>
<a href="add.php" class="btn btn-success mb-3">Добавить оборудование</a>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Тип</th>
            <th>Название</th>
            <th>Модель</th>
            <th>Сер. номер</th>
            <th>Инв. номер</th>
            <th>CPU</th>
            <th>RAM</th>
            <th>HDD</th>
            <th>ОС</th>
            <th>Ввод в эксплуатацию</th>
            <th>Гарантия</th>
            <th>Статус</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['type']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['model']) ?></td>
            <td><?= htmlspecialchars($row['serial_number']) ?></td>
            <td><?= htmlspecialchars($row['inventory_number']) ?></td>
            <td><?= htmlspecialchars($row['cpu']) ?></td>
            <td><?= htmlspecialchars($row['ram']) ?></td>
            <td><?= htmlspecialchars($row['hdd']) ?></td>
            <td><?= htmlspecialchars($row['os']) ?></td>
            <td><?= $row['start_date'] ?></td>
            <td><?= $row['warranty'] ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Изм.</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../templates/footer.php'; ?>
