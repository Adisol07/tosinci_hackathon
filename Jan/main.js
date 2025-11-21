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
        #customModal p { margin:0 0 1rem 0; white-space: pre-wrap; text-align:left; }
        /* utility class to center success text */
        #customModal .centerText { text-align: center; font-size:18px; font-weight:700; }
        #customModal .okBtn {
            display:inline-block; padding:0.45rem 0.8rem; border-radius:6px;
            background:#0b79ff; color:#fff; border:none; cursor:pointer;
            font-weight:600;
        }
    `;
    const style = document.createElement('style');
    style.textContent = css;
    document.head.appendChild(style);

    // showDialog(message, options)
    // options: { centerText: boolean, autoCloseMs: number }
    function showDialog(message, options = {}) {
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
            const msgEl = overlay.querySelector('#customModalMessage');
            msgEl.textContent = String(message);

            // Apply centering class when requested (CSS controls appearance)
            if (options.centerText) {
                msgEl.classList.add('centerText');
            }

            const ok = overlay.querySelector('.okBtn');

            function close(result = true) {
                ok.removeEventListener('click', onOk);
                if (overlay.parentNode) document.body.removeChild(overlay);
                resolve(result);
            }
            function onOk() { close(true); }

            // OK button visible immediately. If autoCloseMs is provided, auto-close after that time.
            if (typeof options.autoCloseMs === 'number' && options.autoCloseMs > 0) {
                // ensure focus is on the overlay, but keep OK visible
                ok.focus();
                const autoTimer = setTimeout(() => {
                    clearTimeout(autoTimer);
                    close(true);
                }, options.autoCloseMs);
            } else {
                ok.focus();
            }

            ok.addEventListener('click', onOk);
            overlay.addEventListener('click', (e) => { if (e.target === overlay) close(true); });
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
function showCompletionScreen() {
    document.body.innerHTML = '';

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
            Pokračovat na domovskou obrazovku
        </button>
    `;
    document.body.appendChild(finishedMessage);
}

// Delegated events
document.addEventListener('click', (e) => {
    const id = e.target && e.target.id;
    if (id === 'restartBtn') {
        currentIndex = 0;
        location.reload();
    } else if (id === 'nextExerciseBtn') {
        showCustomDialog('Chcete doopravdy pokračovat na domovskou obrazovku...').then(() => {});
    }
});

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
            : (questions[currentIndex].incorrectText || 'Špatně. Správná odpověď: ') + correct; //!!!!zde proppjit do DB a fetchnout oduvodneni spravne odpovedi

        // When correct, show centered 'Skvěle' and auto-close after 2s (OK visible immediately)
        const dialogOptions = (selected.value === correct) ? { centerText: true, autoCloseMs: 2000 } : {};
        showCustomDialog(feedback, dialogOptions).then(() => {
        currentIndex++;
        if (currentIndex >= questions.length) {
            showCompletionScreen();
        } else {
            updateQuestion(currentIndex);
            document.querySelectorAll('input[name="answer"]').forEach(r => r.checked = false);
        }
    });
}

// --- Initialize when DOM is ready ---
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('checkBtn').addEventListener('click', checkAnswer);
    const dontKnow = document.getElementById('dontKnowBtn');
    if (dontKnow) {
        dontKnow.addEventListener('click', () => {
            // Show the correct answer in a modal (Czech) and then advance to next question
            const q = questions[currentIndex];
            if (!q) {
                showCustomDialog('Žádná otázka není načtena.');
                return;
            }
            const correctKey = (q.correct || '').toString().trim().toUpperCase();
            let correctText = correctKey;
            if (Array.isArray(q.options) && q.options.length > 0) {
                // Try to find an option that starts with the key (e.g. 'A)')
                const found = q.options.find(o => o.trim().toUpperCase().startsWith(correctKey));
                if (found) {
                    correctText = found;
                } else {
                    // Fallback: map A->0, B->1 etc.
                    const idx = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.indexOf(correctKey);
                    if (idx >= 0 && q.options[idx]) correctText = q.options[idx];
                }
            }

            showCustomDialog(`Správná odpověď: ${correctText}`).then(() => {
                // Advance to next question (same behaviour as after checking)
                currentIndex++;
                if (currentIndex >= questions.length) {
                    showCompletionScreen();
                } else {
                    updateQuestion(currentIndex);
                    document.querySelectorAll('input[name="answer"]').forEach(r => r.checked = false);
                }
            });
        });
    }
    updateQuestion(currentIndex);
});
