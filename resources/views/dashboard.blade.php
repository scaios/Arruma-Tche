<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Painel de Controle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- INÍCIO DA NOSSA ALTERAÇÃO --}}
                    
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Bem-vindo(a) de volta!</h3>
                    
                    <p class="mb-4">
                        A partir desta página você pode gerenciar os dados da sua conta.
                    </p>

                    <a href="{{ route('home') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                        ← Voltar para a Página Inicial
                    </a>
                    
                    {{-- FIM DA NOSSA ALTERAÇÃO --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>