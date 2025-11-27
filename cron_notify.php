<?php
// GST-CMDB: ежедневная email-рассылка по лицензиям
require_once 'includes/db_connect.php';

// 1. Получить email и срок из настроек
$settings = $pdo->query('SELECT * FROM settings ORDER BY id DESC LIMIT 1')->fetch();
$emails = isset($settings['notify_emails']) ? $settings['notify_emails'] : '';
$days = isset($settings['notify_period']) ? intval($settings['notify_period']) : 30;
$emailList = array_map('trim', explode(',', $emails));
if (empty($emailList) || !$emails) die("Нет email для оповещений\n");

// 2. Найти лицензии, у которых истекает срок в заданный период
$today = date('Y-m-d');
$date_limit = date('Y-m-d', strtotime("+{$days} days"));

$stmt = $pdo->prepare('SELECT l.*, s.name as software_name, s.vendor FROM licenses l JOIN software s ON l.software_id = s.id WHERE l.status = "active" AND l.end_date <= ? AND l.end_date >= ?');
$stmt->execute([$date_limit, $today]);
$expiring = $stmt->fetchAll();

foreach ($expiring as $lic) {
    // Меняем статус на "expiring"
    $pdo->prepare('UPDATE licenses SET status = "expiring" WHERE id = ?')->execute([$lic['id']]);
    foreach ($emailList as $to) {
        $subject = "ВНИМАНИЕ: Истекает лицензия [{$lic['software_name']}]";
        $message = "Название ПО: {$lic['software_name']}\nЛицензионный ключ: {$lic['license_key']}\nДата окончания: {$lic['end_date']}\nПоставщик: {$lic['supplier']}\nПодробнее: ссылка на CMDB.";
        $headers = "From: cmdb@localhost\r\nContent-Type: text/plain; charset=UTF-8";
        mail($to, $subject, $message, $headers);
    }
}

// 3. Обновить просроченные лицензии и выслать срочные уведомления
$stmt2 = $pdo->prepare('SELECT l.*, s.name as software_name, s.vendor FROM licenses l JOIN software s ON l.software_id = s.id WHERE l.status != "expired" AND l.end_date < ?');
$stmt2->execute([$today]);
$expired = $stmt2->fetchAll();

foreach ($expired as $lic) {
    $pdo->prepare('UPDATE licenses SET status = "expired" WHERE id=?')->execute([$lic['id']]);
    foreach ($emailList as $to) {
        $subject = "СРОЧНО: Просрочена лицензия [{$lic['software_name']}]";
        $message = "Название ПО: {$lic['software_name']}\nЛицензионный ключ: {$lic['license_key']}\nДата окончания: {$lic['end_date']}\nПоставщик: {$lic['supplier']}\nПодробнее: ссылка на CMDB.";
        $headers = "From: cmdb@localhost\r\nContent-Type: text/plain; charset=UTF-8";
        mail($to, $subject, $message, $headers);
    }
}

echo "Готово. Отправлены уведомления о лицензиях.";
