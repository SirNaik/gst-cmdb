<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /auth/login.php');
    exit;
}
// функция проверки роли
function is_admin() {
    return !empty($_SESSION['role']) && $_SESSION['role']=='admin';
}
function is_operator() {
    return !empty($_SESSION['role']) && $_SESSION['role']=='operator';
}
function is_guest() {
    return !empty($_SESSION['role']) && $_SESSION['role']=='guest';
}
