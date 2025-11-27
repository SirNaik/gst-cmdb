<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="equipment_export_'.date('Ymd_His').'.csv"');
$output = fopen('php://output', 'w');
fputcsv($output, ['ID','Тип','Название','Модель','Сер. номер','Инв. номер','CPU','RAM','HDD','ОС','Ввод в эксплуатацию','Гарантия','Статус']);
$search = trim($_GET['q'] ?? '');
$status_filter = $_GET['status'] ?? '';
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
foreach ($stmt->fetchAll() as $row) {
    fputcsv($output, [
        $row['id'],$row['type'],$row['name'],$row['model'],$row['serial_number'],$row['inventory_number'],$row['cpu'],$row['ram'],$row['hdd'],$row['os'],$row['start_date'],$row['warranty'],$row['status']
    ]);
}
fclose($output);
