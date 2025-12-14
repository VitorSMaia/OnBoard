<?php

namespace App\Livewire\Auth;

use App\Actions\AutoLoginUserAction;
use App\Actions\SendSmsSmsCodeAction;
use App\Actions\VerifySmsCodeAction;
use App\Exceptions\ExcessiveSmsAttemptsException;
use App\Exceptions\InvalidPhoneFormatException;
use App\Exceptions\SmsSendingFailedException;
use Livewire\Component;
use App\Services\TwilioSmsService;

class LoginCostumer extends Component
{
    public $email = '';
    public $password = '';
    public $rememberMe = false;

    protected $smsService;
    protected $autoLoginAction;

    // Propriedades para login por SMS
    public $phone = '';
    public $code = '';

    // Estado para alternar o formulário (email ou sms)
    public $view = 'email'; // 'email' ou 'sms'

    // Mensagens de erro simuladas
    public $errorMessage = '';
    public $successMessage = '';

    public $isLoading = false;

    // Regras de validação (básicas)
    protected $rules = [
        'email' => 'required_if:view,email|email',
        'password' => 'required_if:view,email|min:8',
        'phone' => 'required_if:view,sms|string|exists:users,phone',
        'code' => 'required_if:view,sms|numeric|digits:6',
    ];

    public function __construct(){
        $smsService = new TwilioSmsService(); 
        $this->smsService = $smsService;

        $autoLoginAction = new AutoLoginUserAction(); 
        $this->autoLoginAction = $autoLoginAction;
    }

    /**
     * Alterna a visualização entre Email/Senha e SMS.
     */
    public function toggleView()
    {
        $this->view = ($this->view === 'email' ? 'sms' : 'email');
        $this->reset(['email', 'password', 'phone', 'code', 'errorMessage']); // Limpa os campos e erros ao alternar
    }

    /**
     * Simula a submissão do formulário de login (Email/Senha).
     */
    public function login()
    {
        if ($this->view === 'email') {
            $this->validate([
                'email' => $this->rules['email'],
                'password' => $this->rules['password'],
            ]);

            // Lógica de Autenticação Real deve ir aqui (e.g., Auth::attempt)
            $loginWithEmailAndPassword = $this->autoLoginAction->loginWithEmailAndPassword($this->email, $this->password);
        
            if($loginWithEmailAndPassword) {
                $this->redirect('dashboard');
                
                $this->dispatch('showToast',
                    message: 'Logado com sucesso',
                    type: 'success',
                    icon: 'alert-circle'
                );
                
            }else {
                $this->dispatch('showToast',
                    message: 'Email ou senha inválidos',
                    type: 'error',
                    icon: 'alert-circle'
                );
            }

        } elseif ($this->view === 'sms') {
           
            $this->validate(['code' => 'required|string|size:6']);

            try {
                $sms = app(VerifySmsCodeAction::class)->execute($this->phone, $this->code);
                
                $this->successMessage = 'Telefone verificado com sucesso!';
                $this->reset(['phone', 'code']);
                
                $this->autoLoginAction->execute($sms);

                $this->redirect('dashboard');

                $this->dispatch('showToast',
                    message: 'Logado com sucesso',
                    type: 'success',
                    icon: 'alert-circle'
                );

            } 
            catch (InvalidPhoneFormatException $invalidPhoneFormatException) {
                $this->dispatch('showToast',
                    message: 'Formato de telefone inválido. Use: 11999999999',
                    type: 'error',
                    icon: 'alert-circle'
                );
            } catch (ExcessiveSmsAttemptsException $excessiveSmsAttemptsException) {
                $this->dispatch('showToast',
                    message: 'Muitas tentativas. Aguarde 5 minutos.',
                    type: 'warning',
                    icon: 'clock'
                );
            } catch (SmsSendingFailedException $smsSendingFailedException) {
                $this->dispatch('showToast',
                    message: 'Erro ao enviar SMS. Tente novamente mais tarde.',
                    type: 'error',
                    icon: 'alert-circle'
                );
            } catch (\Exception $exception) {
                $this->dispatch('showToast',
                    message: 'Erro inesperado. Tente novamente.',
                    type: 'error',
                    icon: 'alert-circle'
                );
            } finally {
                $this->isLoading = false;
            }
        }
    }

    /**
     * Simula o login social via Google.
     */
    public function loginWithGoogle()
    {
        $this->errorMessage = 'Redirecionando para o login do Google... (Lógica do Socialite)';
        // Implementar redirecionamento com Laravel Socialite
    }

    /**
     * Simula o envio do código SMS.
     */
    public function sendSmsCode()
    {
        $this->isLoading = true;

        $this->validateOnly('phone');
        $this->errorMessage = 'Código enviado para ' . $this->phone . '. Insira abaixo.';

        try {
            $sms = app(SendSmsSmsCodeAction::class)->execute($this->phone);
            
            $this->errorMessage = 'Código enviado para ' . $this->phone . '. Insira abaixo.';
        }  catch (InvalidPhoneFormatException $e) {
            $this->dispatch('showToast',
                message: 'Formato de telefone inválido. Use: 11999999999',
                type: 'error',
                icon: 'alert-circle'
            );
        } catch (ExcessiveSmsAttemptsException $e) {
            $this->dispatch('showToast',
                message: 'Muitas tentativas. Aguarde 5 minutos.',
                type: 'warning',
                icon: 'clock'
            );
        } catch (SmsSendingFailedException $e) {
            $this->dispatch('showToast',
                message: 'Erro ao enviar SMS. Tente novamente mais tarde.',
                type: 'error',
                icon: 'alert-circle'
            );
        } catch (\Exception $e) {
            $this->dispatch('showToast',
                message: 'Erro inesperado. Tente novamente.',
                type: 'error',
                icon: 'alert-circle'
            );
        } finally {
            $this->isLoading = false;
        }
    }

    public function render()
    {
        return view('livewire.auth.login-costumer');
    }
}
