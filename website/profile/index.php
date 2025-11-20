<?php include_once('../header.php'); ?>
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login/');
}

$username = $_SESSION['user'];
$user = get_user($username);
$marks = get_marks($user['id'], 3);
$mark_count = get_mark_count($user['id']);
$score = calculate_score(get_marks($user['id'], 9999)) / calculate_max_score(get_mark_count($user['id'])) * 100;
?>

<link rel="stylesheet" href="./profile.css">

<div class="profile">
    <img src="<?php echo URL; ?>/content/profile_pictures/<?php echo $user['id']; ?>.jpg" alt="<?php echo $username . " profile picture"; ?>">
    <div>
        <h2><?php echo $username; ?></h2>
        <h3><?php echo $user['school']; ?></h3>
    </div>
</div>

<div class="overview">
    <div class="overview-znamky">
        <h2>Známky:</h2>
        <div class="overview-znamky-list">
            <?php foreach ($marks as $mark): ?>
                <a class="overview-znamky-item" href="<?php echo URL; ?>/mark?id=<?php echo $mark['id']; ?>">
                    <h2><?php echo strrev($mark['mark']); ?><?php echo ($mark['mark'] < 0 ? "" : "&nbsp;"); ?></h2>
                    <div>
                        <h3><?php echo $mark['subject']; ?></h3>
                        <h4><?php echo $mark['description']; ?></h4>
                    </div>
                </a>
            <?php endforeach; ?>
            <?php if ($mark_count > 3): ?>
                <a class="show-more" href="<?php echo URL; ?>/marks">Zobrazit více..</a>
            <?php endif; ?>
        </div>
    </div>

    <div id="overview-result">
        <h2><?php echo round($score); ?> %</h2>
    </div>
</div>

<div>
    <h2>Zlepšete:</h2>
    <div class="zlepsete-list">
    </div>
</div>

<script>
    const percentage = Math.round(<?php echo $score; ?>);

    document.getElementById('overview-result').style.setProperty('--percentage', percentage);
</script>

<?php include_once('../footer.php'); ?>
