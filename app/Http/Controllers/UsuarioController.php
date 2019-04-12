<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsuarioController extends Controller
{
    public function showAll() {
        $users = User::all();

        return response()->json([
            'status' => 'ok',
            'data'   => $users
        ]);
    }

    public function show($id) {
        $user = User::find($id);

        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Usuário não encontrado.'
                ], 404);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'status'  => 'error',
                'message' => $ex->getMessage()
            ], 400);
        }

        return response()->json([ 
            'status' => 'ok',
            'data'   => $user 
        ]);
    }

    public function update(Request $request, $id) {
        $user = null;

        try {
            $user = User::find($id);
            
            if (!$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Usuário não encontrado.'
                ], 404);
            }

            $user->fill($request->all());
            $user->save();
        } catch (\Exception $ex) {
            return response()->json([
                'status'  => 'error',
                'message' => $ex->getMessage()
            ], 400);
        }

        return response()->json([
            'status'  => 'ok',
            'message' => 'Usuário editado com sucesso.',
            'data'    => $user
        ]);
    }

    public function delete($id) {
        $user = null;

        try {
            $user = User::find($id);
            
            if (!$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Usuário não encontrado.'
                ], 404);
            }

            $user->flg_status = 2;
            $user->save();
        } catch (\Exception $ex) {
            return response()->json([
                'status'  => 'error',
                'message' => $ex->getMessage()
            ], 400);
        }
        
        return response()->json([
            'status'  => 'ok',
            'message' => 'Usuário excluído com sucesso.'
        ]);
    }
}
