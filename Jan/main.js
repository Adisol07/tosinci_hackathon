// --- Questions ---
const questions = [
    {
        zadani: 'sin(2x)+3 cos(x)=0',
        options: ['A) x = 15°','B) x = 30°','C) x = 45°','D) x = 60°'],
        correct: 'B'
    },
    {
        zadani: "Najděte x: cos(x)=1/2",
        options: ["A) x = 30°", "B) x = 45°", "C) x = 60°", "D) x = 90°"],
        correct: "A",
        correctText: "Správně! 🎉",
        incorrectText: "Špatně. Správná odpověď: ",
        useModal: true
    }
];

let currentIndex = 0;

// --- Custom modal ---
(function () {
    const css = `
        #customModalOverlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.45); display:flex;
            align-items:center; justify-content:center; z-index: 10000;
        }
        #customModal {
            background: #fff; color:#111; padding:1.25rem; border-radius:8px;
            max-width: 90%; width: 360px; box-shadow: 0 10px 30px rgba(0,0,0,0.25);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }
        #customModal p { margin:0 0 1rem 0; white-space: pre-wrap; }
        #customModal .okBtn {
            display:inline-block; padding:0.45rem 0.8rem; border-radius:6px;
            background:#0b79ff; color:#fff; border:none; cursor:pointer;
            font-weight:600;
        }
    `;
    const style = document.createElement('style');
    style.textContent = css;
    document.head.appendChild(style);

    function showDialog(message) {
        return new Promise(resolve => {
            const overlay = document.createElement('div');
            overlay.id = 'customModalOverlay';
            overlay.innerHTML = `
                <div id="customModal" role="dialog" aria-modal="true">
                    <p id="customModalMessage"></p>
                    <div style="text-align:right;">
                        <button class="okBtn">OK</button>
                    </div>
                </div>
            `;
            document.body.appendChild(overlay);
            overlay.querySelector('#customModalMessage').textContent = String(message);
            const ok = overlay.querySelector('.okBtn');
            function close(result = true) {
                ok.removeEventListener('click', onOk);
                document.body.removeChild(overlay);
                resolve(result);
            }
            function onOk() { close(true); }
            ok.addEventListener('click', onOk);
            overlay.addEventListener('click', (e) => { if (e.target === overlay) close(true); });
            ok.focus();
        });
    }

    window.alert = function(msg) { showDialog(msg); };
    window.showCustomDialog = showDialog;
})();

// --- Update question ---
function updateQuestion(i) {
    const q = questions[i];
    document.getElementById('zadani').textContent = q.zadani;
    const labels = document.querySelectorAll('.label-text');
    labels.forEach((el, idx) => {
        el.textContent = q.options[idx] || '';
    });
    const prog = document.getElementById('progress_num');
    if (prog) prog.textContent = (i + 1) + '/' + questions.length;
}

// --- Show completion screen ---
// --- Show completion screen ---
function showCompletionScreen() {
    // Remove all body children (completely clears previous content)
    document.body.innerHTML = '';

    // Create completion message
    const finishedMessage = document.createElement('div');
    finishedMessage.innerHTML = `
        <h1 style="color:#0f172a; font-weight:600; text-align:center;">🎉 Cvičení dokončeno!</h1>
        <p style="font-size:16px; color:#374151; max-width:480px; margin:20px auto 0 auto; text-align:center;">
            Skvěle, všechny otázky byly zodpovězeny. Pokračujte dalším cvičením nebo si zopakujte tento blok.
        </p>
        <button id="restartBtn" style="display:block; margin:30px auto 0 auto; padding:10px 20px; background:#0b79ff; color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:600;">
            Restartovat cvičení
        </button>
        <button id="nextExerciseBtn" style="display:block; margin:15px auto 0 auto; padding:10px 20px; background:#10b981; color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:600;">
            Pokračovat dalším cvičením
        </button>
    `;
    document.body.appendChild(finishedMessage);
}


// --- Check answer ---
function checkAnswer() {
    const selected = document.querySelector('input[name="answer"]:checked');
    if (!selected) {
        showCustomDialog('Vyberte prosím jednu možnost.');
        return;
    }

    const correct = questions[currentIndex].correct;
    const feedback = (selected.value === correct)
        ? (questions[currentIndex].correctText || 'Správně! 🎉')
        : (questions[currentIndex].incorrectText || 'Špatně. Správná odpověď: ') + correct;

    showCustomDialog(feedback).then(() => {
        currentIndex++;
        if (currentIndex >= questions.length) {
            showCompletionScreen();
        } else {
            updateQuestion(currentIndex);
            document.querySelectorAll('input[name="answer"]').forEach(r => r.checked = false);
        }
    });
}

// --- Bind button ---
document.getElementById('checkBtn').addEventListener('click', checkAnswer);

// --- Initialize first question ---
updateQuestion(currentIndex);
