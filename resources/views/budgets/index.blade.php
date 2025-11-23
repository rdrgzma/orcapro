<x-layouts.app :title="__('Budgets')">
    <div class="p-6">
        <div class="bg-white shadow rounded">
            <div class="p-4 border-b flex justify-between items-center">
                <h2 class="text-lg font-bold">Budgets</h2>
                <a href="{{ route('budgets.create') }}" class="btn bg-blue-600 text-white p-2 rounded">Create Budget</a>
            </div>
            <div class="p-4">
                @livewire('budget-list')
            </div>
        </div>
    </div>
</x-layouts.app>
