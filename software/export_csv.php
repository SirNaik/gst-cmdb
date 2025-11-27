<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="software_export_'.date('Ymd_His').'.csv"');
$output = fopen('php://output', 'w');
fputcsv($output, ['ID','Название','Производитель','Тип']);
$search = trim($_GET['q'] ?? '');
$type_filter = $_GET['type'] ?? '';
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
foreach ($stmt->fetchAll() as $row) {
    fputcsv($output, [
        $row['id'], $row['name'], $row['vendor'], $row['type']
    ]);
}
fclose($output);
