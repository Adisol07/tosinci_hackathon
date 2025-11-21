<?php
include_once('../config.php');
include_once('../database.php');

session_start();
$username = $_SESSION['user'];
if (!isset($username)) {
    header('Location: ' . URL . '/login');
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? -1;
if ($id === -1) {
    header('Location: ' . URL . '/profile');
    exit;
}

add_xp_from_practice($id, $username);
header('Location: ' . URL . '/profile');
exit;
