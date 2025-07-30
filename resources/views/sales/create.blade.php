<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registrar Nova Venda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Card Principal --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-full">
                    
                    {{-- Exibição de Erros --}}
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Opa! Verifique os erros:</p>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Erro</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('sales.store') }}" method="POST" id="sale-form" class="space-y-6">
                        @csrf
                        
                        {{-- Seção 1: Cliente e Itens --}}
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    1. Cliente e Itens da Venda
                                </h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Selecione o cliente (se aplicável) e adicione os produtos para esta venda.
                                </p>
                            </header>

                            <div class="mt-6 space-y-4">
                                <div>
                                    <x-input-label for="client_id" value="Cliente (Opcional)" />
                                    <select name="client_id" id="client_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">Nenhum cliente selecionado</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                    <h3 class="text-md font-medium text-gray-900 dark:text-gray-100">Adicionar Produto</h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-6 gap-4 items-end mt-2">
                                        <div class="sm:col-span-3">
                                            <x-input-label for="product_search" value="Produto" />
                                            <select id="product_search" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                                <option value="">Selecione...</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }} (R$ {{ number_format($product->price, 2, ',', '.') }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <x-input-label for="item_quantity" value="Qtd." />
                                            <x-text-input type="number" id="item_quantity" value="1" min="1" class="mt-1 block w-full"/>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <x-secondary-button type="button" id="add-product-btn" class="w-full justify-center !bg-green-600 hover:!bg-green-500 !text-white">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                                Adicionar
                                            </x-secondary-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- Tabela de Itens --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Produto</th>
                                        <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Qtd.</th>
                                        <th class="py-3 px-6 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Preço Unit.</th>
                                        <th class="py-3 px-6 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subtotal</th>
                                        <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ação</th>
                                    </tr>
                                </thead>
                                <tbody id="sale-items-table" class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
                            </table>
                        </div>
                        <div class="text-right">
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Total: <span id="total-amount-display">R$ 0,00</span></h3>
                            <input type="hidden" id="total_amount_hidden" value="0">
                        </div>

                        {{-- Seção 2: Pagamento --}}
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    2. Pagamento e Parcelas
                                </h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Defina a forma de pagamento e gere o plano de parcelas para esta venda.
                                </p>
                            </header>

                            <div class="mt-6 grid grid-cols-1 sm:grid-cols-6 gap-4 items-end">
                                <div class="sm:col-span-2">
                                    <x-input-label for="payment_method_id" value="Forma de Pagamento" />
                                    <select name="payment_method_id" id="payment_method_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                        @foreach($paymentMethods as $pm)
                                            <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sm:col-span-1">
                                    <x-input-label for="installments_count_input" value="Nº Parcelas" />
                                    <x-text-input type="number" id="installments_count_input" value="1" min="1" class="mt-1 block w-full"/>
                                </div>
                                <div class="sm:col-span-2">
                                    <x-input-label for="first_due_date" value="1º Vencimento" />
                                    <x-text-input type="date" id="first_due_date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full"/>
                                </div>
                                <div class="sm:col-span-1">
                                    <x-secondary-button type="button" id="generate-installments-btn" class="w-full justify-center !bg-purple-600 hover:!bg-purple-500 !text-white">
                                        Gerar
                                    </x-secondary-button>
                                </div>
                            </div>
                        </section>

                        {{-- Tabela de Parcelas --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vencimento</th>
                                        <th class="py-3 px-6 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valor (R$)</th>
                                    </tr>
                                </thead>
                                <tbody id="installments-table" class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
                            </table>
                        </div>

                        {{-- Botões Finais --}}
                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>Finalizar Venda</x-primary-button>
                            <a href="{{ route('sales.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // O SCRIPT JAVASCRIPT CONTINUA O MESMO
        $(document).ready(function() {
            // --- LÓGICA DOS ITENS ---
            $('#add-product-btn').on('click', function() {
                const productSelect = $('#product_search');
                const productId = productSelect.val();
                if (!productId) { return; }
                const selectedOption = productSelect.find('option:selected');
                const productName = selectedOption.text().split(' (R$')[0];
                const productPrice = parseFloat(selectedOption.data('price'));
                const quantity = parseInt($('#item_quantity').val());
                if ($(`#sale-items-table tr[data-product-id="${productId}"]`).length > 0) { alert('Este produto já foi adicionado.'); return; }
                const subtotal = quantity * productPrice;
                const newRow = `<tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200" data-product-id="${productId}"><td class="py-4 px-6">${productName}<input type="hidden" name="items[${productId}][product_id]" value="${productId}"></td><td class="py-4 px-6 text-center"><input type="number" name="items[${productId}][quantity]" value="${quantity}" min="1" class="w-20 text-center border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm item-quantity" data-price="${productPrice}"></td><td class="py-4 px-6 text-right">R$ ${productPrice.toFixed(2).replace('.', ',')}</td><td class="py-4 px-6 text-right subtotal">R$ ${subtotal.toFixed(2).replace('.', ',')}</td><td class="py-4 px-6 text-center"><button type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 remove-item-btn"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></td></tr>`;
                $('#sale-items-table').append(newRow);
                updateTotal();
            });

            $('#sale-items-table').on('click', '.remove-item-btn', function() {
                $(this).closest('tr').remove();
                updateTotal();
            });

            $('#sale-items-table').on('input', '.item-quantity', function() {
                updateTotal();
            });

            function updateTotal() {
                let total = 0;
                $('#sale-items-table tr').each(function() {
                    const quantityInput = $(this).find('.item-quantity');
                    const quantity = parseInt(quantityInput.val()) || 0;
                    const price = parseFloat(quantityInput.data('price'));
                    const subtotal = quantity * price;
                    $(this).find('.subtotal').text(`R$ ${subtotal.toFixed(2).replace('.', ',')}`);
                    total += subtotal;
                });
                $('#total-amount-display').text(`R$ ${total.toFixed(2).replace('.', ',')}`);
                $('#total_amount_hidden').val(total);
            }

            // --- LÓGICA DAS PARCELAS ---
            $('#generate-installments-btn').on('click', function() {
                const totalAmount = parseFloat($('#total_amount_hidden').val());
                if (totalAmount <= 0) { alert("Adicione produtos à venda antes de gerar as parcelas."); return; }
                const installmentsCount = parseInt($('#installments_count_input').val());
                const firstDueDateStr = $('#first_due_date').val();
                if (!firstDueDateStr) { alert("Por favor, informe a data do primeiro vencimento."); return; }
                const installmentValue = Math.round((totalAmount / installmentsCount) * 100) / 100;
                let totalCalculated = 0;
                const installmentsTable = $('#installments-table');
                installmentsTable.empty();
                for (let i = 1; i <= installmentsCount; i++) {
                    let currentInstallmentValue = installmentValue;
                    if (i === installmentsCount) { currentInstallmentValue = totalAmount - totalCalculated; }
                    totalCalculated += currentInstallmentValue;
                    let dueDate = new Date(firstDueDateStr + 'T12:00:00');
                    dueDate.setMonth(dueDate.getMonth() + (i - 1));
                    let formattedDate = dueDate.toISOString().split('T')[0];
                    const newRow = `<tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"><td class="py-4 px-6 text-center">${i}<input type="hidden" name="installments[${i-1}][installment_number]" value="${i}"></td><td class="py-4 px-6"><input type="date" name="installments[${i-1}][due_date]" value="${formattedDate}" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></td><td class="py-4 px-6 text-right"><input type="number" name="installments[${i-1}][value]" value="${currentInstallmentValue.toFixed(2)}" step="0.01" class="w-32 text-right border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></td></tr>`;
                    installmentsTable.append(newRow);
                }
            });

            // --- VALIDAÇÃO FINAL ---
            $('#sale-form').on('submit', function(event) {
                if ($('#sale-items-table tr').length === 0) {
                    alert('Você precisa adicionar pelo menos um produto à venda.');
                    event.preventDefault(); return;
                }
                if ($('#installments-table tr').length === 0) {
                    alert('Você precisa gerar as parcelas antes de finalizar a venda.');
                    event.preventDefault(); return;
                }
            });
        });
    </script>
</x-app-layout>