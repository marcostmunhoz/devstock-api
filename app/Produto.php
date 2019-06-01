<?php

namespace App;

class Produto extends CustomModel
{
    protected $primaryKey = 'id_produto';
    protected $table = 'produtos';

    protected $fillable = [
        'cod_produto', 
        'nm_produto', 
        'nr_qtd_estocada',
        'id_fornecedor',
        'flg_status', 
    ];

    public function fornecedor() {
        return $this->hasOne(Fornecedor::class, 'id_fornecedor', 'id_fornecedor');
    }
}
