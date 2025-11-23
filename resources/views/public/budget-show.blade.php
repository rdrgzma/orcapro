<div>
{{$budget->client->name ?? ''}}
{{$budget->company->name}}
    {{$budget->items}}


    <div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">
        <h1 class="text-2xl font-bold mb-4">Orçamento</h1>

        <table class="w-full mb-4">
            @foreach($budget->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="text-right">{{ $item->quantity }} x</td>
                    <td class="text-right">R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>

        <p class="text-right font-bold text-lg">
            Total: R$ {{ number_format($budget->total, 2, ',', '.') }}
        </p>

        <div class="flex gap-3 mt-6">
            <form method="POST" action="{{ route('budgets.public.approve', $budget->token) }}">
                @csrf
                <button class="px-4 py-2 bg-green-600 text-white rounded">Aprovar</button>
            </form>

            <form method="POST" action="{{ route('budgets.public.reject', $budget->token) }}">
                @csrf
                <button class="px-4 py-2 bg-red-600 text-white rounded">Reprovar</button>
            </form>

            <a href="{{ route('budgets.public.pdf', $budget->token) }}" class="px-4 py-2 bg-gray-800 text-white rounded">
                Download PDF
            </a>
        </div>
    </div>
</div>


