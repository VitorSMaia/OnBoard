<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordResetRequest extends Component
{
    /**
     * O e-mail do usuário para quem o link de reset será enviado.
     * @var string
     */
    public $email = '';

    /**
     * Controla o estado da interface: 'request' ou 'sent'.
     * @var string
     */
    public $status = 'request';

    /**
     * Mensagem de sucesso ou erro a ser exibida.
     * @var string
     */
    public $message = '';

    protected $rules = [
        'email' => 'required|email',
    ];

    /**
     * Envia o link de redefinição de senha.
     */
    public function sendResetLink()
    {
        // 1. Valida o campo de e-mail
        $this->validate();

        // 2. Chama o método do Laravel para enviar o e-mail de redefinição
        $response = Password::sendResetLink(
            $this->only('email')
        );

        // 3. Verifica o status da resposta
        if ($response == Password::RESET_LINK_SENT) {
            
            // Sucesso: Define o status e a mensagem para a view
            $this->status = 'sent';
            $this->message = "Enviamos o link de redefinição de senha para o seu e-mail: **{$this->email}**.";

            // Limpa o e-mail
            $this->reset('email');

        } else {
            // Falha (e.g., e-mail não encontrado)
            $this->status = 'request';
            $this->message = "Não foi possível enviar o link. Verifique o e-mail ou tente novamente mais tarde.";
            
            // Você também pode lançar uma exceção de validação específica:
            // throw ValidationException::withMessages([
            //     'email' => [trans($response)],
            // ]);
        }
    }
    
    public function render()
    {
        return view('livewire.auth.password-reset-request');
    }
}
