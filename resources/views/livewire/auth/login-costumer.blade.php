<div class=" bg-gray-100 p-4">
    <div class="w-full max-w-md bg-white rounded-lg shadow-xl p-8 space-y-6 border border-gray-200">


        <h2 class="text-2xl font-semibold text-center text-gray-900">
            Login na sua conta
        </h2>
        <p class="text-center text-gray-500 text-sm">
            Insira suas credenciais ou use as opções sociais.
        </p>

        @if ($errorMessage)
            <div class="p-3 text-sm text-red-700 bg-red-100 border border-red-200 rounded-lg" role="alert">
                {{ $errorMessage }}
            </div>
        @endif
        


        <div class="space-y-3">

            <livewire:components.google-login-button />
            
            <button wire:click="toggleView"
                x-data="{ isEmail: @js($view === 'email') }"
                :class="{
                    'bg-indigo-600 text-white hover:bg-indigo-700': isEmail,
                    'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50': !isEmail
                }"
                class="w-full flex items-center justify-center px-4 py-2 rounded-lg shadow-sm text-sm font-medium transition duration-150">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Alternar para Login por **SMS** (Telefone)
            </button>
        </div>

        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">
                Ou
            </span>
            <div class="absolute inset-0 -bottom-10 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
        </div>
        @error('email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        @error('password') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        @error('phone') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        @error('code') <span class="text-sm text-red-500">{{ $message }}</span> @enderror

        <form wire:submit.prevent="login" class="space-y-4">
            @csrf
            @if ($view === 'email')
                <div class="space-y-4" wire:key="email-form">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Endereço de Email
                        </label>
                        <div class="mt-1">
                            <input id="email" wire:model.live="email" type="email" required
                                placeholder="email@exemplo.com"
                                class="appearance-none block w-full px-3 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Senha
                        </label>
                        <div class="mt-1">
                            <input id="password" wire:model.live="password" type="password" required
                                placeholder="Mínimo 8 caracteres"
                                class="appearance-none block w-full px-3 py-2 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>
            @endif

            @if ($view === 'sms')
                <div class="space-y-4" wire:key="sms-form">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">
                            Número de Telefone
                        </label>
                        <div class="mt-1">
                            <input id="phone" wire:model.live="phone" type="tel" required
                                placeholder="(99) 99999-9999"
                                class="appearance-none block w-full px-3 py-2 border @error('phone') border-red-500 @else border-gray-300 @enderror rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700">
                            Código de SMS (OTP)
                        </label>
                        <div class="mt-1 flex space-x-2">
                            <input id="code" wire:model.live="code" type="text" required
                                placeholder="Código de 6 dígitos"
                                class="appearance-none block w-full px-3 py-2 border @error('code') border-red-500 @else border-gray-300 @enderror rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <button type="button" wire:click="sendSmsCode"
                                class="flex-shrink-0 px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 transition duration-150"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="sendSmsCode">Enviar Código</span>
                                <span wire:loading wire:target="sendSmsCode">Enviando...</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" wire:model.live="rememberMe" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                        Lembrar-me
                    </label>
                </div>

                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Esqueceu sua senha?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-700 transition duration-150"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="login">
                        Fazer Login
                    </span>
                    <span wire:loading wire:target="login">
                        Verificando...
                    </span>
                </button>
            </div>
        </form>

        <div class="text-center text-sm">
            Não tem uma conta?
            <a href="{{ route('auth.register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                Cadastre-se
            </a>
        </div>
    </div>
</div>