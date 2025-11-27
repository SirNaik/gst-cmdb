<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
include '../templates/header.php';
if (!is_admin() && !is_operator()) { echo "<div class='alert alert-danger'>Нет прав."; include '../templates/footer.php'; exit; }
$error = $msg = '';
if (!empty($_FILES['csvfile']['tmp_name'])) {
    $f = fopen($_FILES['csvfile']['tmp_name'],'r');
    $head = fgetcsv($f, 2000, ',');
    $required = ['software_id','license_key','license_type','start_date','end_date','price','supplier','status'];
    if (array_map('strtolower',$head)!=$required) {
        $error = 'Неверная структура файла (ожидается: '.implode(',',$required).')';
    } else {
        $count = $errors = 0;
        while($row=fgetcsv($f,2000,',')) {
            $data = array_combine($required, $row);
            try {
                $pdo->prepare("INSERT INTO licenses (software_id,license_key,license_type,start_date,end_date,price,supplier,status) VALUES (?,?,?,?,?,?,?,?)")
                    ->execute([$data['software_id'],$data['license_key'],$data['license_type'],$data['start_date'],$data['end_date'],$data['price'],$data['supplier'],$data['status']]);
                $count++;
            } catch(Exception $e) {
                $errors++;
            }
        }
        $msg = "Импорт завершён: добавлено $count, ошибок $errors.";
    }
    fclose($f);
}
?>
<h3>Импорт лицензий из CSV</h3>
<?php if($error): ?><div class="alert alert-danger"><?=$error?></div><?php endif; ?>
<?php if($msg): ?><div class="alert alert-success"><?=$msg?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="mb-3">
  <label class="form-label">Файл (CSV, с заголовками):</label>
  <input type="file" name="csvfile" accept=".csv" required class="form-control w-50 mb-2">
  <button class="btn btn-primary">Загрузить</button>
  <a href="sample.csv" class="btn btn-link">Пример файла</a>
</form>
<ul><li>Заголовки: software_id,license_key,license_type,start_date,end_date,price,supplier,status</li><li>Тип лицензии: perpetual или subscription. Статус: active, expiring, expired</li></ul>
<a href="index.php" class="btn btn-secondary mt-3">Назад к лицензиям</a>
<?php include '../templates/footer.php'; ?>
