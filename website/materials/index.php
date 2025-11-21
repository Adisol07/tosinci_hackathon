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

<div id="buy-popup">
    <div>
        <h2 id="buy-popup-title"></h2>
        <p id="buy-popup-text"></p>
        <div>
            <button class="btn" id="cancel-popup-btn">Zrušit</button>
            <button class="btn" id="buy-popup-btn">Zakoupit</button>
        </div>
    </div>
</div>

<h2>Materiály <?php echo $subject; ?></h2>
<?php if ($score < 50): ?>

    <h3>Získané materiály</h3>

    <h3>Objevit materiály</h3>
    <div class="materials-list">
        <?php foreach ($materials as $material): ?>
            <a class="materials-item buyable-materials-item" href="#" data-price="<?php echo $material['price']; ?>">
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

<script>
    const buyPopup = document.getElementById('buy-popup');
    const buyPopupTitle = document.getElementById('buy-popup-title');
    const buyPopupText = document.getElementById('buy-popup-text');
    const buyPopupBtn = document.getElementById('buy-popup-btn');
    const cancelPopupBtn = document.getElementById('cancel-popup-btn');

    const buyableMaterialsItems = document.querySelectorAll('.buyable-materials-item');
    for (const item of buyableMaterialsItems) {
        item.addEventListener('click', () => {
            buyPopupTitle.innerText = item.querySelector('h3').innerText;
            buyPopupText.innerText = 'Cena: ' + item.getAttribute('data-price');
            buyPopup.style.display = 'flex';
        });
    }

    buyPopupBtn.addEventListener('click', () => {
        buyPopup.style.display = 'none';
    });

    cancelPopupBtn.addEventListener('click', () => {
        buyPopup.style.display = 'none';
    });
</script>

<?php include_once('../footer.php'); ?>
