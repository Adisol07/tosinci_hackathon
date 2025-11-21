const classes = {
  E4: { students: [], average: 0 },
  C4: { students: [], average: 0 }
};

// Generování studentů
for (const className of ["E4", "C4"]) {
  const students = [];
  for (let i = 1; i <= 10; i++) {
    const grades = [];
    for (let j = 1; j <= 10; j++) {
      grades.push(Math.floor(Math.random() * 51) + 50);
    }
    students.push({
      name: `Student${String(i).padStart(2, "0")}`,
      grades: grades,
      gradeNames: Array.from({ length: 10 }, (_, k) => `Úkol ${k + 1}`)
    });
  }
  classes[className].students = students;

  const allGrades = students.flatMap(s => s.grades);
  classes[className].average = Math.round(
    allGrades.reduce((sum, g) => sum + g, 0) / allGrades.length
  );
}

const classDropdown = document.getElementById("classDropdown");
const studentsList = document.getElementById("studentsList");
const classAverage = document.getElementById("classAverage");
const addStudentBtn = document.getElementById("addStudentBtn");

function updateClassView(className) {
  const cls = classes[className];
  classAverage.textContent = cls.average + "%";
  studentsList.innerHTML = "";

  cls.students.forEach(student => {
    const tr = document.createElement("tr");

    const nameTd = document.createElement("td");
    nameTd.className = "student-name-cell";
    nameTd.textContent = student.name;
    tr.appendChild(nameTd);

    student.grades.forEach((g, idx) => {
      const td = document.createElement("td");
      td.className = "grade-cell";

      const span = document.createElement("span");
      span.className = "grade";
      span.textContent = g;

      const tooltip = document.createElement("div");
      tooltip.className = "tooltip";
      tooltip.textContent = student.gradeNames[idx];

      td.appendChild(span);
      td.appendChild(tooltip);
      tr.appendChild(td);
    });

    const actionTd = document.createElement("td");

    const btnPractice = document.createElement("button");
    btnPractice.className = "button-practice";
    btnPractice.textContent = "Procvičit";
    btnPractice.addEventListener("click", () =>
      console.log("Procvičit", student.name)
    );

    const btnReset = document.createElement("button");
    btnReset.className = "button-reset";
    btnReset.textContent = "Resetovat heslo";
    btnReset.addEventListener("click", () =>
      console.log("Resetovat heslo", student.name)
    );

    const btnDelete = document.createElement("button");
    btnDelete.className = "button-delete";
    btnDelete.textContent = "Vymazat účet";
    btnDelete.addEventListener("click", () =>
      console.log("Vymazat účet", student.name)
    );

    actionTd.appendChild(btnPractice);
    actionTd.appendChild(btnReset);
    actionTd.appendChild(btnDelete);

    tr.appendChild(actionTd);
    studentsList.appendChild(tr);
  });
}

updateClassView(classDropdown.value);
classDropdown.addEventListener("change", (e) => updateClassView(e.target.value));

function createStudentModal() {
  // If modal already exists, don't recreate
  if (document.getElementById('studentModal')) return;

  const overlay = document.createElement('div');
  overlay.id = 'studentModal';
  overlay.className = 'modal-overlay';

  overlay.innerHTML = `
    <div class="modal">
      <div class="modal-header">
        <h2>Přidat studenta</h2>
        <button class="modal-close" aria-label="Zavřít">&times;</button>
      </div>
      <form class="modal-form">
        <div class="input-row">
          <input type="text" name="firstName" placeholder="Jméno" required />
          <input type="text" name="lastName" placeholder="Příjmení" required />
        </div>
        <div class="input-row">
          <input type="email" name="email" placeholder="Email" required />
          <input type="password" name="password" placeholder="Heslo" required />
        </div>
        <div class="modal-actions">
          <button type="submit" class="button-practice">Uložit</button>
          <button type="button" class="button-reset">Zrušit</button>
        </div>
      </form>
    </div>
  `;

  document.body.appendChild(overlay);

  const closeBtn = overlay.querySelector('.modal-close');
  const cancelBtn = overlay.querySelector('.button-reset');
  const form = overlay.querySelector('.modal-form');

  function closeModal() { overlay.remove(); }
  closeBtn.addEventListener('click', closeModal);
  cancelBtn.addEventListener('click', closeModal);

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const first = form.elements['firstName'].value.trim();
    const last = form.elements['lastName'].value.trim();
    const email = form.elements['email'].value.trim();
    const password = form.elements['password'].value;

    if (!first || !last || !email || !password) {
      alert('Vyplňte prosím všechna pole.');
      return;
    }

    const newStudent = {
      name: `${first} ${last}`,
      grades: Array.from({ length: 10 }, () => Math.floor(Math.random() * 51) + 50),
      gradeNames: Array.from({ length: 10 }, (_, k) => `Úkol ${k + 1}`),
      email: email
    };

    const currentClass = classDropdown.value;
    classes[currentClass].students.push(newStudent);

    // Recalculate class average
    const allGrades = classes[currentClass].students.flatMap(s => s.grades);
    classes[currentClass].average = Math.round(
      allGrades.reduce((sum, g) => sum + g, 0) / allGrades.length
    );

    updateClassView(currentClass);
    closeModal();
  });
}

addStudentBtn.addEventListener("click", () => {
  createStudentModal();
});
