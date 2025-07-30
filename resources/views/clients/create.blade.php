<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Novo Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-full">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Cadastrar Novo Cliente
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Preencha os dados abaixo para registrar um novo cliente no sistema.
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

                        <form method="post" action="{{ route('clients.store') }}" class="mt-6 space-y-6">
                            @csrf

                            <div>
                                <x-input-label for="name" :value="__('Nome')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email (Opcional)')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" autocomplete="email" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div>
                                <x-input-label for="phone" :value="__('Telefone (Opcional)')" />
                                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" autocomplete="tel" />
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Salvar Cliente') }}</x-primary-button>
                                <a href="{{ route('clients.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
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