<?php
require_once 'auth/check_auth.php';
require_once 'includes/db_connect.php';
include 'templates/header.php';
function plural($n,$root,$suffixes) { return $root.($n%10==1&&$n%100!=11?$suffixes[0]:in_array($n%10,[2,3,4])&&($n%100<10||$n%100>=20)?$suffixes[1]:$suffixes[2]); }
// Счётчики
$count_equipment = $pdo->query('SELECT COUNT(*) FROM equipment')->fetchColumn();
$count_software = $pdo->query('SELECT COUNT(*) FROM software')->fetchColumn();
$count_licenses = $pdo->query('SELECT COUNT(*) FROM licenses')->fetchColumn();
$count_expiring = $pdo->query('SELECT COUNT(*) FROM licenses WHERE status = "expiring"')->fetchColumn();
$count_expired = $pdo->query('SELECT COUNT(*) FROM licenses WHERE status = "expired"')->fetchColumn();
?>
<h2>Дашборд GST-CMDB</h2>
<div class="row row-cols-1 row-cols-md-3 mb-4 g-4">
  <div class="col"><div class="card text-bg-light"><div class="card-body">
    <div class="h5">Оборудование:</div>
    <div class="display-6"><?=$count_equipment?></div>
  </div></div></div>
  <div class="col"><div class="card text-bg-light"><div class="card-body">
    <div class="h5">ПО:</div>
    <div class="display-6"><?=$count_software?></div>
  </div></div></div>
  <div class="col"><div class="card text-bg-light"><div class="card-body">
    <div class="h5">Лицензии:</div>
    <div class="display-6"><?=$count_licenses?></div>
  </div></div></div>
  <div class="col"><div class="card text-bg-warning"><div class="card-body">
    <div class="h5">Лицензий истекает:</div>
    <div class="display-6"><?=$count_expiring?></div>
  </div></div></div>
  <div class="col"><div class="card text-bg-danger"><div class="card-body">
    <div class="h5">Лицензий просрочено:</div>
    <div class="display-6"><?=$count_expired?></div>
  </div></div></div>
</div>
<?php include 'templates/footer.php'; ?>
