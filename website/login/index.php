<?php include_once('../header.php'); ?>
<?php
session_start();
$_SESSION['user'] = 'Debug';

echo 'Debug user has been logged in';
?>

<?php include_once('../footer.php'); ?>
