<?php

namespace App\Livewire\Auth;

use DragonCode\Support\Facades\Helpers\Str;
use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ResetPassword extends Component
{
    /**
     * O token de redefinição de senha da URL.
     * @var string
     */
    public $token;

    /**
     * O e-mail do usuário, preenchido a partir da URL.
     * @var string
     */
    public $email = '';

    /**
     * A nova senha que o usuário deseja definir.
     * @var string
     */
    public $password = '';

    /**
     * Confirmação da nova senha.
     * @var string
     */
    public $passwordConfirmation = '';

    /**
     * Mensagem de status após a tentativa de redefinição.
     * @var string|null
     */
    public $statusMessage = null;

    /**
     * @var string Define se houve sucesso na redefinição para mudar o layout.
     */
    public $resetSuccess = false;

    protected $rules = [
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8',
    ];

    /**
     * Método de inicialização, tipicamente chamado com o token e email.
     * @param string $token
     * @param string|null $email
     */
    public function mount($token, $email = null)
    {
        $this->token = $token;
        // O Laravel envia o email na query string.
        $this->email = $email;
    }

    /**
     * Redefine a senha do usuário.
     */
    public function resetPassword()
    {
        // 1. Validação dos campos
        $this->validate();
        
        // Dados necessários para a redefinição
        $credentials = [
            'token' => $this->token,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->passwordConfirmation,
        ];

        // 2. Chama o método do Laravel para redefinir a senha
        $response = Password::reset($credentials, function ($user, $password) {
            // Lógica interna do Laravel: atualiza a senha e deleta o token
            $user->forceFill([
                'password' => bcrypt($password),
                'remember_token' => Str::random(60),
            ])->save();
        });

        // 3. Verifica o status da resposta
        if ($response == Password::PASSWORD_RESET) {
            
            // Sucesso: Redireciona ou exibe mensagem de sucesso.
            $this->resetSuccess = true;
            $this->statusMessage = "Sua senha foi redefinida com sucesso! Você pode fazer login agora.";
            
            // Opcional: Autenticar o usuário
            // Auth::login($user); 
            
            // Opcional: Redirecionar
            // return redirect()->route('login');

        } else {
            // Falha: Token inválido, expirado, ou outro erro.
            // O Livewire pode ter problemas com `throw ValidationException::withMessages`, 
            // então exibimos a mensagem de erro.
            $this->statusMessage = "Falha na redefinição: " . trans($response);
            $this->addError('email', $this->statusMessage);
        }
    }
    
    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
