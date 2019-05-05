<?php

namespace App;

class Fornecedor extends CustomModel
{
    protected $primaryKey = 'id_fornecedor';
    protected $table = 'fornecedores';
    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj_fornecedor',
        'end_fornecedor'
    ];

    public function telefones() {
        return $this->hasMany(Telefone::class, 'id_fornecedor', 'id_fornecedor');
    }

    public function emails() {
        return $this->hasMany(Email::class, 'id_fornecedor', 'id_fornecedor');
    }

    public function produtos() {
        return $this->hasMany(ProdutoFornecedor::class, 'id_fornecedor', 'id_fornecedor')->with('produto');
    }
}
