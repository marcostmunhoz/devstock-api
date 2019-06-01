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
        return $this->hasOne(ProdutoFornecedor::class, 'id_produto', 'id_produto')->with('fornecedor');
    }

    public function movimentacoes() {
        return $this->hasMany(ProdutoMovimentacao::class, 'id_produto', 'id_produto')->with('movimentacao');
    }
}
