<x-layouts.app :title="__('Work Order')">
    <div class="p-6">
        <div class="bg-white shadow rounded">
            <div class="p-4 border-b">
                <h2 class="text-lg font-bold">Work Order #{{ $workOrder->number }}</h2>
            </div>
            <div class="p-4">
                <p><strong>Status:</strong> {{ $workOrder->status }}</p>
            </div>
        </div>
    </div>
</x-layouts.app>
