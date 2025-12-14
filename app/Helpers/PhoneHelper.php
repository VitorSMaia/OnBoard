<?php

namespace App\Helpers;

use App\Models\SmsSent;
use App\Models\User;

class PhoneHelper
{
    public static function hasExcessiveAttempts(string $phone, int $limit = 3, int $minutes = 5): bool
    {
        $attempts = SmsSent::where('phone_number', $phone)
            ->where('created_at', '>=', now()->subMinutes($minutes))
            ->count();

        return $attempts >= $limit;
    }

    public static function hasUserWithPhone(string $phone): int
    {
        $user = User::where('phone', $phone)
            ->firstOrFail();

        return $user->id;
    }

    /**
     * Validar formato do telefone brasileiro
     * Aceita: 11999999999 ou +5511999999999
     */
    public static function validatePhoneFormat(string $phone): bool
    {
        // Validar com +55 no início
        if (preg_match('/^\+55\d{11}$/', $phone) === 1) {
            return true;
        }

        // Validar apenas 11 dígitos
        if (preg_match('/^\d{11}$/', $phone) === 1) {
            return true;
        }

        return false;
    }

    /**
     * Formatar telefone para o padrão +55XXXXXXXXXXX
     */
    public static function format(string $phone): string
    {
        // Remove caracteres especiais
        $phone = preg_replace('/[^\d]/', '', $phone);

        // Se tiver 11 dígitos, adiciona +55
        if (strlen($phone) === 11) {
            return '+55' . $phone;
        }

        // Se já tiver 13 (55 + 11), apenas adiciona +
        if (strlen($phone) === 13) {
            return '+' . $phone;
        }

        return $phone;
    }

    /**
     * Remover formatação do telefone
     */
    public static function removeFormat(string $phone): string
    {
        return preg_replace('/[^\d]/', '', $phone);
    }

    /**
     * Formatar para exibição: (11) 99999-9999
     */
    public static function formatDisplay(string $phone): string
    {
        $clean = self::removeFormat($phone);

        if (strlen($clean) === 11) {
            return sprintf(
                "(%s) %s-%s",
                substr($clean, 0, 2),
                substr($clean, 2, 5),
                substr($clean, 7)
            );
        }

        if (strlen($clean) === 13) {
            // Remove o 55 do início para exibir
            $clean = substr($clean, 2);
            return sprintf(
                "(%s) %s-%s",
                substr($clean, 0, 2),
                substr($clean, 2, 5),
                substr($clean, 7)
            );
        }

        return $phone;
    }

    /**
     * Extrair apenas os dígitos do telefone
     */
    public static function getDigits(string $phone): string
    {
        return self::removeFormat($phone);
    }

    /**
     * Verificar se o telefone é válido (não é sequência numérica)
     */
    public static function isValid(string $phone): bool
    {
        if (!self::validatePhoneFormat($phone)) {
            return false;
        }

        $digits = self::getDigits($phone);

        // Remover 55 do início se existir
        if (str_starts_with($digits, '55')) {
            $digits = substr($digits, 2);
        }

        // Verificar se não é uma sequência (como 11111111111)
        if (preg_match('/^(\d)\1{10}$/', $digits)) {
            return false;
        }

        return true;
    }

    /**
     * Adicionar código de país (55)
     */
    public static function addCountryCode(string $phone): string
    {
        $clean = self::removeFormat($phone);

        if (str_starts_with($clean, '55')) {
            return '+' . $clean;
        }

        return '+55' . $clean;
    }
}