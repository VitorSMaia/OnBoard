<?php

namespace App\Actions;

use App\Exceptions\ExpiredSmsCodeException;
use App\Exceptions\InvalidPhoneFormatException;
use App\Exceptions\InvalidSmsCodeException;
use App\Exceptions\PendingSmsCodNotFoundException;
use App\Helpers\PhoneHelper;
use App\Models\SmsSent;
use Carbon\Carbon;


class VerifySmsCodeAction
{
    /**
     * Verificar código SMS
     */
    public function execute(string $phone, string $code): SmsSent
    {
        // Validar formato do telefone
        if (!PhoneHelper::validatePhoneFormat($phone)) {
            throw new InvalidPhoneFormatException($phone);
        }

        $phoneFormatted = $phone;

        // Buscar o SMS mais recente não verificado
        $sms = SmsSent::where('phone_number', $phoneFormatted)
            ->where('code', $code)
            ->where('status', 'sent')
            ->first();

        if (!$sms) {
            throw new PendingSmsCodNotFoundException($phone);
        }

        // Verificar se o código está correto
        if ($sms->code !== $code) {
            throw new InvalidSmsCodeException();
        }

        $expirationMinutes = 5;
        // Verificar se o código expirou (5 minutos)
        if (Carbon::parse($sms->sent_at)->diffInMinutes(now()) > $expirationMinutes) {
            throw new ExpiredSmsCodeException($expirationMinutes);
        }

        // Marcar como verificado
        $sms->update([
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        // Atualizar número de telefone do usuário
        if (auth()->check()) {
            auth()->user()->update(['phone' => $phone]);
        }

     

        return $sms;
    }
}
