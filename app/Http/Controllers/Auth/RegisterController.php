<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nm_usuario' => 'required|string|max:50',
            'login'      => 'required|string|max:20|unique:users',
            'password'   => 'required|string|min:6|confirmed',
            'email'      => 'required|string|email|max:191|unique:users',
        ]);
    }

    public function register(\Illuminate\Http\Request $request) {
        $user = null;
        $data = $request->all();
        $validator = $this->validator($data);

        try {
            $validator->validate();
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);
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

        event(new \Illuminate\Auth\Events\Registered($user));

        return response()->json([
            'status'  => 'ok',
            'message' => 'Usuário criado com sucesso.'
        ], 201);
    }
}
