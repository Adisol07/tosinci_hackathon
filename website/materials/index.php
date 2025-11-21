<?php include_once('../header.php'); ?>
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login/');
}

$subject = filter_input(INPUT_GET, 'subject', FILTER_SANITIZE_STRING) ?? '';
if ($subject == '') {
    header('Location: ../profile/');
}

$username = $_SESSION['user'];
$user = get_user($username);
$marks = get_marks_by_subject($subject, $user['id']);
$score = calculate_score($marks) / calculate_max_score(count($marks)) * 100;
?>

<link rel="stylesheet" href="./materials.css">

<h2>Materiály <?php echo $subject; ?></h2>
<?php if ($score < 50): ?>

<?php else: ?>

<?php endif; ?>

<?php include_once('../footer.php'); ?>
