<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
include '../templates/header.php';
function rus_date($d) { if(!$d) return ''; $dt=DateTime::createFromFormat('Y-m-d',$d); return $dt?$dt->format('d.m.Y'):htmlspecialchars($d); }
// Поиск
$search = trim($_GET['q'] ?? '');
$status_filter = $_GET['status'] ?? '';
$success = $_GET['success'] ?? '';
$sql = 'SELECT * FROM equipment WHERE 1';
$params = [];
if ($search) {
    $sql .= ' AND (name LIKE ? OR model LIKE ? OR serial_number LIKE ? OR inventory_number LIKE ?)';
    $w = "%$search%";
    array_push($params, $w, $w, $w, $w);
}
if ($status_filter) {
    $sql .= ' AND status = ?';
    $params[] = $status_filter;
}
$sql .= ' ORDER BY id DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();
function status_badge($status) {
  if ($status=='active') return '<span class="badge bg-success">В работе</span>';
  elseif ($status=='reserve') return '<span class="badge bg-warning text-dark">В резерве</span>';
  else return '<span class="badge bg-secondary">Списан</span>';
}
?>
<h2>Оборудование</h2>
<?php if($success=='del'): ?><div class="alert alert-success">Запись удалена.</div><?php endif; ?>
<form class="row g-2 mb-3">
  <div class="col-auto"><input type="text" name="q" value="<?=htmlspecialchars($search)?>" class="form-control" placeholder="Поиск по имени, модели, SN..."></div>
  <div class="col-auto">
    <select name="status" class="form-select">
      <option value="">- любой статус -</option>
      <option value="active" <?=$status_filter=='active'?'selected':''?>>В работе</option>
      <option value="reserve" <?=$status_filter=='reserve'?'selected':''?>>В резерве</option>
      <option value="decommissioned" <?=$status_filter=='decommissioned'?'selected':''?>>Списан</option>
    </select>
  </div>
  <div class="col-auto"><button class="btn btn-primary">Найти</button></div>
  <div class="col-auto"><a href="export_csv.php<?=http_build_query(['q'=>$search,'status'=>$status_filter])?'?q='.urlencode($search).'&status='.urlencode($status_filter):''?>" class="btn btn-outline-secondary">Экспорт в CSV</a></div>
  <?php if (is_admin() || is_operator()): ?>
  <div class="col-auto"><a href="import.php" class="btn btn-outline-success">Импорт из CSV</a></div>
  <?php endif; ?>
</form>
<a href="add.php" class="btn btn-success mb-3">Добавить оборудование</a>
<div class="table-responsive">
<table class="table table-bordered table-hover mb-0">
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
            <td><?= rus_date($row['start_date']) ?></td>
            <td><?= rus_date($row['warranty']) ?></td>
            <td><?= status_badge($row['status']) ?></td>
            <td>
              <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Изм.</a>
              <a href="software.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-secondary">ПО</a>
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
