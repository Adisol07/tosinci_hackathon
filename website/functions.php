<?php
function calculate_score($marks)
{
    $score = 0;
    foreach ($marks as $mark) {
        $value = $mark['mark'];
        if ($value < 0) {
            $value *= -1;
            $value += 0.5;
        }

        $score += 6 - $value;
    }
    return $score;
}

function calculate_max_score($mark_count)
{
    return $mark_count * 6;
}

function get_subjects($marks)
{
    $subjects = [];
    foreach ($marks as $mark) {
        if (!in_array($mark['subject'], $subjects)) {
            $subjects[] = $mark['subject'];
        }
    }
    return $subjects;
}

function get_subjects_detailed($marks)
{
    $subjects = [];
    foreach ($marks as $mark) {
        $name = $mark['subject'];
        $value = $mark['mark'];

        if ($value < 0) {
            $value *= -1;
            $value += 0.5;
        }
        $score_contribution = 6 - $value;

        if (!isset($subjects[$name])) {
            $subjects[$name] = [$name, $score_contribution, 6];
        } else {
            $subjects[$name][1] += $score_contribution;
            $subjects[$name][2] += 6;
        }
    }
    return $subjects;
}

function get_subject_marks($subject, $marks)
{
    $subject_marks = [];
    foreach ($marks as $mark) {
        if ($mark['subject'] == $subject) {
            $subject_marks[] = $mark;
        }
    }
    return $subject_marks;
}

function get_bad_subjects($marks)
{
    $subjects = get_subjects($marks);

    $bad_subjects = [];
    foreach ($subjects as $subject) {
        $value = calculate_score(get_subject_marks($subject, $marks));
        $max_value = calculate_max_score(count(get_subject_marks($subject, $marks)));

        if ($value / $max_value < 0.5) {
            $bad_subjects[] = [$subject, $value, $max_value];
        }
    }

    return $bad_subjects;
}
