<?php include_once('../header.php'); ?>
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login/');
}

$subject = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING) ?? '';
if ($subject == '') {
    header('Location: ../subjects/');
}

$username = $_SESSION['user'];
$user = get_user($username);
$marks = get_marks_by_subject($subject, $user['id']);
$score = calculate_score($marks) / calculate_max_score(count($marks)) * 100;
?>

<link rel="stylesheet" href="./subject.css">

<h2 style="<?php echo ($score < 50 ? 'color:red;' : ''); ?>"><?php echo $subject; ?></h2>
<div class="subject-grid">
    <div class="marks-list">
        <?php foreach ($marks as $mark): ?>
            <a class="marks-item" href="<?php echo URL; ?>/mark?id=<?php echo $mark['id']; ?>">
                <h2><?php echo strrev($mark['mark']); ?><?php echo ($mark['mark'] < 0 ? "" : "&nbsp;"); ?></h2>
                <div>
                    <h3><?php echo $mark['description']; ?></h3>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
    <div class="success-rate">
        <div id="success-rate-value">
            <h2><?php echo round($score); ?> %</h2>
        </div>
        <h3>Úspěšnost</h3>
    </div>
</div>
<div class="subject-more">
    <a class="btn" href="<?php echo URL; ?>/practices?subject=<?php echo $subject; ?>">Procvičovat</a>
    <?php if ($score < 50): ?>
        <a class="btn" href="<?php echo URL; ?>/materials?subject=<?php echo $subject; ?>">Získat materiály</a>
    <?php else: ?>
        <a class="btn" href="<?php echo URL; ?>/materials?subject=<?php echo $subject; ?>">Vložit materiály</a>
    <?php endif; ?>
</div>

<script>
    const percentage = Math.round(<?php echo $score; ?>);
    document.getElementById('success-rate-value').style.setProperty('--percentage', percentage);
</script>

<?php include_once('../footer.php'); ?>
