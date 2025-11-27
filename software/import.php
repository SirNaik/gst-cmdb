<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
include '../templates/header.php';
if (!is_admin() && !is_operator()) { echo "<div class='alert alert-danger'>Нет прав.</div>"; include '../templates/footer.php'; exit; }
$error = $msg = '';
if (!empty($_FILES['csvfile']['tmp_name'])) {
    $f = fopen($_FILES['csvfile']['tmp_name'],'r');
    $head = fgetcsv($f, 2000, ',');
    $required = ['name','vendor','type'];
    if (array_map('strtolower',$head)!= $required) {
        $error = 'Неверная структура файла (ожидается: name,vendor,type)';
    } else {
        $count = $errors = 0;
        while($row=fgetcsv($f,2000,',')) {
            $data = array_combine($required, $row);
            try {
                $pdo->prepare("INSERT INTO software (name, vendor, type) VALUES (?, ?, ?)")
                    ->execute([$data['name'],$data['vendor'],$data['type']]);
                $count++;
            } catch(Exception $e) { $errors++; }
        }
        $msg = "Импорт завершён: добавлено $count записей, ошибок $errors.";
    }
    fclose($f);
}
?>
<h3>Импорт ПО из CSV</h3>
<?php if($error): ?><div class="alert alert-danger"><?=$error?></div><?php endif; ?>
<?php if($msg): ?><div class="alert alert-success"><?=$msg?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="mb-3">
  <label class="form-label">Файл (CSV, с заголовками):</label> 
  <input type="file" name="csvfile" accept=".csv" required class="form-control w-50 mb-2">
  <button class="btn btn-primary">Загрузить</button>
  <a href="sample.csv" class="btn btn-link">Пример файла</a>
</form>
<ul><li>Заголовки: name,vendor,type</li><li>Тип: os, app, dbms</li></ul>
<a href="index.php" class="btn btn-secondary mt-3">Назад к ПО</a>
<?php include '../templates/footer.php'; ?>
