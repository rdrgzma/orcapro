<x-layouts.app :title="__('Edit Budget')">
    <div class="p-6">
        @livewire('budget-form', ['budget' => $budget])
    </div>
</x-layouts.app>
