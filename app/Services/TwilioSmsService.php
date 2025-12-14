<?php

namespace App\Services;

use Twilio\Rest\Client;
use Exception;

class TwilioSmsService
{
    protected $twilio;
    protected $fromNumber;

    /**
     * Inicializa o cliente Twilio com as credenciais do arquivo de configuração.
     */
    public function __construct()
    {
        // Pega as credenciais do arquivo config/twilio.php
        $sid = config('twilio.sid');
        $token = config('twilio.token');
        $this->fromNumber = config('twilio.from');

        if (empty($sid) || empty($token) || empty($this->fromNumber)) {
             throw new Exception("As credenciais Twilio não foram configuradas corretamente.");
        }

        $this->twilio = new Client($sid, $token);
    }

    /**
     * Envia uma mensagem SMS.
     *
     * @param string $to O número de telefone de destino (Ex: +5511988887777)
     * @param string $message O corpo da mensagem
     * @return \Twilio\Rest\Api\V2010\Account\MessageInstance|null
     */
    public function sendSMS(string $to, string $message)
    {
        try {
            $messageInstance = $this->twilio->messages->create(
                $to,
                [
                    'from' => $this->fromNumber, // Seu número Twilio configurado
                    'body' => $message,
                ]
            );

            // Retorna a instância da mensagem para verificação (SID, status, etc.)
            return $messageInstance;

        } catch (Exception $e) {
            // Loga o erro para depuração
            \Log::error("Erro ao enviar SMS via Twilio: " . $e->getMessage(), [
                'to' => $to,
                'from' => $this->fromNumber,
            ]);
            
            // Você pode relançar a exceção ou retornar null/false
            return null;
        }
    }
}