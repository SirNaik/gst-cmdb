<?php
require_once '../auth/check_auth.php';
require_once '../includes/db_connect.php';
include '../templates/header.php';
if (!is_admin()) {
    echo "<div class='alert alert-danger'>Только для администратора.</div>"; include '../templates/footer.php'; exit;
}
$msg = '';
$s = $pdo->query('SELECT * FROM settings ORDER BY id DESC LIMIT 1')->fetch();
$emails = $s['notify_emails'] ?? '';
$period = $s['notify_period'] ?? '30';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $emails = trim($_POST['notify_emails'] ?? '');
    $period = in_array($_POST['notify_period'],['30','60','90']) ? $_POST['notify_period'] : '30';
    $stmt = $pdo->prepare($s?
        'UPDATE settings SET notify_emails=?, notify_period=? WHERE id=?'
        : 'INSERT INTO settings (notify_emails,notify_period) VALUES (?,?)');
    $params = $s ? [$emails,$period,$s['id']] : [$emails,$period];
    $stmt->execute($params);
    $msg = 'Настройки сохранены';
}
?>
<h2>Настройки уведомлений</h2>
<?php if($msg): ?><div class="alert alert-success"><?=$msg?></div><?php endif; ?>
<form method="post" class="mb-4" style="max-width:480px;">
    <label class="form-label">Email для уведомлений (через запятую):</label>
    <input type="text" name="notify_emails" class="form-control mb-2" value="<?=htmlspecialchars($emails)?>">
    <label class="form-label">Период предупреждения (дней):</label>
    <select name="notify_period" class="form-select mb-2">
        <option value="30" <?=$period=='30'?'selected':''?>>30</option>
        <option value="60" <?=$period=='60'?'selected':''?>>60</option>
        <option value="90" <?=$period=='90'?'selected':''?>>90</option>
    </select>
    <button class="btn btn-primary">Сохранить</button>
</form>
<?php include '../templates/footer.php'; ?>
