<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    protected $fillable = [
        'budget_id', 'name', 'description', 'quantity', 'unit_price',
        'discount_value', 'discount_type', 'tax_value', 'tax_type', 'total'
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
}
