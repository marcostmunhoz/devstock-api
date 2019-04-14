<?php

namespace App\Http\Controllers;

class ProdutoController extends Controller
{
    public function __construct() {
        $this->useStatusFlag = true;
        $this->model = \App\Produto::class;
        $this->friendlyName = 'Produto';
        $this->rules = [
            'cod_produto' => 'required|string|max:15|unique:produtos',
            'nm_produto'  => 'required|string|max:50'
        ];
        $this->relations = [
            'fornecedores'
        ];
    }

    public function show($id, $includeRelations = true) {
        return parent::show($id, $includeRelations);
    }
}
