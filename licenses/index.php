<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
include '../templates/header.php';
function rus_date($d) { if(!$d) return ''; $dt=DateTime::createFromFormat('Y-m-d',$d); return $dt?$dt->format('d.m.Y'):htmlspecialchars($d); }
$search = trim($_GET['q'] ?? '');
$status_filter = $_GET['status'] ?? '';
$success = $_GET['success'] ?? '';
$sql = 'SELECT l.*, s.name as software_name, l.software_id FROM licenses l JOIN software s ON l.software_id = s.id WHERE 1';
$params = [];
if ($search) {
    $sql .= ' AND (s.name LIKE ? OR l.license_key LIKE ? OR l.supplier LIKE ?)';
    $w = "%$search%";
    array_push($params, $w, $w, $w);
}
if ($status_filter) {
    $sql .= ' AND l.status = ?';
    $params[] = $status_filter;
}
$sql .= ' ORDER BY l.id DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();
?>
<h2>Лицензии</h2>
<?php if($success=='del'): ?><div class="alert alert-success">Запись удалена.</div><?php endif; ?>
<form class="row g-2 mb-3">
  <div class="col-auto"><input type="text" name="q" value="<?=htmlspecialchars($search)?>" class="form-control" placeholder="Поиск по ПО, ключу, поставщику..."></div>
  <div class="col-auto">
    <select name="status" class="form-select">
      <option value="">- любой статус -</option>
      <option value="active" <?=$status_filter=='active'?'selected':''?>>Активна</option>
      <option value="expiring" <?=$status_filter=='expiring'?'selected':''?>>Истекает</option>
      <option value="expired" <?=$status_filter=='expired'?'selected':''?>>Просрочена</option>
    </select>
  </div>
  <div class="col-auto"><button class="btn btn-primary">Найти</button></div>
  <div class="col-auto"><a href="export_csv.php<?=http_build_query(['q'=>$search,'status'=>$status_filter])?'?q='.urlencode($search).'&status='.urlencode($status_filter):''?>" class="btn btn-outline-secondary mb-3">Экспорт в CSV</a></div>
  <?php if (is_admin() || is_operator()): ?>
  <div class="col-auto"><a href="import.php" class="btn btn-outline-success mb-3">Импорт из CSV</a></div>
  <?php endif; ?>
</form>
<a href="add.php" class="btn btn-success mb-3">Добавить лицензию</a>
<div class="table-responsive">
<table class="table table-bordered table-hover mb-0">
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
        <tr class="<?= $row['status']=='expired'?'table-danger':($row['status']=='expiring'?'table-warning':'') ?>">
            <td><?= $row['id'] ?></td>
            <td><a href="../software/equipment.php?id=<?= $row['software_id'] ?>"><?= htmlspecialchars($row['software_name']) ?></a></td>
            <td><?= htmlspecialchars($row['license_key']) ?></td>
            <td><?= htmlspecialchars($row['license_type']) ?></td>
            <td><?= rus_date($row['start_date']) ?></td>
            <td><?= rus_date($row['end_date']) ?></td>
            <td>
            <?php if ($row['status']=='expired'): ?>
              <span class="badge bg-danger">Просрочена</span>
            <?php elseif ($row['status']=='expiring'): ?>
              <span class="badge bg-warning text-dark">Истекает</span>
            <?php else: ?>
              <span class="badge bg-success">Активна</span>
            <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['supplier']) ?></td>
            <td><?= $row['price'] ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Изм.</a>
                <?php if (is_admin() || is_operator()): ?>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить?');">Удалить</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php include '../templates/footer.php'; ?>
