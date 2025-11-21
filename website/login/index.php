<?php include_once('../header.php'); ?>
<?php
if (isset($_SESSION['user'])) {
    header('Location: ../profile/');
    exit();
}
?>

<link rel="stylesheet" href="./login.css">

<h2>Login</h2>
<form action="<?php echo URL; ?>/api/login.php" method="post">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button class="btn" type="submit">Login</button>
</form>
<?php if (isset($_GET['error'])): ?>
    <p class="error"><?php echo $_GET['error']; ?></p>
<?php endif; ?>

<?php include_once('../footer.php'); ?>
