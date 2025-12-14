<?php

namespace App\Exceptions;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class InvalidPhoneFormatException extends Exception
{
    public $phone;

    public function __construct(string $phone = '')
    {
        $this->phone = $phone;
        parent::__construct(
            "O formato do telefone '{$phone}' é inválido. Use o formato: 11999999999"
        );
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        Log::channel('daily')->error("O formato do telefone '{$this->phone}' é inválido. Use o formato: 11999999999");
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request)
    {
        //
    }
}
