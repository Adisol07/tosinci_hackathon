<?php include_once('../header.php'); ?>
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login/');
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING) ?? '';
if ($id == '') {
    header('Location: ../practices/');
}

$username = $_SESSION['user'];
$user = get_user($username);
$practice = get_practice($id);
$questions = get_practice_questions($id);
?>

<link rel="stylesheet" href="./practice.css">

<div id="practice-popup">
    <div>
        <h2 id="practice-popup-title"></h2>
        <p id="practice-popup-text"></p>
        <button class="btn" id="practice-popup-close-btn">OK</button>
    </div>
</div>

<h2><?php echo $practice['name']; ?></h2>
<h3 id="practice-question"><?php echo $questions[0]['question']; ?></h3>
<div class="practice-grid">
    <label class="practice-grid-item">
        <input type="radio" name="answer" value="A" id="practice-answer-a-input">
        <span class="practice-grid-item-dot"></span>
        <span>A)</span>
        <span id="practice-answer-a"><?php echo $questions[0]['answerA']; ?></span>
    </label>
    <label class="practice-grid-item">
        <input type="radio" name="answer" value="B" id="practice-answer-b-input">
        <span class="practice-grid-item-dot"></span>
        <span>B)</span>
        <span id="practice-answer-b"><?php echo $questions[0]['answerB']; ?></span>
    </label>
    <label class="practice-grid-item">
        <input type="radio" name="answer" value="C" id="practice-answer-c-input">
        <span class="practice-grid-item-dot"></span>
        <span>C)</span>
        <span id="practice-answer-c"><?php echo $questions[0]['answerC']; ?></span>
    </label>
    <label class="practice-grid-item">
        <input type="radio" name="answer" value="D" id="practice-answer-d-input">
        <span class="practice-grid-item-dot"></span>
        <span>D)</span>
        <span id="practice-answer-d"><?php echo $questions[0]['answerD']; ?></span>
    </label>
</div>
<div class="practice-buttons">
    <button class="btn" id="checkBtn">Zkontrolovat</button>
    <button class="btn" id="nevimBtn">Nevím</button>
</div>

<script>
    const checkBtn = document.getElementById('checkBtn');
    const nevimBtn = document.getElementById('nevimBtn');
    const practiceQuestion = document.getElementById('practice-question');
    const practiceAnswerA = document.getElementById('practice-answer-a');
    const practiceAnswerB = document.getElementById('practice-answer-b');
    const practiceAnswerC = document.getElementById('practice-answer-c');
    const practiceAnswerD = document.getElementById('practice-answer-d');
    const practiceAnswerAInput = document.getElementById('practice-answer-a-input');
    const practiceAnswerBInput = document.getElementById('practice-answer-b-input');
    const practiceAnswerCInput = document.getElementById('practice-answer-c-input');
    const practiceAnswerDInput = document.getElementById('practice-answer-d-input');
    const practicePopup = document.getElementById('practice-popup');
    const practicePopupTitle = document.getElementById('practice-popup-title');
    const practicePopupText = document.getElementById('practice-popup-text');
    const practicePopupCloseBtn = document.getElementById('practice-popup-close-btn');

    const practiceId = "<?php echo $id; ?>";
    let current_question = 0;
    const questions = [
        <?php foreach ($questions as $question): ?> {
                question: '<?php echo $question['question']; ?>',
                answerA: '<?php echo $question['answerA']; ?>',
                answerB: '<?php echo $question['answerB']; ?>',
                answerC: '<?php echo $question['answerC']; ?>',
                answerD: '<?php echo $question['answerD']; ?>',
                correct: '<?php echo $question['correct']; ?>',
            },
        <?php endforeach; ?>
    ];

    practicePopupCloseBtn.addEventListener('click', () => {
        practicePopup.style.display = 'none';
    });
    checkBtn.addEventListener('click', () => {
        let selected = 0;
        if (practiceAnswerAInput.checked) {
            selected = 1;
        } else if (practiceAnswerBInput.checked) {
            selected = 2;
        } else if (practiceAnswerCInput.checked) {
            selected = 3;
        } else if (practiceAnswerDInput.checked) {
            selected = 4;
        }

        const question = questions[current_question];

        const correct_letter = question.correct == 1 ? 'A' : question.correct == 2 ? 'B' : question.correct == 3 ? 'C' : 'D';

        if (question.correct == selected) {
            practicePopupTitle.innerText = 'Správně! 🎉';
            showPopup();
        } else {
            practicePopupTitle.innerText = 'Špatně! 😢';
            practicePopupText.innerText = 'Odpověď byla odpověď ' + correct_letter;
            showPopup();
        }
        nextQuestion();
    });
    nevimBtn.addEventListener('click', () => {
        practicePopupTitle.innerText = '';
        practicePopupText.innerText = 'Odpověď byla ' + current_question;
        showPopup();
        nextQuestion();
    });

    function showPopup() {
        practicePopup.style.display = 'flex';
    }

    function nextQuestion() {
        current_question++;
        if (current_question >= questions.length) {
            endPractice();
            return;
        }

        const question = questions[current_question];
        practiceAnswerA.innerText = question.answerA;
        practiceAnswerB.innerText = question.answerB;
        practiceAnswerC.innerText = question.answerC;
        practiceAnswerD.innerText = question.answerD;

        practiceQuestion.innerText = question.question;

        practiceAnswerAInput.checked = false;
        practiceAnswerBInput.checked = false;
        practiceAnswerCInput.checked = false;
        practiceAnswerDInput.checked = false;
    }

    function endPractice() {
        practicePopupTitle.innerText = 'Úspěšně jste dokončili všechny úkoly!';
        practicePopupText.innerText = '';
        showPopup();
        practicePopupCloseBtn.addEventListener('click', () => {
            practicePopup.style.display = 'none';
            location.href = '../api/completed_practice.php?id=' + practiceId;
        });
    }
</script>

<?php include_once('../footer.php'); ?>
