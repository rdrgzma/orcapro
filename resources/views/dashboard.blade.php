<x-layouts.app :title="__('Dashboard')">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
        <div class="bg-white shadow rounded p-4 text-center">
            <p class="text-sm text-gray-500">Orçamentos Pendentes</p>
            <h2 class="text-2xl font-bold text-blue-600">{{ $pendingBudgets }}</h2>
        </div>

        <div class="bg-white shadow rounded p-4 text-center">
            <p class="text-sm text-gray-500">OS Abertas</p>
            <h2 class="text-2xl font-bold text-green-600">{{ $openOS }}</h2>
        </div>

        <div class="bg-white shadow rounded p-4 text-center">
            <p class="text-sm text-gray-500">Receita do Mês</p>
            <h2 class="text-2xl font-bold text-orange-600">R$ {{ number_format($revenueMonth,2,',','.') }}</h2>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
        <a href="{{ route('budgets.create') }}" class="bg-white shadow rounded p-4 text-center hover:bg-gray-50">
            <h2 class="text-lg font-bold">Novo Orçamento</h2>
            <p class="text-sm text-gray-500">Crie um novo orçamento para um cliente</p>
        </a>

        <a href="{{ route('work-orders.create') }}" class="bg-white shadow rounded p-4 text-center hover:bg-gray-50">
            <h2 class="text-lg font-bold">Nova Ordem de Serviço</h2>
            <p class="text-sm text-gray-500">Crie uma nova ordem de serviço</p>
        </a>
    </div>
</x-layouts.app>
