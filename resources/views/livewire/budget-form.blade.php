<div class="bg-white shadow rounded-lg p-6">
    <form wire:submit.prevent="save">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nome do Orçamento</label>
            <input type="text" id="name" wire:model="budget.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @error('budget.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <h3 class="text-lg font-medium text-gray-900 mb-4">Itens</h3>

        @foreach($items as $index => $item)
            <div class="grid grid-cols-5 gap-4 mb-4 items-end">
                <div>
                    <label for="item-name-{{$index}}" class="block text-sm font-medium text-gray-700">Nome do Item</label>
                    <input type="text" id="item-name-{{$index}}" wire:model="items.{{$index}}.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('items.'.$index.'.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="col-span-2">
                    <label for="item-description-{{$index}}" class="block text-sm font-medium text-gray-700">Descrição</label>
                    <input type="text" id="item-description-{{$index}}" wire:model="items.{{$index}}.description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="item-quantity-{{$index}}" class="block text-sm font-medium text-gray-700">Quantidade</label>
                    <input type="number" id="item-quantity-{{$index}}" wire:model.live="items.{{$index}}.quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="item-price-{{$index}}" class="block text-sm font-medium text-gray-700">Preço Unitário</label>
                    <input type="number" step="0.01" id="item-price-{{$index}}" wire:model.live="items.{{$index}}.unit_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="item-total-{{$index}}" class="block text-sm font-medium text-gray-700">Total</label>
                    <input type="number" step="0.01" id="item-total-{{$index}}" wire:model="items.{{$index}}.total" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                </div>
                <div class="col-span-5 flex justify-end">
                    <button type="button" wire:click="removeItem({{$index}})" class="text-red-500 hover:text-red-700">Remover</button>
                </div>
            </div>
        @endforeach

        <button type="button" wire:click="addItem" class="mb-4 text-sm text-blue-600 hover:text-blue-800">Adicionar Item</button>

        <h3 class="text-lg font-medium text-gray-900 mb-4">Totais</h3>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="discount-value" class="block text-sm font-medium text-gray-700">Desconto</label>
                <input type="number" step="0.01" id="discount-value" wire:model.live="budget.discount_value" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label for="discount-type" class="block text-sm font-medium text-gray-700">Tipo de Desconto</label>
                <select id="discount-type" wire:model.live="budget.discount_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="percent">Porcentagem</option>
                    <option value="fixed">Fixo</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
                <label for="tax-value" class="block text-sm font-medium text-gray-700">Imposto</label>
                <input type="number" step="0.01" id="tax-value" wire:model.live="budget.tax_value" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label for="tax-type" class="block text-sm font-medium text-gray-700">Tipo de Imposto</label>
                <select id="tax-type" wire:model.live="budget.tax_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="percent">Porcentagem</option>
                    <option value="fixed">Fixo</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <label for="additional-fees" class="block text-sm font-medium text-gray-700">Taxas Adicionais</label>
            <input type="number" step="0.01" id="additional-fees" wire:model.live="budget.additional_fees" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>

        <div class="mt-6">
            <h3 class="text-lg font-medium">Subtotal: R$ {{ number_format($budget->subtotal, 2, ',', '.') }}</h3>
            <h3 class="text-xl font-bold">Total: R$ {{ number_format($budget->total, 2, ',', '.') }}</h3>
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Salvar Orçamento</button>
        </div>
    </form>
</div>
