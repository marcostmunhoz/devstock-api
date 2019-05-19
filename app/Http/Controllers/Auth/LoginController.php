<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dotenv\Exception\ValidationException;
use Illuminate\Auth\Events\Login as LoginEvent;

class LoginController extends Controller {
    protected function validator(array $data) {
        return validator($data, [
            'login'    => 'required|string',
            'password' => 'required|string'
        ]);
    }

    public function login(Request $req) {
        $user = null;
        $token = null;
        $credentials = $req->only([ 'login', 'password' ]);

        try {
            $this->validator($credentials);
            $token = JWTAuth::attempt($credentials);
            if (!$token) {
                throw new Exception('Usuário e/ou senha inválidos.');
            }
            $user = auth()->user();
        } catch (JWTException $ex) {
            return response()->json([
                'status'  => 'error',
                'message' => $ex->getMessage()
            ], 500);
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
            ], 401);
        }

        event(new LoginEvent($user, false));

        return response()->json([
            'status'  => 'ok',
            'message' => 'Login realizado com sucesso.',
            'data'    => [
                'token' => $token,
                'user'  => $user
            ]
        ]);
    }

    public function checkToken(Request $req) {
        $user = JWTAuth::parseToken()->toUser();

        if (!$user) {
            return respose()->json([
                'status'  => 'error',
                'message' => 'Usuário não encontrado.'
            ], 404);
        }

        return response()->json([
            'status'  => 'ok',
            'message' => 'Token válido',
            'data' => [
                'user' => $user
            ]
        ]);
    }
}
