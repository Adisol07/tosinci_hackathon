<?php
include_once('../config.php');
include_once('../database.php');

if (isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] != '' && $_POST['password'] != '') {
    $name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $is_password_correct = is_password_correct($name, $password);
    if ($is_password_correct) {
        $_SESSION['user'] = $name;
        header('Location: ' . URL . '/profile');
        exit;
    } else {
        header('Location: ' . URL . '/login?error=' . urlencode('Neplatné jméno nebo heslo.'));
    }
} else {
    header('Location: ' . URL . '/login?error=' . urlencode('Chybí jméno nebo heslo.'));
}
