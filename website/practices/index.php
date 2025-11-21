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
$practices = get_practices($subject);
?>

<link rel="stylesheet" href="./practices.css">

<h2>Procvičovaní <?php echo $subject; ?></h2>
<div class="practices-list">
    <?php foreach ($practices as $practice): ?>
        <a class="practices-item" href="<?php echo URL; ?>/practice?id=<?php echo htmlspecialchars($practice['id']); ?>">
            <h3><?php echo $practice['name']; ?></h3>
        </a>
    <?php endforeach; ?>
</div>

<?php include_once('../footer.php'); ?>
