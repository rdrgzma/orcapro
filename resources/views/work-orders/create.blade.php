<x-layouts.app :title="__('Create Work Order')">
    <div class="p-6">
        <div class="bg-white shadow rounded">
            <div class="p-4 border-b">
                <h2 class="text-lg font-bold">Create Work Order</h2>
            </div>
            <div class="p-4">
                <form action="{{ route('work-orders.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                        <select name="client_id" id="client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="delivered">Delivered</option>
                            <option value="canceled">Canceled</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn bg-blue-600 text-white p-2 rounded">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
