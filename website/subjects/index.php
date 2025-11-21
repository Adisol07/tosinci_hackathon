<?php include_once('../header.php'); ?>
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login/');
}

$username = $_SESSION['user'];
$user = get_user($username);
$marks = get_marks($user['id'], 9999);
$subjects = get_subjects_detailed($marks);
?>

<link rel="stylesheet" href="./subjects.css">

<h2>Všechny předměty</h2>
<div class="subjects-list">
    <?php foreach ($subjects as $subject): ?>
        <a class="subjects-item" href="<?php echo URL; ?>/subject?id=<?php echo htmlspecialchars($subject[0]); ?>" data-percentage="<?php echo round($subject[1] / $subject[2] * 100); ?>">
            <div class="subjects-item-value">
                <h2><?php echo round($subject[1] / $subject[2] * 100); ?> %</h2>
            </div>
            <h3><?php echo $subject[0]; ?></h3>
        </a>
    <?php endforeach; ?>
</div>

<script>
    const subjectsItems = document.querySelectorAll('.subjects-item');
    for (const item of subjectsItems) {
        const value = item.querySelector('.subjects-item-value');
        const percentage = Math.round(item.getAttribute('data-percentage'));
        value.style.setProperty('--percentage', percentage);
    }
</script>

<?php include_once('../footer.php'); ?>
