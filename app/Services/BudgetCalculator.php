<?php
namespace App\Services;

use App\Models\Budget;

class BudgetCalculator
{
    public function calculate(Budget $budget): Budget
    {
        $subtotal = $budget->items->sum('total');

        $discount = $this->calcValue($subtotal, $budget->discount_value, $budget->discount_type);
        $tax = $this->calcValue($subtotal - $discount, $budget->tax_value, $budget->tax_type);

        $total = $subtotal - $discount + $tax + $budget->additional_fees;

        $budget->subtotal = $subtotal;
        $budget->total = max($total, 0);

        return $budget;
    }

    private function calcValue(float $base, ?float $value, ?string $type): float
    {
        if (!$value || !$type) return 0;
        return $type === 'percent'
            ? ($base * $value / 100)
            : $value;
    }
}

