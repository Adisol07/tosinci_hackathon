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

addStudentBtn.addEventListener("click", () => {
  console.log("Přidat studenta – funkce zatím není implementována.");
});
