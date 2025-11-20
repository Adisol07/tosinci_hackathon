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

function test()
{
    $db = create_db();
    $result = $db->query("SELECT * FROM users WHERE id = '1'");
    while ($row = $result->fetch_assoc()) {
        $user = $row["username"];
    }
    $result->free();
    $db->close();
    return $user;
}
