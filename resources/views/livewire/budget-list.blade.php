<div>
    <h1 class="text-xl font-bold mb-4">Orçamentos</h1>

    <a href="{{ route('budgets.create') }}" class="btn btn-primary mb-4">Novo Orçamento</a>

    <table class="table-auto w-full">
        <thead>
        <tr class="border-b bg-gray-100 text-left">
            <th>Nome</th>
            <th>Total</th>
            <th>Status</th>
            <th class="text-right">Ações</th>
        </tr>
        </thead>
        <tbody>
        @foreach($budgets as $budget)
            <tr class="border-b">
                <td>{{ $budget->number ?? 'Sem número' }}</td>
                <td>R$ {{ number_format($budget->total, 2, ',', '.') }}</td>
                <td>{{ $budget->status }}</td>
                <td class="text-right">
                    <a class="btn btn-sm btn-secondary" href="{{ route('budgets.show', $budget) }}">Ver</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $budgets->links() }}
</div>

