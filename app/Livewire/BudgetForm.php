<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Budget;
use App\Models\BudgetItem;
use App\Services\BudgetCalculator;

class BudgetForm extends Component
{
    public Budget $budget;
    public $items = [];

    protected $rules = [

        'budget.discount_value' => 'nullable|numeric',
        'budget.discount_type' => 'nullable|string',
        'budget.tax_value' => 'nullable|numeric',
        'budget.tax_type' => 'nullable|string',
        'budget.additional_fees' => 'nullable|numeric',
        'items.*.name' => 'required|string|max:255',
        'items.*.description' => 'nullable|string',
        'items.*.quantity' => 'required|numeric|min:1',
        'items.*.unit_price' => 'required|numeric|min:0',
    ];

    public function mount($budget = null)
    {
        if ($budget) {
            $this->budget = $budget;
            $this->items = $budget->items->toArray();
        } else {
            $this->budget = new Budget([
                'discount_type' => 'percent',
                'tax_type' => 'percent'
            ]);
        }
        $this->calculateBudgetTotals();
    }

    public function updated($name, $value)
    {
        if (preg_match('/items\.(\d+)\.(quantity|unit_price)/', $name, $matches)) {
            $index = $matches[1];
            $this->calculateItemTotal($index);
        } elseif (str_starts_with($name, 'budget.')) {
            $this->calculateBudgetTotals();
        }
    }

    public function calculateItemTotal($index)
    {
        $item = $this->items[$index];
        $quantity = (float)($item['quantity'] ?? 0);
        $unitPrice = (float)($item['unit_price'] ?? 0);
        $this->items[$index]['total'] = $quantity * $unitPrice;
        $this->calculateBudgetTotals();
    }

    public function calculateBudgetTotals()
    {
        // Use the existing budget model instance from the component.
        // Set its 'items' relation using the current state of the form's items array.
        $this->budget->setRelation('items', collect($this->items)->map(function($item) {
            return new BudgetItem($item);
        }));

        // Pass the updated model directly to the calculator.
        $calculator = new BudgetCalculator();
        $this->budget = $calculator->calculate($this->budget);
    }

    public function addItem()
    {
        $this->items[] = [
            'name' => '',
            'description' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'total' => 0,
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateBudgetTotals();
    }

    public function save(BudgetCalculator $calc)
    {
        $this->validate();

        $this->budget->company_id = auth()->user()->company_id ?? 1;
        $this->budget->save();

        BudgetItem::where('budget_id', $this->budget->id)->delete();

        foreach ($this->items as $item) {
            BudgetItem::create([
                'budget_id' => $this->budget->id,
                'name' => $item['name'],
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price']
            ]);
        }

        $this->budget->refresh();
        $calc->calculate($this->budget)->save();

        session()->flash('success', 'Orçamento salvo com sucesso!');
        return redirect()->route('budgets.show', $this->budget->id);
    }

    public function render()
    {
        return view('livewire.budget-form');
    }
}
