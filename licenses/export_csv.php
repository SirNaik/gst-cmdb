<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="licenses_export_'.date('Ymd_His').'.csv"');
$output = fopen('php://output', 'w');
fputcsv($output, ['ID','ПО','Ключ','Тип','Начало','Окончание','Статус','Поставщик','Стоимость']);
// Те же фильтры что в licenses/index.php:
$search = trim($_GET['q'] ?? '');
$status_filter = $_GET['status'] ?? '';
$sql = 'SELECT l.*, s.name as software_name FROM licenses l JOIN software s ON l.software_id = s.id WHERE 1';
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
foreach ($stmt->fetchAll() as $row) {
    fputcsv($output, [
        $row['id'], $row['software_name'], $row['license_key'],
        $row['license_type'], $row['start_date'], $row['end_date'],
        $row['status'], $row['supplier'], $row['price']
    ]);
}
fclose($output);
