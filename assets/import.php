<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
include '../templates/header.php';
if (!is_admin() && !is_operator()) { echo "<div class='alert alert-danger'>Нет прав.</div>"; include '../templates/footer.php'; exit; }
$error = $msg = '';
if (!empty($_FILES['csvfile']['tmp_name'])) {
    $f = fopen($_FILES['csvfile']['tmp_name'],'r');
    $head = fgetcsv($f, 2000, ',');
    $required = ['type','name','model','serial_number','inventory_number','cpu','ram','hdd','os','start_date','warranty','status'];
    if (array_map('strtolower',$head)!=$required) {
        $error = 'Неверная структура файла (ожидается: '.implode(', ',$required).')';
    } else {
        $count = $errors = 0;
        while($row=fgetcsv($f,2000,',')) {
            $data = array_combine($required, $row);
            try {
                $pdo->prepare("INSERT INTO equipment (type, name, model, serial_number, inventory_number, cpu, ram, hdd, os, start_date, warranty, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)")
                    ->execute([$data['type'],$data['name'],$data['model'],$data['serial_number'],$data['inventory_number'],$data['cpu'],$data['ram'],$data['hdd'],$data['os'],$data['start_date'],$data['warranty'],$data['status']]);
                $count++;
            } catch(Exception $e) { $errors++; }
        }
        $msg = "Импорт завершён: добавлено $count записей, ошибок $errors.";
    }
    fclose($f);
}
?>
<h3>Импорт оборудования из CSV</h3>
<?php if($error): ?><div class="alert alert-danger"><?=$error?></div><?php endif; ?>
<?php if($msg): ?><div class="alert alert-success"><?=$msg?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="mb-3">
  <label class="form-label">Файл (CSV, с заголовками):</label> 
  <input type="file" name="csvfile" accept=".csv" required class="form-control w-50 mb-2">
  <button class="btn btn-primary">Загрузить</button>
  <a href="sample.csv" class="btn btn-link">Пример файла</a>
</form>
<ul><li>Все даты — в формате YYYY-MM-DD</li><li>Заголовки: type,name,model,serial_number,inventory_number,cpu,ram,hdd,os,start_date,warranty,status</li><li>Тип: server, pc, network_device. Статус: active, reserve, decommissioned.</li></ul>
<a href="index.php" class="btn btn-secondary mt-3">Назад к оборудованию</a>
<?php include '../templates/footer.php'; ?>
