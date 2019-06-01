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
        'end_fornecedor',
        'fone_fornecedor',
        'email_fornecedor',
        'flg_status'
    ];

    public function produtos() {
        return $this->hasOne(ProdutoFornecedor::class, 'id_fornecedor', 'id_fornecedor')->with('produto');
    }
}
