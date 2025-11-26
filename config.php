<?php
// GST-CMDB :: Конфигурация

$db_host = 'localhost';
$db_port = '3306';
$db_name = 'cmdbbd';
$db_user = 'cmdbuser';
$db_pass = 'cmdbpass123';

// DSN для PDO
$dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4";

// EMAIL для отправки уведомлений (можно менять в настройках админки)
define('GST_NOTIFY_EMAIL', 'admin@example.com');
// Период оповещений по умолчанию
define('GST_NOTIFY_PERIOD', 30); // дней
// Название системы
define('GST_SYS_NAME', 'GST-CMDB');
