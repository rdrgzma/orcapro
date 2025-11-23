<x-layouts.app :title="__('Budget')">
<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">
    <h1 class="text-xl font-bold mb-4">Orçamento: {{ $budget->number }}</h1>

    <p>Total: <strong>R$ {{ number_format($budget->total,2,',','.') }}</strong></p>
    <p>Status: {{ $budget->status }}</p>

    <a class="btn bg-blue-600 text-white p-2 rounded block mt-4"
       href="{{ route('budgets.public.show', $budget->token) }}" target="_blank">
        Link para o cliente
    </a>

    @if($budget->status === 'approved')
        <form method="POST" action="{{ route('budgets.convert', $budget) }}" class="mt-4">
            @csrf
            <button class="btn bg-green-600 text-white p-2 rounded">
                Gerar Ordem de Serviço
            </button>
        </form>
    @endif

    @php
    if($budget->client->phone) {
        $waText = urlencode("Olá! Segue seu orçamento:\n".route('budgets.public.show', $budget->token));

        $waLink = "https://wa.me/55{$budget->client->phone}?text={$waText}";
       }
    @endphp

    @if($budget->client && $budget->client->phone)
        <a href="{{ $waLink }}" target="_blank"
           class="btn bg-green-600 text-white p-2 rounded block mt-3">
            Enviar por WhatsApp 📲
        </a>
    @endif

</div>
</x-layouts.app>
