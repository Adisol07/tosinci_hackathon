<?php include_once('../header.php'); ?>
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login/');
}

$username = $_SESSION['user'];
$user = get_user($username);
?>

<link rel="stylesheet" href="./profile.css">
<div class="profile">
    <img src="<?php echo URL; ?>/content/profile_pictures/<?php echo $user['id']; ?>" alt="<?php echo $username . " profile picture"; ?>">
    <div>
        <h2><?php echo $username; ?></h2>
        <h3><?php echo $user['school']; ?></h3>
    </div>
</div>

<?php include_once('../footer.php'); ?>
