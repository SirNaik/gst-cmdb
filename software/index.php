<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
include '../templates/header.php';
function type_badge($type) {
  if ($type=='os') return '<span class="badge bg-primary">ОС</span>';
  elseif ($type=='dbms') return '<span class="badge bg-purple" style="background:#6f42c1">СУБД</span>';
  else return '<span class="badge bg-secondary">Приложение</span>';
}
$search = trim($_GET['q'] ?? '');
$type_filter = $_GET['type'] ?? '';
$success = $_GET['success'] ?? '';
$sql = 'SELECT * FROM software WHERE 1';
$params = [];
if ($search) {
    $sql .= ' AND (name LIKE ? OR vendor LIKE ?)';
    $w = "%$search%";
    $params = [$w, $w];
}
if ($type_filter) {
    $sql .= ' AND type = ?';
    $params[] = $type_filter;
}
$sql .= ' ORDER BY id DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();
?>
<h2>Программное обеспечение</h2>
<?php if($success=='del'): ?><div class="alert alert-success">Запись удалена.</div><?php endif; ?>
<form class="row g-2 mb-3">
  <div class="col-auto"><input type="text" name="q" value="<?=htmlspecialchars($search)?>" class="form-control" placeholder="Поиск по названию или производителю"></div>
  <div class="col-auto">
    <select name="type" class="form-select">
      <option value="">- любой тип -</option>
      <option value="os" <?=$type_filter=='os'?'selected':''?>>ОС</option>
      <option value="app" <?=$type_filter=='app'?'selected':''?>>Приложение</option>
      <option value="dbms" <?=$type_filter=='dbms'?'selected':''?>>СУБД</option>
    </select>
  </div>
  <div class="col-auto"><button class="btn btn-primary">Найти</button></div>
  <div class="col-auto">
    <a href="export_csv.php<?=http_build_query(['q'=>$search,'type'=>$type_filter])?'?q='.urlencode($search).'&type='.urlencode($type_filter):''?>" class="btn btn-outline-secondary">Экспорт в CSV</a>
  </div>
  <?php if (is_admin() || is_operator()): ?>
    <div class="col-auto"><a href="import.php" class="btn btn-outline-success">Импорт из CSV</a></div>
  <?php endif; ?>
</form>
<a href="add.php" class="btn btn-success mb-3">Добавить ПО</a>
<div class="table-responsive">
<table class="table table-bordered table-hover mb-0">
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
            <td><?= type_badge($row['type']) ?></td>
            <td>
              <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Изм.</a>
              <a href="equipment.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-secondary">Устройства</a>
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
