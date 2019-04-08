<?php

namespace App;

class Fornecedor extends CustomModel
{
    protected $primaryKey = 'id_fornecedor';
    protected $table = 'fornecedores';

    public function telefones() {
        return $this->hasMany(Telefone::class, 'id_fornecedor', 'id_fornecedor');
    }

    public function produtos() {
        return $this->hasMany(ProdutoFornecedor::class, 'id_fornecedor', 'id_fornecedor')->with('produto');
    }
}
