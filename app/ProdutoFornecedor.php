<?php

namespace App;

class ProdutoFornecedor extends CustomModel
{
    protected $primaryKey = 'id_produto_fornecedor';
    protected $table = 'produtos_fornecedor';

    public function produto() {
        return $this->hasOne(Produto::class, 'id_produto', 'id_produto');
    }

    public function fornecedor() {
        return $this->hasOne(Fornecedor::class, 'id_fornecedor', 'id_fornecedor');
    }
}
