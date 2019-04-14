<?php

namespace App\Http\Controllers;

class UsuarioController extends Controller
{
    public function __construct() {
        $this->useStatusFlag = true;
        $this->model = \App\User::class;
        $this->friendlyName = 'Usuário';
        $this->rules = [
            'nm_usuario' => 'required|string|max:50',
            'login'      => 'required|string|max:20|unique:users',
            'password'   => 'required|string|min:6|confirmed',
            'email'      => 'required|string|email|max:191|unique:users'
        ];
    }
}
