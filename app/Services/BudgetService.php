<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\Submission;
use Illuminate\Support\Carbon;
use RuntimeException;

class BudgetService
{
    public function getBudgetForSubmission(Submission $submission){
        $dateValue = $submission->submitted_at
            ?? $submission->created_at;

        $date = $dateValue instanceof Carbon
            ? $dateValue
            : Carbon::parse($dateValue);

        $period = $date->format('Y-m');

        $budget = Budget::query()
            ->where(
                'category_id',
                $submission->category_id
            )
            ->where('period', $period)
            ->first();

        if (!$budget) {
            throw new RuntimeException(
                "Budget for category {$submission->category_id} "
                    . "and period {$period} was not found."
            );
        }

        return $budget;
    }

    public function remaining(Budget $budget){
        return (float) $budget->amount
            - (float) $budget->used_amount;
    }

    public function isSufficient(Budget $budget,float $amount){
        return $this->remaining($budget) >= $amount;
    }

    public function consume(Budget $budget,float $amount){
        if (!$this->isSufficient($budget, $amount)) {
            throw new RuntimeException(
                'Category budget is insufficient.'
            );
        }

        $budget->increment('used_amount', $amount);
    }
}
