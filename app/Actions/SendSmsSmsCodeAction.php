<?php

namespace App\Actions;

use App\Exceptions\ExcessiveSmsAttemptsException;
use App\Exceptions\InvalidPhoneFormatException;
use App\Helpers\CodeHelper;
use App\Helpers\PhoneHelper;
use App\Models\SmsSent;
use App\Services\TwilioSmsService;
use Illuminate\Support\Facades\Log;
use Loggable;

class SendSmsSmsCodeAction
{
    protected $twilioSmsService;

    public function __construct(TwilioSmsService $twilioSmsService) {
        $this->twilioSmsService = $twilioSmsService;
    }

    /**
     * Enviar código SMS e salvar na base de dados
     */
    public function execute(string $phone, ?int $userId = null): SmsSent
    {
        

        // Validar formato do telefone
        if (!PhoneHelper::validatePhoneFormat($phone)) {
            throw new InvalidPhoneFormatException($phone);
        }

        // Verificar tentativas excessivas
        if (PhoneHelper::hasExcessiveAttempts($phone)) {
            throw new ExcessiveSmsAttemptsException();
        }

        $code = CodeHelper::generateCode();
        $phoneFormatted = $phone;
        $message = "Bem-vindo(a)! Seu código de verificação é: $code";

        try {
            // Enviar SMS via serviço
            // $this->twilioSmsService->sendSMS($phoneFormatted, $message);

            // Salvar na base de dados
            $sms = SmsSent::create([
                'user_id' => PhoneHelper::hasUserWithPhone($phone) ?? auth()->id(),
                'phone_number' => $phoneFormatted,
                'code' => $code,
                'message' => $message,
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            return $sms;
        } catch (\Exception $e) {
            // Salvar com status failed
            $sms = SmsSent::create([
                'user_id' => $userId ?? auth()->id(),
                'phone_number' => $phoneFormatted,
                'code' => $code,
                'message' => $message,
                'status' => 'failed',
            ]);

            throw $e;
        }
    }
}