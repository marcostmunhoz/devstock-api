<?php

namespace App\Http\Controllers;

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
        $this->relations = [
            'telefones'
        ];
    }

    public function show($id, $includeRelations = true) {
        return parent::show($id, $includeRelations);
    }
}
