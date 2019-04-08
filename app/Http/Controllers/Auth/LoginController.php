<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    protected function guard() {
        return Auth::guard();
    }

    protected function validator(array $data) {
        return Validator::make($data, [
            'login'    => 'required|string',
            'password' => 'required|string'
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'login';
    }

    public function login(Request $request) {
        $user = null;
        $data = $request->only([ 'login', 'password' ]);
        $validator = $this->validator($data);
        $remember = $request->filled('remember');

        try {
            $validator->validate();
            $attempt = $this->guard()->attempt($data, $remember);
            if (!$attempt) {
                throw new \Exception('Usuário e/ou senha inválidos.');
            }
            $request->session()->regenerate();
            $user = $this->guard()->user();
        } catch (\Illuminate\Validation\ValidationException $ex) {
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

        event(new \Illuminate\Auth\Events\Login($user, $remember));

        return response()->json([
            'status'  => 'ok',
            'message' => 'Login realizado com sucesso.',
            'data'    => $user
        ]);
    }

    public function logout(Request $request) {
        $this->guard()->logout();
        
        $request->session()->invalidate();

        return response()->json([
            'status'  => 'ok',
            'message' => 'Logout realizado com sucesso.'
        ]);
    }
}
