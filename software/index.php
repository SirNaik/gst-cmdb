<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
include '../templates/header.php';

$stmt = $pdo->query('SELECT * FROM software ORDER BY id DESC');
$items = $stmt->fetchAll();
?>
<h2>Программное обеспечение</h2>
<a href="add.php" class="btn btn-success mb-3">Добавить ПО</a>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Производитель</th>
            <th>Тип</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['vendor']) ?></td>
            <td><?= htmlspecialchars($row['type']) ?></td>
            <td><a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Изм.</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../templates/footer.php'; ?>
