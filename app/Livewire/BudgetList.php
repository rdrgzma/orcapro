<?php

namespace App\Livewire;

use App\Models\Budget;
use Livewire\Component;

class BudgetList extends Component
{
    public function render()
    {
        return view('livewire.budget-list', [
            'budgets' => Budget::latest()->paginate(12)
        ]);
    }
}

