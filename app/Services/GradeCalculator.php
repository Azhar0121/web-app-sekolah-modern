<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\GradeWeight;
use Illuminate\Support\Collection;

class GradeCalculator
{
    /**
     * @return array{final: ?float, categoryAverages: array<string, ?float>, isComplete: bool}
     */
    public static function calculate(Collection $grades, ?GradeWeight $weight): array
    {
        $categoryAverages = [];

        foreach (Grade::CATEGORIES as $category) {
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