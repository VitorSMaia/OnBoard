<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SmsSendingFailedException extends Exception
{
    protected $statusCode = 500;

    public function __construct(string $message = '')
    {
        parent::__construct(
            $message ?: 'Falha ao enviar SMS. Tente novamente mais tarde.'
        );
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        Log::channel('daily')->error("Falha ao enviar SMS. Tente novamente mais tarde.");
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request)
    {
        //
    }
}
