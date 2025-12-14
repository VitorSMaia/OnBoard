<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    // Propriedades do formulário
    public $name = '';
    public $email = '';
    public $phone = '';
    public $password = '';
    public $password_confirmation = '';

    // Estado para controlar a mensagem de sucesso
    public $registerSuccess = false;

    /**
     * Regras de validação para o formulário.
     */
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users', // Verifica se o email é único na tabela 'users'
        'phone' => 'required|string|max:20|unique:users', // <-- NOVO: Adicionei regras básicas e unique
        'password' => 'required|string|min:8|confirmed',
    ];

    /**
     * Processa a submissão do formulário de cadastro.
     */
    public function register()
    {
        // 1. Validação dos dados
        $this->validate();

        try {
            // 2. Criação do Usuário
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone, // <-- NOVO: Adicionado ao array de criação
                'password' => Hash::make($this->password),
            ]);

            // 3. Login Automático (Opcional, mas comum)
            Auth::login($user);

            // 4. Marca o sucesso e/ou redireciona
            $this->registerSuccess = true;

            // Se for um SPA (Single Page Application) Livewire, você pode não querer um redirect imediato.
            // Se for para redirecionar para a home page:
            // return redirect()->intended('/');

        } catch (\Exception $e) {
            // Logar o erro (opcional)
            // \Log::error('Registration failed: ' . $e->getMessage());

            // Adicionar erro genérico (se não for um erro de validação)
            session()->flash('error', 'Houve um problema ao processar seu cadastro. Tente novamente.');
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
