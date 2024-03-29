<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsuarioController extends Controller
{
    public function __construct() {
        $this->useStatusFlag = true;
        $this->model = \App\User::class;
        $this->friendlyName = 'Usuário';
        $this->insertRules = [
            'nm_usuario'    => 'required|string|max:50',
            'login'         => 'required|string|max:20|unique:users',
            'password'      => 'string|min:6',
            'email'         => 'required|string|email|max:191|unique:users',
            'flg_edit_usu'  => 'required|int|in:0,1',
            'flg_edit_forn' => 'required|int|in:0,1',
            'flg_edit_prod' => 'required|int|in:0,1',
            'flg_mov'       => 'required|int|in:0,1'
        ];
        $this->updateRules = [
            'nm_usuario'    => 'string|max:50',
            'login'         => 'string|max:20',
            'email'         => 'string|email|max:191',
            'flg_edit_usu'  => 'required|int|in:0,1',
            'flg_edit_forn' => 'required|int|in:0,1',
            'flg_edit_prod' => 'required|int|in:0,1',
            'flg_mov'       => 'required|int|in:0,1'
        ];
        $this->searchColumns = [
            'nm_usuario',
            'login',
            'email'
        ];
    }

    public function showAll($includeRelations = false) {
        $result = null;

        if ($this->useStatusFlag) {
            if ($includeRelations && count($this->relations)) {
                $result = $this->model::with($this->relations)->where('flg_status', '!=', 2)->get();
            } else {
                $result = $this->model::where('flg_status', '!=', 2)
                                    ->where('id_usuario', '!=', auth()->id())
                                    ->get();
            }
        } else {
            if ($includeRelations && count($this->relations)) {
                $result = $this->model::with($this->relations)->all();
            } else {
                $result = $this->model::all();
            }
        }

        return response()->json([
            'status' => 'ok',
            'data'   => $result
        ]);
    }

    public function create(Request $request) {
        $result = null;

        try {
            $fields = $this->validateWith($this->insertRules, $request);

            $fields['password'] = bcrypt($fields['password']);

            \DB::beginTransaction();
            $result = new $this->model;
            $result->fill($fields);
            $result->save();

            if ($this->afterInsert && is_callable(($this->afterInsert))) {
                call_user_func($this->afterInsert, $request, $result);
            }
            \DB::commit();
        } catch (\Illuminate\Validation\ValidationException $ex) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Ocorreu um erro na validação.',
                'data'    => $ex->validator->getMessageBag()->toArray()
            ], 400);
        } catch (\Exception $ex) {
            \DB::rollback();
            return response()->json([
                'status'  => 'error',
                'message' => $ex->getMessage()
            ], 400);
        }

        $result->refresh();

        return response()->json([
            'status'  => 'ok',
            'message' => "$this->friendlyName cadastrado(a) com sucesso.",
            'data'    => $result
        ]);
    }

    public function update(Request $request, $id) {
        $result = null;

        try {
            $fields = $this->validateWith($this->updateRules, $request);

            $result = $this->model::find($id);
            
            if (!$result) {
                return response()->json([
                    'status'  => 'error',
                    'message' => "$this->friendlyName não encontrado(a)."
                ], 404);
            } else {
                if (array_key_exists('login', $fields)) {
                    $dupLogin = User::where('login', '=', $fields['login'])
                                    ->where('flg_status', '=', 1)
                                    ->where('id_usuario', '!=', $id)
                                    ->count();

                    if ($dupLogin > 0) {
                        return response()->json([
                            'status'  => 'error',
                            'message' => "Usuário já existente para o login {$fields['login']}."
                        ], 400);
                    }
                } elseif (array_key_exists('email', $fields)) {
                    $dupEmail = User::where('email', '=', $fields['email'])
                                    ->where('flg_status', '=', 1)
                                    ->where('id_usuario', '!=', $id)
                                    ->count();

                    if ($dupEmail > 0) {
                        return response()->json([
                            'status'  => 'error',
                            'message' => "Usuário já existente para o e-mail {$fields['email']}."
                        ], 400);
                    }
                }
            }

            $result->fill($fields);
            $result->save();
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

        return response()->json([
            'status'  => 'ok',
            'message' => "$this->friendlyName editado(a) com sucesso.",
            'data'    => $result
        ]);
    }

    public function resetPassword($id) {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Usuário não encontrado.'
                ], 404);
            }

            $user->password = bcrypt('12345');
            $user->save();
        } catch (\Exception $ex) {
            return response()->json([
                'status'  => 'error',
                'message' => $ex->getMessage()
            ], 400);
        }

        return response()->json([
            'status'  => 'ok',
            'message' => 'Senha resetada com sucesso.'
        ]);
    }

    public function changePassword(Request $request, $id) {
        $result = null;

        try {
            $fields = $this->validateWith([
                'old_password' => 'required|string',
                'password'     => 'required|string|min:8'
            ], $request);

            $result = $this->model::find($id);
            
            if (!$result) {
                return response()->json([
                    'status'  => 'error',
                    'message' => "$this->friendlyName não encontrado(a)."
                ], 404);
            }

            if (!\Hash::check($fields['old_password'], $result->password)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'A senha atual não confere.'
                ], 401);
            }

            $result->password = bcrypt($fields['password']);
            $result->save();
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

        return response()->json([
            'status'  => 'ok',
            'message' => "Senha alterada com sucesso.",
            'data'    => $result
        ]);
    }
}
