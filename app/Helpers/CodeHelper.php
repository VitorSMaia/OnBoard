<?php

namespace App\Helpers;

class CodeHelper
{
    /**
     * Gerar código de verificação de 6 dígitos
     */
    public static function generateCode(int $digits = 6): string
    {
        $max = pow(10, $digits) - 1;
        return str_pad(random_int(0, $max), $digits, '0', STR_PAD_LEFT);
    }

    /**
     * Gerar código numérico simples
     */
    public static function generate(int $digits = 6): string
    {
        return self::generateCode($digits);
    }
}