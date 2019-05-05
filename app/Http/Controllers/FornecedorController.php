<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fornecedor;
use App\Telefone;
use App\Email;

class FornecedorController extends Controller
{
    public function __construct() {
        $this->useStatusFlag = true;
        $this->model = \App\Fornecedor::class;
        $this->friendlyName = 'Fornecedor';
        $this->insertRules = [
            'razao_social'    => 'required|string|max:100',
            'nome_fantasia'   => 'required|string|max:100',
            'cnpj_fornecedor' => 'required|string|max:14|unique:fornecedores',
            'end_fornecedor'  => 'required|string|max:150'
        ];
        $this->updateRules = [
            'razao_social'    => 'string|max:100',
            'nome_fantasia'   => 'string|max:100',
            'cnpj_fornecedor' => 'string|max:14',
            'end_fornecedor'  => 'string|max:150'
        ];
        $this->searchColumns = [
            'razao_social',
            'nome_fantasia',
            'cnpj_fornecedor'
        ];
        $this->relations = [
            'telefones',
            'emails'
        ];
        $this->afterInsert = function (Request $request, Fornecedor $fornecedor) {
            if ($request->has('ddd_telefone')) {
                $fields = $this->validateWith([
                    'ddd_telefone' => 'required|string|size:2',
                    'nr_telefone'  => 'required|string|digits_between:8,9',
                    'tp_telefone'  => 'required|integer|in:1,2,3'
                ], $request);

                $fields['id_fornecedor'] = $fornecedor->id_fornecedor;

                $telefone = new Telefone;
                $telefone->fill($fields);
                $telefone->save();
            }

            if ($request->has('email')) {
                $fields = $this->validateWith([
                    'email'     => 'required|string|max:100',
                    'tp_email'  => 'required|integer|in:1,2,3'
                ], $request);

                $fields['id_fornecedor'] = $fornecedor->id_fornecedor;

                $email = new Email;
                $email->fill($fields);
                $email->save();
            }
        };
    }

    public function show($id, $includeRelations = true) {
        return parent::show($id, $includeRelations);
    }

    public function deleteTelefone($id) {
        try {
            $telefone = Telefone::find($id);
            
            if (!$telefone) {
                return response()->json([
                    'status'  => 'error',
                    'message' => "Telefone não encontrado."
                ], 404);
            }

            $telefone->delete();
        } catch (\Exception $ex) {
            return response()->json([
                'status'  => 'error',
                'message' => $ex->getMessage()
            ]);
        }

        return response()->json([
            'status'  => 'ok',
            'message' => 'Telefone excluído com sucesso.'
        ]);
    }


    public function deleteEmail($id) {
        try {
            $email = Email::find($id);
            
            if (!$email) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'E-mail não encontrado.'
                ], 404);
            }

            $email->delete();
        } catch (\Exception $ex) {
            return response()->json([
                'status'  => 'error',
                'message' => $ex->getMessage()
            ], 400);
        }

        return response()->json([
            'status'  => 'ok',
            'message' => 'E-mail excluído com sucesso.'
        ]);
    }
}
