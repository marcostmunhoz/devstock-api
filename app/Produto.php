<?php

namespace App;

class Produto extends CustomModel
{
    protected $primaryKey = 'id_produto';
    protected $table = 'produtos';

    public function fornecedores() {
        return $this->hasMany(ProdutoFornecedor::class, 'id_produto', 'id_produto')->with('fornecedor');
    }

    public function movimentacoes() {
        return $this->hasMany(ProdutoMovimentacao::class, 'id_produto', 'id_produto')->with('movimentacao');
    }
}
