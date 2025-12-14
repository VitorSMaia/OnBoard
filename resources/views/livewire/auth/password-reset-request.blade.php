<div class="flex items-center justify-center bg-gray-100 p-4">
    <div class="w-full max-w-md bg-white rounded-lg shadow-xl p-8 space-y-6 border border-gray-200">
        <div class="text-center">
            <h2 class="mt-2 text-2xl font-semibold text-gray-900">
                Esqueceu sua Senha?
            </h2>
            <p class="mt-2 text-sm text-gray-500">
                Informe seu endereço de e-mail abaixo.
            </p>
        </div>

        @if ($status === 'sent')
            <div class="p-4 text-sm text-green-700 bg-green-100 border border-green-200 rounded-lg" role="alert">
                <p class="font-bold mb-1">Link Enviado!</p>
                {!! nl2br(e($message)) !!}
                <div class="mt-4 text-center">
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 text-sm">
                        Voltar para o Login
                    </a>
                </div>
            </div>
        @endif

        @if ($status === 'request')
            @if ($message)
                <div class="p-3 text-sm @if(strpos($message, 'Não foi possível') !== false) text-red-700 bg-red-100 border border-red-200 @else text-green-700 bg-green-100 border border-green-200 @endif rounded-lg" role="alert">
                    {{ $message }}
                </div>
            @endif

            <form wire:submit.prevent="sendResetLink" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Endereço de Email
                    </label>
                    <div class="mt-1">
                        <input id="email" wire:model.live="email" type="email" required autofocus
                            placeholder="email@exemplo.com"
                            class="appearance-none block w-full px-3 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="sendResetLink">
                            Enviar Link de Redefinição
                        </span>
                        <span wire:loading wire:target="sendResetLink">
                            Enviando...
                        </span>
                    </button>
                </div>
            </form>

            <div class="text-center text-sm">
                <a href="{{ route('login') }}" class="font-medium text-gray-600 hover:text-gray-900">
                    Lembrei minha senha
                </a>
            </div>
        @endif
    </div>
</div>