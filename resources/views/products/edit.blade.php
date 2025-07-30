<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Produto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-full">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Informações do Produto
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Atualize os dados cadastrais do produto "{{ $product->name }}".
                            </p>
                        </header>

                        {{-- Exibição de Erros --}}
                        @if ($errors->any())
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 my-4" role="alert">
                                <p class="font-bold">Opa! Verifique os erros:</p>
                                <ul class="mt-2 list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="post" action="{{ route('products.update', $product->id) }}" class="mt-6 space-y-6">
                            @csrf
                            @method('PUT')

                            <div>
                                <x-input-label for="name" :value="__('Nome do Produto')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product->name)" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="price" :value="__('Preço (R$)')" />
                                <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full" :value="old('price', $product->price)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('price')" />
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Descrição (Opcional)')" />
                                <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="4">{{ old('description', $product->description) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Atualizar Produto') }}</x-primary-button>
                                <a href="{{ route('products.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                    Cancelar
                                </a>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>