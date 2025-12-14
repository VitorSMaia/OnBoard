<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Actions\GoogleLoginAction;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirecionar para Google
     */
    public function redirect()
    {
        return Socialite::driver('google')
            ->scopes(['profile', 'email'])
            ->redirect();
    }

    /**
     * Callback do Google
     */
    public function callback(GoogleLoginAction $action)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Fazer login/registro
            $user = $action->execute($googleUser);

            return redirect()->route('dashboard')
                ->with('success', 'Bem-vindo ' . $user->name . '!');

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Erro ao fazer login com Google. Tente novamente.');
        }
    }
}