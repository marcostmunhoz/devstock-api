<?php

namespace App;

class Telefone extends CustomModel
{
    protected $primaryKey = 'id_telefone';
    protected $table = 'telefones';

    public function fornecedor() {
        return $this->belongsTo(Fornecedor::class, 'id_fornecedor', 'id_fornecedor');
    }
}
