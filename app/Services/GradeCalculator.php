<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\GradeWeight;
use Illuminate\Support\Collection;

class GradeCalculator
{
    public static function taskAverage(int $teachingAssignmentId, int $semesterId, int $studentId): ?float
    {
        $average = \App\Models\TaskSubmission::whereHas('task', function ($q) use ($teachingAssignmentId, $semesterId) {
            $q->where('teaching_assignment_id', $teachingAssignmentId)
                ->where('semester_id', $semesterId);
        })
            ->where('student_id', $studentId)
            ->whereNotNull('grade')
            ->avg('grade');

        return $average !== null ? round((float) $average, 2) : null;
    }

    /**
     * @return array{final: ?float, categoryAverages: array<string, ?float>, isComplete: bool}
     */
    public static function calculate(Collection $grades, ?GradeWeight $weight, ?float $taskAverage = null): array
    {
        $categoryAverages = ['tugas' => $taskAverage];

        foreach (array_diff(Grade::CATEGORIES, ['tugas']) as $category) {
            $scores = $grades->where('category', $category)->pluck('score');
            $categoryAverages[$category] = $scores->isNotEmpty()
                ? round((float) $scores->avg(), 2)
                : null;
        }

        if (! $weight) {
            return ['final' => null, 'categoryAverages' => $categoryAverages, 'isComplete' => false];
        }

        $totalWeightedScore = 0;
        $totalWeightUsed = 0;

        foreach ($categoryAverages as $category => $average) {
            if ($average === null) {
                continue;
            }

            $w = $weight->weightFor($category);
            $totalWeightedScore += $average * $w;
            $totalWeightUsed += $w;
        }

        $final = $totalWeightUsed > 0 ? round($totalWeightedScore / $totalWeightUsed, 2) : null;
        $isComplete = ! in_array(null, $categoryAverages, true);

        return ['final' => $final, 'categoryAverages' => $categoryAverages, 'isComplete' => $isComplete];
    }
}