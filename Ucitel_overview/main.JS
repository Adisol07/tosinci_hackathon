// Sample class data with 10 grades each student
const classes = {
    E4: {
        students: [
            { name: "Student01", grades: [80, 90, 85, 70, 95, 88, 76, 92, 85, 90] },
            { name: "Student02", grades: [70, 75, 80, 65, 90, 78, 82, 84, 76, 80] }
        ]
    },
    C4: {
        students: [
            { name: "Student03", grades: [85, 88, 92, 80, 90, 85, 87, 90, 92, 88] },
            { name: "Student04", grades: [60, 70, 65, 55, 75, 68, 72, 70, 66, 64] }
        ]
    }
};

// DOM elements
const classDropdown = document.getElementById('classDropdown');
const studentsList = document.getElementById('studentsList');
const classAverage = document.getElementById('classAverage');

// Compute class average
function computeClassAverage(students) {
    const totalGrades = students.flatMap(s => s.grades);
    const avg = totalGrades.reduce((sum, g) => sum + g, 0) / totalGrades.length;
    return Math.round(avg);
}

// Populate students list
function updateClassView(className) {
    const cls = classes[className];

    // Update class average
    classAverage.textContent = computeClassAverage(cls.students) + "%";

    // Clear previous list
    studentsList.innerHTML = '';

    // Add students
    cls.students.forEach(student => {
        const li = document.createElement('li');

        const nameDiv = document.createElement('div');
        nameDiv.className = 'student-name';
        nameDiv.textContent = student.name;

        const gradesDiv = document.createElement('div');
        gradesDiv.className = 'grades';
        student.grades.forEach(g => {
            const gSpan = document.createElement('span');
            gSpan.className = 'grade';
            gSpan.textContent = g;
            gradesDiv.appendChild(gSpan);
        });

        li.appendChild(nameDiv);
        li.appendChild(gradesDiv);
        studentsList.appendChild(li);
    });
}

// Initial load
updateClassView(classDropdown.value);

// Dropdown change listener
classDropdown.addEventListener('change', (e) => {
    updateClassView(e.target.value);
});

