<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered as RegisteredEvent;
use Dotenv\Exception\ValidationException;

class RegisterController extends Controller {
    protected function validator(array $data) {
        return validator($data, [
            'nm_usuario' => 'required|string|max:50',
            'login'      => 'required|string|max:20|unique:users',
            'password'   => 'required|string|min:6|confirmed',
            'email'      => 'required|string|email|max:191|unique:users',
        ]);
    }

    public function register(Request $request) {
        $user = null;
        $data = $request->only([ 'nm_usuario', 'login', 'password', 'email' ]);

        try {
            $this->validator($data);
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);
        } catch (ValidationException $ex) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Ocorreu um erro na validação.',
                'data'    => $ex->validator->getMessageBag()->toArray()
            ], 400);
        } catch (\Exception $ex) {
            return response()->json([
                'status'  => 'error',
                'message' => $ex->getMessage()
            ], 400);
        }

        event(new RegisteredEvent($user));

        return response()->json([
            'status'  => 'ok',
            'message' => 'Usuário criado com sucesso.'
        ], 201);
    }
}
