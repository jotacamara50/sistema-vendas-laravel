<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Histórico de Vendas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Card de Filtros --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Filtrar Vendas</h2>
                <form action="{{ route('sales.index') }}" method="GET">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <x-input-label for="start_date" value="Data Inicial" />
                            <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="request('start_date')" />
                        </div>
                        <div>
                            <x-input-label for="end_date" value="Data Final" />
                            <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="request('end_date')" />
                        </div>
                        <div>
                            <x-input-label for="client_id" value="Cliente" />
                            <select name="client_id" id="client_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Todos os clientes</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" @selected(request('client_id') == $client->id)>{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end space-x-2">
                            <x-primary-button class="w-full justify-center">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                                Filtrar
                            </x-primary-button>
                            <a href="{{ route('sales.index') }}" class="w-full">
                                <x-secondary-button class="w-full justify-center">
                                    Limpar
                                </x-secondary-button>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Mensagens de feedback --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                    <p class="font-bold">Sucesso</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <p class="font-bold">Erro</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- Card da Lista de Vendas --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Lista de Vendas</h2>
                        <a href="{{ route('sales.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Nova Venda
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vendedor</th>
                                    <th class="py-3 px-6 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valor Total</th>
                                    <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Parcelas</th>
                                    <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($sales as $sale)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td class="py-4 px-6 whitespace-nowrap">#{{ $sale->id }}</td>
                                        <td class="py-4 px-6 whitespace-nowrap">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}</td>
                                        <td class="py-4 px-6 whitespace-nowrap">{{ $sale->client->name ?? 'N/A' }}</td>
                                        <td class="py-4 px-6 whitespace-nowrap">{{ $sale->user->name }}</td>
                                        <td class="py-4 px-6 whitespace-nowrap text-right font-medium">R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</td>
                                        <td class="py-4 px-6 whitespace-nowrap text-center">{{ $sale->installments_count }}x</td>
                                        <td class="py-4 px-6 whitespace-nowrap text-center">
                                            <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta venda? Esta ação não pode ser desfeita.');">
                                                <a href="{{ route('sales.edit', $sale->id) }}" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 ml-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-10 text-gray-500 dark:text-gray-400">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                <p class="mt-2">Nenhuma venda registrada ainda.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $sales->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>