<?php

namespace App\Actions;

use App\Models\SmsSent;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class AutoLoginUserAction
{
    /**
     * Fazer login automático do usuário após verificação de SMS
     */
    public function execute(SmsSent $sms): User
    {
        // Validar se o SMS foi verificado
        if ($sms->status !== 'verified' || !$sms->verified_at) {
            throw new Exception('SMS não foi verificado.');
        }

        // Obter usuário associado ao SMS
        $user = $sms->user;

        if (!$user) {
            throw new Exception('Usuário não encontrado.');
        }

        // Fazer login do usuário
        Auth::login($user, remember: true);

        // Opcionalmente: registrar o login na sessão
        session()->regenerate();

        return $user;
    }

    /**
     * Login por telefone (quando o número já existe na base)
     */
    public function executeByPhone(string $phone): User
    {
        // Formatar telefone se necessário
        $phoneFormatted = \App\Helpers\PhoneHelper::addCountryCode($phone);

        // Buscar usuário pelo telefone
        $user = User::where('phone', $phone)
            ->orWhere('phone', $phoneFormatted)
            ->first();

        if (!$user) {
            throw new Exception('Usuário com esse telefone não encontrado.');
        }

        // Fazer login
        Auth::login($user, remember: true);
        session()->regenerate();

        return $user;
    }

    /**
     * Criar novo usuário e fazer login após SMS verificado
     */
    public function createAndLogin(string $phone, array $data = []): User
    {
        // Formatar telefone
        $phoneFormatted = \App\Helpers\PhoneHelper::addCountryCode($phone);
        $phoneClean = \App\Helpers\PhoneHelper::getDigits($phone);

        // Verificar se o usuário já existe
        $existingUser = User::where('phone', $phoneClean)
            ->orWhere('phone', $phoneFormatted)
            ->first();

        if ($existingUser) {
            return $this->executeByPhone($phone);
        }

        // Criar novo usuário
        $user = User::create([
            'name' => $data['name'] ?? 'Usuário',
            'email' => $data['email'] ?? null,
            'phone' => $phoneClean,
            'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(32)),
            'email_verified_at' => now(), // Considerar verificado por SMS
        ]);

        // Fazer login
        Auth::login($user, remember: true);
        session()->regenerate();

        return $user;
    }

    /**
     * Login utiliznado email e senha
     */
    public function loginWithEmailAndPassword(string $email, string $password): bool
    {
       $credentialsConfirm = Auth::attempt(['email' => $email, 'password' => $password]);

        if ($credentialsConfirm) {
            $user = Auth::user();
            Auth::login($user, remember: true);
            return true;
        }

        return false;
    }
}