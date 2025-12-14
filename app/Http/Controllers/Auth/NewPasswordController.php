<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewPasswordController extends Controller
{
    /**
     * Exibe a view de redefinição de senha.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        // Esta view deve ter o formulário para inserir a nova senha, 
        // e receber o 'token' e o 'email' via URL.
        return view('auth.reset-password', ['request' => $request]);
    }
}
