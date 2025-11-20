<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/credentials.php';

function create_db()
{
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
    if ($mysqli->connect_error) {
        die("Connect Error: " . $mysqli->connect_error);
    }
    if (!$mysqli->set_charset('utf8mb4')) {
        die("Failed to set charset: " . $mysqli->error);
    }
    return $mysqli;
}

function get_user($username)
{
    $db = create_db();
    $result = $db->query("SELECT * FROM users WHERE username = '$username'");
    while ($row = $result->fetch_assoc()) {
        $user = $row;
    }
    $result->free();
    $db->close();
    return $user;
}
