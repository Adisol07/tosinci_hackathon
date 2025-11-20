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

function select_bad_marks($marks)
{
    $bad_marks = [];
    foreach ($marks as $mark) {
        if (
            $mark['mark'] == -2 ||
            $mark['mark'] == 3 ||
            $mark['mark'] == -3 ||
            $mark['mark'] == 4 ||
            $mark['mark'] == -4 ||
            $mark['mark'] == 5
        ) {
            $bad_marks[] = $mark;
        }
    }
    return $bad_marks;
}
