<?php

namespace App\Actions;

use App\Models\User;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class GoogleLoginAction
{
    /**
     * Fazer login ou registrar via Google
     */
    public function execute(SocialiteUser $googleUser): User
    {
                
        // Buscar conta social existente
        $socialAccount = SocialAccount::where('provider', 'google')
            ->where('provider_id', $googleUser->getId())
            ->first();

        // Se a conta social existe, fazer login
        if ($socialAccount) {
            $user = $socialAccount->user;
            Auth::login($user, remember: true);
            session()->regenerate();
            return $user;
        }
Log::info(json_encode($googleUser));

        // Verificar se já existe usuário com esse email
        $user = User::where('email', $googleUser->getEmail())->first();
Log::info(json_encode($user));
        // Se existe, vincular a conta social
        if ($user) {
            $this->attachSocialAccount($user, $googleUser, 'google');
            Auth::login($user, remember: true);
            session()->regenerate();
            return $user;
        }
Log::info('aqui');
        // Criar novo usuário
        $user = $this->createUserFromGoogle($googleUser);
 Log::info(json_encode($user));   
        // Fazer login
        Auth::login($user, remember: true);
        session()->regenerate();

        return $user;
    }

    /**
     * Criar novo usuário a partir do Google
     */
    private function createUserFromGoogle(SocialiteUser $googleUser): User
    {
        $user = User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'phone' => '',
            'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(32)),
            'email_verified_at' => now(), // Já verificado pelo Google
            'avatar' => $googleUser->getAvatar(),
        ]);

        // Vincular conta social
        $this->attachSocialAccount($user, $googleUser, 'google');

        return $user;
    }

    /**
     * Vincular conta social ao usuário
     */
    private function attachSocialAccount(User $user, SocialiteUser $googleUser, string $provider): void
    {
        $user->socialAccounts()->updateOrCreate(
            ['provider' => $provider, 'provider_id' => $googleUser->getId()],
            ['provider_token' => $googleUser->token]
        );
    }
}