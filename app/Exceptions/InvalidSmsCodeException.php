<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class InvalidSmsCodeException extends Exception
{
    public function __construct()
    {
        parent::__construct('Código de verificação inválido. Tente novamente.');
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        Log::channel('daily')->error("Código de verificação inválido. Tente novamente.");
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request)
    {
        //
    }
}
