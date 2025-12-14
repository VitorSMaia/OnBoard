<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ExcessiveSmsAttemptsException extends Exception
{
    protected $statusCode = 429;

    public function __construct(int $waitMinutes = 5)
    {
        parent::__construct(
            "Muitas tentativas. Aguarde {$waitMinutes} minutos antes de tentar novamente."
        );
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        Log::channel('daily')->error("Muitas tentativas. Aguarde 5 minutos antes de tentar novamente.");
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request)
    {
        //
    }
}
