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

function get_marks($user_id, $topK)
{
    $db = create_db();
    $result = $db->query("SELECT * FROM marks WHERE user_id = '$user_id' ORDER BY id DESC LIMIT $topK");
    $marks = [];
    while ($row = $result->fetch_assoc()) {
        $marks[] = $row;
    }
    $result->free();
    $db->close();
    return $marks;
}

function get_mark_count($user_id)
{
    $db = create_db();
    $result = $db->query("SELECT COUNT(*) AS total_count FROM marks WHERE user_id = '$user_id'");
    while ($row = $result->fetch_assoc()) {
        $total_count = $row['total_count'];
    }
    $result->free();
    $db->close();
    return $total_count;
}

function is_password_correct($username, $password)
{
    $mysqli = create_db();
    $result = $mysqli->query("SELECT * FROM users WHERE username = '$username'");
    while ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            return true;
        }
    }
    $result->free();
    $mysqli->close();
    return false;
}

function get_practice($id)
{
    $db = create_db();
    $result = $db->query("SELECT * FROM practices WHERE id = '$id'");
    while ($row = $result->fetch_assoc()) {
        $practice = $row;
    }
    $result->free();
    $db->close();
    return $practice;
}

function get_practice_questions($id)
{
    $db = create_db();
    $result = $db->query("SELECT * FROM practice_questions WHERE practice_id = '$id'");
    $questions = [];
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
    $result->free();
    $db->close();
    return $questions;
}

function get_practices($subject)
{
    $db = create_db();
    $result = $db->query("SELECT * FROM practices WHERE subject = '$subject'");
    $practices = [];
    while ($row = $result->fetch_assoc()) {
        $practices[] = $row;
    }
    $result->free();
    $db->close();
    return $practices;
}

function get_materials($subject)
{
    $db = create_db();
    $result = $db->query("SELECT * FROM materials WHERE subject = '$subject'");
    $materials = [];
    while ($row = $result->fetch_assoc()) {
        $materials[] = $row;
    }
    $result->free();
    $db->close();
    return $materials;
}

function select_materials_by_owner($user_id, $original)
{
    $materials = [];

    for ($i = 0; $i < count($original); $i++) {
        if ($original[$i]['owner_id'] == $user_id) {
            $materials[] = $original[$i];
        }
    }

    return $materials;
}

function add_xp_from_practice($id, $user)
{
    $db = create_db();
    $stmt = $db->prepare("SELECT xp FROM practices WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $stmt2 = $db->prepare("UPDATE users SET xp = xp + ? WHERE username = ?");
        $stmt2->bind_param("is", $row['xp'], $user);
        $stmt2->execute();
        $stmt2->close();
    }

    $stmt->close();
    $db->close();
}
