<?php

namespace App\Exceptions;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PhoneAlreadyExistsException extends Exception
{
    public $phone;

    public function __construct(string $phone = '')
    {
        $this->phone = $phone;
        parent::__construct(
            "O telefone '{$phone}' já está registrado no sistema."
        );
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        Log::channel('daily')->error("O telefone '{$this->phone}' já está registrado no sistema.");
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request)
    {
        //
    }
}
