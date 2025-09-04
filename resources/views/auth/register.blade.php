<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Criar Conta no AgroPerto</h2>
        <p class="text-gray-600">Escolha o tipo de conta que melhor se adequa ao seu perfil</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Tipo de Usuário -->
        <div class="mb-6">
            <x-input-label for="type" value="Tipo de Conta" class="mb-3 text-base font-semibold" />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Produtor -->
                <label class="relative cursor-pointer">
                    <input type="radio" name="type" value="producer" class="sr-only peer" {{ old('type') === 'producer' ? 'checked' : '' }} required>
                    <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 hover:border-green-300 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-green-100 rounded-full">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Sou Produtor</h3>
                                <p class="text-sm text-gray-600">Quero vender meus produtos</p>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Consumidor -->
                <label class="relative cursor-pointer">
                    <input type="radio" name="type" value="consumer" class="sr-only peer" {{ old('type') === 'consumer' ? 'checked' : '' }} required>
                    <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-blue-300 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-blue-100 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Sou Consumidor</h3>
                                <p class="text-sm text-gray-600">Quero comprar produtos</p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
            <x-input-error :messages="$errors->get('type')" class="mt-2" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nome Completo" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Senha" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmar Senha" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" href="{{ route('login') }}">
                Já tem uma conta?
            </a>

            <x-primary-button class="bg-green-600 hover:bg-green-700 focus:bg-green-700 active:bg-green-900">
                Criar Conta
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
