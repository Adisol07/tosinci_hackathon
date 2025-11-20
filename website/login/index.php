<?php include_once('../header.php'); ?>
<?php
session_start();
$_SESSION['user'] = 'Test';

echo 'Test user has been logged in';
?>

<?php include_once('../footer.php'); ?>
