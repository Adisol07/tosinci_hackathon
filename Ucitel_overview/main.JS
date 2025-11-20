const classes = {
  E4: { students: [], average: 0 },
  C4: { students: [], average: 0 }
};

// Vygeneruj 10 studentů na každou třídu s 10 známkami a názvy testů
for (const className of ["E4", "C4"]) {
  const students = [];
  for (let i = 1; i <= 10; i++) {
    const grades = [];
    for (let j = 1; j <= 10; j++) {
      // náhodná známka 50–100
      grades.push(Math.floor(Math.random() * 51) + 50);
    }
    students.push({
      name: `Student${String(i).padStart(2, "0")}`,
      grades: grades,
      gradeNames: Array.from({ length: 10 }, (_, k) => `Úkol ${k + 1}`) // pojmenujeme testy "Úkol 1, Úkol 2, …"
    });
  }
  classes[className].students = students;

  // spočítat průměr třídy
  const allGrades = students.flatMap(s => s.grades);
  const avg = allGrades.reduce((sum, g) => sum + g, 0) / allGrades.length;
  classes[className].average = Math.round(avg);
}

const classDropdown = document.getElementById("classDropdown");
const studentsList = document.getElementById("studentsList");
const classAverage = document.getElementById("classAverage");

function updateClassView(className) {
  const cls = classes[className];
  classAverage.textContent = cls.average + "%";

  studentsList.innerHTML = "";

  cls.students.forEach(student => {
    const tr = document.createElement("tr");

    // jméno studenta
    const nameTd = document.createElement("td");
    nameTd.className = "student-name-cell";
    nameTd.textContent = student.name;
    tr.appendChild(nameTd);

    // známky
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

    // tlačítko procvičit
    const btnTd = document.createElement("td");
    const btn = document.createElement("button");
    btn.className = "button-practice";
    btn.textContent = "Procvičit";
    // momentálně nedělá nic
    btn.addEventListener("click", () => {
      console.log("Procvičit pro", student.name);
    });
    btnTd.appendChild(btn);
    tr.appendChild(btnTd);

    studentsList.appendChild(tr);
  });
}

// počáteční vykreslení
updateClassView(classDropdown.value);

classDropdown.addEventListener("change", (e) => {
  updateClassView(e.target.value);
});
