<?php

namespace App\Http\Controllers;

class ProdutoController extends Controller
{
    public function __construct() {
        $this->useStatusFlag = true;
        $this->model = \App\Produto::class;
        $this->friendlyName = 'Produto';
        $this->insertRules = [
            'cod_produto'     => 'required|string|max:15|unique:produtos',
            'nm_produto'      => 'required|string|max:50',
            'id_fornecedor'   => 'required|integer|exists:fornecedores,id_fornecedor',
            'nr_qtd_estocada' => 'integer'
        ];
        $this->updateRules = [
            'cod_produto'     => 'string|max:15',
            'nm_produto'      => 'string|max:50',
            'id_fornecedor'   => 'integer|exists:fornecedores,id_fornecedor',
            'nr_qtd_estocada' => 'integer'
        ];
        $this->searchColumns = [
            'cod_produto',
            'nm_produto'
        ];
        $this->relations = [
            'fornecedores'
        ];
    }

    public function show($id, $includeRelations = true) {
        return parent::show($id, $includeRelations);
    }
}
