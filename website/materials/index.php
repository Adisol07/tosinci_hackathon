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
$materials = get_materials($subject);
?>

<link rel="stylesheet" href="./materials.css">

<h2>Materiály <?php echo $subject; ?></h2>
<?php if ($score < 50): ?>

    <h3>Získané materiály</h3>

    <h3>Objevit materiály</h3>
    <div class="materials-list">
        <?php foreach ($materials as $material): ?>
            <a class="materials-item" href="<?php echo URL; ?>/material?id=<?php echo htmlspecialchars($material['id']); ?>">
                <h3><?php echo $material['name']; ?></h3>
            </a>
        <?php endforeach; ?>
    </div>

<?php else:
    $created_materials = select_materials_by_owner($user['id'], $materials);
?>

    <h3>Nahrané materiály</h3>
    <div class="materials-list">
        <?php foreach ($created_materials as $material): ?>
            <a class="materials-item" href="<?php echo URL; ?>/material?id=<?php echo htmlspecialchars($material['id']); ?>">
                <h3><?php echo $material['name']; ?></h3>
            </a>
        <?php endforeach; ?>
    </div>
    <a class="btn" href="<?php echo URL; ?>/upload_material?subject=<?php echo $subject; ?>">Nahrát materiály</a>

<?php endif; ?>

<?php include_once('../footer.php'); ?>
