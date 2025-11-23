<html>
<head>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        td,th { border-bottom: 1px solid #ccc; padding: 6px; }
    </style>
</head>
<body>
<h2>Orçamento #{{ $budget->number }}</h2>

<table>
    <thead>
    <tr><th>Item</th><th>Qtd</th><th>Unit</th><th>Total</th></tr>
    </thead>
    <tbody>
    @foreach($budget->items as $item)
        <tr>
            <td>{{ $item->description }}</td>
            <td>{{ $item->quantity }}</td>
            <td>R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
            <td>R$ {{ number_format($item->total, 2, ',', '.') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<h3 style="text-align:right">TOTAL: R$ {{ number_format($budget->total, 2, ',', '.') }}</h3>
</body>
</html>
