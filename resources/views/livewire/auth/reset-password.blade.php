<div class="flex items-center justify-center bg-gray-100 p-4">
    <div class="w-full max-w-md bg-white rounded-lg shadow-xl p-8 space-y-6 border border-gray-200">
        
        <div class="text-center">
            <h2 class="text-2xl font-semibold text-gray-900">
                Nova Senha
            </h2>
            <p class="mt-2 text-sm text-gray-500">
                Redefina sua senha abaixo.
            </p>
        </div>

        @if ($statusMessage)
            <div class="p-4 text-sm 
                @if($resetSuccess) text-green-700 bg-green-100 border border-green-200
                @else text-red-700 bg-red-100 border border-red-200
                @endif rounded-lg" role="alert">
                <p>{{ $statusMessage }}</p>
                <div class="mt-4 text-center">
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 text-sm">
                        Ir para o Login
                    </a>
                </div>
            </div>
        @endif

        @if (!$resetSuccess)
            <form wire:submit.prevent="resetPassword" class="space-y-4">
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email
                    </label>
                    <div class="mt-1">
                        <input id="email" wire:model="email" type="email" required readonly
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 bg-gray-50 rounded-lg shadow-sm sm:text-sm">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div x-data="{ show: false }">
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Nova Senha
                    </label>
                    <div class="mt-1 relative">
                        <input id="password" wire:model.live="password" :type="show ? 'text' : 'password'" required
                            class="appearance-none block w-full px-3 py-2 pr-10 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-lg shadow-sm sm:text-sm">

                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 text-gray-600 hover:text-gray-900 transition duration-150">
                            <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7a10.05 10.05 0 011.875.175M16 12a4 4 0 11-8 0 4 4 0 018 0zM20 12l-1.5 1.5M4 12l1.5 1.5M.5 12l-1.5 1.5M23.5 12l-1.5 1.5M3 3l18 18"></path></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div x-data="{ showConfirmation: false }"> <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirmar Nova Senha
                    </label>
                    <div class="mt-1 relative">
                        <input id="password_confirmation" wire:model.live="passwordConfirmation" :type="showConfirmation ? 'text' : 'password'" required
                            class="appearance-none block w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg shadow-sm sm:text-sm">
                        
                        <button type="button" @click="showConfirmation = !showConfirmation"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 text-gray-600 hover:text-gray-900 transition duration-150">
                            <svg x-show="!showConfirmation" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="showConfirmation" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7a10.05 10.05 0 011.875.175M16 12a4 4 0 11-8 0 4 4 0 018 0zM20 12l-1.5 1.5M4 12l1.5 1.5M.5 12l-1.5 1.5M23.5 12l-1.5 1.5M3 3l18 18"></path></svg>
                        </button>
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="resetPassword">
                            Redefinir Senha
                        </span>
                        <span wire:loading wire:target="resetPassword">
                            Confirmando...
                        </span>
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>