<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ExpiredSmsCodeException extends Exception
{
    public function __construct(int $expirationMinutes = 5)
    {
        parent::__construct(
            "Código expirado. Você tem {$expirationMinutes} minutos para verificar."
        );
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        Log::channel('daily')->error("Código expirado. Você tem 5 minutos para verificar.");
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request)
    {
        //
    }
}
