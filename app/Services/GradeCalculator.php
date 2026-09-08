<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\GradeWeight;
use Illuminate\Support\Collection;

class GradeCalculator
{
    public static function taskSubmissionScores(int $teachingAssignmentId, int $semesterId, int $studentId): array
    {
        return \App\Models\TaskSubmission::whereHas('task', function ($q) use ($teachingAssignmentId, $semesterId) {
            $q->where('teaching_assignment_id', $teachingAssignmentId)
                ->where('semester_id', $semesterId);
        })
            ->where('student_id', $studentId)
            ->whereNotNull('grade')
            ->pluck('grade')
            ->map(fn ($v) => (float) $v)
            ->all();
    }

    /**
     * @return array{final: ?float, categoryAverages: array<string, ?float>, isComplete: bool}
     */
    public static function calculate(Collection $grades, ?GradeWeight $weight, array $taskSubmissionScores = []): array
    {
        $manualTugasScores = $grades->where('category', 'tugas')->pluck('score')->map(fn ($v) => (float) $v)->all();
        $pooledTugasScores = array_merge($taskSubmissionScores, $manualTugasScores);

        $categoryAverages = [
            'tugas' => count($pooledTugasScores) > 0 ? round(array_sum($pooledTugasScores) / count($pooledTugasScores), 2) : null,
        ];

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