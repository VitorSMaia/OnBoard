<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PendingSmsCodNotFoundException extends Exception
{
    public $phone;

    public function __construct(string $phone = '')
    {
        $this->phone = $phone;
        parent::__construct(
            "Nenhum código pendente encontrado para o telefone '{$phone}'."
        );
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        Log::channel('daily')->error("Nenhum código pendente encontrado para o telefone '{$this->phone}'.");
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request)
    {
        //
    }
}
