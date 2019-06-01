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
            'razao_social'     => 'required|string|max:100',
            'nome_fantasia'    => 'required|string|max:100',
            'cnpj_fornecedor'  => 'required|string|max:14|unique:fornecedores',
            'end_fornecedor'   => 'required|string|max:150',
            'fone_fornecedor'  => 'required|string|max:11',
            'email_fornecedor' => 'required|string|max:50'
        ];
        $this->updateRules = [
            'razao_social'     => 'string|max:100',
            'nome_fantasia'    => 'string|max:100',
            'cnpj_fornecedor'  => 'string|max:14',
            'end_fornecedor'   => 'string|max:150',
            'fone_fornecedor'  => 'string|max:11',
            'email_fornecedor' => 'string|max:50'
        ];
        $this->searchColumns = [
            'razao_social',
            'nome_fantasia',
            'cnpj_fornecedor'
        ];
    }

    public function show($id, $includeRelations = true) {
        return parent::show($id, $includeRelations);
    }
}
